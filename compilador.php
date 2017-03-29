<?php
require 'autoload.php';

//use app\lexico\AnalisLexico as AnalisadorLexico;

if (isset($_POST['codigo']))
{

    $inputCodigo = $_POST['codigo'];
    
    $codigo = trim($inputCodigo);
 
    $codigoPorLinha = explode("\n", $codigo);
    $codigoPorLinha[] = "EOF";
    

    echo "Explode";
    echo "<pre>";
    print_r($codigoPorLinha);
    echo "</pre>";
    echo "*************";

    /*
    for ($i = 0; $i < count($temp); $i++)
    {
       $temp[$i] = str_replace("<br />", "", $temp[$i]);
        echo "<pre>";
        print_r(str_split(trim($temp[$i])));
        echo "</pre>";
        echo "*************";
    }
     * *
     */
    
    $teste = new App\Lexico\AnalisLexico();
    
    $teste->init();

    include("index.php");
}else{
    
}

