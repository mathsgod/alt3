<?

namespace ALT;

use Exception;

class InputSelectCollection extends \P\Query
{
    public function ds($a)
    {
        throw new Exception(__CLASS__ . " " . __FUNCTION__ . "  is deprecated");
    }

    public function options($a)
    {
        foreach ($this as $node) {
            foreach ($a as $b) {
                $node->addItem($b);
            }
        }
        return $this;
    }
}
