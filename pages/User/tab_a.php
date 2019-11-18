<?

class User_tab_a extends App\Page
{
    public function get()
    {
        $tab = $this->createTab("B");
        $tab->add("Tab B", "tab_b", json_encode(["a"=>1, "b"=>2]));
        $this->write($tab);
    }
}