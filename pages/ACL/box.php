<?

class ACL_box extends App\Page
{

	public function post()
	{

		$path = $_POST["path"];
		$module = explode("/", $path)[0];
		$usergroup_id = $_POST["usergroup_id"];

		$w = [];
		$w[] = ["module=?", $module];
		$w[] = ["path=?", $path];
		$w[] = ["usergroup_id=?", $usergroup_id];
		foreach (App\ACL::find($w) as $a) {
			$a->delete();
		}

		if ($_POST["selected"]) {
			$acl = new App\ACL();
			$acl->module = $module;
			$acl->usergroup_id = $usergroup_id;
			$acl->value = "allow";
			$acl->path = $path;
			$acl->save();
		}
	}

	public function get()
	{

	}
}
