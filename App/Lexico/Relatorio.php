<?php

namespace App\Lexico;

/**
 * 
 *
 * @author Fernanda Pires
 * @author Peter Clayder
 */
class Relatorio
{

    public static function get($token, $chave)
    {
        $tabelaToken = array(
            "id" => TabelaSimbolos::getSimbolos()[$chave]['id'],
            "descricao" => TabelaSimbolos::getSimbolos()[$chave]['descricao'],
            "lexema" => $token,
            "reservado" => TabelaSimbolos::getSimbolos()[$chave]['reservado'],
        );

        return $tabelaToken;
    }

}
