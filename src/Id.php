<?php
/**
 * Created by PhpStorm.
 * User: thamilton
 * Date: 10/29/14
 * Time: 3:58 PM
 */
require 'Expression.php';

class Id implements Expression
{

    private $value;

    function __construct($value)
    {
        $this->value = strtolower($value);
    }

    function evaluate()
    {
        $memory = new Memory();
        return $memory->fetch($this->value);
    }

    function get_char()
    {
        return $this->value;
    }

} 