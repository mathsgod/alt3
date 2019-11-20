<?
class System_log extends App\Page
{
    public function get()
    {

        outp(file_get_contents(getcwd() . "/log/" . date("Y-m-d") . ".log"));
    }

    public function del()
    {
        unlink(getcwd() . "/log/" . date("Y-m-d") . ".log");
        echo "del";
    }
}