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
     * @return array com o seguinte formato array('token' => $token, 'chAtual' => $chAtual, 'idChatual' => $idChAtual, 'relatorio' => $relatorio)
     */
    public function gerarToken($token, $chAtual, $idChAtual);
    
    public function gerarRelatorio($token);
    
}
