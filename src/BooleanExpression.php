<?php
/**
 * Created by PhpStorm.
 * User: thamilton
 * Date: 10/31/14
 * Time: 2:13 PM
 */

class BooleanExpression {

    function __construct($operator, $expression_1, $expression_2)
    {
        if($operator == NULL)
            throw new InvalidArgumentException("operator is null");
        if($expression_1 == NULL || $expression_2 == NULL)
            throw new InvalidArgumentException("expression is null");
        $this->operator = $operator;
        $this->expression_1 = $expression_1;
        $this->expression_2 = $expression_2;
    }

    function evaluate()
    {
        if($this->operator == RelationalOperator::GT_OP)
            $value = $this->expression_1->evaluate() > $this->expression_2->evaluate();
        elseif($this->operator == RelationalOperator::GE_OP)
            $value = $this->expression_1->evaluate() >= $this->expression_2->evaluate();
        elseif($this->operator == RelationalOperator::LT_OP)
            $value = $this->expression_1->evaluate() < $this->expression_2->evaluate();
        elseif($this->operator == RelationalOperator::LE_OP)
            $value = $this->expression_1->evaluate() <= $this->expression_2->evaluate();
        elseif($this->operator == RelationalOperator::EQ_OP)
            $value = $this->expression_1->evaluate() == $this->expression_2->evaluate();
        else
            $value = $this->expression_1->evaluate() != $this->expression_2->evaluate();
        return $value;
    }
} 