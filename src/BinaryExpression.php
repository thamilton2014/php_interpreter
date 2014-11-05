<?php

/**
 * Created by PhpStorm.
 * User: thamilton
 * Date: 10/29/14
 * Time: 4:18 PM
 */
class BinaryExpression implements Expression
{
    private $operator;
    private $expression_1;
    private $expression_2;

    function __construct($operator, $expression_1, $expression_2)
    {
        if ($operator == NULL)
            throw new InvalidArgumentException("operator is null");
        if ($expression_1 == NULL || $expression_2 == NULL)
            throw new InvalidArgumentException("expression is null");
        $this->operator = $operator;
        $this->expression_1 = $expression_1;
        $this->expression_2 = $expression_2;
    }

    function evaluate()
    {
        // TODO: Implement evaluate() method.
        if ($this->operator = ArithmeticOperator::ADD_OP)
            $value = $this->expression_1->evaluate() + $this->expression_2->evaluate();
        elseif ($this->operator = ArithmeticOperator::SUB_OP)
            $value = $this->expression_1->evaluate() - $this->expression_2->evaluate();
        elseif ($this->operator = ArithmeticOperator::MUL_OP)
            $value = $this->expression_1->evaluate() * $this->expression_2->evaluate();
        else
            $value = $this->expression_1->evaluate() / $this->expression_2->evaluate();
        return $value;
    }
}