<?php

namespace App;

class Composer
{
    public $app;
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function auth(): array
    {
        return json_decode(file_get_contents($this->path() . "/auth.json"), true);
    }

    public function installed(): array
    {
        $ret = self::exec("show -f json");
        $ret = json_decode($ret, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            //self update
            self::exec("self-update");
            $ret = self::exec("show -f json");
            $ret = json_decode($ret, true);
        }
        return $ret["installed"] ?? [];
    }

    public function show()
    {
        return self::exec("show -f json");
    }

    public function package($name)
    {
        $path = $this->path();
        foreach ($this->installed() as $p) {
            if ($p["name"] == $name) {
                $package = new Package();
                $package->name = $p["name"];
                $package->version = $p["version"];
                $package->description = $p["description"];
                $package->path = $path . "/vender/" . $name;

                return $package;
            }
        }
    }

    public function getPHP(): string
    {
        $v = PHP_VERSION_ID;
        $v = (string) intval($v / 100);
        $v = $v[0] . intval(substr($v, 1));

        $php = "php$v";
        if (`$php -v`) {
            return $php;
        }
        return "php";
    }

    public function exec(string $command): string
    {
        $cwd = getcwd();

        putenv("COMPOSER_HOME=" . $this->path());

        $phar = $this->phar();
        chdir($this->path());

        $php = $this->getPHP();
        if ($command) {
            $ret = `$php $phar $command 2>&1`;
        } else {
            $ret = `$php $phar 2>&1`;
        }

        chdir($cwd);

        return $ret;
    }

    public function hasPackage($package): bool
    {
        foreach ($this->installed() as $p) {
            if ($p["name"] == $package) {
                return true;
            }
        }
        return false;
    }

    public function path()
    {
        $p = $this->app->pathInfo();
        return $p["composer_root"];
    }

    public function packageSuggest($package)
    {
        $suggest = $this->exec("suggests $package");
        $suggest = explode("\n", $suggest);
        array_walk($suggest, "trim");
        $suggest = array_filter($suggest, "strlen");
        return $suggest;
    }

    public function suggests(): array
    {
        $suggest = $this->exec("suggests");
        $suggest = explode("\n", $suggest);
        array_walk($suggest, "trim");
        $suggest = array_filter($suggest, "strlen");
        return $suggest ?? [];
    }

    public function info(bool $checkupdate = false): array
    {
        if ($checkupdate) {
            $info = $this->exec("show -l --format=json");
        } else {
            $info = $this->exec("show --format=json");
        }

        return json_decode($info, true)["installed"] ?? [];
    }

    public function config()
    {
        if (file_exists($file = $this->path() . "/composer.json")) {
            return json_decode(file_get_contents($file), true);
        }
        return [];
    }

    public function lockConfig()
    {
        if (file_exists($file = $this->path() . "/composer.lock")) {
            return json_decode(file_get_contents($file), true);
        }
        return [];
    }

    public function phar()
    {
        if (!file_exists($file = $this->path() . "/composer.phar")) {
            file_put_contents($file, fopen("https://getcomposer.org/composer.phar", 'r'));
        }
        return $file;
    }

    public function changeOwn()
    {
        $folder = $this->path();
        `find $folder -type d -exec chmod 0777 {} +`;
        `find $folder -type f -exec chmod 0777 {} +`;
    }

    public function removeAll()
    {
        $folder = $this->path();
        `rm -rf $folder`;
    }

    public function remove($package)
    {
        return $this->exec("remove $package");
    }

    public function install($package, $version)
    {

        $config = $this->config();

        if ($config["require"][$package]) {
            return;
        }

        $ret = $this->exec("require $package");
        $this->changeOwn();

        return $ret;
    }
}
