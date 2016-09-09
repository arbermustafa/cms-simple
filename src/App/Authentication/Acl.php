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
        $this->addResource('intranet.profile');
        $this->addResource('intranet.unauthorized');
        $this->addResource('intranet.dashboard');
        $this->addResource('intranet.settings');
        $this->addResource('intranet.users.list');
        $this->addResource('intranet.users.add');
        $this->addResource('intranet.users.edit');
        $this->addResource('intranet.users.delete');
        $this->addResource('intranet.pages.list');
        $this->addResource('intranet.pages.add');
        $this->addResource('intranet.pages.edit');
        $this->addResource('intranet.pages.delete');
        $this->addResource('intranet.categories.list');
        $this->addResource('intranet.categories.add');
        $this->addResource('intranet.categories.edit');
        $this->addResource('intranet.categories.delete');
        $this->addResource('intranet.posts.list');
        $this->addResource('intranet.posts.add');
        $this->addResource('intranet.posts.edit');
        $this->addResource('intranet.posts.delete');
        $this->addResource('intranet.slides.list');
        $this->addResource('intranet.slides.add');
        $this->addResource('intranet.slides.edit');
        $this->addResource('intranet.slides.delete');
        $this->addResource('intranet.menus');

        //Permissions ALL
        $this->deny(null, null, null);
        $this->allow(null, 'intranet.login', null);
        $this->allow(null, 'intranet.logout', null);
        $this->allow(null, 'intranet.unauthorized', null);

        //Permissions ADMIN
        $this->allow('ADMIN', null, null);

        //Permissions AUTHOR
        $this->allow('AUTHOR', 'intranet.profile', null);
        $this->allow('AUTHOR', 'intranet.dashboard', null);
        $this->allow('AUTHOR', 'intranet.pages.list', null);
        $this->allow('AUTHOR', 'intranet.pages.add', null);
        $this->allow('AUTHOR', 'intranet.pages.edit', null);
        $this->allow('AUTHOR', 'intranet.pages.delete', null);
        $this->allow('AUTHOR', 'intranet.categories.list', null);
        $this->allow('AUTHOR', 'intranet.categories.add', null);
        $this->allow('AUTHOR', 'intranet.categories.edit', null);
        $this->allow('AUTHOR', 'intranet.categories.delete', null);
        $this->allow('AUTHOR', 'intranet.posts.list', null);
        $this->allow('AUTHOR', 'intranet.posts.add', null);
        $this->allow('AUTHOR', 'intranet.posts.edit', null);
        $this->allow('AUTHOR', 'intranet.posts.delete', null);
        $this->allow('AUTHOR', 'intranet.slides.list', null);
        $this->allow('AUTHOR', 'intranet.slides.add', null);
        $this->allow('AUTHOR', 'intranet.slides.edit', null);
        $this->allow('AUTHOR', 'intranet.slides.delete', null);
        $this->allow('AUTHOR', 'intranet.menus', null);
    }
}
