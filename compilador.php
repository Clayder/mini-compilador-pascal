<?php

require 'autoload.php';

use App\Lexico\AnalisLexico as Lexico;
use App\Lexico\TabelaSimbolos;
use App\Codigo\Codigo;

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
    
    echo "<pre>";
    print_r($arrayCodigo);
    echo "</pre>";
    
    // carrega a tabela de simbolos
    TabelaSimbolos::setSimbolos();

    // carrega o array com palavras reservadas
    TabelaSimbolos::setPalavrasReservadas();
    
    $lexico = new Lexico(new Codigo($arrayCodigo));

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
