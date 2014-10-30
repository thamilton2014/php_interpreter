<?php
/**
 * Created by PhpStorm.
 * User: thamilton
 * Date: 10/29/14
 * Time: 3:59 PM
 */
require 'Statement.php';
require 'Memory.php';
class Assignment implements Statement{

    private $id;
    private $expression;

    function __construct($id, $expression)
    {
        if($id == NULL)
            throw new InvalidArgumentException("[Assignment] null Id argument");
        if($expression == NULL)
            throw new InvalidArgumentException("[Assignment] null Expression argument");
        $this->id = $id;
        $this->expression = $expression;
    }

    function execute()
    {
        var_dump($this->expression->evaluate());
        $memory = new Memory();
        $memory->store($this->id, $this->expression->evaluate());
    }

} 