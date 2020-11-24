<?php

class System_update extends ALT\Page
{


    public function globr($sDir, $sPattern, $nFlags = null)
    {
        $sDir = escapeshellcmd($sDir);
        $aFiles = glob("$sDir/$sPattern", $nFlags);
        foreach (glob("$sDir/*", GLOB_ONLYDIR) as $sSubDir) {
            $aSubFiles = globr($sSubDir, $sPattern, $nFlags);
            $aFiles = array_merge($aFiles, $aSubFiles);
        }
        return $aFiles;
    }

    public function download($plugins)
    {
        $name = substr($plugins, 0, -3);
        $path = CMS_ROOT . "/plugins/" . $name;
        $gz = CMS_ROOT . "/plugins/$plugins";

        file_put_contents($gz, file_get_contents(App::Config("system", "update_source") . "plugins/$plugins"));
        // remove lib folder
        `rm -rf $path`;
        // create empty folder
        mkdir($path);
        // unzip it
        `tar -xvzf $gz -C plugins/$name`;
        // chmod
        `find $path -type d -exec chmod 0777 {} +`;
        `find $path -type f -exec chmod 0777 {} +`;
        // remove gz file
        unlink($gz);
        $this->alert->info("$plugins installed");
        $this->redirect("System/update");
    }

    public function deletePlugin($name)
    {
        if ($name) {
            $path = CMS_ROOT . "/plugins/" . $name;

            `rm -rf $path`;

            $this->alert->info($name . " removed");
        }
        $this->redirect("System/update");
    }

    public function get()
    {

        // check permission
        $pi = $this->app->pathInfo();
        extract($pi);

        $p = $this->app->page("System/db_check");

        if ($p->needUpdate()) {
            $this->callout->warning("System", "DB version updated, please update db.");
        }
        if (My\File::_($cms_root . "/plugins")->permission() != "0777") {
            $this->callout->warning("System", "$cms_root/plugins permission not equal 0777");
        }

        $this->navbar()->addButton("DB check", "System/db_check");


        $this->write("<h4>Use compose to update system: <a href='System/composer'>composer</a></h4>");
        $this->write("<h4>Your current system: " . $this->app->version() . "</h4>");

        return;


        $path = $this->app->config["system"]["update_source"] . "get3.php";
        $source = json_decode(file_get_contents($path), true);


        $path = $this->app->config["system"]["update_source"] . "plugins/get.php";
        $t = $this->createT(json_decode(file_get_contents($path), true));
        $t->header("Download new plugins");
        $t->add("Name", 'name');
        $t->add("Size", 'size');
        $t->add("Download")->button()->html("<i class='fa fa-fw fa-download'></i>")->href(function ($obj) {
            return "System/update/download?plugins=" . $obj["name"];
        })->addClass("btn-primary");
        $this->write($t);

        $t = $this->createT(glob(CMS_ROOT . "/plugins/*"));
        $t->header("Installed plugins");
        $t->add("Name", function ($i) {
            return basename($i);
        });
        $t->add("Permission", function ($s) {
            return substr(sprintf('%o', fileperms($s)), -4);
        });
        $t->add("")->button()->html("<i class='fa fa-fw fa-times'></i>")->href(function ($obj) {
            $s = basename($obj);
            return "System/update/deletePlugin?name=$s";
        })->addClass("btn-danger");
        $this->write($t);
    }
}
