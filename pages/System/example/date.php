<?
class System_example_date extends ALT\Page
{
    public function get()
    {
        $e = $this->createE(null);
        $e->add("Date yyyy-mm-dd")->date("date1");
        $e->add("Date YYYY-MM")->datetime("date2")->attr("format", "YYYY-MM");
        $e->add("Date minDate")->datetime("date3")->attr("min-date", "2019-06-01");
        $e->add("Date minDate and format")->datetime("date4")->attr("format", "YYYY-MM")->attr("min-date", "2019-06");
        $this->write($this->createForm($e));
    }
}
