<?

class _access_deny extends ALT\Page
{
    public function get()
    {
        $this->header->title = "Access deny";
        $this->write("access deny");
    }
}
