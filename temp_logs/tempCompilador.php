<?php

require 'autoload.php';

use App\Lexico\AnalisLexico as Lexico;
use App\Lexico\TabelaSimbolos;

if (isset($_POST['codigo']))
{

    $inputCodigo = $_POST['codigo'];

    $codigo = trim($inputCodigo);
    $codigo = $codigo . " EOF";
    
    $arrayCodigo = str_split($codigo);
    
    // carrega a tabela de simbolos
    TabelaSimbolos::setSimbolos();

    // carrega o array com palavras reservadas
    TabelaSimbolos::setPalavrasReservadas();


    $lexico = new Lexico();

    $lexico->setCodigo($arrayCodigo);

    do
    {
        $lexico->setToken("");
        $lexico->nextToken();
    
        echo "<br />";
        echo "Tokens145: ".$lexico->getToken();
        echo "<br />";
        //$lexico->imprime();

    } while ($lexico->getToken() !== "EOF");

}
include("index.php");
