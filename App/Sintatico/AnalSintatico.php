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
  public function vars()
  {
    if( self::$token === "var")
    {
      echo 111111111111;
      $this->verificarToken("var");
      $this->listaIdent();
      $this->verificarToken(",");
    }
  }

  public function listaIdent(){
    echo 2222222;
    if($this->ident()){
      $this->listaIdent2();
    }else{
       $this->error("listaIdent");
    }
  }

  public function listaIdent2(){
      if(self::$token === ","){
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

  }

  public function verificarToken($tokenCorreto)
  {
    if(self::$token === $tokenCorreto){
      $this->nextToken();
    }else{
      $this->error($tokenCorreto);
    //  exit(0);

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
