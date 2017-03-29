<?php

namespace App\Lexico;
/**
 * Description of TabelaSimbolos
 *
 */
class TabelaSimbolos
{

    private static $simbolos;

    public static function getSimbolos()
    {
        self::$simbolos = array(
            array(
                "id" => 1,
                "descricao" => "ponto",
                "reservado" => false,
            ),
            array(
                "id" => 2,
                "descricao" => "var",
                "reservado" => true,
            ),
             array(
                "id" => 3,
                "descricao" => "abre_parenteses",
                "reservado" => false,
            ),
            array(
                "id" => 4,
                "descricao" => "fecha_parenteses",
                "reservado" => false,
            ),
            array(
                "id" => 5,
                "descricao" => "virgula",
                "reservado" => false,
            ),
             array(
                "id" => 6,
                "descricao" => "ponto_virgula",
                "reservado" => false,
            ),
             array(
                "id" => 7,
                "descricao" => "begin",
                "reservado" => true,
            ),
             array(
                "id" => 8,
                "descricao" => "end",
                "reservado" => true,
            ),
              array(
                "id" => 9,
                "descricao" => "print",
                "reservado" => true,
            ),
            array(
                "id" => 10,
                "descricao" => "read",
                "reservado" => true,
            ),
             array(
                "id" => 11,
                "descricao" => "if",
                "reservado" => true,
            ),
            array(
                "id" => 12,
                "descricao" => "then",
                "reservado" => true,
            ),
             array(
                "id" => 13,
                "descricao" => "for",
                "reservado" => true,
            ),
             array(
                "id" => 14,
                "descricao" => "to",
                "reservado" => true,
            ),
              array(
                "id" => 15,
                "descricao" => "do",
                "reservado" => true,
            ),
              array(
                "id" => 16,
                "descricao" => "else",
                "reservado" => true,
            ),
             array(
                "id" => 17,
                "descricao" => "igual",
                "reservado" => false,
            ),
             array(
                "id" => 18,
                "descricao" => "atribuicao",
                "reservado" => false,
            ),
             array(
                "id" => 19,
                "descricao" => "mais",
                "reservado" => false,
            ),
              array(
                "id" => 20,
                "descricao" => "menos",
                "reservado" => false,
            ),
              array(
                "id" => 21,
                "descricao" => "vezes",
                "reservado" => false,
            ),
             array(
                "id" => 22,
                "descricao" => "barra",
                "reservado" => false,
            ),
             array(
                "id" => 23,
                "descricao" => "diferente",
                "reservado" => false,
            ),
             array(
                "id" => 24,
                "descricao" => "maior",
                "reservado" => false,
            ),
             array(
                "id" => 25,
                "descricao" => "menor",
                "reservado" => false,
            ),
             array(
                "id" => 26,
                "descricao" => "maior_igual",
                "reservado" => false,
            ),
             array(
                "id" => 27,
                "descricao" => "menor_igual",
                "reservado" => false,
            ),
            array(
                "id" => 28,
                "descricao" => "variavel",
                "reservado" => false,
            ),
            array(
                "id" => 29,
                "descricao" => "numero",
                "reservado" => false,
            ),
            array(
                "id" => 30,
                "descricao" => "fim_codigo",
                "reservado" => false,
            ),
        );
        
        return self::$simbolos;
    }

}
