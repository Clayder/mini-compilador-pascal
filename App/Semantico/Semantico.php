<?php
namespace App\Semantico;

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
    private $tabelaSimbolo;
    private $auxTabelaSimbolo;
    private $tamCodigo;
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
        Teste::pre($this->arrayTokens);
        self::$token = $this->arrayTokens[self::$posToken];
        // verifica se o primeiro token é um comentário
        $this->ehComentario(self::$token);
        $this->start();
    }

    public function start(){
        $tam = $this->tamCodigo;
        while ($tam > 0){
            $tam--;
            switch (self::$token)
            {
                case ("const"):
                    echo "achei constante";
                    echo "<br/>";
                    $this->nextToken();
                    $this->getConstantes();
                    echo "Sai da const: ".self::$posToken;
                    echo "<br/>";
                    break;
                case ("var"):
                    echo "Entrei no var: ".self::$posToken;
                    echo "<br/>";
                    echo "achei variavel: ".self::$posToken;
                    echo "<br/>";
                    $this->nextToken();
                    $this->getVar();
                    break;
                case ("begin"):
                    echo "achei begin: ".self::$posToken;
                    echo "<br/>";
                    $this->addNivel();
                    $this->nextToken();
                    break;
                case ("end"):
                    echo "entrei end: ".self::$posToken;
                    echo "<br/>";
                    $this->retirarNivel();
                    $this->nextToken();
                    break;
                case ("else"):
                    $this->nextToken();
                    break;
                case ($this->ehCOM()):
                    $this->com();
                    echo "ehCOM: ".self::$posToken;
                    echo "<br/>";
                    break;
                default:
                    break;
            }
        }
    }

    public function getConstantes(){
        if(self::$token !== "var" && self::$token !== "begin"){
            $variavel = self::$token;
            $this->nextToken();
            if(self::$token === "="){
                $this->nextToken();
            }
            $this->setSimboloTabela("const", $variavel, "const", $this->nivel);
            $this->nextToken();
            if(self::$token === ";"){
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
    public function ehCOM(){
        $arrayCom = array("print", "if", "for", "read");
        if(in_array(self::$token, $arrayCom)){
            return true;
        }else{
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
    public function com(){
        switch (self::$token){
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
    public function verificaPrint(){
        /*
         * Quando entro no método
         * self::$token = print
         */
        $this->nextToken(); // self::$token = (
        $this->nextToken(); // self::$token = variavel
        while (self::$token != ")"){
            if(self::$token == ","){
                $this->nextToken();
            }else{
                if(!$this->verificoVarDeclarada()){
                    $this->error("Variável não declarada");
                }
                $this->nextToken();
            }
        }
        $this->nextToken(); // self::$token = ;
        $this->nextToken(); // volto para o metodo start e verifico esse token
    }

    public function verificaIf(){
        /*
         * Quando entro no método
         * self::$token = if
         */
        $this->nextToken(); // self::$token = variavel
        while (self::$token !== "then"){
            if($this->ehVariavel(self::$token)){
                if(!$this->verificoVarDeclarada()){
                    $this->error("Variável não declarada");
                }
            }
            $this->nextToken();
        }
        // self::$token = then
        $this->nextToken(); // volto para o metodo start e verifico esse token
    }

    public function verificaFor(){
        $arrayTipo = array();
        /*
         * Quando entro no método
         * self::$token = for
         */
        $this->nextToken(); // self::$token = variavel
        if(!$this->verificoVarDeclarada()){
            $this->error("Variável não declarada");
        }
        $variavelFor = self::$token;
        if($this->ehConstante($variavelFor)){
            $this->error("A variável é uma constante");
        }
        $this->nextToken(); // self::$token = :=
        $this->nextToken(); // self::$token = variavel
        while (self::$token !== "to"){
            if($this->ehVariavel(self::$token)){
                if(!$this->verificoVarDeclarada()){
                    $this->error("Variável não declarada");
                }else{
                    $dadosVariavel = $this->getDadosVariavelTabSimbolo(self::$token);
                    $arrayTipo[] = $dadosVariavel['tipo'];
                }
            }elseif(is_numeric(self::$token)){
                // transformo em inteiro ou float
                $numero = $this->get_numeric(self::$token);
                if(is_float($numero)){
                    $arrayTipo[] = "real";
                }else{
                    $arrayTipo[] = "integer";
                }
            }
            $this->nextToken();
        }
        if(!$this->verificarTipoVariavel($variavelFor, $arrayTipo)){
            $this->error("Erro de tipo", $variavelFor);
        }
        // self::$token = to
        $this->nextToken(); // self::$token = variavel ou numero
        while (self::$token !== "do"){
            if($this->ehVariavel(self::$token)){
                if(!$this->verificoVarDeclarada()){
                    $this->error("Variável não declarada");
                }
            }
            $this->nextToken();
        }
        // self::$token = do
        $this->nextToken(); // volto para o metodo start e verifico esse token
    }
    public function ehConstante($variavel){
        $dadosVariavel = $this->getDadosVariavelTabSimbolo($variavel);
        if($dadosVariavel['tipo'] === "const"){
            return true;
        }else{
            return false;
        }
    }
    public function verificarTipoVariavel($variavel, $arrayTipoRecebido){
        $dadosVariavel = $this->getDadosVariavelTabSimbolo($variavel);
        if($dadosVariavel['tipo'] === "integer"){
            if(!in_array("integer", $arrayTipoRecebido)){
                return false;
            }
        }
        return true;
    }

    public function verificaRead(){

    }

    public function verificoVarDeclarada(){
        foreach ($this->tabelaSimbolo as $simbolo){
            if($simbolo['variavel'] === self::$token){
                return true;
            }
        }
        return false;
    }
    /**
     *
     */
    public function getVar(){
        $variaveis = array();
        $tipo = "";
        if(self::$token !== "begin"){
            $variaveis[] = self::$token;
            $this->nextToken();
            while (self::$token != ":"){
                if(self::$token === ","){
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
            for($i = 0; $i < count($variaveis); $i++){
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
    public function setSimboloTabela($idToken, $variavel, $tipo, $nivel){
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

    public function getDadosVariavelTabSimbolo($variavel){
        foreach ($this->tabelaSimbolo as $simbolo){
            if($simbolo['variavel'] === $variavel){
                return array(
                    "idToken" => $simbolo['idToken'],
                    "variavel" => $simbolo['variavel'],
                    "tipo" => $simbolo['tipo'],
                    "nivel" => $simbolo['nivel'],
                );
            }
        }
        return array();
    }
    public function atualizarTabelaSimbolo($novaTabela){
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

    public function addNivel(){
        $this->nivel ++;
    }

    public function retirarNivel(){
        $array = $this->getTabelaSimbolo();
        foreach($this->getTabelaSimbolo() as $chave => $simbolo){
            if($simbolo['nivel'] == $this->nivel - 1){
                unset($array[$chave]);
            }
        }
        $this->atualizarTabelaSimbolo($array);
        $this->nivel --;
    }

    public function ehVariavel(){
        $arrayPalavrasReservadas = TabelaSimbolos::getPalavrasReservadas();
        if(!is_numeric(self::$token) && !in_array(self::$token, $arrayPalavrasReservadas) && !$this->ehSinal(self::$token) ){
            return true;
        }
        else{
            return false;
        }
    }

    public function ehSinal($token){
        $array = array(">","<","<>", ">=", "<=", ":=");
        if(in_array($token, $array)){
            return true;
        }elseif(Sinal::ehSinal($token)){
           return true;
        }else{
            return false;
        }
    }
    /**
     *
     * @param type $tokenEsperado
     * @return void
     */
    public function error($tipoErro, $variavel = null)
    {
        self::$msgError .= "<span style='color: red'> Erro </span> <span style='color: #23f617'> semantico </span> na linha: <span style='color: red'>";
        self::$msgError .= $this->arrayTokensLinha[self::$posToken]['linha'] . "</span> | ";
        if($variavel == null){
            self::$msgError .= "Token recebido: <span class='text-primary'>" . self::$token . "</span> | ";
        }else{
            self::$msgError .= "Token recebido: <span class='text-primary'>" . $variavel . "</span> | ";
        }

        self::$msgError .= "Tipo erro: <span style='color: red'>" . $tipoErro . "</span><br/>";
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