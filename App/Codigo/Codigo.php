<?php

namespace App\Codigo;

/**
 * Description of Codigo
 *
 */
class Codigo
{
    /**
     * Tamanho do código
     * @var int $tamCodigo
     */
    private $tamCodigo;
    
    /**
     * Array de caracteres do código
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
     * @var array $arrayCodigo
     */
    private $arrayCodigo;

     /**
     * 
     * @param array $arrayCodigo
     */
    function __construct($arrayCodigo)
    {
        $this->arrayCodigo = $arrayCodigo;
        $this->tamCodigo = count($arrayCodigo);
    }

    /**
     * 
     * @param array $arrayCodigo
    */
    public function setCodigo($arrayCodigo)
    {
        $this->arrayCodigo = $arrayCodigo;
    }
    
    /**
     * 
     * @return array
     */
    public function getArrayCodigo()
    {
        return $this->arrayCodigo;
    }
    
    /**
     * Retorna um carácter específico pela sua posição.
     * @param int $idCaracter
     * @return type
     */
    public function getCaracterCodigo($idCaracter){
        return $this->arrayCodigo[$idCaracter];
    }

    /**
     * Elimina os caracteres inválidos, indo para o proximo caracter até encontrar um caracter valido
     * @param int $idChAtual
     * @param type $chAtual
     * @return array
     */
    public function eliminaCaracterInvalidos($idChAtual, $chAtual)
    {
        while ($chAtual == " " || $chAtual == "\n" || $chAtual == "\r")
        {
            
            echo "**************";
            echo "<br />";
            echo "Entrei eliminarCaracter chAtual: $chAtual";
            echo "<br />";
            echo "Entrei idchAtual: $idChAtual";
            echo "<br />";
            echo "**************";
            echo "<br />";
             
             
            $dadosProximo = $this->proximoCaracter($idChAtual, $chAtual);
            $chAtual = $dadosProximo['chAtual'];
            $idChAtual = $dadosProximo['idChAtual'];
        }
        
        return array('idChAtual' => $idChAtual, 'chAtual' => $chAtual);
    }
    
    
    /**
     * Vai para o próximo carácter, sempre verificando se já chegou no final do código.
     * @param int $idChAtual
     * @param type $chAtual
     * @return array
     */
    public  function proximoCaracter($idChAtual, $chAtual)
    {        
        // verifico se existe o próximo caracter
        if ($idChAtual < $this->tamCodigo - 1)
        {
            $idChAtual = $idChAtual + 1;
            $chAtual = $this->arrayCodigo[$idChAtual];
        }
        
        return array('idChAtual' => $idChAtual, 'chAtual' => $chAtual);
    }
}
