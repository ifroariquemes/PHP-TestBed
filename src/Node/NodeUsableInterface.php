<?php

namespace PhpTestBed\Node;

/**
 * Defines the structure needed for any node that generates a reposity entry.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
interface NodeUsableInterface
{

    /**
     * A usable node needs to implement a result, the value that will be 
     * used at its repository entry.
     */
    public function getResult();

    /**
     * This method may be used to explain in what repository entry that
     * node should be in (const, var or array).
     */
    public function addUsage();

    public function getName();
}
