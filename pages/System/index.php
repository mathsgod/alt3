<?php

class System_index extends ALT\Page
{
    public function get()
    {
        $card = $this->createCard();
        $card->header->title = "System";

        $list = html("ul")->class("list-group");
        $list->li->class("list-group-item")->a->href("System/composer")->text("Composer");
        $list->li->class("list-group-item")->a->href("System/alter_table_utf8")->text("Alter table charset");
        $list->li->class("list-group-item")->a->href('System/tlscheck')->text("Check TLS version");
        $list->li->class("list-group-item")->a->href("System/db_process")->text("Show DB Process");
        $list->li->class("list-group-item")->a->href("System/phpinfo")->text("System info");
        $list->li->class("list-group-item")->a->href("System/email_test")->text("Email test");
//        $list->li->class("list-group-item")->a->href("UI/clear")->text("Clear UI");
        $list->li->class("list-group-item")->a->href("System/update")->text("System update");
        $list->li->class("list-group-item")->a->href("System/shell")->text("Shell");
        $list->li->class("list-group-item")->a->href("System/csv_import")->text("CSV import");
        $list->li->class("list-group-item")->a->href("System/export")->text("Export");
        $list->li->class("list-group-item")->a->href("System/pdo")->text("PDO log");
        $list->li->class("list-group-item")->a->href("System/db_check")->text("DB Check");
        $list->li->class("list-group-item")->a->href("System/db_migration")->text("DB migration");
        $list->li->class("list-group-item")->a->href("System/composer")->text("Composer");
        $list->li->class("list-group-item")->a->href("System/adminer")->text("Adminer");

        $list->li->class("list-group-item")->a->href("System/front_translate_twig")->text("Front translate");
        $list->li->class("list-group-item")->a->href("System/phpunit")->text("Unit test");

        p($card->body)->html($list);

        $this->write($card);


        $card = $this->createCard();
        $card->header->title = "System locale";
        p($card->body)->html(implode("<br/>", $this->locale()));
        $this->write($card);
    }

    private function  locale(): array
    {
        $locale = `locale -a`;
        return array_filter(explode("\n", $locale), function ($l) {
            return strlen($l) > 0;
        });
    }
}
