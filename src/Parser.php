<?php
require 'LexicalAnalyzer.php';
require 'ParserException.php';
require 'Assignment.php';
require 'Feature.php';
require 'Id.php';
require 'Compound.php';
require 'Constant.php';
require 'PrintStatement.php';

/**
 * Created by PhpStorm.
 * User: thamilton
 * Date: 10/28/14
 * Time: 8:56 AM
 */
class Parser
{
    private $lex;

    function __construct($file_name)
    {
        if ($file_name === NULL)
            throw new InvalidArgumentException("invalid file name argument");
        $this->lex = new LexicalAnalyzer($file_name);
    }

    function feature()
    {
        $token = $this->get_next_token();
        $this->match($token, TokenType::FEATURE_TOK);
        $id = $this->get_id();
        $token = $this->get_next_token();
        $this->match($token, TokenType::IS_TOK);
        $token = $this->get_next_token();
        $this->match($token, TokenType::DO_TOK);
        $compound = $this->get_compound();
        $token = $this->get_next_token();
        if($token->token_type != TokenType::END_TOK)
            throw new ParserException("garbage at end of program");
        return new Feature($compound);
    }

    function get_compound()
    {
        $statement = $this->get_statement();
        $compound = new Compound();
        $compound->add($statement);
        $token = $this->get_look_ahead_token();
        while($this->is_valid_start($token))
        {
            $statement = $this->get_statement();
            $compound->add($statement);
            $token = $this->get_look_ahead_token();
        }
        return $compound;
    }

    function is_valid_start($token)
    {
        if($token == NULL)
            throw new ParserException("token is null");
        return $token->token_type == TokenType::ID_TOK || $token->token_type == TokenType::PRINT_TOK;
    }

    function get_statement()
    {
        $token = $this->get_look_ahead_token();
        switch($token->token_type){
            case TokenType::ID_TOK:
                $statement = $this->get_assignment_statement();
                break;
            case TokenType::PRINT_TOK:
                $statement = $this->get_print_statement();
                break;
            default:
                throw new ParserException("statement initializing lexeme expected, $token->get_lexeme");
        }
        return $statement;
    }

    function get_assignment_statement()
    {
        $id = $this->get_id();
        $token = $this->get_next_token();
        $this->match($token, TokenType::ASSIGN_TOK);
        $expression = $this->get_expression();
        return new Assignment($id, $expression);
    }

    function get_id()
    {
        $token = $this->get_next_token();
        $this->match($token, TokenType::ID_TOK);
        return new Id($token->value);
    }

    function get_print_statement()
    {
        $token = $this->get_next_token();
        $this->match($token, TokenType::PRINT_TOK);
        $token = $this->get_next_token();
        $this->match($token, TokenType::LPARAN_TOK);
        $expression = $this->get_expression();
        $token = $this->get_next_token();
        $this->match($token, TokenType::RPARAN_TOK);
        return new PrintStatement($expression);
    }

    function get_expression()
    {
        $token = $this->get_look_ahead_token();
        if($token->token_type == TokenType::ID_TOK)
            $expression = $this->get_id();
        elseif($token->token_type == TokenType::CONST_TOK)
            $expression = $this->get_literal_integer();
        else {
            $operator = $this->get_arithmetic_operator();
            $expression_1 = $this->get_expression();
            $expression_2 = $this->get_expression();
            $expression = new BinaryExpression($operator, $expression_1, $expression_2);
        }
        return $expression;
    }

    function get_literal_integer()
    {
        $token = $this->get_next_token();
        $this->match($token, TokenType::CONST_TOK);
        return new Constant($token->value);
    }

    function get_arithmetic_operator()
    {
        $token = $this->get_next_token();
        return "";
    }

    function match($token, $token_type)
    {
        if($token->token_type != $token_type)
            throw new ParserException("$token_type expected at $token->row_num and $token->col_num: $token->value");
    }

    function get_next_token()
    {
        return $this->lex->get_next_token();
    }

    function get_look_ahead_token()
    {
        return $this->lex->get_look_ahead_token();
    }

    function get_lexeme()
    {
        return $this->lex->get_lexeme();
    }

} 