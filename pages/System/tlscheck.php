<?php

class System_tlscheck extends ALT\Page
{
    public function get()
    {
        $ch = curl_init('https://www.howsmyssl.com/a/check');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSLVERSION, 1);

        $data = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($data, true);



        $this->write("TLS version:" . $json["tls_version"]);
        $this->write("<pre>$json</pre>");
    }
}
