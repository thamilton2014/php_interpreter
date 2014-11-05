<?php

/**
 * Created by PhpStorm.
 * User: thamilton
 * Date: 10/31/14
 * Time: 3:29 PM
 */
class LoopStatement
{
    private $statement;
    private $boolean_expression;
    private $compound;

    function __construct($statement, $boolean_expression, $compound)
    {
        if ($statement == NULL)
            throw new InvalidArgumentException("statement is null");
        if ($boolean_expression == NULL)
            throw new InvalidArgumentException("boolean expression is null");
        if ($compound == NULL)
            throw new InvalidArgumentException("compound is null");
        $this->statement = $statement;
        $this->boolean_expression = $boolean_expression;
        $this->compound = $compound;
    }

    function execute()
    {
        $this->statement->execute();
        while (!$this->boolean_expression->evaluate())
            $this->compound->execute();
    }
} 