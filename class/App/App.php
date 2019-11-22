<?

namespace App;

use Exception;
use Composer\Autoload\ClassLoader;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use R\Psr7\Response;
use R\Psr7\Stream;
use Symfony\Component\Yaml\Yaml;

class App extends \R\App
{
    public $config = [];
    public $user;
    public $user_id;
    public $locale = "zh-hk";

    public function __construct(string $root = null, ClassLoader $loader = null, LoggerInterface  $logger = null)
    {
        //check config file
        if (!file_exists($root . "/config.ini")) {
            throw new Exception("config.ini not found");
        }

        parent::__construct($root, $loader, $logger);
        Model::$_db = $this->db;
        Model::$_app = $this;
        Module::$_app = $this;

        //system config
        $pi = $this->pathInfo();
        $file = $pi["system_root"] . "/config.ini";
        $this->config = parse_ini_file($file, true);

        foreach (Config::Query() as $c) {
            $this->config["user"][$c->name] = $c->value;
        }

        //user
        if (!$_SESSION["app"]["user"]) {
            $_SESSION["app"]["user"] = new User(2);
        }
        $this->user = $_SESSION["app"]["user"];
        $this->user_id = $this->user->user_id;
    }

    public function run()
    {
        $this->alert = new Alert();

        $request = $this->request;
        $router = new \R\Router();

        $router->addRoute(function (RequestInterface $request, $loader) {
            return new Route($request, $this);
        });


        $route = $router->getRoute($this->request, $this->loader);

        $request = $request->withAttribute("route", $route);

        //----
        $ps = explode("/", $route->path);
        $ps = array_values(array_filter($ps, "strlen"));
        foreach ($this->modules() as $module) {
            if ($module->name == $ps[0]) {
                $this->module = $module;
                break;
            }
        }


        $class = $route->class;
        $page = new $class($this);
        if ($page) {
            $response = new Response(200);
            try {
                $request = $request->withMethod($route->method);
                if ($this->logger) $this->logger->debug("invoke page");
                $response = $page($request, $response);
            } catch (\Exception $e) {
                if ($this->request->getHeader("accept")[0] == "application/json") {
                    $response = new Response(200);
                    $response = $response->withHeader("content-type", "application/json");
                    $response = $response->withBody(new Stream($e->getMessage()));
                } else {
                    $this->alert->danger($e->getMessage());

                    if ($referer = $this->request->getHeader("Referer")[0]) {
                        if ($url = $_SESSION["app"]["referer"][$referer]) {
                            $response = $response->withHeader("Location", $url);
                        }
                    }
                }
            }


            foreach ($response->getHeaders() as $name => $values) {
                header($name . ": " . implode(", ", $values));
            }


            file_put_contents("php://output", (string) $response->getBody());
        } elseif (self::Logined()) {
            $this->redirect("404_not_found");
        } else {
            //$this->redirect("/");
        }
    }

    public function flushMessage(): array
    {
        $msg = $_SESSION["app"]["message"];
        $_SESSION["app"]["message"] = [];
        return $msg ?? [];
    }

    public function login(string $username, string $password, $code = null): bool
    {
        //check AuthLock
        if ($this->config["user"]["auth-lockout"]) {
            if (AuthLock::IsLock()) {
                throw new \Exception("IP locked 180 seconds", 403);
            }
        }

        try {
            $user = User::Login($username, $password);
        } catch (Exception $e) {
            AuthLock::Add();
            throw new Exception("Login error");
        }

        if ($this->config["user"]["2-step verification"]) {
            $need_check = true;
            if ($setting = $user->setting()) {
                if (in_array($_SERVER["REMOTE_ADDR"], $setting["2-step_ip_white_list"])) {
                    $need_check = false;
                }
            }

            if ($need_check && !$this->IP2StepExemptCheck($_SERVER['REMOTE_ADDR'])) {
                if (($code == "" || !$user->checkCode($code)) && $user->secret != "") {
                    throw new \Exception("2-step verification", 403);
                }
            }
        }

        $_SESSION["app"]["user_id"] = $user->user_id;

        $_SESSION["app"]["user"] = $user;

        $_SESSION["app"]["login"] = true;

        $user->createUserLog("SUCCESS");

        $user->online();

        AuthLock::Clear();

        $this->user = $user;

        return true;
    }

    public function logined(): bool
    {
        return (bool) $_SESSION["app"]["login"];
    }

    public function twig(string $file)
    {
        if ($file[0] != "/" || file_exists($file)) {
            $pi = pathinfo($file);
            $root = $pi["dirname"];
            $template_file = $pi["basename"];
        } else {
            $root = $this->pathInfo()["document_root"];
            $template_file = substr($file, strlen($root) + 1);
        }
        if (is_readable($root . "/" . $template_file)) {

            if (!$config = $this->config["twig"]) {
                $config = [];
            }
            array_walk($config, function (&$o) use ($root) {
                $o = str_replace("{root}", $root, $o);
            });

            $twig["loader"] = new \Twig_Loader_Filesystem($root);
            $twig["environment"] = new \Twig_Environment($twig["loader"], $config);
            $twig["environment"]->addExtension(new \Twig_Extensions_Extension_I18n());

            return $twig["environment"]->loadTemplate($template_file);
        }
    }

    public function pathInfo(): array
    {
        $server = $this->request->getServerParams();

        $document_root = $server["DOCUMENT_ROOT"];
        if ($this->config["system"]["document_root"]) {
            $document_root = $this->config["system"]["document_root"];
        }

        $cms = dirname($server["PHP_SELF"]);
        $cms_root = $document_root . $cms;

        if (file_exists($document_root . "/vendor")) {
            $composer_root = $document_root . "/vendor";
        } else if (file_exists($cms_root . "/vendor")) {
            $composer_root = $cms_root . "/vendor";
        }


        $composer_base = substr($composer_root, strlen($document_root));
        $composer_base = str_replace(DIRECTORY_SEPARATOR, "/", $composer_base);

        $system_root = dirname(__DIR__, 2);
        $system_base = substr($system_root, strlen($document_root));
        $system_base = str_replace(DIRECTORY_SEPARATOR, "/", $system_base);

        return compact("composer_base", "composer_root", "document_root", "cms_root", "system_root", "system_base");
    }

    public function module(string $name)
    {
        $ms = $this->modules();

        foreach ($ms as $m) {
            if ($m->name == $name) {
                return $m;
            }
        }
    }

    private $_modules = null;
    public function modules(): array
    {
        if ($this->_modules) {
            return $this->_modules;
        }


        $modules = [];

        $pi = $this->pathInfo();
        $system_root = $pi["system_root"];
        $page = "pages";

        foreach (glob($system_root . DIRECTORY_SEPARATOR . $page . DIRECTORY_SEPARATOR . "*", GLOB_ONLYDIR) as $m) {
            $name = basename($m);
            $config = [];
            if (is_readable($config_file = $m . DIRECTORY_SEPARATOR . "setting.yml")) {
                $config = Yaml::parseFile($config_file);
            }
            $module = new Module($name, $config);
            $modules[$name] = $module;
        }

        $cms_root = $pi["cms_root"];
        foreach (glob($cms_root . DIRECTORY_SEPARATOR . $page . DIRECTORY_SEPARATOR . "*", GLOB_ONLYDIR) as $m) {
            $name = basename($m);
            $config = [];
            if (is_readable($config_file = $m . DIRECTORY_SEPARATOR . "setting.yml")) {
                $config = Yaml::parseFile($config_file);
            } elseif (is_readable($config_file = $m . DIRECTORY_SEPARATOR . "setting.ini")) {
                $config = parse_ini_file($config_file, true);
            }
            if (!$module = $modules[$name]) {
                $module = new Module($name, $config);
            } else {
                $module->loadConfig($config);
            }
        }

        //sorting
        usort($modules, function ($a, $b) {
            return $a->sequence <=> $b->sequence;
        });


        $this->_modules = $modules;
        return $modules;
    }

    public function translate(string $str): string
    {
        return $str;
    }

    public function acl(string $path): bool
    {
        $user = $this->user;
        $raw_path = $path;
        $p = parse_url($path);
        $path = $p["path"];

        if ($p["scheme"]) { //external
            return true;
        }

        if ($path[0] == "/") { //absolute path

            $result = $user->isAdmin();

            $ugs = $user->UserGroup();

            $w = [];
            $w[] = ["path=?", $path];

            $u = [];
            $u[] = "user_id=" . $user->user_id;

            foreach ($ugs as $ug) {
                $u[] = "usergroup_id=$ug->usergroup_id";
            }

            $w[] = implode(" or ", $u);
            foreach (ACL::Find($w) as $acl) {
                $v = $acl->value();
                if ($v == "deny") {
                    return false;
                }
                if ($v == "allow") {
                    $result = true;
                }
            }

            return $result;
        }
        return ACL::Allow($path);
    }

    public function createMail()
    {
        $mail = new Mail(true);
        $smtp = $this->config["user"]["smtp"];

        if ($smtp && $smtp->value) {
            $this->IsSMTP();
            $this->Host = (string) $smtp;
            $this->SMTPAuth = true;
            $this->Username = $this->config["user"]["smtp-username"];
            $this->Password = $this->config["user"]["smtp-password"];
        }

        return $mail;
    }

    public function accessDeny(RequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        $uri = substr($uri, 1);
        if ($q = $request->getUri()->getQuery()) {
            $uri .= "?" . $q;
        }

        $base = $request->getUri()->getBasePath();
        if ($this->logined()) {

            if ($request->getHeader("accept")[0] == "application/json") {
                $response = new Response(200);
                $msg = [];
                $msg["error"]["message"] = "access deny";
                $msg["error"]["code"] = 403;
                $response = $response->withHeader("content-type", "application/json");
                $response = $response->withBody(new Stream(json_encode($msg)));
            } else {
                $q = http_build_query(["q" => $uri]);
                $response = new Response(403);
                $response = $response->withHeader("location", $base . "/access_deny?" . $q);
            }
        } else {
            $response = new Response(403);
            $response = $response->withHeader("location", $base . "/#" . $uri);
        }

        return $response;
    }

    public function version(): string
    {
        if ($_SESSION["app"]["version"])
            return $_SESSION["app"]["version"];
        $composer = new Composer($this);
        $package = $composer->package("mathsgod/alt");

        $_SESSION["app"]["version"] = $package->version ?? "6.0.0";
        return $_SESSION["app"]["version"];
    }


    public function savePlace()
    {
        $uri = $this->request->getURI();
        $path = $uri->getPath();

        if ($path[0] == "/") {
            $path = substr($path, 1);
        }

        if ($query = $uri->getQuery()) {
            $_SESSION["app"]["redirect"] = $path . "?" . $query;
        } else {
            $_SESSION["app"]["redirect"] = $path;
        }
    }
}
