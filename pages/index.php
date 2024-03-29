<?php

/**
 * @property App\App $app
 */
class _index extends R\Page
{
    public function get($token)
    {
        $config = $this->app->config;

        if ($this->app->logined()) {
            $user = $this->app->user;
            if ($user->secret == "" && $config["user"]["2-step verification"]) {
                $this->response = $this->response->withHeader("Location", "User/2step?auto_create=1");
                return;
            }

            if ($p = $user->default_page) {
                $this->response = $this->response->withHeader("Location", $p);
            } else {
                $this->response = $this->response->withHeader("Location", "Dashboard");
            }

            return;
        }

        if ($token) {
            if ($this->app->loginByToken($token)) {
                $this->response = $this->response->withHeader("Location", "User/profile");
                return;
            }
        }

        $twig = $this->app->twig(__DIR__ . "/index.twig");

        $pi = $this->app->pathInfo();

        $data = $pi;
        $data["app"] = $this->app;

        $this->write($twig->render($data));
    }
}
