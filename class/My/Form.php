<?php

namespace My;
class Form extends Query {
    public $method;
    public $enctype;

    private $submit_button;
    private $back_button;

    public $show_back = true;
    public $show_submit = true;

    public function __construct() {
        parent::__construct("form");
        $this->submit_button = new \BS\Button("success");
        $this->submit_button->text(\T::_("Submit"));
        $this->submit_button->attr("type", "submit");

        $this->back_button = new \BS\Button("warning");
        $this->back_button->text(\T::_("Back"));
        $this->back_button->attr("type", "button");
        $this->back_button->attr("onClick", 'javascript:history.back(-1)');
    }

    public function addHidden($name, $value) {
        $input = new Query("input");

        $input->attr("name", $name);
        $input->attr("type", "hidden");
        $input->val($value);
        $this->append($input);

        return $this;
    }

 /*   public static function _($contents, $multipart = false) {
        $form = new Form();

        if ($_SERVER['HTTP_REFERER'] != "") {
            $form->addHidden("_http_referer", $_SERVER['HTTP_REFERER']);
        }
        if ($_GET["_redirect"] != "") {
            $form->addHidden("_redirect", $_GET["_redirect"]);
        }

        if (\API::Object() && (\API::Object() instanceof \My\Object)) {
            $p = \API::Page();
            switch ($p["action"]) {
                case "ae":
                    $form->action(\API::Object()->uri());
                    break;
                default:
                    $form->action(\API::Object()->uri($p["action"]));
                    break;
            }
        }
        if ($multipart) {
            $form->attr("enctype", "multipart/form-data");
        }

        $form->attr("method", "post");
        if (is_array($contents)) {
            $form->append((string)implode("", $contents));
        } else {
            $form->append((string)$contents);
        }

        return $form;
    }*/

    public function submitCheck($func) {
        $this->submit_check = $func;
        return $this;
    }

    public function __toString() {
        if ($this->submit_check) {
            if (is_array($this->submit_check)) {
                $submit_check = $this->submit_check[0]->context()->request()->path() . "/" . $this->submit_check[1];
            } else {
                $submit_check = $this->submit_check;
            }

            $this->attr("submit_check", $submit_check);
        }

        if ($this->show_submit) {
            $html .= '<div class="clearfix">';
            if ($this->show_back) {
                $html .= '<div class="pull-left">' . $this->back_button . '</div>';
            }
            $html .= '<div class="pull-right">' . $this->submit_button . '</div>';
            $html .= '</div>';
            $this->append($html);
        }

        return "<div class='panel panel-default'><div class='panel-body'>" . parent::__tostring() . "</div></div>";
    }
}

?>