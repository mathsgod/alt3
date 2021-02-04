<?php



class System_example_ae extends ALT\Page
{
    public function post()
    {
        outp($_POST);
        die();
    }

    public function get()
    {
        $f = $this->createRForm();

        $f->add("input")->input("a");
        $this->write($f);


        return;
        $table = $this->createFormTable(App\User::Query()->toArray(), "user_id", "users");

        $table->add("Username")->input("username")->required();
        $table->add("Email")->email("email")->required();
        $table->add("Number")->number("status");
        $table->add("Status")->select("status", [0 => "Active", 1 => "Inactive"]);
        $table->add('remark')->textarea("remark");

        $table->add("upload")->fileman("test");
        //      $table->add("Join date")->datePicker("join_date");

        //$table->add("Check box")->checkbox("status");

        $this->write("<form method='POST'><vue>$table</vue> <button type='submit'>Submit</button></form>");
        return;

        $langs = ["en" => "EN", "zh-hk" => "HK"];
        $form = $this->createRForm([
            "color1" => "#409EFF"
        ]);

        $form->add("Fileman")->fileman("file1");

        $form->add("color picker")->colorPicker("color1");
        $form->add("Select option group")->multiselect("user_id")->optionGroup($langs, "language", App\User::Query());
        //$form->add("Select option group")->select("user_id")->option(App\User::Query(), "username", "user_id");
        $this->write($form);
    }
}
