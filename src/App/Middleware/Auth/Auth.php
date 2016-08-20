<?php
/**
 * Europa Re
 * This Middleware is a combination of Mika Tuupola and Jeremy Kendall authentication and authorization
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Middleware\Auth;

use \Slim\Middleware;
use \Zend\Authentication\AuthenticationServiceInterface;
use \Zend\Permissions\Acl\AclInterface;
use \App\Middleware\Auth\Rules\RequestPathRule;

class Auth extends Middleware
{
    protected $rules = null;
    protected $options = array(
        'path'        => null,
        'passthrough' => null
    );

    public function __construct($options = array())
    {
        $this->rules = new \SplStack();

        $this->hydrate($options);

        if (null !== ($this->options["path"])) {
            $this->addRule(new RequestPathRule(array(
                "path" => $this->options["path"],
                "passthrough" => $this->options["passthrough"]
            )));
        }
    }

    public function call()
    {
        // Bypass if authentication is not required
        if (false === $this->shouldAuthenticate()) {
            return $this->next->call();
        }

        $app = $this->app;

        $app->hook('slim.before.dispatch', function() use ($app)
        {
            $resource = $app->router()->getCurrentRoute()->getName();
            $permission = $app->request->getMethod();

            $auth = $app->auth;
            $acl = $app->acl;

            if (!$auth->hasIdentity()) {
                return $app->redirectTo('intranet.login');
            }

            $identity = $auth->getIdentity();
            $app->view()->getInstance()->addGlobal('identity', $identity);

            if (!$acl->isAllowed($identity['role'], $resource, $permission)) {
                return $app->redirectTo('intranet.unauthorized');
            }
        });

        $this->next->call();
    }

    public function shouldAuthenticate()
    {
        foreach ($this->rules as $rule) {
            if (false === $rule($this->app)) {
                return false;
            }
        }

        return true;
    }

    private function hydrate($data = array())
    {
        foreach ($data as $key => $value) {
            $method = "set" . ucfirst($key);
            if (method_exists($this, $method)) {
                call_user_func(array($this, $method), $value);
            }
        }

        return $this;
    }

    public function getPath()
    {
        return $this->options["path"];
    }

    public function setPath($path)
    {
        $this->options["path"] = $path;

        return $this;
    }

    public function getPassthrough()
    {
        return $this->options["passthrough"];
    }

    public function setPassthrough($passthrough)
    {
        $this->options["passthrough"] = $passthrough;

        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function setRules(array $rules)
    {
        unset($this->rules);
        $this->rules = new \SplStack();

        foreach ($rules as $rule) {
            $this->addRule($rule);
        }

        return $this;
    }

    public function addRule($callable)
    {
        $this->rules->push($callable);

        return $this;
    }
}
