<?php
require 'LexicalAnalyzer.php';
require 'ParserException.php';
require 'Assignment.php';
require 'Feature.php';
require 'Id.php';
require 'Compound.php';
require 'Constant.php';
require 'PrintStatement.php';
require 'IfStatement.php';
require 'RelationalOperator.php';
require 'BooleanExpression.php';
require 'LoopStatement.php';
require 'BinaryExpression.php';
require 'ArithmeticOperator.php';

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
        if ($token->token_type != TokenType::END_TOK)
            throw new ParserException("garbage at end of program");
        return new Feature($compound);
    }

    function get_compound()
    {
        $statement = $this->get_statement();
        $compound = new Compound();
        $compound->add($statement);
        $token = $this->get_look_ahead_token();
        while ($this->is_valid_start($token)) {
            $statement = $this->get_statement();
            $compound->add($statement);
            $token = $this->get_look_ahead_token();
        }
        return $compound;
    }

    function is_valid_start($token)
    {
        if ($token == NULL)
            throw new ParserException("token is null");
        return $token->token_type == TokenType::ID_TOK || $token->token_type == TokenType::PRINT_TOK
        || $token->token_type == TokenType::IF_TOK || $token->token_type == TokenType::FROM_TOK;
    }

    function get_statement()
    {
        $token = $this->get_look_ahead_token();
        switch ($token->token_type) {
            case TokenType::ID_TOK:
                $statement = $this->get_assignment_statement();
                break;
            case TokenType::PRINT_TOK:
                $statement = $this->get_print_statement();
                break;
            case TokenType::IF_TOK:
                $statement = $this->get_if_statement();
                break;
            case TokenType::FROM_TOK:
                $statement = $this->get_loop_statement();
                break;
            default:
                throw new ParserException("statement initializing lexeme expected, $token->get_lexeme");
        }
        return $statement;
    }

    function get_loop_statement()
    {
        $token = $this->get_next_token();
        $this->match($token, TokenType::FROM_TOK);
        $statement = $this->get_assignment_statement();
        $token = $this->get_next_token();
        $this->match($token, TokenType::UNTIL_TOK);
        $boolean_expression = $this->get_boolean_expression();
        $token = $this->get_next_token();
        $this->match($token, TokenType::LOOP_TOK);
        $compound = $this->get_compound();
        $token = $this->get_next_token();
        $this->match($token, TokenType::END_TOK);
        return new LoopStatement($statement, $boolean_expression, $compound);
    }

    function get_if_statement()
    {
        $token = $this->get_next_token();
        $this->match($token, TokenType::IF_TOK);
        $boolean_expression = $this->get_boolean_expression();
        $token = $this->get_next_token();
        $this->match($token, TokenType::THEN_TOK);
        $compound_1 = $this->get_compound();
        $token = $this->get_next_token();
        $this->match($token, TokenType::ELSE_TOK);
        $compound_2 = $this->get_compound();
        $token = $this->get_next_token();
        $this->match($token, TokenType::END_TOK);
        return new IfStatement($boolean_expression, $compound_1, $compound_2);
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
        if ($token->token_type == TokenType::ID_TOK)
            $expression = $this->get_id();
        elseif ($token->token_type == TokenType::CONST_TOK)
            $expression = $this->get_literal_integer();
        else {
            $operator = $this->get_arithmetic_operator();
            $expression_1 = $this->get_expression();
            $expression_2 = $this->get_expression();
            $expression = new BinaryExpression($operator, $expression_1, $expression_2);
        }
        return $expression;
    }

    function get_boolean_expression()
    {
        $operator = $this->get_relational_operator();
        $expression_1 = $this->get_expression();
        $expression_2 = $this->get_expression();
        return new BooleanExpression($operator, $expression_1, $expression_2);
    }

    function get_relational_operator()
    {
        $token = $this->get_next_token();
        if ($token->token_type == TokenType::GT_TOK)
            $operator = RelationalOperator::GT_OP;
        elseif ($token->token_type == TokenType::GE_TOK)
            $operator = RelationalOperator::GE_OP;
        elseif ($token->token_type == TokenType::LT_TOK)
            $operator = RelationalOperator::LT_OP;
        elseif ($token->token_type == TokenType::LE_TOK)
            $operator = RelationalOperator::LE_OP;
        elseif ($token->token_type == TokenType::NE_TOK)
            $operator = RelationalOperator::NE_OP;
        elseif ($token->token_type == TokenType::EQ_TOK)
            $operator = RelationalOperator::EQ_OP;
        else
            throw new ParserException("relation operator expected : $token->get_lexeme");
        return $operator;
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
        switch ($token->token_type) {
            case TokenType::ADD_TOK:
                $operator = ArithmeticOperator::ADD_OP;
                break;
            case TokenType::SUB_TOK:
                $operator = ArithmeticOperator::SUB_OP;
                break;
            case TokenType::MUL_TOK:
                $operator = ArithmeticOperator::MUL_OP;
                break;
            case TokenType::DIV_TOK:
                $operator = ArithmeticOperator::DIV_OP;
                break;
            default:
                throw new ParserException("could not find arithmetic operator");
        }
        return $operator;
    }

    function match($token, $token_type)
    {
        if ($token->token_type != $token_type)
            throw new ParserException("$token_type expected at $token->row_num : $token->value");
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