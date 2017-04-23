<?php

namespace App\Codigo;

/**
 * Classe utilizada para analisar o código fornecido pelo textarea. 
 * 
 * @author Fernanda Pires
 * @author Peter Clayder
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
     * @example description exemplo de código: 
     * @example var a b c d
     * @example if
     * @example Resultado:                                  
     * @example Array
     * @example (
     * @example [0] => v
     * @example [1] => a
     * @example [2] => r
     * @example [3] =>
     * @example [4] => a
     * @example [5] =>
     * @example [6] => b
     * @example [7] =>
     * @example [8] => c
     * @example [9] =>
     * @example [10] => d
     * @example [11] =>
     * @example [12] =>
     * @example [13] => i
     * @example [14] => f
     * @example [15] =>
     * @example [16] => E
     * @example [17] => O
     * @example [18] => F
     * @example )
     * @var array $arrayCodigo
     */
    private $arrayCodigo;
    
    /**
     * Conta a quantidade de linha
     * @var int
     */
    public static $linha = 1;

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
    public function getCaracterCodigo($idCaracter)
    {
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

            //Teste\Teste::gerarToken("Entrei eliminarCaracter", $chAtual, "", $idChAtual);

            if($chAtual == "\n")
                self::$linha++;
            
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
    public function proximoCaracter($idChAtual, $chAtual)
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
