<?php

class User_myfav extends ALT\Page
{
    public function post()
    {
        foreach ($_POST["ui_id"] as $i => $ui_id) {
            $ui = new App\UI($ui_id);
            $content = $ui->content();
            $content["sequence"] = $i;
            $ui->layout = json_encode($content, JSON_UNESCAPED_UNICODE);
            $ui->save();
        }
    }

    public function get()
    {
        $this->app->savePlace();

        $ds = App\UI::Query([
            "user_id" => $this->app->user_id,
            "uri" => 'fav'
        ])->orderBy("sequence")->toArray();

        $t = $this->createT($ds);
        $t->header->title = "My favorite";
        $t->setAttribute("id", "table1");

        $t->add("", function ($o) {
            return "<i class='fa fa-sort'></i><input type='hidden' name='ui_id[]' value='{$o->ui_id}'/>";
        });

        $t->addEdit();
        $t->addDel();
        $t->add("Label", function ($ui) {
            return $ui->content()["label"];
        });
        $t->add("Link", function ($ui) {
            return $ui->content()["link"];
        });

        $t->add("Icon", function ($ui) {
            $icon = $ui->content()["icon"];
            $i = p("i")->addClass("fa");
            if ($icon) {
                $i->addClass($icon);
            }
            $color = $ui->content()["color"];
            if ($color) {
                $i->addClass($color);
            }
            return $i;
        });

        $f = p("form")->attr("id", "form1")->append($t);

        $this->write($f);
        $this->write($t->script());
    }
}
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $("#table1 table>tbody").sortable({
            handle: ".fa-sort",
            axis: "y",
            cursor: "move",
            update(event, ui) {

                var ids = [];
                $("#form1 input").each((index, el) => {
                    ids.push(parseInt(el.value));
                });

                Vue.gql.mutation("api", {
                    updateFavoriteSequence: {
                        __args: {
                            id: ids
                        }
                    }
                });

            }
        });
    });
</script>