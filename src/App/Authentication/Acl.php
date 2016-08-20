<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Authentication;

use \Zend\Permissions\Acl\Acl as ZendAcl;

class Acl extends ZendAcl
{
    public function __construct()
    {
        //Roles
        $this->addRole('ADMIN');
        $this->addRole('AUTHOR');

        //Resources
        $this->addResource('intranet.login');
        $this->addResource('intranet.logout');
        $this->addResource('intranet.unauthorized');
        $this->addResource('intranet.dashboard');
        $this->addResource('intranet.user');
        $this->addResource('intranet.page');
        $this->addResource('intranet.category');
        $this->addResource('intranet.post');
        $this->addResource('intranet.menu');
        $this->addResource('intranet.setting');

        //Permissions ALL
        $this->deny(null, null, null);
        $this->allow(null, 'intranet.login', null);
        $this->allow(null, 'intranet.logout', null);
        $this->allow(null, 'intranet.unauthorized', null);

        //Permissions ADMIN
        $this->allow('ADMIN', null, null);

        //Permissions AUTHOR
        $this->allow('AUTHOR', 'intranet.dashboard', null);
        $this->allow('AUTHOR', 'intranet.page', null);
        $this->allow('AUTHOR', 'intranet.category', null);
        $this->allow('AUTHOR', 'intranet.post', null);
        $this->allow('AUTHOR', 'intranet.menu', null);
    }
}
