<?php
require 'Token.php';
require 'TokenType.php';
require 'LexicalException.php';

class LexicalAnalyzer {

    private $token_list;

    function __construct($file_name)
    {
        $file_contents = file_get_contents($file_name);
        $words = preg_split('/[\s]+/', $file_contents, -1, PREG_SPLIT_NO_EMPTY);
        $row_num = 0;
        $col_num = 0;
        $this->token_list = array();
        foreach($words as $value)
        {
            $row_num++;
            $token_type = $this->get_token_type($row_num, $col_num, $value);
            $object = new Token($row_num + 1, $col_num + 1, $value, $token_type);
            $this->token_list[] = $object;
        };
//        var_dump($this->token_list);
//        $object = new Token(-1 , -1, "EOS_TOK", TokenType::EOS_TOK);
//        $token_list[] = $object;
    }

    private function get_token_type($current_line, $column_num, $word)
    {
        if($word === "feature")
            $token_type = TokenType::FEATURE_TOK;
        elseif($word === "a")
            $token_type = TokenType::ID_TOK;
        elseif($word === "is")
            $token_type = TokenType::IS_TOK;
        elseif($word === "do")
            $token_type = TokenType::DO_TOK;
        elseif($word === "x")
            $token_type = TokenType::ID_TOK;
        elseif($word === ":=")
            $token_type = TokenType::ASSIGN_TOK;
        elseif($word === "1")
            $token_type = TokenType::CONST_TOK;
        elseif($word === "print")
            $token_type = TokenType::PRINT_TOK;
        elseif($word === ")")
            $token_type = TokenType::RPARAN_TOK;
        elseif($word === "(")
            $token_type = TokenType::LPARAN_TOK;
        elseif($word === "end")
            $token_type = TokenType::END_TOK;
        else
            throw new LexicalException("invalid lexeme at row $current_line and column $column_num : $word");
        return $token_type;
    }

    function get_look_ahead_token()
    {
        if($this->token_list === NULL)
            throw new LexicalException("No more tokens");
        return $this->token_list[0];
    }

    function get_next_token()
    {
        if($this->token_list === NULL)
            throw new LexicalException("No more tokens");
        $token = $this->token_list[0];
        unset($this->token_list[0]);
        $this->token_list = array_values($this->token_list);
        return $token;
    }

    function get_lexeme()
    {
        return $this->token_list["value"];
    }

}