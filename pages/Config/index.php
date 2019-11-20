<?php

class Config_index extends ALT\Page {
    public function get() {
        $tab = $this->createTab();
        $tab->add("All Config", "list");
        $tab->add("User Config", "user_list");
        $this->write($tab);
    }
}