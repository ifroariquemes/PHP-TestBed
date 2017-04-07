<?php

namespace PhpTestBed\Node;

/**
 * Defines the structure needed for any node.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
interface NodeBaseInterface
{

    /**
     * All nodes needs to implement this method explaining the functioning
     * of that statement.
     */
    public function resolve();
}
