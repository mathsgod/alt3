<?php

class EFormItem
{

    public function input()
    {
    }
}

class EForm
{

    public function __construct()
    {
        $this->form = new Element\Form();
    }

    public function add(string $label): EFormItem
    {
        $formitem = new EFormItem();
        $this->form->append($formitem);
        return $formitem;
    }
}


class System_test extends ALT\Page
{
    public function post()
    {
        outP($_POST);
    }


    public function get()
    {
        $form = new Element\Form();
        $form->setAttribute(":model", "form");
        $form->setAttribute("method", "POST");
        $form->setAttribute("label-width", "auto");
        $form->setAttribute("ref", "form1");


        //--date
        $formitem = new Element\FormItem();
        $formitem->setAttribute("prop", "date1");
        $formitem->setAttribute("label", "date1");
        $formitem->setAttribute(":rules", json_encode([
            ["required" => true]
        ]));

        $date = new Element\DatePicker();
        $date->setAttribute("name", "date1");
        $date->setAttribute("v-model", "form.date1");
        $formitem->append($date);
        $form->append($formitem);


        //--input
        $formitem = new Element\FormItem();
        $formitem->setAttribute("prop", "input1");
        $formitem->setAttribute("label", "input1");
        $formitem->setAttribute(":rules", json_encode([
            ["required" => true]
        ]));

        $input = new Element\Input();
        $input->setAttribute("name", "input1");
        $input->setAttribute("v-model", "form.input1");

        $formitem->append($input);
        $form->append($formitem);



        $btn = new Element\Button();
        $btn->setAttribute("type", "primary");
        $btn->setAttribute("v-on:click", "submitForm");
        $btn->textContent = "Submit";
        $formitem = new Element\FormItem();
        $formitem->append($btn);
        $form->append($formitem);


        $card = new Element\Card;
        $card->append($form);

        $card->setAttribute("id", "card");




        $this->write($card);
    }
}
?>

<script>
    new Vue({
        el: "#card",
        data: {
            form: {}
        },
        methods: {
            submitForm() {
                this.$refs.form1.validate((valid) => {
                    if (valid) {
                        this.$refs.form1.$el.submit();
                    } else {
                        console.log('error submit!!');
                        return false;

                    }
                });

            }
        }
    });
</script>

<div id="div2">

    <el-form :label-position="labelPosition" label-width="100px" :model="formLabelAlign">
        <el-form-item label="Name" :rules='["required" => true]'>
            <el-input v-model="formLabelAlign.name"></el-input>
            <el-input v-model="formLabelAlign.name1"></el-input>
            <el-input v-model="formLabelAlign.name2"></el-input>
            <el-input v-model="formLabelAlign.name3"></el-input>
        </el-form-item>
        <el-form-item label="Activity zone">
            <el-input v-model="formLabelAlign.region"></el-input>
        </el-form-item>
        <el-form-item label="Activity form">
            <el-input v-model="formLabelAlign.type"></el-input>
        </el-form-item>
    </el-form>
</div>

<script>
    new Vue({
        el: "#div2",
        data() {
            return {
                labelPosition: 'right',
                formLabelAlign: {
                    name: '',
                    name1: '',
                    name2: '',
                    name3: '',
                    region: '',
                    type: ''
                }
            };
        }
    });
</script>