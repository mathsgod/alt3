<?php
class System_example_input extends ALT\Page
{
    public function get()
    {

        $e = $this->createE([]);
        $e->add("input")->input("a")->required();
        $this->write($this->createForm($e));
    }
}
