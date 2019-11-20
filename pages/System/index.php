<?php

class System_index extends ALT\Page
{
    public function get()
    {
        $panel = new BS\Panel("primary");
        $panel->heading("System");
        $panel->collapsible(false);

        $list = new BS\ListGroup();
        $list->addLinkedItem("Composer", "System/composer");
        //    	$list->addLinkedItem("Composer (system)","System/composer_system");
        //$list->addLinkedItem("Bower","System/bower");

        $list->addLinkedItem("Alter table charset", "System/alter_table_utf8");
        $list->addLinkedItem("alter table column charset to default", "System/alter_table_column_utf8");
        $list->addLinkedItem("Check TLS version", 'System/tlscheck');
        // $list->addLinkedItem("Clear MyView Session",'System/session_clear');
        $list->addLinkedItem("Show DB Process", "System/db_process");
        $list->addLinkedItem("System info", "System/phpinfo");
        //        $list->addLinkedItem("System check","System/check");
        $list->addLinkedItem("Email test", "System/email_test");
        $list->addLinkedItem("Clear UI", "UI/clear")->addClass('confirm');
        $list->addLinkedItem("System update", "System/update");
        $list->addLinkedItem("Shell", "System/shell");
        $list->addLinkedItem("CSV import", "System/csv_import");
        $list->addLinkedItem("Export", "System/export");
        //$list->addLinkedItem("WebAPI log","System/webapi");
        $list->addLinkedItem("PDO log", "System/pdo");
        //$list->addLinkedItem("wikiParser","System/wikiParser");
        $list->addLinkedItem("DB Check", "System/db_check");
        $list->addLinkedItem("DB migration", "System/db_migration");
        $list->addLinkedItem("Composer", "System/composer");
        $list->addLinkedItem("Adminer", "System/adminer");

        $list->addLinkedItem("Front translate", "System/front_translate_twig");
        $list->addLinkedItem("Unit test", "System/phpunit");

        $panel->body()->append($list);
        // $this->write($panel);
        $panel2 = new BS\Panel("primary");
        $panel2->heading("System locale");
        $panel2->collapsible(true);
        $panel2->body()->append(implode("<br/>", App\System::Locale()));

        $pg = new BS\PanelGroup();
        $pg->addPanel($panel);
        $pg->addPanel($panel2);
        $this->write($pg);
    }
}
