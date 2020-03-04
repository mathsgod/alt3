<?

class api_test extends ALT\Page{
    public function get(){
        outp(App\User::__attribute("style"));
    }
}