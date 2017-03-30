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
        $lexico->setFinalizar(true);
        $lexico->setToken("");
        $lexico->nextToken();

        /*
        
        if (!$lexico->getFinalizar())
        {
            echo "<br />";
            echo "saí";
            echo "<br />";
        }
        
         * 
         */
        //echo "<br />";
        //echo "Tokens: ".$lexico->getToken();
        //echo "<br />";
        //$lexico->imprime();

    } while ($lexico->getToken() !== "EOF");

}
include("index.php");
