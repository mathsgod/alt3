<?php
// Create by: Raymond Chong
// Date: 27/6/2016 16:13:14
class MailLog_index extends ALT\Page {
    public function get() {
        $tab = $this->createTab();
        $tab->add("All mail log", "list");

        $this->write($tab);
    }
}