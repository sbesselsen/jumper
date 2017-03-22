<?php
namespace Jumper;

final class ArrayDefinedVM implements VMInterface
{
    /**
     * @var array
     */
    private $opImplementations = [];

    /**
     * @param array $opImplementations
     */
    public function __construct(array $opImplementations)
    {
        $this->opImplementations = $opImplementations;
    }

    public function compileProgramClass(
      array $ops,
      array $args,
      $namespace,
      $className
    ) {
        $output = [];
        $output[] = '<' . '?php';
        if ($namespace !== null) {
            $output[] = 'namespace ' . $namespace . ';';
        }
        $output[] = '';
        $output[] = 'class ' . $className;
        $output[] = '{';
        $output[] = '    public function run(' . implode(', ', $args) . ') {';

        $opIndent = '        ';
        foreach ($ops as $op) {
            if (!is_array($op)) {
                throw new \InvalidArgumentException("Invalid opcode: expected an array");
            }
            $opKey = array_shift($op);
            if (!isset ($this->opImplementations[$opKey])) {
                throw new \InvalidArgumentException("Invalid opcode: {$opKey}");
            }
            $opImplementation = $this->opImplementations[$opKey];
            for ($i = 1; strpos($opImplementation, 'PARAM_' . $i) !== false; $i++) {
                if (count($op) < $i) {
                    throw new \InvalidArgumentException("Param #{$i} not provided for {$opKey}");
                }
                $opImplementation = str_replace('PARAM_' . $i, var_export($op[$i - 1], true), $opImplementation);
            }
            $opImplementation = $opIndent . implode(PHP_EOL . $opIndent, explode(PHP_EOL, $opImplementation));
            $output[] = $opImplementation;
        }

        $output[] = '    }';
        $output[] = '}';
        $output[] = '';
        return implode(PHP_EOL, $output);
    }
}
