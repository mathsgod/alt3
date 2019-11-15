<?

namespace App;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use R\Psr7\Stream;

class Page extends \R\Page
{

    public $data = [];
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $route = $request->getAttribute("route");
        ob_start();
        $response = parent::__invoke($request, $response);
        $echo_content = ob_get_contents();
        ob_end_clean();

        $content = "";

        foreach ($request->getHeader("Accept") as $accept) {
            list($media,) = explode(",", $accept);
            switch ($media) {
                case "text/html":

                    $file = $route->file;
                    $pi = pathinfo($file);
                    if (file_exists($template_file = $pi["dirname"] . "/" . $pi["filename"] . ".twig")) {
                        $this->template = $this->app->twig($template_file);

                        $data = $this->data;
                        $content .= (string) $response;
                        $content .= $echo_content;
                        $content .= $this->template->render($data);

                        $response = $response->withHeader("Content-Type", "text/html; charset=UTF-8");
                    } else {
                        $content .= $echo_content;
                        $content .= (string) $response;
                    }
            }
        }

        return $response->withBody(new Stream($content));
    }
}
