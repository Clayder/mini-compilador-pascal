<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lexico\Teste;

/**
 * Description of Teste
 *
 * @author clayder
 */
class Teste
{

    public static function nextToken($chAtual, $idAtual)
    {
        echo "**************";
        echo "<br />";
        echo "Entrei nextToken chAtual: $chAtual";
        echo "Entrei idchAtual: $idAtual";
        echo "<br />";
        echo "**************";
        echo "<br />";
    }
    
    public static function gerarToken($categoria, $chAtual, $token, $idChAtual){
         echo "############################";
        echo "<br />";
        echo "Gerar token $categoria";
        echo "<br />";
        echo "ch atual: " . $chAtual;
        echo "<br />";
        echo "token: " . $token;
        echo "<br />";
        echo "id: " . $idChAtual;
        echo "<br />";
        echo "############################";
    }
    
    public static function pre(array $array){
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

}
