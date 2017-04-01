<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lexico;

use App\Codigo\Codigo;

/**
 * Description of SinalMenor
 *
 * @author Peter Clayder e Fernanda Pires
 */
class SinalPontoIgual implements IToken
{

    /**
     *
     * @var type 
     */
    private $tipoToken;
    
    /**
     *
     * @var Codigo
    */
    private $codigo;
    
    public function __construct($codigo)
    {
        $this->codigo = $codigo;
    }

    //put your code here
    public function gerarRelatorio($token)
    {
        return Relatorio::get($token, $this->tipoToken);
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
        echo "Gerar token Atribuição";
        echo "<br />";
        echo "ch atual: " . $chAtual;
        echo "<br />";
        echo "token: " . $token;
        echo "<br />";
        echo "id: " . $idChAtual;
        echo "<br />";
        echo "############################";


        // próximo caracter 
        $dadosProxCaracter = $this->codigo->proximoCaracter($idChAtual, $chAtual);

        // atualiza os dados do caracter atual 
        $chProximo = $dadosProxCaracter['chAtual'];
        $idChProximo = $dadosProxCaracter['idChAtual'];

        if ($dadosProxCaracter['chAtual'] === "=")
        {
            
            echo "<br />";
            echo " eRRRRRRRRRRRRRRRRRRROOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO : " . $dadosProxCaracter['chAtual'];
            echo "<br />";
            
            $token = $chAtual . "=";
            $dadosProxCaracter = $this->codigo->proximoCaracter($idChProximo, $chProximo);
            $this->tipoToken = "atribuicao";

            $relatorio = $this->gerarRelatorio($token);

            return array('token' => $token, 'chAtual' => $dadosProxCaracter['chAtual'], 'idChatual' => $dadosProxCaracter['idChAtual'], 'relatorio' => $relatorio);
        } else
        {
            return array();
        }
    }


}
