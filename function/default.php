<?
if (!function_exists("outp")) {
    function outp($o)
    {
        echo "<pre>";
        print_r($o);
        echo "</pre>";
    }
}
