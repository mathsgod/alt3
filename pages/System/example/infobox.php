<?

class System_example_infobox extends ALT\Page
{
    public function get()
    {
        $box = new ALT\InfoBox;
        $box->addText("abc");
        $box->addNumber(123);
        $box->setIcon("fa fa-fw fa-user","red");

        $this->write($box);
    }
}
