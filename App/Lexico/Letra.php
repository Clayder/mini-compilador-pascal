<?php

namespace App\Lexico;

use App\Codigo\Codigo;

/**
 * Description of Letra
 * @author Peter Clayder e Fernanda Pires
 */
class Letra implements IToken
{

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

    /**
     * Verifica se o caracter é uma letra 
     * @param type $letra
     * @return boolean
     */
    public static function ehLetra($letra)
    {
        if (ord($letra) >= ord("a") && ord($letra) <= ord("z") || ord($letra) >= ord("A") && ord($letra) <= ord("Z"))
        {
            return true;
        } else
        {
            return false;
        }
    }

    /**
     * Analisa a letra atual e percorre os próximos carácteres do código, 
     * enquanto for letra. 
     * O token é formado, concatenando os caracteres encontrados.
     * @param string $token
     * @param type $chAtual
     * @param int $idChAtual
     * @return array
     */
    public function gerarToken($token, $chAtual, $idChAtual)
    {
        do
        {
            echo "############################";
            echo "<br />";
            echo "Gerar token Letra";
            echo "<br />";
            echo "ch atual: " . $chAtual;
            echo "<br />";
            echo "token: " . $token;
            echo "<br />";
            echo "id: " . $idChAtual;
            echo "<br />";
            echo "############################";

            // forma o token
            $token = $token . $chAtual;

            // próximo caracter 
            $dadosProxCaracter = $this->codigo->proximoCaracter($idChAtual, $chAtual);

            // atualiza os dados do caracter atual 
            $chAtual = $dadosProxCaracter['chAtual'];
            $idChAtual = $dadosProxCaracter['idChAtual'];
        } while (self::ehLetra($chAtual) && $token !== "EOF");

        $relatorio = $this->gerarRelatorio($token);

        return array('token' => $token, 'chAtual' => $chAtual, 'idChatual' => $idChAtual, 'relatorio' => $relatorio);
    }

    public function gerarRelatorio($token)
    {
        $palavrasReservadas = TabelaSimbolos::getPalavrasReservadas();
        
        if (!in_array($token, $palavrasReservadas))
        {
            $chave = "variavel";
        } else
        {
            $chave = $token;
        }
        
        return Relatorio::get($token, $chave);
    }

}
