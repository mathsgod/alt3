<?php

class System_html2twig extends ALT\Page {
    public function f1($h) {
        $n = [];
        foreach($h as $c) {
            if ($c instanceof P\Text) {
                $n[] = $c;
            } else {
                foreach($this->f1($c->childNodes) as $n1) {
                    $n[] = $n1;
                }
            }
        }
        return $n;
    }

    public function post() {
        $h = p(trim($_POST["html"]));
        $_SESSION["app"]["system.ft.source"] = $_POST["html"];

        $n = $this->f1($h->contents());

        foreach($n as $n1) {
            if (trim((string)$n1) == "")continue;
            $str = str_replace('"', '\"' , (string)$n1);
            $html = '{% trans "' . trim($str) . '" %}';

            $n1->wholeText = trim($html);
        }

        $_SESSION["app"]["system.ft"] = (string)$h;

        App::Redirect("System/html2twig");
    }

    public function get() {
        $s = new stdClass();
        $s->html = $_SESSION["app"]["system.ft.source"];
        $s->res = $_SESSION["app"]["system.ft"];
        $e = $this->createE($s);
        $e->add("HTML")->textarea("html")->css("height", "300px");
        if ($_SESSION["app"]["system.ft"]) {
            $e->add("TWIG")->textarea("res")->css("height", "300px");
        }
        $this->write($this->createForm($e));
    }
}

?>