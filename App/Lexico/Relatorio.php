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

    public static function get($token, $chave, $ignorar = false)
    {
        $tabelaToken = array(
            "id" => ($ignorar)? null : TabelaSimbolos::getSimbolos()[$chave]['id'],
            "descricao" => ($ignorar)? null : TabelaSimbolos::getSimbolos()[$chave]['descricao'],
            "lexema" => ($ignorar)? null : $token,
            "reservado" => ($ignorar)? null : TabelaSimbolos::getSimbolos()[$chave]['reservado'],
            "linha" => ($ignorar)? null : \App\Codigo\Codigo::$linha
        );

        return $tabelaToken;
    }

}
