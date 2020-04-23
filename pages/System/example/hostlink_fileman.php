<?

use Firebase\JWT\JWT;

class System_example_hostlink_fileman extends ALT\Page
{
    public function get()
    {
        $this->addLib("ckeditor/ckeditor");
        $e = $this->createE([]);
        $e->add("ckeditor")->ckeditor("text1");
        $e->add("file man")->fileman("text2");


        $this->write($e);
    }
}
