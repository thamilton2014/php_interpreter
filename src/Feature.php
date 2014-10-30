<?php
/**
 * Created by PhpStorm.
 * User: thamilton
 * Date: 10/29/14
 * Time: 3:47 PM
 */

class Feature {

    private $compound;

    function __construct($compound)
    {
        if($compound == NULL)
            throw new InvalidArgumentException("[Feature] null compound argument");
        $this->compound = $compound;
    }

    function execute()
    {
        $this->compound->execute();
    }
} 