<?

namespace App\UI;

class A extends \P\HTMLAnchorElement
{
    public function __construct()
    {
        parent::__construct();
        $this->classList->add("btn");
    }
}
