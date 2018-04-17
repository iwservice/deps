<?php
/**
 * Created by PhpStorm.
 * User: dlewandowski
 * Date: 4/17/18
 * Time: 11:16 AM
 */

namespace IWService\DependencyInjection;


/**
 * Class Container
 * @package IWService\DependencyInjection
 */
class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private $available = [];
    /**
     * @var array
     */
    private $enabled = [];

    public function __construct()
    {
        /* at first register yourself as ContainerInterface supplier */
        $this->enabled[ContainerInterface::class] = $this;
    }

    /**
     * @param $interface
     * @param $class
     */
    public function register($interface, $class)
    {
        $this->available[$interface] = $class;
    }

    /**
     * @param $interface
     * @return object $interface
     * @throws \ReflectionException
     */
    public function get($interface)
    {
        if (array_key_exists($interface, $this->enabled))
            return $this->enabled[$interface];
        if (!array_key_exists($interface, $this->available))
            return null;
        /* create interface instance */
        $this->enabled[$interface] = $this->load($this->available[$interface]);
        /* return object */
        return $this->enabled[$interface];
    }

    /**
     * @param $class
     * @return object $class
     * @throws \ReflectionException
     */
    private function load($class)
    {
        /* create reflection */
        $ref = new \ReflectionClass($class);
        $constructor = $ref->getConstructor();
        if (!($constructor instanceof \ReflectionMethod)) {
            return $ref->newInstance();
        }
        $parameters = [];
        foreach ($constructor->getParameters() as $parameter) {
            $parameters[] = $this->get($parameter->getClass()->getName());
        }
        return $ref->newInstanceArgs($parameters);
    }
}