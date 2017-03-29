<?php
require 'autoload.php';

use App\Lexico\AnalisLexico as Lexico;


if (isset($_POST['codigo']))
{

    $inputCodigo = $_POST['codigo'];
    
    $codigo = trim($inputCodigo);
 
    $codigo = $codigo . " EOF";
    
    $arrayCodigo = str_split(trim($codigo));
    
    $lexico = new Lexico();
    $lexico->setCodigo($arrayCodigo);
    $lexico->nextToken();
    
    echo "<pre>";
    echo "Imprimir token";
    echo $lexico->getToken();
    
    echo "</pre>";
    
    $i = 0;
    //do{
    //    $i++;
        
    //    $lexico->nextToken($arrayCodigo);
    //    $lexico->imprime();
        
    //}while($lexico->getToken() !== "EOF");
    
    /*
     * @var $codigoPorLinha
     * Array com o código separado por linha 
     * Array
       (
            [0] => var
            [1] => a, b, cd;
     *  )
  
    $codigoPorLinha = explode("\n", $codigo);
    $codigoPorLinha[] = "EOF";
    
    
    $arrayTokenLinha = array();

    /*
     * @var $arrayTokenLinha
     * Array
        (
            [0] => Array
                (
                    [0] => v
                    [1] => a
                    [2] => r
                )

            [1] => Array
                (
                    [0] => a
                    [1] => ,
                    [2] =>  
                    [3] => b
                    [4] => ,
                    [5] =>  
                    [6] => c
                    [7] => d
                    [8] => ;
        ) 
    
    for ($i = 0; $i < count($codigoPorLinha); $i++)
    {
        $arrayTokenLinha[$i] = str_replace("<br />", "", $codigoPorLinha[$i]);
        $arrayTokenLinha[$i] = str_split(trim($codigoPorLinha[$i]));
    }
     * 
     */
    
    /*
    
    */
   

    /*
    
     * *
     */
    
    //$teste = new App\Lexico\AnalisLexico();
    
    //$teste->init();

    include("index.php");
}else{
    header('Location: index.php');
}

