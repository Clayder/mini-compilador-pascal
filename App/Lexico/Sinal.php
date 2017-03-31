<?php

namespace App\Lexico;

use App\Codigo\Codigo;

/**
 * Description of Sinal
 *
 * @author Peter Clayder e Fernanda Pires
 */
class Sinal implements IToken
{

    /**
     * Armazena os sinais que a linguagem possui 
     * @var array
     */
    private static $arraySinais;

    /**
     *
     * @var Codigo
     */
    private $codigo;

    /**
     * 
     * @param Codigo $codigo
     */
    public function __construct(Codigo $codigo)
    {
        $this->codigo = $codigo;
    }

    //put your code here
    public function gerarRelatorio($token)
    {
        $palavrasReservadas = TabelaSimbolos::getPalavrasReservadas();

        $dadosSinal = $this->getSinal($token);
        
        $tabelaToken = array(
            "id" => TabelaSimbolos::getSimbolos()[$dadosSinal[0]]['id'],
            "descricao" => TabelaSimbolos::getSimbolos()[$dadosSinal[0]]['descricao'],
            "lexema" => $token,
            "reservado" => TabelaSimbolos::getSimbolos()[$dadosSinal[0]]['reservado'],
        );

        return $tabelaToken;
    }

    /**
     * Analisa o sinal atual, todos os sinais possuem apenas 1 caracter
     * 
     * @param string $token
     * @param type $chAtual
     * @param int $idChAtual
     * @return array
     */
    public function gerarToken($token, $chAtual, $idChAtual)
    {

        echo "############################";
        echo "<br />";
        echo "Gerar token Sinal";
        echo "<br />";
        echo "ch atual: " . $chAtual;
        echo "<br />";
        echo "token: " . $token;
        echo "<br />";
        echo "id: " . $idChAtual;
        echo "<br />";
        echo "############################";

        // forma o token
        $token = $chAtual;

        // próximo caracter 
        $dadosProxCaracter = $this->codigo->proximoCaracter($idChAtual, $chAtual);

        // atualiza os dados do caracter atual 
        $chAtual = $dadosProxCaracter['chAtual'];
        $idChAtual = $dadosProxCaracter['idChAtual'];

        

        $relatorio = $this->gerarRelatorio($token);

        return array('token' => $token, 'chAtual' => $chAtual, 'idChatual' => $idChAtual, 'relatorio' => $relatorio);
    }

    /**
     * 
     * @param type $caracter
     * @return boolean
     */
    public static function ehSinal($sinal)
    {
        self::setArraySinais();
        
        $existeSinal = self::getSinal($sinal);
        
        if(count($existeSinal) > 0){
            return true;
        }
        return false;
    }
    
    
    /*
     * Retorna os dados de um sinal específico
     * @return void
     */
    public static function getSinal($sinal){
        foreach (self::$arraySinais as $value)
        {
            if($sinal === $value[1]){
                return $value;
            }
        }
        return array();
    }

    /**
     * 
     * @return array
     */
    public function getArraySinais()
    {
        return self::$arraySinais;
    }

    /**
     * Cria o array de sinais, onde cada posição do array é um array.
     * Na posição [0]=> chave do sinal, [1]=> sinal.
     * A chave do sinal é a mesma chave utilizado no array de simbolos. 
     * @return void
     */
    public static function setArraySinais()
    {
        self::$arraySinais = array(
            0 => array("ponto", "."),
            1 => array("virgula", ","),
            2 => array("ponto_virgula" , ";"),
            3 => array("mais", "+"),
            4 => array("menos", "-"),
            5 => array("vezes", "*"),
            6 => array("barra", "/"),
            7 => array("abre_parenteses" ,"("),
            8 => array("fecha_parenteses" , ")"),
            9 => array("igual", "="),
        );
    }

}
