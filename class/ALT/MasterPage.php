<?php

namespace ALT;

use PHP\Psr7\StringStream;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use stdClass;

class MasterPage extends \R\Page
{
    public $data = [];
    private $template;

    /**
     * @var \App\App
     */
    public $app;

    public function __construct(\App\App $app)
    {
        $this->app = $app;
        $this->template = $app->twig("template/master.twig");
        $this->data = $app->pathInfo();
    }


    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $app = $this->app;

        $pi = $this->app->pathInfo();

        $this->data["base"] = $pi["cms_base"];


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


        $menu_gen = function ($ms) use (&$menu_gen, $app, $path) {
            $sidebar_menu = [];
            foreach ($ms as $modulegroup_name => $modules) {
                if (is_array($modules)) {

                    if (!sizeof($modules)) {
                        continue;
                    }

                    $menu = new NavItem();
                    $menu->label = $app->translate($modulegroup_name);
                    $menu->link = "#";
                    $menu->icon = $app->setting['group'][$modulegroup_name]["icon"] ?? "far fa-circle";
                    $menu->keyword = $menu->label . " " . $modulegroup_name;
                    $menu->active = false;
                    $menu->submenu = $menu_gen($modules);

                    foreach ($menu->submenu as $submenu) {
                        if ($submenu->active) {
                            $menu->active = true;
                        }
                    }
                    if (!sizeof($menu->submenu)) {
                        continue;
                    }

                    $sidebar_menu[] = $menu;
                } else {
                    $module = $modules;
                    $links = [];


                    foreach ($module->getMenuLink($path) as $link) {

                        if ($this->app->acl($link->link)) {
                            if ($link->badge) {
                                $p = $app->page($link->badge);
                                $link->badge = $p->get();
                            }

                            $links[] = $link;
                        }
                    }
                    if (!sizeof($links)) {
                        continue;
                    }
                    $menu = new NavItem();
                    $menu->label = $module->translate($module->name);
                    $menu->icon = $module->icon;
                    $menu->keyword = $module->keyword();

                    /*                    if ($module->badge) {
                        $p = $app->page($module->badge);
                        $menu["badge"] = $p->get();
                    }
*/

                    $menu->active = $app->module->name == $module->name;

                    if (sizeof($links) > 1) {
                        $menu->link = "#";
                        $menu->submenu = $links;
                    } else {
                        $menu->link = $links[0]->link;
                        $menu->target = $links[0]->target;
                    }
                    $sidebar_menu[] = $menu;
                }
            }
            return $sidebar_menu;
        };

        $sidebar_menu = $menu_gen($ms);


        foreach ($sidebar_menu as $menu) {
            $menu->getBadge();
        }

        $this->data["sidebar_menu"] = $sidebar_menu;
        $this->data["alerts"] = $this->app->flushMessage();
        $this->data["app"] = $this->app;

        if ($_SESSION["app"]["org_user_id"]) {
            $this->data["allow_cancel_viewas"] = true;
        }

        // my fav

        $this->data["favs"] = [];
        $favs = \App\UI::Query([
            "user_id" => $this->app->user->user_id,
            "uri" => 'fav'
        ]);


        foreach ($favs as $ui) {
            $content = json_decode($ui->layout, true);
            $this->data["favs"][] = $content;
        }

        /*
        $start = microtime(true);
        $this->app->composer->installed();
        $time_elapsed_secs = microtime(true) - $start;
        echo $time_elapsed_secs;
*/


        return $response->withBody(new StringStream($this->template->render($this->data)));
    }
}
