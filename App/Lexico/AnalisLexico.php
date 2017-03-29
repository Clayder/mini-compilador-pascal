<?php
namespace App\Lexico;

/**
 * Description of AnalisLexico
 */
class AnalisLexico
{
    public function __construct()
    {
        
    }
    
    public function init(){
        echo "<pre>";
        print_r(TabelaSimbolos::getSimbolos());
        echo "</pre>";
    }
}
