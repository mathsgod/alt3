<?

class _logout extends R\Page
{
    public function get()
    {
        $pi = $this->app->pathinfo();

        unset($_SESSION["app"]);

        $this->response = $this->response->withHeader("location", $pi["system_base"]);
    }
}
