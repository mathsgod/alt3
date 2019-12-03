<?

class _cancel_viewas extends App\Page
{
    public function get()
    {

        if ($_SESSION["app"]["org_user"]) {
            $_SESSION["app"]["user"] = new App\User($_SESSION["app"]["org_user"]->user_id);
            unset($_SESSION["app"]["org_user"]);
        }

        $this->redirect();
    }
}
