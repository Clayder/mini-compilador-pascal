<?php

namespace App\Sintatico;

use App\Lexico\TabelaSimbolos;

/**
 * Description of AnalSintatico
 *
 * @author Peter Clayder
 * @author Fernanda Pires
 */
class AnalSintatico
{
    /**
     * Array de tokens formado pelo código.
     * @var array
     */
    private $arrayTokens;

    /**
     * Cada índice possui um array com o token e a sua respectiva linha escrita no código. ex: $arrayTokensLinha[0]['token'] = "if"; $arrayTokensLinha[0]['linha'] = "8"; 
     * @var array
     */
    private $arrayTokensLinha;

    /**
     * Recebe a posição no código do token que está sendo verificado
     */
    private static $posToken = 0;

    /**
     * Recebe o token que está sendo verificado
     * @var type
     */
    private static $token;

    /**
     * Armazena os erros gerados
     * @var string
     */
    private static $msgError;

    /**
     * 
     * @param array $arrayTokens É um array de token, onde cada índice do array é um token. 
     * @param array $arrayTokensLinha É um array, onde cada índice possui um array com o token e a sua respectiva linha. 
     */
    public function __construct($arrayTokens, $arrayTokensLinha)
    {
        $this->arrayTokens = $arrayTokens;
        $this->arrayTokensLinha = $arrayTokensLinha;
        self::$token = $this->arrayTokens[self::$posToken];
        $this->prog();
    }

    /**
     * PROG -> CONSTANTES BLOCO .
     * 
     * @return void
     */
    public function prog()
    {
        $this->constantes();
        $this->bloco();
        $this->verificarToken(".");
        $this->verificarToken("EOF");
    }

    public function constantes(){
        if($this->tokenIgual("const")){
            $this->nextToken();
            $this->listaConstantes();
        }
    }

    public function listaConstantes(){
        $this->defConst();
        $this->listasConstantes2();
    }

    public function listasConstantes2(){
        if($this->verificaVariavel()) {
            $this->listaConstantes();
        }
    }

    public function defConst(){
        $this->ident();
        $this->verificarToken("=");
        $this->num();
        $this->verificarToken(";");
    }

    public function num(){
        if($this->numInt()){
        }elseif($this->numFloat()){
        }else{
            $this->error("número inteiro ou real");
        }
    }

    public function numFloat(){
        if (is_float($this->get_numeric(self::$token)))
        {
            $this->nextToken();
            return true;
        } else
        {
            return false;
        }
    }

    /**
     * VARS -> var LISTAS_IDENT | E
     * 
     * @return void 
     */
    public function vars()
    {
        if ($this->tokenIgual("var"))
        {
            $this->nextToken();
            $this->listasIdent();
        }
    }
    /*
     * LISTAS_IDENT -> DEF_LISTAS_IDENT lISTAS_IDENT2
     */
    public function listasIdent(){
        $this->defListasIdent();
        $this->listasIdent2();
    }

    public function listasIdent2(){
        if($this->verificaVariavel()){
            $this->listasIdent();
        }
    }

    public function defListasIdent(){
        $this->listaIdent();
        $this->verificarToken(":");
        $this->defListasIdent2();
    }

    public function defListasIdent2(){
        if ($this->tokenIgual("integer") || $this->tokenIgual("real"))
        {
            $this->nextToken();
            $this->verificarToken(";");
        }else{
            $this->error("integer ou real");
        }
    }

    /**
     * LISTA_IDENT -> IDENT LISTA_IDENT2
     * 
     * @return void
     */
    public function listaIdent()
    {
        if ($this->ident())
        {
            $this->listaIdent2();
        } else
        {
            $this->error("VARIAVEL 1");
        }
    }

    /**
     * LISTA_IDENT2 -> , LISTA_IDENT
     * 
     * LISTA_IDENT2 -> E
     * 
     * @return void 
     */
    public function listaIdent2()
    {
        if ($this->tokenIgual(","))
        {
            $this->nextToken();
            $this->listaIdent();
        }
    }

    /**
     * IDENT -> CARACTER IDENT2
     * 
     * IDENT2 -> IDENT
     * 
     * IDENT2 -> E
     * 
     * ----------------------------------------------------------------------------
     * 
     * CARACTER -> a | ... | z | A | ... | Z 
     * 
     * Se o token for uma variável, então ele já vai ser um conjunto de caracteres. 
     * Com isso não tivemos a necessidade de implementar os métodos caracter e ident2
     * 
     * @return boolean
     */
    public function ident()
    {
        if ($this->verificaVariavel())
        {
            $this->nextToken();
            return true;
        }
        return false;
    }

    /**
     * BLOCO -> VARS begin COMS end
     * 
     * @return void 
     */
    public function bloco()
    {
        $this->vars();
        $this->verificarToken("begin");
        $this->coms();
        $this->verificarToken("end");
    }

    /**
     * COMS -> COM ; COMS | E
     * 
     * @return void
     */
    public function coms()
    {
        if ($this->com())
        {
            $this->verificarToken(";");
            $this->coms();
        }
    }

    /**
     * COM -> print ( LISTA_IDENT ) |
     *        if exp_rel then BLOCO ELSE_OPC |
     *        IDENT := EXP |
     *        for IDENT := EXP to EXP do BLOCO |
     *        read ( LISTA_IDENT )
     * 
     * @return boolean
     */
    public function com()
    {
        $return = true;
        if ($this->tokenIgual("print"))
        {
            $this->verificaPrint();
        } elseif ($this->tokenIgual("if"))
        {
            $this->verificarIf();
        } elseif ($this->ident())
        {
            $this->verificarToken(":=");
            $this->exp();
        } elseif ($this->tokenIgual("for"))
        {
            $this->verificarFor();
        } elseif ($this->tokenIgual("read"))
        {
            $this->verificarRead();
        } else
        {
            $return = false;
        }
        return $return;
    }

    /**
     * 
     * @return void 
     */
    public function verificaPrint()
    {
        $this->nextToken();
        $this->verificarToken("(");
        $this->listaIdent();
        $this->verificarToken(")");
    }

    /**
     * 
     * @return void 
     */
    public function verificarIf()
    {
        $this->nextToken();
        $this->expRel();
        $this->verificarToken("then");
        $this->bloco();
        $this->elseOpc();
    }

    /**
     * 
     * @return void 
     */
    public function verificarFor()
    {
        $this->nextToken();
        $this->ident();
        $this->verificarToken(":=");
        $this->exp();
        $this->verificarToken("to");
        $this->exp();
        $this->verificarToken("do");
        $this->bloco();
    }

    /**
     * 
     * @return void 
     */
    public function verificarRead()
    {
        $this->nextToken();
        $this->verificarToken("(");
        $this->listaIdent();
        $this->verificarToken(")");
    }

    /**
     * EXP_REL -> EXP OP_REL EXP
     * 
     * @return void 
     */
    public function expRel()
    {
        $this->exp();
        $this->opRel();
        $this->exp();
    }

    /**
     * EXP -> TERMO EXP2
     * 
     * @return void 
     */
    public function exp()
    {
        $this->termo();
        $this->exp2();
    }

    /**
     * EXP2 -> + TERMO EXP2 |
     *         - TERMO EXP2 |
     *         E
     * 
     * 
     * @return void 
     */
    public function exp2()
    {
        if ($this->tokenIgual("+"))
        {
            $this->verificarToken("+");
            $this->termo();
            $this->exp2();
        } elseif ($this->tokenIgual("-"))
        {
            $this->verificarToken("+");
            $this->termo();
            $this->exp2();
        }
    }

    /**
     * TERMO -> FATOR TERMO2
     * 
     * @return void
     */
    public function termo()
    {
        $this->fator();
        $this->termo2();
    }

    /**
     * TERMO2 -> * FATOR TERMO2 |
     *           / FATOR TERMO2 |
     *           E
     * 
     * @return void
     */
    public function termo2()
    {
        if ($this->tokenIgual("*"))
        {
            $this->nextToken();
            $this->fator();
            $this->termo2();
        } elseif ($this->tokenIgual("/"))
        {
            $this->nextToken();
            $this->fator();
            $this->termo2();
        }
    }

    /**
     * OP_REL -> =  |
     *           <> |
     *           <  |
     *           >  |
     *           <= |
     *           >= 
     * 
     * @return void
     */
    public function opRel()
    {
        if ($this->tokenIgual("=") || $this->tokenIgual("<>") || $this->tokenIgual("<") || $this->tokenIgual(">") || $this->tokenIgual("<=") || $this->tokenIgual(">="))
        {
            $this->nextToken();
        }
    }

    /**
     * FATOR -> ( EXP ) |
     *          IDENT   |
     *          NUM
     * 
     * @return void
     */
    public function fator()
    {
        if ($this->tokenIgual("("))
        {
            $this->verificarToken("(");
            $this->exp();
            $this->verificarToken(")");
        } else
        {
            if ($this->ident())
            {
                
            } elseif ($this->num())
            {
                
            } else
            {
                $this->error("'(' ou 'VARIAVEL' ou 'NUM' ");
            }
        }
    }

    /**
     * NUM_INT -> DIGITO NUM_INT2
     * --------------------------------------------------
     * NUM_INT2 -> DIGITO |
     *         E
     * ---------------------------------------------------
     * 
     * DIGITO -> 0 | ... | 9
     * 
     * Se o token for um número, então ele já vai ser um conjunto de dígitos. 
     * Então não tivemos a necessidade de implementar os métodos num2 e digito
     * 
     * @return boolean
     */
    public function numInt()
    {
        if (is_int($this->get_numeric(self::$token)))
        {
            $this->nextToken();
            return true;
        } else
        {
            return false;
        }
    }

    /**
     * ELSE_OPC -> else BLOCO |
     *             E
     * 
     * @return void
     */
    public function elseOpc()
    {
        if ($this->tokenIgual("else"))
        {
            $this->nextToken();
            $this->bloco();
        }
    }

    /**
     * Verifica se o token é igual ao token esperado ($tokenCorreto). 
     * Se for igual, selecionamos o próximo token. 
     * Caso contrário, retornamos erro sintático. 
     * 
     * @param type $tokenCorreto
     * @return void
     */
    public function verificarToken($tokenCorreto)
    {
        if ($this->tokenIgual($tokenCorreto))
        {
            $this->nextToken();
        } else
        {
            $this->error($tokenCorreto);
        }
    }

    /**
     * Compara o token esperado ($token) com o token atual (self::$token). 
     * 
     * @param type $token
     * @return boolean
     */
    public function tokenIgual($token)
    {
        if (self::$token === $token)
        {
            return true;
        } else
        {
            return false;
        }
    }

    /**
     * Seleciona o próximo token 
     * 
     * return void
     */
    public function nextToken()
    {
        $tamCodigo = count($this->arrayTokens);
        self::$posToken++;
        if ($tamCodigo > self::$posToken)
        {
            self::$token = $this->arrayTokens[self::$posToken];
        }
    }

    /**
     * Recebe um número no formato de string e transforma ele em inteiro ou float
     * @param numeric
     * @return int|float | string
     */
    public function get_numeric($val) {
        if (is_numeric($val)) {
            return $val + 0;
        }
        return 'n';
    }

    /**
     * 
     * @return string
     */
    public static function getMsgError()
    {
        return self::$msgError;
    }

    /**
     * 
     * @param type $tokenEsperado
     * @return void 
     */
    public function error($tokenEsperado)
    {
        self::$msgError .= "<span style='color: red'> Erro </span> <span style='color: #23f617'> sintático </span> na linha: <span style='color: red'>";
        self::$msgError .= $this->arrayTokensLinha[self::$posToken]['linha'] . "</span> | ";
        self::$msgError .= "Token recebido: <span class='text-primary'>" . self::$token . "</span> | ";
        self::$msgError .= "Token esperado: <span style='color: red'>" . $tokenEsperado . "</span><br/>";
    }

    /**
     * Verifica se o token é uma variavel
     * @return boolean
     */
    public function verificaVariavel()
    {
        if (is_string(self::$token) && !in_array(self::$token, TabelaSimbolos::getPalavrasReservadas()))
        {
            return true;
        }
        return false;
    }

}
