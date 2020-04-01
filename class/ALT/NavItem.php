<?

namespace ALT;

class NavItem
{
    public function getBadge(): int
    {
        $total = 0;
        if ($this->submenu) {
            foreach ($this->submenu as $menu) {
                $total += $menu->getBadge();
            }

            if ($total != 0) {
                $this->badge = [
                    "class" => "primary",
                    "content" => $total
                ];
            }
        } else {
            if (is_numeric($this->badge["content"])) {
                $total = $this->badge["content"];
            }
        }
        return $total;
    }
}
