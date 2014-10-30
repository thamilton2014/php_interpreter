<?php
/**
 * Created by PhpStorm.
 * User: thamilton
 * Date: 10/29/14
 * Time: 4:12 PM
 */

class PrintStatement implements Statement{

    private $expression;

    function __construct($expression)
    {
        if($expression == NULL)
            throw new InvalidArgumentException("[Print Statement] ");
        $this->expression = $expression;
    }

    function execute()
    {
        // TODO: Implement execute() method.
        echo "[Print Statement] " . $this->expression->evaluate() . "\n";
    }
}