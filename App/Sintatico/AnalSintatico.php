<?php

namespace App\Sintatico;

use App\Lexico\TabelaSimbolos;
/*
* To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

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
  * Array de tokens com a sua respectiva linha.
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

  public function __construct($arrayTokens, $arrayTokensLinha)
  {
    $this->arrayTokens = $arrayTokens;
    $this->arrayTokensLinha = $arrayTokensLinha;
    self::$token = $this->arrayTokens[self::$posToken];
    $this->prog();
  }

  public function prog(){
    $this->vars();
    $this->bloco();
    $this->verificarToken(".");
    $this->verificarToken("EOF");
  }

  public function vars(){
    if( $this->tokenIgual("var") )
    {
      $this->nextToken();
      $this->listaIdent();
      $this->verificarToken(";");
    }
  }

  public function listaIdent(){
    if($this->ident()){
      $this->listaIdent2();
    }else{
       $this->error("VARIAVEL 1");
    }
  }

  public function listaIdent2(){
      if($this->tokenIgual(",")){
        $this->nextToken();
        $this->listaIdent();
      }
  }

  public function ident(){
    if($this->verificaVariavel()){
      $this->nextToken();
      return true;
    }
    return false;
  }

  public function bloco(){
    $this->verificarToken("begin");
    $this->coms();
    $this->verificarToken("end");
  }

  public function coms(){
    if($this->com()){
      $this->verificarToken(";");
      $this->coms();
    }
  }

  public function com(){
    $return = true;
    if($this->tokenIgual("print")){
      $this->verificaPrint();
    }
    elseif($this->tokenIgual("if")){
      $this->verificarIf();
    }
    elseif($this->ident()){
        $this->verificarToken(":=");
        $this->exp();
    }
    elseif($this->tokenIgual("for")){
        $this->verificarFor();
    }
    elseif($this->tokenIgual("read")){
        $this->verificarRead();
    }
    else{
      $return = false;
    }
    return $return;
  }

  public function verificaPrint(){
    $this->nextToken();
    $this->verificarToken("(");
    $this->listaIdent();
    $this->verificarToken(")");
  }

  public function verificarIf(){
    $this->nextToken();
    $this->expRel();
    $this->verificarToken("then");
    $this->bloco();
    $this->elseOpc();
  }

  public function verificarFor(){
    $this->nextToken();
    $this->ident();
    $this->verificarToken(":=");
    $this->exp();
    $this->verificarToken("to");
    $this->exp();
    $this->verificarToken("do");
    $this->bloco();
  }

  public function verificarRead(){
      $this->nextToken();
      $this->verificarToken("(");
      $this->listaIdent();
      $this->verificarToken(")");
  }

  public function expRel(){
      $this->exp();
      $this->opRel();
      $this->exp();
  }

  public function exp(){
    $this->termo();
    $this->exp2();
  }

  public function exp2(){
    if($this->tokenIgual("+")){
      $this->verificarToken("+");
      $this->termo();
      $this->exp2();
    }elseif($this->tokenIgual("-")){
      $this->verificarToken("+");
      $this->termo();
      $this->exp2();
    }
  }

  public function termo(){
    $this->fator();
    $this->termo2();
  }

  public function termo2(){
    if($this->tokenIgual("*")){
      $this->nextToken();
      $this->fator();
      $this->termo2();
    }elseif($this->tokenIgual("/")){
      $this->nextToken();
      $this->fator();
      $this->termo2();
    }
  }

  public function opRel(){
    if($this->tokenIgual("=") || $this->tokenIgual("<>") || $this->tokenIgual("<") || $this->tokenIgual(">") || $this->tokenIgual("<=") || $this->tokenIgual(">=")){
      $this->nextToken();
    }
  }

  public function fator(){
    if($this->tokenIgual("(")){
      $this->verificarToken("(");
      $this->exp();
      $this->verificarToken(")");
    }else{
      if($this->ident()){
      }
      elseif ($this->num()) {
      }
      else{
        $this->error("'(' ou 'VARIAVEL' ou 'NUM' ");
      }
    }
  }

  public function num(){
      if(is_int(self::$token)){
        $this->nextToken();
        return true;
      }else{
        return false;
      }
  }

  public function elseOpc(){
    if($this->tokenIgual("else")){
      $this->nextToken();
      $this->bloco();
    }
  }

  public function verificarToken($tokenCorreto)
  {
    if($this->tokenIgual($tokenCorreto)){
      $this->nextToken();
    }else{
      $this->error($tokenCorreto);
    }
  }

  public function tokenIgual($token){
    if(self::$token === $token){
      return true;
    }else {
      return false;
    }
  }

  public function nextToken()
  {
    $tamCodigo = count($this->arrayTokens);
    self::$posToken++;
    if($tamCodigo > self::$posToken){
      self::$token = $this->arrayTokens[self::$posToken];
    }
  }

  public static function getMsgError(){
    return self::$msgError;
  }

  public function error($tokenEsperado){
    self::$msgError .= "<span style='color: red'> Erro </span> <span style='color: #23f617'> sintático </span> na linha: <span style='color: red'>";
    self::$msgError .= $this->arrayTokensLinha[self::$posToken]['linha'] . "</span> | ";
    self::$msgError .= "Token recebido: <span class='text-primary'>". self::$token . "</span> | ";
    self::$msgError .= "Token esperado: <span style='color: red'>". $tokenEsperado. "</span><br/>";
  }

  /**
   * Verifica se o token é uma variavel
   * @return boolean
   */
  public function verificaVariavel(){
    if(is_string(self::$token) && !in_array(self::$token, TabelaSimbolos::getPalavrasReservadas())){
      return true;
    }
    return false;
  }

}
