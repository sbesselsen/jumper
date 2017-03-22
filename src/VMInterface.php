<?php
namespace Jumper;

interface VMInterface
{
    const OP_LABEL = '_label';
    const OP_GOTO = '_goto';
    const OP_GOTO_COND = '_goto_cond';

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
