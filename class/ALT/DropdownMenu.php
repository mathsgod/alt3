<?

namespace ALT;

class DropdownMenu extends \P\HTMLDivElement
{
    public function __construct()
    {
        parent::__construct();
        $this->classList->add("dropdown-menu");
    }

    public function addItem(string $value): \P\Query
    {
        $a = p("a")->text($value);
        $a->addClass("dropdown-item");

        $this->append($a[0]);

        return $a;
    }
}
