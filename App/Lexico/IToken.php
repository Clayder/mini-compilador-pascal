<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lexico;
use App\Codigo\Codigo;

/**
 *
 * @author Peter Clayder e Fernanda Pires
 */
interface IToken
{
    /**
     * 
     * @param type $token
     * @param type $chAtual
     * @param int $idChAtual
     * @return array Retorna um array com o seguinte formato array('token' => $token, 'chAtual' => $chAtual, 'idChatual' => $idChAtual, 'relatorio' => $relatorio)
     */
    public function gerarToken($token, $chAtual, $idChAtual);
    
    /**
     * Cria um array com os dados  (id, descrição, lexema e reservado) do token, para ser utilizado como relatório.
     * Esses dados são obtidos no array $simbolos da classe TabelaSimbolos.
     * @param type $token
     * @return array Retorna um array com o seguinte formato array("id" => ,"descricao" => ,"lexema" => ,"reservado" => )
     */
    public function gerarRelatorio($token);
    
    
}
