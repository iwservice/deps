<?php
/**
 * Released under GPLv3
 * Created by dlewandowski@iwservice.pl
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
        $this->available[ContainerInterface::class] = self::class;
    }

    /**
     * @param $interface
     * @param $class
     */
    public function set($interface, $class)
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

    public function has($interface)
    {
        return array_key_exists($interface, $this->available);
    }
}
