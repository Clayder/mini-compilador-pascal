<?php

namespace App\Semantico;

use App\GeradorCodigo\GeradorCodigo as Gerador;
use App\Lexico\Sinal;
use App\Lexico\TabelaSimbolos;
use App\Lexico\Teste\Teste;

/**
 * Class Semantico
 * @author Peter Clayder
 * @author Fernanda Pires
 */
class Semantico
{
    private $nivel = 0;
    private $hidden = "";
    private $tabelaSimbolo;
    private $auxTabelaSimbolo;
    private $tamCodigo;
    private $existeErro = false;
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

    /** MODIFIQUEI
     * @param array $arrayTokens É um array de token, onde cada índice do array é um token.
     * @param array $arrayTokensLinha É um array, onde cada índice possui um array com o token e a sua respectiva linha.
     */
    public function __construct($arrayTokens, $arrayTokensLinha)
    {
        $this->tabelaSimbolo = array();
        $this->auxTabelaSimbolo = array();
        $this->arrayTokens = $arrayTokens;
        $this->arrayTokensLinha = $arrayTokensLinha;
        $this->tamCodigo = count($this->arrayTokens);
        self::$token = $this->arrayTokens[self::$posToken];
        // verifica se o primeiro token é um comentário
        $this->ehComentario(self::$token);
        $this->start();
    }

    public function start()
    {
        $tam = $this->tamCodigo;
        while ($tam > 0) {
            $tam--;
            switch (self::$token) {
                case ("const"):
                    $this->nextToken();
                    $this->getConstantes();
                    break;
                case ("var"):
                    $this->nextToken();
                    $this->getVar();
                    break;
                case ("begin"):
                    Gerador::addToken("{", true);
                    $this->addNivel();
                    $this->nextToken();
                    break;
                case ("end"):
                    Gerador::addToken("}", true);
                    $this->retirarNivel();
                    $this->nextToken();
                    break;
                case (";"):
                    $this->nextToken();
                    break;

                case ("else"):
                    Gerador::addToken("else", true);
                    $this->nextToken();
                    break;
                case ($this->ehCOM()):
                    $this->com();
                    break;

                case ($this->ehVariavel()):
                    $this->verificaVariavel();
                    break;
                default:
                    break;
            }
        }
    }

    public function verificaVariavel()
    {
        $arrayTipo = array();
        $variavelRecebe = "";
        // self::$token = variavel
        if ($this->ehConstante(self::$token)) {
            $this->error("Modificação da constante");
        }
        if (!$this->verificoVarDeclarada()) {
            $this->error("Variável não declarada");
        }
        $variavelRecebe = self::$token;
        Gerador::addToken("$" . $variavelRecebe, true);
        $this->nextToken(); // self::$token = :=
        Gerador::addToken("=");
        $this->nextToken(); // self::$token = variavel ou expressão
        while (self::$token != ";") {
            if ($this->ehVariavel(self::$token)) {
                if (!$this->verificoVarDeclarada()) {
                    $this->error("Variável não declarada");
                } else {
                    $dadosVariavel = $this->getDadosVariavelTabSimbolo(self::$token);
                    $arrayTipo[] = $dadosVariavel['tipo'];
                }
                $this->insVarCodPhp(self::$token);
            } elseif (is_numeric(self::$token)) {
                // transformo em inteiro ou float
                $numero = $this->get_numeric(self::$token);
                if (is_float($numero) || is_double($numero)) {
                    $arrayTipo[] = "real";
                } else {
                    $arrayTipo[] = "integer";
                }
                Gerador::addToken(self::$token);
            } else {
                Gerador::addToken(self::$token);
            }
            $this->nextToken();
        }
        if ($this->verificarTipoVariavel($variavelRecebe, $arrayTipo)) {
            $this->error("Erro de tipo", $variavelRecebe, true);
        }
        Gerador::addToken(";");
        // self::token = ;
        $this->nextToken(); // volto para start e verifico esse token


    }

    public function getConstantes()
    {
        if (self::$token !== "var" && self::$token !== "begin") {
            Gerador::addToken("const", true);
            $variavel = self::$token;
            Gerador::addToken($variavel);
            $this->nextToken();
            if (self::$token === "=") {
                Gerador::addToken("=");
                $this->nextToken(); // self::$token = variavel ou numeric
                Gerador::addToken(self::$token);
            }
            $this->setSimboloTabela("const", $variavel, "const", $this->nivel);
            $this->nextToken();
            if (self::$token === ";") {
                Gerador::addToken(";");
                $this->nextToken();
                $this->getConstantes();
            }
        }
    }

    /**
     * Verifica se o token eh print, if, for ou read
     *
     * @return boolean
     */
    public function ehCOM()
    {
        $arrayCom = array("print", "if", "for", "read");
        if (in_array(self::$token, $arrayCom)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
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
        switch (self::$token) {
            case ("print"):
                $this->verificaPrint();
                break;
            case ("if"):
                $this->verificaIf();
                break;
            case ("for"):
                $this->verificaFor();
                break;
            case ("read"):
                $this->verificaRead();
                break;
            default:
                break;
        }
    }

    public function verificaPrint()
    {
        /*
         * Quando entro no método
         * self::$token = print
         */
        $this->nextToken(); // self::$token = (
        $this->nextToken(); // self::$token = variavel
        while (self::$token != ")") {
            if (self::$token == ",") {
                $this->nextToken();
            } else {
                if (!$this->verificoVarDeclarada()) {
                    $this->error("Variável não declarada");
                }
                Gerador::addToken("echo ", true);
                $this->insVarCodPhp(self::$token);
                Gerador::addToken(";");
                Gerador::addToken("echo '<br />';", true);
                $this->nextToken();
            }
        }
        $this->nextToken(); // self::$token = ;
        $this->nextToken(); // volto para o metodo start e verifico esse token
    }

    public function insVarCodPhp($variavel)
    {
        $dolar = '';
        if (!$this->ehConstante($variavel)) {
            $dolar = '$';
        }
        Gerador::addToken($dolar . self::$token);
    }

    public function verificaIf()
    {
        /*
         * Quando entro no método
         * self::$token = if
         */
        Gerador::addToken("if(", true);
        $this->nextToken(); // self::$token = variavel
        while (self::$token !== "then") {
            if ($this->ehVariavel(self::$token)) {
                $this->insVarCodPhp(self::$token);
                if (!$this->verificoVarDeclarada()) {
                    $this->error("Variável não declarada");
                }
            } else {
                Gerador::addToken(self::$token);
            }
            $this->nextToken();
        }
        // self::$token = then
        Gerador::addToken(")");
        $this->nextToken(); // volto para o metodo start e verifico esse token
    }

    public function verificaFor()
    {
        $arrayTipo = array();
        /*
         * Quando entro no método
         * self::$token = for
         */
        Gerador::addToken("for(", true);
        $this->nextToken(); // self::$token = variavel
        if (!$this->verificoVarDeclarada()) {
            $this->error("Variável não declarada");
        }
        $variavelFor = self::$token;
        Gerador::addToken("$" . $variavelFor);
        if ($this->ehConstante($variavelFor)) {
            $this->error("A variável é uma constante");
        }
        $this->nextToken(); // self::$token = :=
        Gerador::addToken("=");
        $this->nextToken(); // self::$token = variavel
        while (self::$token !== "to") {
            if ($this->ehVariavel(self::$token)) {
                if (!$this->verificoVarDeclarada()) {
                    $this->error("Variável não declarada");
                } else {
                    $dadosVariavel = $this->getDadosVariavelTabSimbolo(self::$token);
                    $arrayTipo[] = $dadosVariavel['tipo'];
                }
                $this->insVarCodPhp(self::$token);
            } elseif (is_numeric(self::$token)) {
                // transformo em inteiro ou float
                $numero = $this->get_numeric(self::$token);
                if (is_float($numero) || is_double($numero)) {
                    $arrayTipo[] = "real";
                } else {
                    $arrayTipo[] = "integer";
                }
                Gerador::addToken($numero);
            } else {
                Gerador::addToken(self::$token);
            }
            $this->nextToken();
        }
        if ($this->verificarTipoVariavel($variavelFor, $arrayTipo)) {
            $this->error("Erro de tipo", $variavelFor, true);
        }
        // self::$token = to
        $this->nextToken(); // self::$token = variavel ou numero
        Gerador::addToken(";");
        Gerador::addToken("$" . $variavelFor);
        while (self::$token !== "do") {
            if ($this->ehVariavel(self::$token)) {
                if (!$this->verificoVarDeclarada()) {
                    $this->error("Variável não declarada");
                }
                Gerador::addToken("<=");
                $this->insVarCodPhp(self::$token);
            } else {
                Gerador::addToken("<=");
                Gerador::addToken(self::$token);
            }
            $this->nextToken();
        }
        Gerador::addToken("; $" . $variavelFor . "++");
        Gerador::addToken(")");
        // self::$token = do
        $this->nextToken(); // volto para o metodo start e verifico esse token
    }

    public function verificaRead()
    {
        /*
         * Quando entro no método
         * self::$token = read
         */
        $this->nextToken(); // self::$token = (
        $this->nextToken(); // self::$token = variavel
        while (self::$token != ")") {
            if (self::$token == ",") {
                $this->nextToken();
            } else {
                if (!$this->verificoVarDeclarada()) {
                    $this->error("Variável não declarada");
                }
                if($this->ehConstante(self::$token)){
                    $this->error("Modificação da constante");
                }
                $this->escreveReadCodigo(self::$token);
                $this->nextToken();
            }
        }
        $this->nextToken(); // self::$token = ;
        $this->nextToken(); // volto para o metodo start e verifico esse token

    }

    public function escreveReadCodigo($variavel){
        $s = "$";
        $read = $s.$variavel." = (!isset(\$_GET['$variavel']))? exit('<form action=\"\"> Forneça o valor de $variavel : <input value=\"\" name=\"$variavel\" autofocus/> <input type=\"submit\" value=\"enter\"> $this->hidden </form>') : \$_GET['$variavel'];";
        $this->hidden = $this->hidden . "<input type=\"hidden\" value=\"'.\$_GET['".$variavel."'].'\" name=\" $variavel\" />";
        Gerador::addToken($read, true);
    }

    public function ehConstante($variavel)
    {
        $dadosVariavel = $this->getDadosVariavelTabSimbolo($variavel);
        if ($dadosVariavel['tipo'] === "const") {
            return true;
        } else {
            return false;
        }

    }

    public function verificarTipoVariavel($variavel, $arrayTipoRecebido)
    {
        $dadosVariavel = $this->getDadosVariavelTabSimbolo($variavel);
        $arrayTipoRecebido = $this->retirarItemArray($arrayTipoRecebido, "const");
        $warning = false;

        if ($dadosVariavel['tipo'] != "const") {
            if ($dadosVariavel['tipo'] === "integer") {
                if (!in_array("integer", $arrayTipoRecebido)) {
                    $warning = true;
                }
            }
            if (count($arrayTipoRecebido) > 1 && !$this->verificaTipoIguais($arrayTipoRecebido)) {
                $warning = true;
            }
        }

        return $warning;
    }

    public function verificaTipoIguais($arrayTipo)
    {
        $tam = count($arrayTipo);
        for ($i = 0; $i < $tam - 1; $i++) {
            for ($j = $i + 1; $j < $tam; $j++) {
                if ($arrayTipo[$i] !== $arrayTipo[$j]) {
                    return false;
                }
            }
        }
        return true;
    }

    public function retirarItemArray($array, $item)
    {
        $aux = array();
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i] !== $item) {
                $aux[] = $array[$i];
            }
        }
        return $aux;
    }

    public function verificoVarDeclarada()
    {
        foreach ($this->tabelaSimbolo as $simbolo) {
            if ($simbolo['variavel'] === self::$token) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     */
    public function getVar()
    {
        $variaveis = array();
        $tipo = "";
        if (self::$token !== "begin") {
            $variaveis[] = self::$token;
            $this->nextToken();
            while (self::$token != ":") {
                if (self::$token === ",") {
                    $this->nextToken();
                }
                $variaveis[] = self::$token;
                $this->nextToken();
            }
            /*
             * terminou o while, então o token atual é :
             * Acesso o proximo token.
             */
            $this->nextToken();
            $tipo = self::$token;
            for ($i = 0; $i < count($variaveis); $i++) {
                $this->setSimboloTabela("variavel", $variaveis[$i], $tipo, $this->nivel);
            }
            // self:$token = ;
            $this->nextToken();
            $this->nextToken();
            $this->getVar();
        }
    }

    /**
     * @param $idToken
     * @param $variavel
     * @param $tipo
     * @param $nivel
     */
    public function setSimboloTabela($idToken, $variavel, $tipo, $nivel)
    {
        $this->tabelaSimbolo[] = array(
            "idToken" => $idToken,
            "variavel" => $variavel,
            "tipo" => $tipo,
            "nivel" => $nivel,
        );

        $this->auxTabelaSimbolo[] = array(
            "idToken" => $idToken,
            "variavel" => $variavel,
            "tipo" => $tipo,
            "nivel" => $nivel,
        );
    }

    public function getDadosVariavelTabSimbolo($variavel)
    {
        foreach ($this->tabelaSimbolo as $simbolo) {
            if ($simbolo['variavel'] === $variavel) {
                return array(
                    "idToken" => $simbolo['idToken'],
                    "variavel" => $simbolo['variavel'],
                    "tipo" => $simbolo['tipo'],
                    "nivel" => $simbolo['nivel'],
                );
            }
        }
        return array("idToken" => null, "variavel" => "", "tipo" => "", "nivel" => null);
    }

    public function atualizarTabelaSimbolo($novaTabela)
    {
        $this->tabelaSimbolo = $novaTabela;
    }

    /**
     * @return array
     */
    public function getTabelaSimbolo()
    {
        return $this->tabelaSimbolo;
    }

    /**
     * @return array
     */
    public function getAuxTabelaSimbolo()
    {
        return $this->auxTabelaSimbolo;
    }

    /**
     * Verifica se o token é um comentário
     * Se for comentário, ele tem que ser ignorado
     * @param $token
     * @return void
     */
    public function ehComentario($token)
    {
        $ultimoCaracterToken = substr($token, -1);
        $primeiroCaracterToken = $token[0];
        if ($primeiroCaracterToken === "{" && $ultimoCaracterToken === "}") {
            $this->nextToken();
        } else {
            self::$token = $token;
        }
    }

    /**
     *
     * @return string
     */
    public static function getMsgError()
    {
        return self::$msgError;
    }

    public function addNivel()
    {
        $this->nivel++;
    }

    public function retirarNivel()
    {
        $array = $this->getTabelaSimbolo();
        foreach ($this->getTabelaSimbolo() as $chave => $simbolo) {
            if ($simbolo['nivel'] == $this->nivel - 1) {
                unset($array[$chave]);
            }
        }
        $this->atualizarTabelaSimbolo($array);
        $this->nivel--;
    }

    public function ehVariavel()
    {
        $arrayPalavrasReservadas = TabelaSimbolos::getPalavrasReservadas();
        if (!is_numeric(self::$token) && !in_array(self::$token, $arrayPalavrasReservadas) && !$this->ehSinal(self::$token)) {
            return true;
        } else {
            return false;
        }
    }

    public function ehSinal($token)
    {
        $array = array(">", "<", "<>", ">=", "<=", ":=");
        if (in_array($token, $array)) {
            return true;
        } elseif (Sinal::ehSinal($token)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param type $tokenEsperado
     * @return void
     */
    public function error($tipoErro, $variavel = null, $isWarning = false)
    {
        if ($isWarning) {
            self::$msgError .= "<span style='color: yellow'> WARNING! </span> <span style='color: #23f617'> semantico </span> na linha: <span style='color: red'>";
        } else {
            $this->existeErro = true;
            self::$msgError .= "<span style='color: red'> Erro </span> <span style='color: #23f617'> semantico </span> na linha: <span style='color: red'>";
        }
        self::$msgError .= $this->arrayTokensLinha[self::$posToken]['linha'] . "</span> | ";
        if ($variavel == null) {
            self::$msgError .= "Token recebido: <span class='text-primary'>" . self::$token . "</span> | ";
        } else {
            self::$msgError .= "Token recebido: <span class='text-primary'>" . $variavel . "</span> | ";
        }
        self::$msgError .= "Tipo erro: <span style='color: red'>" . $tipoErro . "</span><br/>";
    }

    /**
     * @return bool
     */
    public function existeErro()
    {
        return $this->existeErro;
    }


    /** MODIFIQUEI
     * Seleciona o próximo token
     *
     * return void
     */
    public function nextToken()
    {
        self::$posToken++;
        if ($this->tamCodigo > self::$posToken) {
            $this->ehComentario($this->arrayTokens[self::$posToken]);
        }
    }

    /**
     * Recebe um número no formato de string e transforma ele em inteiro ou float
     * @param numeric
     * @return int|float | string
     */
    public function get_numeric($val)
    {
        if (is_numeric($val)) {
            return $val + 0;
        }
        return 'n';
    }
}