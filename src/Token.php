<?php

/**
 * Created by PhpStorm.
 * User: thamilton
 * Date: 10/28/14
 * Time: 8:57 AM
 */
class Token
{
    function __construct($row_num, $value, $token_type)
    {
        if ($row_num === NULL)
            throw new InvalidArgumentException("invalid row value");
        if ($value === NULL)
            throw new InvalidArgumentException("invalid string value");
        if ($token_type === NULL)
            throw new InvalidArgumentException("invalid token type value");
        $this->row_num = $row_num;
        $this->value = $value;
        $this->token_type = $token_type;
    }

    function get_row_number()
    {
        return $this->row_num;
    }

    function get_lexeme()
    {
        return $this->value;
    }

    function get_token_type()
    {
        return $this->token_type;
    }
} 