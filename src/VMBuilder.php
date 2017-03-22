<?php
namespace Jumper;

use PhpParser\Parser;
use PhpParser\PrettyPrinterAbstract;
use Symfony\Component\Finder\Finder;

final class VMBuilder
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    private $finder;

    /**
     * @var \PhpParser\Parser
     */
    private $parser;

    /**
     * @var \PhpParser\PrettyPrinterAbstract
     */
    private $prettyPrinter;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param \PhpParser\Parser $parser
     * @param \PhpParser\PrettyPrinterAbstract $prettyPrinter
     */
    public function __construct(Finder $finder, Parser $parser, PrettyPrinterAbstract $prettyPrinter)
    {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->prettyPrinter = $prettyPrinter;
    }

    /**
     * @return \Jumper\VMInterface
     */
    public function build()
    {
        $opImplementations = [];

        foreach ($this->finder as $item) {
            $baseName = $item->getBasename('.php');
            $code = file_get_contents($item->getRealPath());
            $tree = $this->parser->parse($code);
            $opImplementations[$baseName] = $this->prettyPrinter->prettyPrint($tree);
        }

        return new ArrayDefinedVM($opImplementations);
    }
}