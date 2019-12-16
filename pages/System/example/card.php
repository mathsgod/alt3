<?

class System_example_card extends ALT\Page
{
    public function get()
    {

        $card = $this->createCard("primary");
        $card->header->title = "abc";
        $card->header->tools->addBadge("test");
        p($card->header->tools->addButton(""))->html("<i class='fa fa-user'></i>");

        $dd = $card->header->tools->addDropdown("A");
        $dd->addItem("test", "User/1/v");

        $card->body->innerText = "test";

        $card->collapsible(true);


        $div = p("div");
        $div[0]->appendChild($card);

        $this->write($div);

        //$c = $this->createCard();
    }
}
