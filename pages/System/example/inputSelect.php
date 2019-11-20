<?

class System_example_inputSelect extends ALT\Page
{
    public function get()
    {

        $e = $this->createE([]);

        $ds["a"] = "a";
        $ds["b"] = "b";
        $ds["c"] = "c";
        $e->add("input select")->inputSelect("username")->ds($ds);
        $this->write($e);
    }
}
