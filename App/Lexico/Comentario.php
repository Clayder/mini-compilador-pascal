<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lexico;

use App\Codigo\Codigo;

/**
 * Description of Comentario
 *
 * @author clayder
 */
class Comentario implements IToken
{

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
        return Relatorio::get(null, null, true);
    }

    public function gerarToken($token, $chAtual, $idChAtual)
    {
        $qtdCaractersCodigo = count($this->codigo->getArrayCodigo());
        $idUltimoCaracter = $qtdCaractersCodigo - 4;
        $qtdCaracterRestante = $idUltimoCaracter - $idChAtual;
        $stop = false;

        do
        {
            Teste\Teste::gerarToken("Letra", $chAtual, $token, $idChAtual);
            // forma o token
            $token = $token . $chAtual;

            // próximo caracter 
            $dadosProxCaracter = $this->codigo->proximoCaracter($idChAtual, $chAtual);

            // atualiza os dados do caracter atual 
            $chAtual = $dadosProxCaracter['chAtual'];
            $idChAtual = $dadosProxCaracter['idChAtual'];

            $qtdCaracterRestante--;
            
            // sair do loop quando acabar o arquivo ou quando terminar o comentário 
            if($qtdCaracterRestante == 0 || $chAtual == "}"){
                $stop = true;
            }
        } while (!$stop);


        // próximo caracter 
        $dadosProxCaracter = $this->codigo->proximoCaracter($idChAtual, $chAtual);

        // atualiza os dados do caracter atual 
        $chAtual = $dadosProxCaracter['chAtual'];
        $idChAtual = $dadosProxCaracter['idChAtual'];

        $relatorio = $this->gerarRelatorio($token);

        return array('token' => $token, 'chAtual' => $chAtual, 'idChatual' => $idChAtual, 'relatorio' => $relatorio);
    }

}
