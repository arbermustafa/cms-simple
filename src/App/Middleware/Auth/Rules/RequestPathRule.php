<?php
/**
 * Europa Re
 * Class based on Tuupola slim-jwt-auth
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Middleware\Auth\Rules;

use \Slim\Slim;

class RequestPathRule implements RuleInterface
{
    protected $options = array(
        'path'        => array('/'),
        'passthrough' => array()
    );

    public function __construct($options = array())
    {
        $this->options = array_merge($this->options, $options);
    }

    public function __invoke(Slim $app)
    {
        $uri = $app->request->getRootUri() . $app->request->getResourceUri();

        $uri = str_replace("//", "/", $uri);

        /* If request path is matches passthrough should not authenticate. */
        foreach ((array)$this->options["passthrough"] as $passthrough) {
            $passthrough = rtrim($passthrough, "/");
            if (!!preg_match("@^{$passthrough}(/.*)?$@", $uri)) {
                return false;
            }
        }

        /* Otherwise check if path matches and we should authenticate. */
        foreach ((array)$this->options["path"] as $path) {
            $path = rtrim($path, "/");
            if (!!preg_match("@^{$path}(/.*)?$@", $uri)) {
                return true;
            }
        }
        return false;
    }
}
