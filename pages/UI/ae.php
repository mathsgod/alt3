<?

class UI_ae extends ALT\Page
{
	public function post()
	{
		$obj = $this->object();
		$content = $obj->content();

		foreach ($_POST as $k => $v) {
			$content[$k] = $v;
		}

		$obj->layout = json_encode($content, JSON_UNESCAPED_UNICODE);
		$obj->save();
		$this->redirect();
	}

	public function get()
	{
		$this->addLib("fontawesome-iconpicker");

		$obj = $this->object();

		$e = $this->createE($obj->content());
		$e->add("Label")->input("label");
		$e->add("Link")->input("link");
		$e->add("Icon", function ($o) {
			$v = $o["icon"];
			return <<<EOT
			<div class="input-group">
				<input data-placement="bottomRight" class="form-control" id="iconpicker" type="text" name="icon" value="$v"/>
				<span class="input-group-addon"></span>
			</div>		
EOT;
		});
		$e->add("Color")->select("color")->options(["", "text-red", "text-yellow", "text-aqua", "text-blue", "text-black", "text-light-blue", "text-green", "text-gray", "text-navy", "text-teal", "text-oliver", "text-lime", "text-orange", "text-fuchsia", "text-purple", "text-maroon"])->attr("id", "color1");


		$f = $this->createForm($e);
		$f->action("");
		$this->write($f);

	}
}
?>
<!-- script src="/alt/plugins/fontawesome-iconpicker-3.2.0/js/fontawesome-iconpicker.min.js"></script>
<link rel="stylesheet" href="/alt/plugins/fontawesome-iconpicker-3.2.0/css/fontawesome-iconpicker.min.css" / -->

<script>
document.addEventListener("DOMContentLoaded",function(){
	$("#color1").select2({
		templateResult:function(state){
	 		if (!state.id) { return state.text; }
			
			var v = state.element.value;
			
			return $("<span class='"+v+"'>"+v+"</span>");
		}
	});

	$("#iconpicker").iconpicker();
});
</script>