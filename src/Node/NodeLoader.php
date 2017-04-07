<?php

namespace PhpTestBed\Node;

use PhpTestBed\ScriptCrawler;
use PhpTestBed\Stylizer;
use PhpTestBed\I18n;

/**
 * Loads a PhpTestBed node based on PhpParser statement
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class NodeLoader
{

    /**
     * Returns a NodeBaseAbstract object based on PhpParser statement node.
     * @param \PhpParser\NodeAbstract $node
     * @return NodeBaseAbstract
     */
    public static function load(\PhpParser\NodeAbstract $node)
    {
        $nodeClass = str_replace('PhpParser\\', 'PhpTestBed\\', get_class($node));
        if (!class_exists($nodeClass)) {
            ScriptCrawler::getInstance()->printMessage(
                    Stylizer::systemException(
                            I18n::getInstance()->get('exceptions.node-not-implemented'
                                    , ['node' => $nodeClass])
                    )
            );
            return;
        }
        return new $nodeClass($node);
    }

}
