<?php

require 'autoload.php';

use App\Lexico\AnalisLexico as Lexico;
use App\Lexico\TabelaSimbolos;
use App\Codigo\Codigo;
use App\Sintatico\AnalSintatico as sintatico;
use App\Semantico\Semantico;

$erroLexico = "";
$erroSintatico = "";

if (isset($_POST['codigo']))
{

    $inputCodigo = $_POST['codigo'];

    $codigo = trim($inputCodigo);
    $codigo = $codigo . " EOF";

    /**
     * Separo a string em um array de caracteres
     * exemplo de código:
     * var a b c d
     * if
     * Resultado:
     * Array
      (
      [0] => v
      [1] => a
      [2] => r
      [3] =>
      [4] => a
      [5] =>
      [6] => b
      [7] =>
      [8] => c
      [9] =>
      [10] => d
      [11] =>
      [12] =>

      [13] => i
      [14] => f
      [15] =>
      [16] => E
      [17] => O
      [18] => F
      )
     */
    $arrayCodigo = str_split($codigo);

    //App\Lexico\Teste\Teste::pre($arrayCodigo);

    // carrega a tabela de simbolos
    TabelaSimbolos::setSimbolos();

    // carrega o array com palavras reservadas
    TabelaSimbolos::setPalavrasReservadas();
    $lexico = new Lexico(new Codigo($arrayCodigo));
    do
    {
        // reinicializa  o atributo token
        $lexico->setToken("");
        $lexico->nextToken();

        //$lexico->imprime();
    } while ($lexico->getToken() !== "EOF" && !$lexico->getExisteCaracterInvalido());

    if(!$lexico->getExisteCaracterInvalido()){
      new Sintatico($lexico->getArrayTokens(), $lexico->getArrayTokensLinha());
      $erroSintatico = Sintatico::getMsgError();
      // Se não tiver erro sintático, inicia o semantico.
      if($erroSintatico == ""){
          $semantico = new Semantico($lexico->getArrayTokens(), $lexico->getArrayTokensLinha());
          $erroSemantico = Semantico::getMsgError();
          \App\Lexico\Teste\Teste::pre($semantico->getTabelaSimbolo());
      }
    }
}
include("index.php");
