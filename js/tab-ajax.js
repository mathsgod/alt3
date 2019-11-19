//Date: 2017-08-04
//Created by: Raymond Chong
$(function () {
    function MyTab(o) {
        this._current_xhr;
        this._tab_timer;


        var that = this;

        $o = $(o);
        $o.find('[data-toggle="tabajax"]').click(function (e) {
            clearTimeout(that._tab_timer);
            if (that._current_xhr) {
                that._current_xhr.abort();
            }
            var $this = $(this),
                loadurl = $this.attr('href'),
                targ = $this.attr('data-target');

            var div = $this.closest(".my_tab");
            var cookie_name = div.attr('data-cookie');


            var li = $this.closest("li");
            var timer = li.attr("data-timer");


            if (!timer) {
                $(targ).html('<div class="overlay"><i class="fa fa-spinner fa-spin"></i></div>');
            }

            that._current_xhr = $.get(loadurl, function (data) {
                $(targ).html(data);
                localStorage.setItem(cookie_name + "-tab", targ);

                if (timer != undefined) {
                    _tab_timer = setTimeout(function () {
                        $this.trigger("click");
                    }, parseInt(timer));
                }
            }).fail(function () {
                $(targ).html("error when loading this page");
            });

            $this.tab('show');
            return false;
        });

        var cookie_name = $(o).attr('data-cookie');
        var targ = localStorage.getItem(cookie_name + "-tab");
        if (targ != undefined && $(o).find("a[data-target='" + targ + "']").length > 0) {
            $(o).find("a[data-target='" + targ + "']").trigger("click");
        } else {
            $(o).find("a[data-target]:first").trigger('click');
        }

        $(o).find("a[data-toggle]").on("shown.bs.tab", function (e) {

            var targ = $(e.relatedTarget).attr("data-target");
            $(targ).empty();

        });

    }

    MyTab.prototype.test = function () {
        return "test";
    }


    var f = function () {
        $("div.my_tab").each(function (i, o) {
            if ($(o).data("mytab")) return;
            $(o).data("mytab", new MyTab(o));
        });
    };

    f();

    $(document).ajaxComplete(function () {
        setTimeout(f, 0);
    });
});