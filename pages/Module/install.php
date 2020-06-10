<?phpuse App\FTP;

class Module_install extends ALT\Page
{
    public function useFTP()
    {
        //try to connect
        $server = $_POST["server"];
        $connection = ftp_connect($server);
        if (!$connection) {
            return ["error" => ["message" => "Connection attempt failed!"]];
        }

        $login = ftp_login($connection, $_POST["username"], $_POST["password"]);

        if (!$connection || !$login) {
            return ["error" => ["message" => "Login attempt failed!"]];
        }

        $_SESSION["app"]["ftp"]["server"] = $_POST["server"];
        $_SESSION["app"]["ftp"]["username"] = $_POST["username"];
        $_SESSION["app"]["ftp"]["password"] = $_POST["password"];

        return ["code" => 200];

    }

    public function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object))
                        $this->rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            rmdir($dir);
        }
    }

    public function rmove($src, $dest)
    {

        // If source is not a directory stop processing
        if (!is_dir($src)) return false;
    
        // If the destination directory does not exist create it
        if (!is_dir($dest)) {
            if (!mkdir($dest)) {
                // If the destination directory could not be created stop processing
                return false;
            }
        }
    
        // Open the source directory to read in files
        $i = new DirectoryIterator($src);
        foreach ($i as $f) {
            if ($f->isFile()) {
                rename($f->getRealPath(), "$dest/" . $f->getFilename());
            } else if (!$f->isDot() && $f->isDir()) {
                $this->rmove($f->getRealPath(), "$dest/$f");
                unlink($f->getRealPath());
            }
        }
        unlink($src);
    }

    public function getFTP()
    {
        $ftp = new FTP($_SESSION["app"]["ftp"]["server"]);
        $ftp->login($_SESSION["app"]["ftp"]["username"], $_SESSION["app"]["ftp"]["password"]);
        return $ftp;
    }

    public function ftp($id)
    {
        $ftp = $this->getFTP();

        if ($id == "#") {
            $contents = $ftp->nlist("/");
        } else {
            $contents = $ftp->nlist($id);
        }

        $rets = [];

        foreach ($contents as $c) {
            if ($ftp->isDir($c)) {
                $rets[] = [
                    "id" => $c,
                    'text' => basename($c),
                    "icon"=>"fa fa-folder text-yellow",
                    "children" => true
                ];

            }

        }

        $ftp->close();


        return $rets;
    }

    public function get()
    {
        $this->addLib("vakata/jstree");
    }


    public function getChild($full_name)
    {
        $t = $this->createT($this->getGitHubRelease($full_name));
        $t->add("Tag name", "tag_name");
        $t->add("Install", function ($o) use ($full_name) {

            $tag_name = $o["tag_name"];
            $url = "https://github.com/$full_name/archive/$tag_name.zip";
            $btn = html("a");
            $btn->class("btn btn-xs btn-success");
            $btn->href("Module/install/github?url=$url&name=$full_name&tag=$tag_name");
            $btn->text("Install");
            return $btn;
        });

        $t->add("Download", function ($o) use ($full_name) {
            $tag_name = $o["tag_name"];
            $url = "https://github.com/$full_name/archive/$tag_name.zip";
            $btn = html("a");
            $btn->class("btn btn-xs btn-success")->target("_blank");
            $btn->href($url);
            $btn->text("Download");
            return $btn;
        });

        $this->write($t);
    }

    public function unzip($file)
    {
        $pi = $this->app->pathInfo();

        $name = basename($file);
        $name = substr($name, 0, -7);

        $phar_path = "phar://" . $file;
        $target = $pi["cms_root"] . "/pages";

        $phar = new PharData($phar_path);
        $phar->extractTo($target);
    }

    public function installReleaseFTP($name, $tag, $target)
    {
        $basename = basename($name);
        $url = "https://github.com/$name/archive/$tag.zip";


        $temp_dir = sys_get_temp_dir();
        $temp_file = tempnam(sys_get_temp_dir(), "test");
        file_put_contents($temp_file, file_get_contents($url));

        $zip = new ZipArchive;
        if ($zip->open($temp_file) === true) {
            $zip->extractTo($temp_dir . "/" . $name);
            $zip->close();

            $rp = realpath($temp_dir . "/$name/$basename-$tag/src/");

            $ftp = $this->getFTP();
            $errorList = $ftp->send_recursive_directory($rp, $target);

            $ftp->disconnect();

        }

        return ["code" => 200];



    }

    public function installRelease($name, $tag, $use_ftp, $target)
    {
        if ($use_ftp) {
            return $this->installReleaseFTP($name, $tag, $target);
        }

        $url = "https://github.com/$name/archive/$tag.tar.gz";


        $ret = file_get_contents($url);
        $pi = $this->app->pathInfo();

        $name = basename($name);

        $page = $pi["cms_root"] . "/pages";
        $tar = "$page/$name-$tag.tar.gz";

        file_put_contents($tar, $ret);

        $this->rrmdir($page . "/$name-$tag");

        $this->unzip($tar);

        $this->rmove($page . "/$name-$tag/src", $pi["cms_root"] . "/pages");

        $this->rrmdir($page . "/$name-$tag");

        unlink($tar);

        return ["code" => 200];



    }

    public function getGitHubRelease($full_name)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-Agent: hostlink/r-alt"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return data inplace of echoing on screen
        curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/$full_name/releases");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec($ch);
        curl_close($ch);

        $ret = json_decode($ret, true);

        return $ret;
    }

    public function getGitHub()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-Agent: hostlink/r-alt"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return data inplace of echoing on screen
        curl_setopt($ch, CURLOPT_URL, "https://api.github.com/search/repositories?q=user:hostlink");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec($ch);
        curl_close($ch);

        $ret = json_decode($ret, true);

        return $ret['items'];

        return $ret;
    }

}