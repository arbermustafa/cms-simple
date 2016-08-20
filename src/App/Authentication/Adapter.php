<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Authentication;

use \Zend\Authentication\Adapter\AdapterInterface;
use \Zend\Authentication\Result;
use \App\Service\Authentication as AuthService;

class Adapter implements AdapterInterface
{
    protected $_user;
    protected $_credentials;

    public function setCredentials($credentials)
    {
        $this->_credentials = $credentials;
    }

    public function authenticate()
    {
        try {
            $this->_user = AuthService::auth($this->_credentials);
        } catch(\Exception $e) {
            return $this->result(Result::FAILURE_CREDENTIAL_INVALID, $e->getMessage());
        }

        return $this->result(Result::SUCCESS);
    }

    private function result($code, $messages = array())
    {
        if(!is_array($messages)) {
            $messages = array(
                $messages
            );
        }

        return new Result($code, $this->_user, $messages);
    }
}
