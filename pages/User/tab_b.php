<?

class User_tab_b extends App\Page
{
    public function get()
    {
        $rt = $this->createRT([$this, "ds"]);
        $rt->add("A", "username");
        $this->write($this->write($rt));
    }

    public function ds($rt)
    {


        outp($_GET);
    }
}