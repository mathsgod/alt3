<?

class UI_delete extends App\Page
{

    public function get()
    {
        $this->object()->delete();
        $this->redirect("User/myfav");
    }
}
