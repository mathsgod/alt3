<?php

class User_build_bootstrap extends App\Page {
	public function get() {
		App\Plugin::Load("less.php");
		$username = App::User()->username;
		$theme = "cyborg";

		$bootstrap = ROOT . "/" . SYSTEM . "/plugins/bootstrap.3.3.5";
		$this->write($bootstrap);
		$cache_folder = ROOT . '/data/cache/bootstrap';
		if (!file_exists($cache_folder)) {
			mkdir($cache_folder, 0777, true);
		}
		Less_Cache::$cache_dir = $cache_folder;

		$base = App\System::$base;
		$files = array();
		$files["$bootstrap/less/bootstrap.less"] = $base . "data/$username/bootstrap/css/";
		if ($theme) {
			$files[ROOT . "/" . SYSTEM . '/themes/bootstrap/' . $theme . '/variables.less'] =  $base . "data/$username/bootstrap/css/";
		}
		// $style = $this->style?json_decode($this->style, true):array();
		//$style["bootstrap"] = ["@navbar-height" => "50px"];

		outp($files);
		$css_file_name = Less_Cache::Get($files, [], $style["bootstrap"]);

		mkdir(ROOT . "/data/$username/bootstrap/fonts", 0777, true);

		foreach(glob("$bootstrap/fonts/*") as $ff) {
			$basename = basename($ff);
			copy($ff, ROOT . "/data/$username/bootstrap/fonts/$basename");
		}
		// copy to css folder
		mkdir(ROOT . "/data/" . $username . "/bootstrap/css", 0777, true);
		file_put_contents(ROOT . "/data/$username/bootstrap/css/bootstrap.css", file_get_contents("$cache_folder/$css_file_name"));
		chmod(ROOT . "/data/$username/bootstrap/css/bootstrap.css", 0777);

		$this->write("done");
	}
}

?>