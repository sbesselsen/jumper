<?php
namespace Jumper;

interface VMInterface
{
    /**
     * @param array $ops
     * @param array $args
     * @param string|null $namespace
     * @param string $className
     * @return string
     *      A PHP class definition.
     */
    public function compileProgramClass(array $ops, array $args, $namespace, $className);
}
