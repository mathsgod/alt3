<?

namespace App;

use Composer\Autoload\ClassLoader;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\RequestInterface;
use R\Psr7\Response;
use R\Psr7\Stream;
use Symfony\Component\Yaml\Yaml;

class App extends \R\App
{
    public $config = [];

    public function __construct(string $root = null, ClassLoader $loader = null, LoggerInterface  $logger = null)
    {
        //check config file
        if (!file_exists($root . "/config.ini")) {
            throw new Exception("config.ini not found");
        }

        parent::__construct($root, $loader, $logger);
        Core\Model::$_db = $this->db;

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
                    echo $e->getMessage();
                    die();
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

    public function modules(): array
    {
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


        return $modules;
    }

    public function translate(string $str): string
    {
        return $str;
    }

    public function acl(string $path): bool
    {
        return true;
    }
}
