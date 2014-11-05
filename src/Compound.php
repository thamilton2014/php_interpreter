<?php

/**
 * Created by PhpStorm.
 * User: thamilton
 * Date: 10/29/14
 * Time: 3:49 PM
 */
class Compound
{
    private $statements;

    function __construct()
    {
        $this->statements = array();
    }

    function add($statement)
    {
        if ($statement == NULL)
            throw new InvalidArgumentException("[Compound] null statement argument");
        $this->statements[] = $statement;
    }

    function execute()
    {
        foreach ($this->statements as $input)
            $input->execute();
    }
} 