<?

namespace App;

use Exception;
use Composer\Autoload\ClassLoader;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use R\Psr7\Response;
use R\Psr7\ServerRequest;
use R\Psr7\Stream;
use Symfony\Component\Yaml\Yaml;

class App extends \R\App
{
    public $config = [];
    public $user;
    public $user_id;
    public $usergroup_id = [];
    public $locale = "zh-hk";
    public $plugins = [];
    public $setting = [];
    public $system_value = [];

    public function __construct(string $root = null, ClassLoader $loader = null, LoggerInterface  $logger = null)
    {
        //check config file
        if (!file_exists($root . "/config.ini")) {
            throw new Exception("config.ini not found");
        }

        spl_autoload_register(function ($class) use ($root) {
            $class_path = str_replace("\\", DIRECTORY_SEPARATOR, $class);
            $file = realpath($root . "/pages/$class_path/$class.class.php");
            if (is_readable($file)) {
                require_once($file);
            }
        });


        parent::__construct($root, $loader, $logger);
        Model::$_db = $this->db;
        Model::$_app = $this;
        Module::$_app = $this;
        User::$_app = $this;
        UserGroup::$_app = $this;
        ModelTrait::$_app = $this;
        //        Config::$_app = $this;


        //-- CONFIG.INI
        $user_config = $this->config;
        //system config
        $pi = $this->pathInfo();
        $file = $pi["system_root"] . "/config.ini";
        $this->config = parse_ini_file($file, true);

        //user config
        foreach ($user_config as $n => $v) {
            foreach ($v as $a => $b) {
                $this->config[$n][$a] = $b;
            }
        }

        foreach (Config::Query() as $c) {
            $this->config["user"][$c->name] = $c->value;
        }


        //user
        if (!$_SESSION["app"]["user_id"]) {
            $this->user = new User(2);
        } else {
            $this->user = new User($_SESSION["app"]["user_id"]);
        }
        $this->user_id = $this->user->user_id;

        if ($this->user->language) {
            $this->locale = $this->user->language;
        }

        //usergroup
        foreach ($this->user->UserGroup() as $ug) {
            $this->usergroup_id[] = $ug->usergroup_id;
        }

        foreach (SystemValue::Query() as $sv) {
            $this->system_value[$sv->language][$sv->name] = $sv;
        }
    }

    public function run()
    {
        //-- setting
        $this->setting = Yaml::parseFile(dirname(__DIR__, 2) . "/setting.yml");


        //-- Translate
        $translate = Yaml::parseFile(dirname(__DIR__, 2) . "/translate.yml");
        $translate = $translate[$this->user->language];
        $this->translate = $translate;

        //-- ACL
        $this->acl = [];
        $ugs = [];
        foreach ($this->user->UserGroup() as $ug) {
            $ugs[] = (string) $ug;
        }

        $acl = Yaml::parseFile(dirname(__DIR__, 2) . "/acl.yml");
        foreach ($acl["path"] as $path => $usergroups) {
            if (array_intersect($ugs, $usergroups)) {
                $this->acl["path"]["allow"][] = $path;
            }
        }

        foreach ($acl["action"] as $action => $actions) {
            foreach ($actions as $module => $usergroups) {
                if (array_intersect($ugs, $usergroups)) {
                    $this->acl["action"]["allow"][$module][] = $action;
                }
            }
        }

        $w = [];
        $u[] = "user_id=" . $this->user->user_id;
        foreach ($this->user->UserGroup() as $ug) {
            $u[] = "usergroup_id=$ug->usergroup_id";
        }
        $w[] = implode(" or ", $u);
        $query = ACL::Query()->where($w);
        foreach ($query as $acl) {
            if ($acl->action) {
                $this->acl["action"][$acl->value][$acl->module][] = $acl->action;
            } else {
                $this->acl["path"][$acl->value][] = $acl->path();
            }
        }

        //all special user
        foreach (ACL::Query()->where(["special_user is not null"]) as $acl) {
            $this->acl["special_user"][$acl->special_user][$acl->value][$acl->module][] = $acl->action;
        }



        ///-------------

        $this->alert = new Alert();

        $request = $this->request;
        $router = new \R\Router();

        $router->addRoute(function (RequestInterface $request, $loader) {
            return new Route($request, $this);
        });

        ob_start();
        $route = $router->getRoute($this->request, $this->loader);
        $request = $request->withAttribute("included_content", ob_get_contents());
        ob_end_clean();

        $request = $request->withAttribute("route", $route);


        $this->plugins = Yaml::parseFile(dirname(__DIR__, 2) . "/plugins.yml");

        //---- Module --
        $ps = explode("/", $route->path);
        $ps = array_values(array_filter($ps, "strlen"));
        foreach ($this->modules() as $module) {
            if ($module->name == $ps[0]) {
                $this->module = $module;
                break;
            }
        }

        //--- Module Translate 
        foreach (Translate::Query([
            "module" => $this->module->name,
            "language" => $this->user->language
        ]) as $translate) {
            $this->translate[$translate->name] = $translate->value;
        }

        $class = $route->class;


        if ($class) {
            $page = new $class($this);
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
            $base = dirname($this->request->getServerParams()["PHP_SELF"]);
            header("location: {$base}/404_not_found#" . $this->request->getUri()->getPath());
        } else {
            $base = dirname($this->request->getServerParams()["PHP_SELF"]);
            header("location: {$base}#" . $this->request->getUri()->getPath());
        }
    }

    public function flushMessage(): array
    {
        $msg = $_SESSION["app"]["message"];
        $_SESSION["app"]["message"] = [];
        return $msg ?? [];
    }

    public function page(string $path)
    {
        $uri = $this->request->getUri()->withPath($path);
        $request = $this->request->withUri($uri);

        $router = new \R\Router();
        $route = $router->getRoute($request, $this->loader);

        $request = $request
            ->withAttribute("action", $route->action)
            ->withAttribute("route", $route);

        $class = $route->class;
        $page = new $class($this);
        return $page;
    }

    public function IP2StepExemptCheck($ip): bool
    {
        $ips = explode(",", $this->config["user"]["2-step verification white list"]);

        foreach ($ips as $i) {
            $cx = explode("/", $i);
            if (sizeof($cx) == 1) {
                $cx[1] = "255.255.255.255";
            }
            $res = ip2long($cx[0]) & ip2long($cx[1]);
            $res2 = ip2long($ip) & ip2long($cx[1]);
            if ($res == $res2) {
                return true;
            }
        }
        return false;
    }

    public function login(string $username, string $password, string $code = null): bool
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
        $this->user_id = $user->user_id;

        return true;
    }

    public function logined(): bool
    {
        return (bool) $_SESSION["app"]["login"];
    }

    public function twig(string $file)
    {
        $pi = $this->pathInfo();

        $twig_file = null;

        if ($file[0] != "/") {
            if (is_readable($file)) {
                $twig_file = $file;
            } elseif (is_readable($pi["document_root"] . "/" . $file)) {
                $twig_file = $pi["document_root"] . "/" . $file;
            } elseif (is_readable($pi["system_root"] . "/" . $file)) {
                $twig_file = $pi["system_root"] . "/" . $file;
            }
        } else {
            if (is_readable($file)) {
                $twig_file = $file;
            } elseif (is_readable($pi["document_root"] . $file)) {
                $twig_file = $pi["document_root"] . $file;
            }
        }

        if ($twig_file) {
            $pi = pathinfo($twig_file);
            $root = $pi["dirname"];
            $template_file = $pi["basename"];

            if (!$config = $this->config["twig"]) {
                $config = [];
            }
            array_walk($config, function (&$o) use ($root) {
                $o = str_replace("{root}", $root, $o);
            });


            $twig["loader"] = new \Twig\Loader\FilesystemLoader($root);
            $twig["environment"] = new \Twig\Environment($twig["loader"], $config);
            $twig["environment"]->addExtension(new \Twig_Extensions_Extension_I18n());
            //$twig["environment"]->addExtension(new TwigI18n());

            $_this = $this;
            $twig["environment"]->addFilter(new \Twig\TwigFilter('trans', function ($str) use ($_this) {
                return $_this->translate($str ?? "");
            }));


            return $twig["environment"]->load($template_file);
        }
    }

    public function pathInfo(): array
    {
        $system_root = dirname(__DIR__, 2);

        $server = $this->request->getServerParams();

        $document_root = $server["DOCUMENT_ROOT"];
        if ($this->config["system"]["document_root"]) {
            $document_root = $this->config["system"]["document_root"];
        }

        $cms_base = dirname($server["PHP_SELF"]);
        $cms_root = $document_root . $cms_base;

        if (file_exists($document_root . "/composer.json")) {
            $composer_root = $document_root;
        } else if (file_exists($cms_root . "/composer.json")) {
            $composer_root = $cms_root;
        } else if (file_exists($cms_root . "/composer/composer.json")) {
            $composer_root = $cms_root . "/composer";
        } elseif (file_exists($system_root . "/composer.json")) {
            $composer_root = $system_root;
        }

        $composer_base = substr($composer_root, strlen($document_root));
        $composer_base = str_replace(DIRECTORY_SEPARATOR, "/", $composer_base);

        $system_base = substr($system_root, strlen($document_root));
        $system_base = str_replace(DIRECTORY_SEPARATOR, "/", $system_base);

        return compact("composer_base", "composer_root", "document_root", "cms_root", "cms_base", "system_root", "system_base");
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
                $modules[$name] = new Module($name, $config);
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
        return $this->translate[$str] ?? $str;
    }

    public function allowAction(string $action, string $module): bool
    {
        if ($this->user->isAdmin()) {
            return true;
        }

        $acl = $this->acl["action"]["deny"][$module];
        if (in_array("FC", $acl)) {
            return false;
        }
        if (in_array($action, $acl)) {
            return false;
        }
        $acl = $this->acl["action"]["allow"][$module];
        if (in_array("FC", $acl)) {
            return true;
        }
        if (in_array($action, $acl)) {
            return true;
        }
        return false;
    }

    public function acl(string $path): bool
    {
        if ($this->user->isAdmin()) {
            return true;
        }

        if (in_array($path, $this->acl["path"]["deny"])) {
            return false;
        }

        $module = $this->getModuleByPath($path);

        //deny
        $action = $this->acl["action"]["deny"][$module->name];
        if (in_array("FC", $action)) {
            return false;
        }

        $action = $this->acl["action"]["allow"][$module->name];
        if (in_array("FC", $action)) {
            return true;
        }


        $result = false;
        if ($this->config["system"]["user_default_acl"] && $this->user->isUser()) {
            if ($module) {
                if (!starts_with($module->class, "App")) { //module is not system module
                    $result = true;
                }
            }
        }

        if (!$result) {
            $result = (bool) in_array($path, $this->acl["path"]["allow"]);
        }

        return $result;
    }

    public function getModuleByPath(string $path)
    {
        $ps = explode("/", $path);
        $ps = array_values(array_filter($ps, "strlen"));
        return $this->module($ps[0]);
    }

    public function createMail()
    {
        $mail = new Mail(true);
        $smtp = $this->config["user"]["smtp"];

        if ($smtp && $smtp->value) {
            $mail->IsSMTP();
            $mail->Host = (string) $smtp;
            $mail->SMTPAuth = true;
            $mail->Username = $this->config["user"]["smtp-username"];
            $mail->Password = $this->config["user"]["smtp-password"];
        }

        return $mail;
    }

    public function accessDeny(ServerRequest $request): ResponseInterface
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
                $response = new Response(403);
                $response = $response->withHeader("location", $base . "/access_deny#/" . $uri);
            }
        } else {
            $response = new Response(403);
            $response = $response->withHeader("location", $base . "/#/" . $uri);
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

    public function composer(): Composer
    {
        return new Composer($this);
    }

    public function sv(string $name, $locale = null)
    {
        if (!$locale) $locale = $this->locale;
        if ($sv = $this->system_value[$locale][$name]) {
            if ($sv->_value) return $sv->_value;
            return $sv->_value = $sv->values();
        }
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

    public function loginFido2(string $username, string  $assertion): bool
    {
        $user = User::Query([
            "username" => $username
        ])->first();

        if (!$user) {
            return false;
        }

        if (!$user->isAllowLogin()) {
            return false;
        }

        $assertion = json_decode($assertion);
        $weba = new \R\WebAuthn($_SERVER["HTTP_HOST"]);
        if (!$weba->authenticate($assertion, $user->credential)) {
            return false;
        }

        $_SESSION["app"]["user_id"] = $user->user_id;
        $_SESSION["app"]["user"] = $user;
        $_SESSION["app"]["login"] = true;
        $user->createUserLog("SUCCESS");
        $user->online();
        $this->user = $user;

        return true;
    }

    public function ui(string $uri): UI
    {

        $ui = UI::Query([
            "user_id" => $this->user_id,
            "uri" => $uri
        ])->first();

        if (!$ui) {
            $ui = new UI();
            $ui->user_id = $this->user_id;
            $ui->uri = $uri;
        }

        return $ui;
    }
}
