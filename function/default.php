<?
if (!function_exists("outp")) {
    function outp($o)
    {
        echo "<pre>";
        print_r($o);
        echo "</pre>";
    }
}

if (!function_exists("tick")) {
    function tick($value)
    {
        if ($value) {
            return "<i class='fa fa-check'></i>";
            return "&#x2713;";
        }
    }
}


if (!function_exists("nf")) {
    function nf($value)
    {
        return number_format($value, 2);
    }
}
