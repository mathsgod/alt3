<?php

use SplFileInfo;
use RecursiveIteratorIterator;
use My\TreeView;

//require_once(__DIR__ . "/str_chinese.php");
//require_once(__DIR__ . "/msgfmt-functions.php");
//require_once (__dir__ . "/accesstokenauthentication.php");

class PO
{

    public $parser;
    public $file;
    public function __construct(string $file)
    {
        if (!file_exists($file)) {
            throw new Exception("$file not found");
        }
        $this->file = $file;
        $this->parser = \Sepia\PoParser::parseFile($file, ["multiline-glue" => ""]);
    }

    public function setEntry($name, $value)
    {
        $previous = $this->getEntry($name);

        $this->parser->setEntry($name, [
            "msgid" => $name,
            "msgstr" => $value,
            "previous" => $previous
        ]);
    }

    public function getEntry($name)
    {
        $entries = $this->parser->getEntries();
        return $entries[$name];
    }

    public function save()
    {
        return $this->parser->writeFile($this->file);
    }
    public function getEntries()
    {
        return $this->parser->getEntries();
    }

    public function toMoFile(string $mo)
    {
        $hash = parse_po_file($this->file);
        if ($hash === false) {
            throw new Exception("Error reading $this->file, aborted");
        }
        write_mo_file($hash, $mo);
    }
}

class System_front_translate_twig extends \ALT\Page
{
    public function rglob($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, $this->rglob($dir . '/' . basename($pattern), $flags));
        }
        return $files;
    }

    public function generatePO()
    {
        $root = $this->getRootPath();
        $fpath = $this->frontPath();

        $twigs = [];
        foreach ($this->rglob($fpath . "/*.twig") as $twig) {
            $twigs[] = substr($twig, strlen($fpath));
        }

        foreach ($this->getLang() as $lang) {
            foreach ($twigs as $twig) {
                $fi = pathinfo($twig);

                $po = $root . "/locale/$lang/LC_MESSAGES/" . $fi["dirname"] . "/" . $fi["filename"] . ".po";

                if (!file_exists($po)) {
                    mkdir(dirname($po), 0777, true);
                    file_put_contents($po, "");
                }
            }
        }
    }

    public function applyGlobal()
    {
        $this->generatePO();
        $root = $this->getRootPath();
        $entries = $this->globalData()["entries"];

        try {
            foreach ($this->getLang() as $lang) {

                foreach ($this->rglob($root . "/locale/$lang/*.po") as $po_file) {
                    $po = new PO($po_file);

                    foreach ($entries as $entry) {
                        $po->setEntry($entry["name"], $entry["value"][$lang]);
                    }

                    $po->save();

                    //complie to mo
                    $fi = pathinfo($po_file);
                    $mo = dirname($po_file) . "/" . $fi["filename"] . "-" . time() . ".mo";
                    $po->toMoFile($mo);
                }
            }
        } catch (Exception $e) {
            return ["error" => ["message" => $e->getMessage()]];
        }
        return ["data" => true];
    }


    public function upldateGlobal()
    {
        $frontPage = new SplFileInfo($this->frontPath());
        $basePath = new SplFileInfo($frontPage->getPath());
        foreach ($this->getLang() as $lang) {
            $data = [];
            foreach ($_POST["data"] as $d) {
                $data[$d["name"]] = $d["value"][$lang];
            }

            $file = $basePath . "/locale/$lang/global.json";
            file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE));
        }
    }

    public function globalData()
    {
        $data = [];
        $frontPage = new SplFileInfo($this->frontPath());
        $basePath = new SplFileInfo($frontPage->getPath());
        foreach ($this->getLang() as $lang) {
            $file = $basePath . "/locale/$lang/global.json";
            if (!file_exists($file)) {
                file_put_contents($file, json_encode([]));
                $data[$lang] = [];
            } else {
                $data[$lang] = json_decode(file_get_contents($file), true);
            }
        }
        $ds = [];
        foreach ($data as $lang => $d) {
            foreach ($d as $n => $v) {
                $ds[$n][$lang] = $v;
            }
        }

        $data = [];
        foreach ($ds as $name => $v) {
            $data[] = ["name" => $name, "value" => $v];
        }

        return [
            "entries" => $data
        ];
    }

    public function post()
    {
        $post = json_decode(file_get_contents("php://input"), true);

        $data = $post["data"];
        $file = $post["file"];


        $frontPage = new SplFileInfo($this->frontPath());
        $basePath = new SplFileInfo($frontPage->getPath());

        $result = [];
        $fi = pathinfo($file);

        foreach ($this->getLang() as $lang) {

            if ($_POST["lang"]) {
                if ($_POST["lang"] != $lang) {
                    continue;
                }
            }

            if (!file_exists($po_file = $basePath . "/locale/$lang/LC_MESSAGES/" . $fi["dirname"] . "/" . $fi["filename"] . ".po")) {
                mkdir(dirname($po_file), 0777, true);
            }

            $ts = Gettext\Translations::fromPoFile($po_file);
            foreach ($data as $d) {
                $t = $ts->find("", $d["msgid"]);
                if ($t) {
                    $t->setTranslation($d["msgstr"][$lang]);
                } else {
                    $ts->insert("", $d["msgid"], $d["msgstr"]);
                }
            }

            $ts->toPoFile($po_file);

            // del all mo
            foreach (glob(dirname($po_file) . "/" . $fi["filename"] . "-*.mo") as $mo_file) {
                unlink($mo_file);
            }
            // convert po to mo
            $mo = $basePath . "/locale/$lang/LC_MESSAGES/" . $fi["dirname"] . "/" . $fi["filename"] . "-" . time() . ".mo";
          //  $ts->toMoFile($mo);
             
         
            $hash = parse_po_file($po_file);
            if ($hash === false) {
                print "Error reading '{$po_file}', aborted.\n";
            } else {
                write_mo_file($hash, $mo);
            }
 
            $result[] = ["po" => $po_file, "mo" => $mo];
        }
        return ["code" => 200, "result" => $result];
    }

    public function googleTranslate()
    {
        $t = new R\Translate;
        return ["text" => $t->translate($_POST["text"], $_POST["from"], $_POST["to"])];
    }

    public function t2s()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $str = $post["str"];

        return ["text" => str_chinese_simp($str)];
    }

    public function getLocaleFolder(): SplFileInfo
    {
        return new SplFileInfo(realpath($this->root . "/../locale"));
    }

    public function getRootPath()
    {
        $frontPage = new SplFileInfo($this->frontPath());
        $basePath = new SplFileInfo($frontPage->getPath());
        return $basePath;
    }

    public function getLang()
    {

        $this->template = null;
        // get front language
        $ini = parse_ini_file($this->app->document_root . "/config.ini", true);
        $lang = $ini["language"]["value"];
        $lang_locale_map = $ini["language_locale_map"];

        $la = [];
        foreach ($lang as $l) {
            if ($lang_locale_map[$l]) {
                $la[] = $lang_locale_map[$l];
            }
        }
        return $la;
    }

    private function getTextNode($h)
    {
        $n = [];
        foreach ($h as $c) {
            if ($c->tagName == "script") {
                continue;
            }
            if ($c->tagName == "style") {
                continue;
            }
            if ($c instanceof P\Text) {
                $n[] = $c;
            } else {
                foreach ($this->getTextNode($c->childNodes) as $n1) {
                    $n[] = $n1;
                }
            }
        }
        return $n;
    }

    public function getToken($file): array
    {

        $code = file_get_contents($this->frontPath() . "/" . $file);

        $loader = new Twig\Loader\FilesystemLoader($this->frontPath());
        $env = new Twig\Environment($loader, ["debug" => true]);
        $env->addExtension(new Twig\Extensions\I18nExtension());

        $stream = $env->tokenize(new Twig\Source($code, "trans"));
        $node = $env->parse($stream);


        $trans = [];

        $nodes = $node->getNode("body")->getIterator();
        foreach ($nodes as $node) {
            foreach ($node->getIterator() as $n) {
                if ($n instanceof Twig_Extensions_Node_Trans) {
                    $trans[] = $n->getNode("body")->getAttribute("value");
                }
            }
        }

        return $trans;
    }

    public function getTran(string $file)
    {


        $root = $this->app->document_root;
        $text_domain = preg_replace('/.[^.]*$/', '', $file);

        $data = [];
        foreach ($this->getLang() as $lang) {
            $dd = [];
            $f = $root . DIRECTORY_SEPARATOR . "locale" . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . "LC_MESSAGES" . DIRECTORY_SEPARATOR . $text_domain . ".po";
            if (file_exists($f)) {
                $ts = Gettext\Translations::fromPoFile($f);
                foreach ($ts as  $t) {

                    $org = $t->getOriginal();
                    $dd[$org] = $t->getTranslation();
                }
            }
            $data[$lang] = $dd;
        }



        $ds = [];
        foreach ($this->getToken($file) as $str) {
            $msgstr = [];
            foreach ($this->getLang() as $lang) {
                $msgstr[$lang] = $data[$lang][$str];
                unset($data[$lang][$str]);
            }
            $ds[] = ["msgid" => $str, "msgstr" => $msgstr];
        }

        return ["data" => $ds, "unuse" => $data];
    }

    public function frontPath()
    {
        return realpath($this->app->root . "/../pages");
    }

    public function frontPathStruct()
    {
        $root = $this->app->document_root;
        $root = $root . DIRECTORY_SEPARATOR . "pages";
        return $this->getFilesData($root);
    }

    private function getFilesData(string $path): array
    {
        $root = $this->app->document_root;
        $root = $root . DIRECTORY_SEPARATOR . "pages";


        $data = [];

        foreach (glob($path . DIRECTORY_SEPARATOR . "*", GLOB_ONLYDIR) as $d) {
            $data[] = [
                "label" => basename($d),
                "children" => $this->getFilesData($d)
            ];
        }

        foreach (glob($path . DIRECTORY_SEPARATOR . "*.twig") as $p) {
            $data[] = [
                "id" => substr($p, strlen($root) + 1),
                "label" => basename($p)
            ];
        }


        return $data;
    }

    public function renderTree($path, $tree)
    {

        $fpath = $this->frontPath();
        $file = [];
        foreach (glob($path . "/*") as $p) {
            $pi = pathinfo($p);
            if (is_file($p)) {
                $spl = new SplFileInfo($p);
                if ($spl->getExtension() != "twig") {
                    continue;
                }
                $file[] = $spl;
            } else {
                $folder = $tree->addFolder($pi["basename"]);
                $folder->icon("far fa-folder text-yellow");
                $this->renderTree($p, $folder);
            }
        }

        foreach ($file as $spl) {
            $basename = $spl->getBaseName();
            $f = $tree->addFile($basename);
            $path = $spl->getPathname();
            $file = substr($path, strlen($fpath) + 1);
            $f->a()->attr("onClick", "onClickFile('$file')");
        }
    }


    public function get()
    {
        $this->header->title = "Front translate";


        // get locale folder
        $locale_folder = $this->getLocaleFolder();
        if (!$locale_folder->isWritable()) {
            $this->callout->danger("", "Cannot write to locale, please create and change the permission of folder ({$locale_folder}) to 0777");
            $this->template = null;
            return;
        }

        /*         if (!$this->app->composer()->hasPackage("vakata/jstree")) {
            $this->callout->danger("Error", "vakata/jstree not found. <a href='System/composer?require=vakata/jstree'>Install it.</a>");
            $this->template = null;
            return;
        }


        if (!$this->app->composer()->hasPackage("sepia/po-parser")) {
            $this->callout->danger("Error", "sepia/po-parser not found. <a href='System/composer?require=sepia/po-parser'>Install it.</a>");
            $this->template = null;
            return;
        }
 */


        //$this->addLib("vakata/jstree");

        $tree = new TreeView();
        $this->renderTree($this->frontPath(), $tree);
        $this->data["tree"] = (string) $tree;
    }
}
