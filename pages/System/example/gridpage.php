<?php
class System_example_gridpage extends ALT\Page
{
    public function get()
    {

        $grid = $this->createGrid([2, 3]);

        $card1 = $this->createCard("primary");
        $card1->collapsible(true);
        $card1->body->innerHTML = "hello1";

        $grid->add($card1);


        $card1 = $this->createCard();
        $card1->collapsible(true);
        $card1->body->innerHTML = "hello2";
        $grid->add($card1);


        $card1 = $this->createCard();
        $card1->collapsible(true);
        $card1->body->innerHTML = "hello3";
        $grid->add($card1);


        $card1 = $this->createCard();
        $card1->collapsible(true);
        $card1->body->innerHTML = "hello4";
        $grid->add($card1);


        $this->write($grid);
    }
}
