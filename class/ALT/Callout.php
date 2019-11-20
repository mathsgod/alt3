<?

namespace ALT;

class Callout extends \ArrayObject
{
    public function info(string $title, string $description = null)
    {
        $this->append([
            "type" => "info",
            "title" => $title,
            "description" => $description
        ]);
    }

    public function success(string $title, string $description = null)
    {
        $this->append([
            "type" => "success",
            "title" => $title,
            "description" => $description
        ]);
    }

    public function danger(string $title, string $description = null)
    {
        $this->append([
            "type" => "danger",
            "title" => $title,
            "description" => $description
        ]);
    }

    public function warning(string $title, string $description = null)
    {
        $this->append([
            "type" => "warning",
            "title" => $title,
            "description" => $description
        ]);
    }
}
