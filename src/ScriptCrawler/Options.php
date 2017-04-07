<?php

namespace PhpTestBed\ScriptCrawler;

/**
 * Class with option to configure the ScriptCrawler.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Options
{

    /**
     * The script path (absolute or relative).
     * @var string 
     */
    public $scriptPath;

    /**
     * If ScriptCrawler will generate messages with timestamp information.
     * @var bool 
     */
    public $useTimestamp;

    /**
     * If ScriptCrawler will return messages as array or print it directly.
     * @var bool 
     */
    public $returnMessages;

    /**
     * Initilizes option object.
     * @param string $scriptPath The script path (absolute or relative)
     * @param bool $useTimestrap If ScriptCrawler will generate messages with timestamp information
     * @param bool $returnMessages If ScriptCrawler will return messages as array or print it directly
     */
    public function __construct(string $scriptPath, bool $useTimestrap = true, bool $returnMessages = false)
    {
        $this->scriptPath = $scriptPath;
        $this->useTimestamp = $useTimestrap;
        $this->returnMessages = $returnMessages;
    }

}
