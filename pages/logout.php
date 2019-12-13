<?

class _logout extends R\Page
{
    public function get()
    {
        if ($user = $this->app->user) {
            $user->logout();
        }
        $pi = $this->app->pathinfo();

        unset($_SESSION["app"]);

        $this->response = $this->response->withHeader("location", $pi["cms_base"]);
    }
}
