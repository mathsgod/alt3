<?

class System_example_grid extends ALT\GridPage
{
    public function get()
    {

        $t = $this->createT(App\User::Query());
        $t->header->title="Test";
        $t->add("Username", "username");
        $this->write($t);
    }
}
