<?php

class DynamicPage_edit_data extends ALT\Page
{
    public function post()
    {

        $data = $this->request->getParsedBody();

        $obj = $this->object();
        $obj->data = $data;

        $obj->save();

        return ["data" => true];
    }

    public function structure()
    {

        $obj = $this->object();
        $path = $this->app->document_root . "/" . $obj->path;
        $code = file_get_contents($path);

        $ext = new Twig\Dynamic\Extension();

        $ret = $ext->parse($code);

        return $ret;
    }
    public function data()
    {
        return $this->object()->data;
    }
}
