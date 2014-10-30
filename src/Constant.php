<?php
/**
 * Created by PhpStorm.
 * User: thamilton
 * Date: 10/29/14
 * Time: 4:20 PM
 */

class Constant implements Expression{

    private $value;

    function __construct($value)
    {
        if($value == NULL)
            throw new InvalidArgumentException("[Constant] null Expression argument");
        $this->value = $value;
    }

    function evaluate()
    {
        return $this->value;
    }
} 