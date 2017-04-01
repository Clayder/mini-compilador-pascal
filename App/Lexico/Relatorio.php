<?php

namespace App\Lexico;

/**
 * Description of Relatorio
 *
 * @author Peter Clayder e Fernanda Pires
 */
class Relatorio
{
    public static function get($token, $chave){
        $tabelaToken = array(
            "id" => TabelaSimbolos::getSimbolos()[$chave]['id'],
            "descricao" => TabelaSimbolos::getSimbolos()[$chave]['descricao'],
            "lexema" => $token,
            "reservado" => TabelaSimbolos::getSimbolos()[$chave]['reservado'],
        );

        return $tabelaToken;
    }
}
