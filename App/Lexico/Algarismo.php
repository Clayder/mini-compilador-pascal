<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lexico;

use App\Codigo\Codigo;

/**
 * Description of Algarismo
 *
 * @author Peter Clayder e Fernanda Pires
 */
class Algarismo implements IToken
{

    /**
     *
     * @var Codigo
     */
    private $codigo;
    
    /**
     *
     * @var array
     */
    private $relatorioTokens;

    /**
     *
     * @var array
     */
    private $tabelaToken;
    

    public function __construct(Codigo $codigo)
    {
        $this->codigo = $codigo;
    }

    public function gerarToken($token, $chAtual, $idChAtual)
    {
        
        do
        {
            echo "############################";
            echo "<br />";
            echo "Gerar token Algorismo";
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
        } while (is_numeric($chAtual) && $token !== "EOF");
        
        
        $relatorio = $this->gerarRelatorio($token, $chAtual, $idChAtual);
        
        return array('token' => $token, 'chAtual' => $chAtual, 'idChatual' => $idChAtual, 'relatorio' => $relatorio);
    }

    public function gerarRelatorio($token)
    { 
        return Relatorio::get($token, "numero");
    }


}
