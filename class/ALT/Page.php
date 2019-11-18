<?

namespace ALT;

use \Psr\Http\Message\RequestInterface;
use \Psr\Http\Message\ResponseInterface;
use Exception;

class Page extends \App\Page
{
    public function __construct(\App\App $app)
    {
        parent::__construct($app);
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        if ($request->isAccept("text/html") && $request->getMethod() == "get") {
            $this->master = new MasterPage($this->app);
        }

        try {
            $response = parent::__invoke($request, $response);
            if ($this->master) {
                $this->master->data["content"] .= (string) $response;
            }
        } catch (Exception $e) {
            throw $e;
        }

        if ($request->isAccept("text/html") && $request->getMethod() == "get") {
            if ($this->master) {
                return $this->master->__invoke($request, $response);
            }
        } else {
            return $response;
        }
    }

    public function createE($object = null): \App\UI\E
    {
        if (func_num_args() == 0) {
            $object = $this->object();
        }
        return new \App\UI\E($object, $this);
    }
}
