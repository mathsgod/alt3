<?phpclass System_example_createT extends ALT\Page
{
    public function post()
    {
        outp($_POST);
    }

    public function get()
    {

        $data = [];
        foreach (range(1, 10) as $i) {
            $data[] = [
                "start" => $i,
                "end" => $i * 2,
                "to" => $i * 3,
                "final" => $i * 4
            ];
        }

        $t = $this->createT($data);
        $t->formCreate("hello", [
            "start" => 'abc'
        ]);
        $t->table->setAttribute("form-addbottom", true);

        $t->add("Start")->input("start");
        $t->add("End", "end");
        $t->add("To", "to");
        $t->add("Final", "final");



        $this->write($this->createForm($t));
    }
}
