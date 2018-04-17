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
    public function get($interface);
    public function has($interface);
    public function set($interface, $class);
}
