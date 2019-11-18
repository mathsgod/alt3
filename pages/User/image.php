<?php
class User_image extends R\Page
{
    public function get($dummy)
    {
        $info = $this->app->pathInfo();


        $basepath = $this->app->basePath();


        $base = $info["system_base"];

        if ($dummy) {
            header("location: $base/images/user.png");
            return;
        }

        $path = $this->request->getUri()->getPath();
        $path = explode("/", $path);
        $user_id = $path[1];

        if (is_numeric($user_id)) {
            $user = new App\User($user_id);
        } else {
            $user = $this->app->user;
        }


        //$this->setHeader("Content-Type","application/jpeg");
        if (file_exists($f = $info["system_root"] . "/data/{$user->username}/profile.image")) {
            header("location: {$basepath}data/{$user->username}/profile.image");
        } else {

            header("location: {$base}/images/user.png");
        }
    }
}
