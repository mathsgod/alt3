<?

namespace ALT;

use \Psr\Http\Message\RequestInterface;
use \Psr\Http\Message\ResponseInterface;
use Exception;

class Page extends \App\Page
{

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        if ($request->isAccept("text/html") && $request->getMethod() == "get") {
            $this->master = new MasterPage($this->app);
        }

        try {
            $response = parent::__invoke($request, $response);
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
}
