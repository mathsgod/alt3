<?php
use App\Translate;
class Translate_resource extends App\Page {
    public function get($lang) {
        $lang = strtolower($lang);
        // get file first
        $ts = [];

        $ini = parse_ini_file(\App::Path("translate.ini"));
        foreach($ini as $k => $v) {
            $ts[$k] = $k;
        }

        $ini = parse_ini_file(\App::Path("translate.ini"), true);

        foreach($ini[$lang] as $k => $v) {
            $ts[$k] = $v;
        }
        // $langs = explode("-", $lang);
        $w[] = "language=" . App::DB()->quote($lang);
        $w[] = "module is null";
        $w[] = "action is null";

        foreach(Translate::find($w) as $t) {
            $ts[$t->name] = $t->value;
        }

        return ["app" => $ts];
    }
}

?>