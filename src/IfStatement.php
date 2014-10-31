<?php
/**
 * Created by PhpStorm.
 * User: thamilton
 * Date: 10/31/14
 * Time: 1:48 PM
 */

class IfStatement implements Statement{

    private $boolean_expression;
    private $compound_1;
    private $compound_2;

    function __construct($boolean_expression, $compound_1, $compound_2)
    {
        if($boolean_expression == NULL)
            throw new InvalidArgumentException("[If Statement] boolean expression is null");
        if($compound_1 == NULL || $compound_2 == NULL)
            throw new InvalidArgumentException("[If Statement] compound is null");
        $this->boolean_expression = $boolean_expression;
        $this->compound_1 = $compound_1;
        $this->compound_2 = $compound_2;
    }

    function execute()
    {
        // TODO: Implement execute() method.
        if($this->boolean_expression->evaluate())
            $this->compound_1->execute();
        else
            $this->compound_2->execute();
    }
}