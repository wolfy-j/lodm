<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2015
 */
use Spiral\Debug\Dumper;

/**
 * Simple access to spiral Dumper.
 *
 * @param mixed $value  Value to be dumped.
 * @param int   $output Output method, can print, return or log value dump.
 * @return null|string
 */
function dmp($value, $output = Dumper::OUTPUT_ECHO)
{
    return (new Dumper())->dump($value, $output);
}