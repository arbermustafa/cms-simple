<?php
/**
 * Europa Re
 * Interface based on Tuupola slim-jwt-auth
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Middleware\Auth\Rules;

use \Slim\Slim;

interface RuleInterface
{
    public function __invoke(Slim $app);
}
