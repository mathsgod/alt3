<?php
class UI_reset_layout extends App\Page
{
    public function get($uri)
    {
        $w = [];
        $w[] = ["user_id=?", $this->app->user_id];
        $w[]= ["uri like ?",$uri."%"];
        foreach (App\UI::Find($w) as $ui) {
            $ui->delete();
        }
        $this->redirect();
    }
}