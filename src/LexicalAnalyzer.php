<?php
require 'Token.php';
require 'TokenType.php';
require 'LexicalException.php';

class LexicalAnalyzer
{
    private $token_list;

    function __construct($file_name)
    {
        $file_contents = file_get_contents($file_name);
        $words = preg_split('/[\s]+/', $file_contents, -1, PREG_SPLIT_NO_EMPTY);
        $row_num = 0;
        $this->token_list = array();
        foreach ($words as $value) {
            $row_num++;
            $token_type = $this->get_token_type($row_num, $value);
            $object = new Token($row_num + 1 + 1, $value, $token_type);
            $this->token_list[] = $object;
        };
        $object = new Token(-1, -1, "EOS_TOK", TokenType::EOS_TOK);
        $token_list[] = $object;
    }

    private function get_token_type($current_line, $word)
    {
        if ($word === "feature")
            $token_type = TokenType::FEATURE_TOK;
        elseif (ctype_alpha($word) && strlen($word) == 1)
            $token_type = TokenType::ID_TOK;
        elseif ($word === "is")
            $token_type = TokenType::IS_TOK;
        elseif ($word === "do")
            $token_type = TokenType::DO_TOK;
        elseif ($word === "x")
            $token_type = TokenType::ID_TOK;
        elseif ($word === ":=")
            $token_type = TokenType::ASSIGN_TOK;
        elseif (is_numeric($word))
            $token_type = TokenType::CONST_TOK;
        elseif ($word === "print")
            $token_type = TokenType::PRINT_TOK;
        elseif ($word === ")")
            $token_type = TokenType::RPARAN_TOK;
        elseif ($word === "(")
            $token_type = TokenType::LPARAN_TOK;
        elseif ($word === "end")
            $token_type = TokenType::END_TOK;
        elseif ($word === "if")
            $token_type = TokenType::IF_TOK;
        elseif ($word === "else")
            $token_type = TokenType::ELSE_TOK;
        elseif ($word === "then")
            $token_type = TokenType::THEN_TOK;
        elseif ($word === ">")
            $token_type = TokenType::GT_TOK;
        elseif ($word === ">=")
            $token_type = TokenType::GE_TOK;
        elseif ($word === "<")
            $token_type = TokenType::LT_TOK;
        elseif ($word === "<=")
            $token_type = TokenType::LE_TOK;
        elseif ($word === "/=")
            $token_type = TokenType::NE_TOK;
        elseif ($word === "==")
            $token_type = TokenType::EQ_TOK;
        elseif ($word === "from")
            $token_type = TokenType::FROM_TOK;
        elseif ($word === "until")
            $token_type = TokenType::UNTIL_TOK;
        elseif ($word === "loop")
            $token_type = TokenType::LOOP_TOK;
        elseif ($word === "+")
            $token_type = TokenType::ADD_TOK;
        elseif ($word === "-")
            $token_type = TokenType::SUB_TOK;
        elseif ($word === "/")
            $token_type = TokenType::DIV_TOK;
        elseif ($word === "*")
            $token_type = TokenType::MUL_TOK;
        else
            throw new LexicalException("invalid lexeme at row $current_line : $word");
        return $token_type;
    }

    function get_look_ahead_token()
    {
        if ($this->token_list === NULL)
            throw new LexicalException("No more tokens");
        return $this->token_list[0];
    }

    function get_next_token()
    {
        if ($this->token_list === NULL)
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