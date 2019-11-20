<?php
require_once(__DIR__."/str_chinese.php");

class System_front_translate extends ALT\Page {
    public function f1($h, $lang) {
        $n = [];
        foreach($h as $c) {
            foreach($lang as $l) {
                if (endsWith($c->tagName, $l))continue 2;
            }

            if ($c instanceof P\Text) {
                $n[] = $c;
            } else {
                foreach($this->f1($c->childNodes, $lang) as $n1) {
                    $n[] = $n1;
                }
            }
        }
        return $n;
    }

    public function post() {
        $h = p(trim($_POST["html"]));
        $_SESSION["app"]["system.ft.lang"] = $_POST["lang"];
        $_SESSION["app"]["system.ft.source"] = $_POST["html"];
        $lang = [];
    	foreach(explode("\n", $_SESSION["app"]["system.ft.lang"]) as $l){
    		$lang[]=trim($l);
    	}

        $n = $this->f1($h->contents(), $lang);

        foreach($n as $n1) {
            if (trim((string)$n1) == "")continue;
            foreach($lang as $l) {
                if ($n1->parentNode->tagName == ":{$l}")continue 2;
            }
            $html = "";
            foreach($lang as $l) {
            	$text=trim((string)$n1);
            	if($l=="zh-cn"){
            		$text=str_chinese_simp($text);
            	}

                $html .= p("<:$l></:$l>")->text($text);
            }
            p($n1)->replaceWith($html);
        }

        $_SESSION["app"]["system.ft"] = (string)($h);

        App::Redirect("System/front_translate");
        // outp($_POST);
    }

    public function get() {
        if (!$_SESSION["app"]["system.ft.lang"]) {
            $_SESSION["app"]["system.ft.lang"] = "zh-hk\nen";
        }

        $s = new stdClass();
        $s->html = $_SESSION["app"]["system.ft.source"];
        $s->lang = $_SESSION["app"]["system.ft.lang"];
        $s->res = $_SESSION["app"]["system.ft"];
        $e = $this->createE($s);
        $e->add("LANG")->textarea("lang");
        $e->add("HTML")->textarea("html")->css("height", "300px");
        if ($_SESSION["app"]["system.ft"]) {
            $e->add("OUTPUT")->textarea("res")->css("height", "300px");
        }
        $this->write($this->createForm($e));
    }
}

?>