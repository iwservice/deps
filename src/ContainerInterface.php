<?php
/**
 * Created by PhpStorm.
 * User: dlewandowski
 * Date: 4/17/18
 * Time: 6:47 PM
 */

namespace IWService\DependencyInjection;


interface ContainerInterface
{
    public function register($interface, $class);
    public function get($interface);
}
