<?
class System_example_createT extends ALT\Page
{
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

        $t->add("Start", "start");
        $t->add("End", "end");
        $t->add("To", "to");
        $t->add("Final", "final");

        $t->setCreate("User/ae")->fancybox();

        $this->write($t);
    }
}
