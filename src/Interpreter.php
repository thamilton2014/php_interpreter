<?php
require 'Parser.php';
/**
 * Created by PhpStorm.
 * User: thamilton
 * Date: 10/28/14
 * Time: 8:56 AM
 */
class Interpreter
{
    function main()
    {
        $parser = new Parser("/Users/thamilton/PhpstormProjects/Example_Project/test_data/test1.e");
        $feature = $parser->feature();
        $feature->execute();

//        $memory = new Memory();
//        Memory::display_memory();
    }
}

$run = new Interpreter();
$run->main();
