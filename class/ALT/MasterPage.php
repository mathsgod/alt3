<?php

namespace ALT;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use R\Psr7\Stream;


class MasterPage extends \R\Page
{

    public $data = [];
    private $template;

    public function __construct($app)
    {
        $this->app = $app;
        $this->template = $app->twig("template/master.twig");
        $this->data = $app->pathInfo();
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $app = $this->app;

        $pi = $this->app->pathInfo();

        $this->data["base"] = $pi["system_base"] . "/";


        //group menu to structure
        $g = function (&$gs, &$m) use (&$g) {
            if (is_array($gs)) {
                foreach ($gs as $k => &$v) {
                    if (is_array($v)) {
                        $g($v, $m);
                    } else {
                        $v[] = $m;
                    }
                }
            }
        };

        $ms = [];
        foreach ($this->app->modules() as $module) {
            if ($module->hide)
                continue;
            if (is_array($module->group)) {
                $gs = $module->group;
                $g($gs, $module);
                $ms = array_merge_recursive($ms, $gs);
            } elseif ($module->group) {
                $ms[$module->group][] = $module;
            } else {
                $ms[] = $module;
            }
        }
        $path = $request->getUri()->getPath();
        if ($path[0] == "/") $path = substr($path, 1);

        $menu_gen = function ($ms) use (&$menu_gen, $app, $path, $group_icon) {
            $sidebar_menu = [];
            foreach ($ms as $modulegroup_name => $modules) {
                if (is_array($modules)) {

                    if (!sizeof($modules)) {
                        continue;
                    }

                    $menu = [];
                    $menu["label"] = $app->translate($modulegroup_name);
                    $menu["link"] = "#";
                    $menu["icon"] = $group_icon[$modulegroup_name] ? $group_icon[$modulegroup_name] : "fa fa-link fa-fw";
                    $menu["keyword"] = $menu["label"] . " " . $modulegroup_name;
                    $menu["active"] = false;
                    $menu["submenu"] = $menu_gen($modules);
                    foreach ($menu["submenu"] as $submenu) {
                        if ($submenu["active"]) {
                            $menu["active"] = true;
                        }
                    }
                    if (!sizeof($menu["submenu"])) {
                        continue;
                    }

                    $sidebar_menu[] = $menu;
                } else {
                    $module = $modules;
                    $links = $module->getMenuLink($path);
                    if (!sizeof($links)) {
                        continue;
                    }
                    $menu = [];
                    $menu["label"] = $module->translate($module->name);
                    $menu["icon"] = $module->icon;
                    $menu["keyword"] = $module->keyword();


                    $menu["active"] = $app->module->name == $module->name;

                    if (sizeof($links) > 1) {
                        $menu["link"] = "#";
                        $menu["submenu"] = $links;
                    } else {
                        $menu["link"] = $links[0]["link"];
                        $menu["target"] = $links[0]["target"];
                    }
                    $sidebar_menu[] = $menu;
                }
            }
            return $sidebar_menu;
        };

        $this->data["sidebar_menu"] = $menu_gen($ms);


        $this->data["alerts"] = $this->app->flushMessage();


        //   outp($this->data["sidebar_menu"]);

        $stream = new Stream($this->template->render($this->data));

        return $response->withBody($stream);
        return $response;
    }


    /*  return;
        $user = $this->app->user;
        $setting = json_decode($user->setting, true);

        if ($setting["layout"] == "top-nav") {
            $template_file = $app->getFile("AdminLTE/top-nav.html");
        } else {
            $template_file = $app->getFile("AdminLTE/index.html");
        }

        $template_dir = dirname($template_file);
        //\Twig_Autoloader::register();
        $this->_twig["loader"] = new \Twig_Loader_Filesystem($template_dir);
        $this->_twig["environment"] = new \Twig_Environment($this->_twig["loader"]);
        $this->_twig["environment"]->addExtension(new \Twig_Extensions_Extension_I18n());

        if ($config = $this->app->config["user"]["roxy_fileman_path"]) {
            $_SESSION["roxy_fileman_path"] = str_replace("{username}", $user->username, $config);
        }

        $translate_res = [];
        foreach (parse_ini_file($app->getFile("translate.ini"), true) as $k => $v) {
            $translate_res[$k] = $v;
        }
        // translate function
        $function = new \Twig_SimpleFunction('_', function ($a) use ($translate_res) {
            $lang = \App::User()->language;
            if ($text = $translate_res[$lang][$a]) {
                return $text;
            }

            return $a;
        });
        $this->_twig["environment"]->addFunction($function);

        $this->_template = $this->_twig["environment"]->loadTemplate(basename($template_file));
    }

    public function assign($name, $value)
    {
        $this->data[$name] = $value;
    }*/

    /*return;
        $app = $this->app;
        $config = $app->config;
        $data = $this->data;

        // get the data
        $data["lang"] = \My::Language();
        if ($config["user"]["development"]) {
            $data["development"] = "development";
        }
        $data["setting"] = json_decode($this->app->user->setting, true);

        if ($data["setting"]["control-sidebar"]) {
            $data["sidebar"] = $data["setting"]["control-sidebar"];
        } else {
            $data["sidebar"] = "dark";
        }

        if (($skin = \App::User()->skin()) instanceof Skin) {
            $data["skin"] = $skin->setting["base"];
        } else {
            $data["skin"] = $skin;
        }

        $data["user"] = [];
        $data["user"] = (array) $this->app->user;
        $data["user"]["name"] = (string) $this->app->user;
        $data["user"]["usergroup"] = $this->app->user->UserGroup()->implode(",");

        $data["system"] = [];
        $data["system"]["version"] = $this->app->version();

        $data["copyright"]["url"] = $config["user"]["copyright-url"];
        $data["copyright"]["year"] = $config["user"]["copyright-year"];
        $data["copyright"]["name"] = $config["user"]["copyright-name"];

        $data["company"] = $config["user"]["company"];
        $data["logo-mini"] = $config["user"]["logo-mini"];
        $data["logo"] = $config["user"]["logo"];
        $data["base"] = $this->app->basePath() . "/";

        if (\App::User()->isAdmin()) {
            $data["allow_viewas"] = true;
        }

        if ($_SESSION["app"]["org_user"]) {
            $data["allow_cancel_viewas"] = true;
        }

        $firebase = $config["firebase"];
        if ($firebase["apiKey"]) {
            $data["firebase"] = true;
        }

        if ($config["link"]["no-cache"]) {
            $data["t"] = time();
        }


        $custom_header = $app->getFile("AdminLTE/custom-header.html");
        $data["custom_header"] = file_get_contents($custom_header);

        $ms = [];

        //group menu to structure
        $g = function (&$gs, &$m) use (&$g) {
            if (is_array($gs)) {
                foreach ($gs as $k => &$v) {
                    if (is_array($v)) {
                        $g($v, $m);
                    } else {
                        $v[] = $m;
                    }
                }
            }
        };

        $ms = [];
        foreach ($app->getModule() as $m) {
            if ($m->hide) {
                continue;
            }
            if (is_array($m->group)) {
                $gs = $m->group;
                $g($gs, $m);
                $ms = array_merge_recursive($ms, $gs);
            } elseif ($m->group) {
                $ms[$m->group][] = $m;
            } else {
                $ms[] = $m;
            }
        }

        $group_icon = [];
        if ($ini = $app->getFile("icon.ini")) {
            $group_icon = array_merge($group_icon, parse_ini_file($ini));
        }
        if ($ini = $app->getFile("pages/icon.ini")) {
            $group_icon = array_merge($group_icon, parse_ini_file($ini));
        }

        $path = $request->getUri()->getPath();
        if ($path[0] == "/") $path = substr($path, 1);
        $current_module = $request->getAttribute("module");


        $menu_gen = function ($ms) use (&$menu_gen, $app, $path, $current_module, $group_icon) {
            $sidebar_menu = [];
            foreach ($ms as $modulegroup_name => $modules) {
                if (is_array($modules)) {

                    if (!sizeof($modules)) {
                        continue;
                    }

                    $menu = [];
                    $menu["label"] = $app->t($modulegroup_name);
                    $menu["link"] = "#";
                    $menu["icon"] = $group_icon[$modulegroup_name] ? $group_icon[$modulegroup_name] : "fa fa-link fa-fw";
                    $menu["keyword"] = $menu["label"] . " " . $modulegroup_name;
                    $menu["active"] = false;
                    $menu["submenu"] = $menu_gen($modules);
                    foreach ($menu["submenu"] as $submenu) {
                        if ($submenu["active"]) {
                            $menu["active"] = 1;
                        }
                    }
                    if (!sizeof($menu["submenu"])) {
                        continue;
                    }

                    $sidebar_menu[] = $menu;
                } else {
                    $module = $modules;
                    $links = $module->getMenuLink($path);
                    if (!sizeof($links)) {
                        continue;
                    }
                    $menu = [];
                    $menu["label"] = $module->translate($module->name);
                    $menu["icon"] = $module->icon;
                    $menu["keyword"] = $module->keyword();

                    if ($current_module->name == $module->name) {
                        $menu["active"] = true;
                    }

                    if (sizeof($links) > 1) {
                        $menu["submenu"] = $links;
                    } else {
                        $menu["link"] = $links[0]["link"];
                        $menu["target"] = $links[0]["target"];
                    }
                    $sidebar_menu[] = $menu;
                }
            }
            return $sidebar_menu;
        };
        $data["sidebar_menu"] = $menu_gen($ms);


        extract($app->pathInfo());

        $system = $system_base;
        $data["composer_base"] = $composer_base;
        $data["script"][] = "$system/js/cookie.js";
        $data["script"][] = "$system/js/jquery.storageapi.min.js";


        if (file_exists(getcwd() . "/system/{$version}/plugins/RT/locale/" . $data["lang"] . ".js")) {
            $data["script"][] = "system/{$version}/plugins/RT/locale/" . $data["lang"] . ".js";
        }

        $data["script"][] = "$system/plugins/RT/rt.js";
        $data["script"][] = "$system/js/tabajax.js";
        $data["script"][] = "$system/js/confirm.js";

        $data["css"][] = "$system/plugins/RT/css/rt.css";

        $data["alerts"] = $this->app->flushMessage();


        $data["languages"] = $app->config["language"];

        $data["favs"] = [];
        // my fav
        $ds = \App\UI::find(["user_id=" . $app->user->user_id, "uri='fav'"]);
        $ds = $ds->usort(function ($a, $b) {
            if ($a->content()["sequence"] > $b->content()["sequence"]) {
                return 1;
            } elseif ($a->content()["sequence"] < $b->content()["sequence"]) {
                return -1;
            }
            return 0;
        });
        foreach ($ds as $ui) {
            $content = json_decode($ui->layout, true);
            $data["favs"][] = $content;
        }

        //print_r($data["jss"])

        $stream = new Stream($this->_template->render($data));


        return $response->withBody($stream);
    }


    public function template()
    {
        return $this->_template;
    }*/
}
