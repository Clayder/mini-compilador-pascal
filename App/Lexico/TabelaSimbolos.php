<?php

namespace App\Lexico;

/**
 * Description of TabelaSimbolos
 *
 */
class TabelaSimbolos
{

    /**
     *
     * @var array
     */
    private static $simbolos;

    /**
     *
     * @var array
     */
    private static $palavrasReservadas = array();

    /**
     * 
     * @return array
     */
    public static function getSimbolos()
    {
        return self::$simbolos;
    }

    /**
     * @return void
     */
    static function setSimbolos()
    {
        self::$simbolos = array(
            'ponto' => array(
                "id" => 1,
                "descricao" => "ponto",
                "reservado" => false,
            ),
            'var' => array(
                "id" => 2,
                "descricao" => "var",
                "reservado" => true,
            ),
            'abre_parenteses' => array(
                "id" => 3,
                "descricao" => "abre_parenteses",
                "reservado" => false,
            ),
            'fecha_parenteses' => array(
                "id" => 4,
                "descricao" => "fecha_parenteses",
                "reservado" => false,
            ),
            'virgula' => array(
                "id" => 5,
                "descricao" => "virgula",
                "reservado" => false,
            ),
            'ponto_virgula' => array(
                "id" => 6,
                "descricao" => "ponto_virgula",
                "reservado" => false,
            ),
            'begin' => array(
                "id" => 7,
                "descricao" => "begin",
                "reservado" => true,
            ),
            'end' => array(
                "id" => 8,
                "descricao" => "end",
                "reservado" => true,
            ),
            'print' => array(
                "id" => 9,
                "descricao" => "print",
                "reservado" => true,
            ),
            'read' => array(
                "id" => 10,
                "descricao" => "read",
                "reservado" => true,
            ),
            'if' => array(
                "id" => 11,
                "descricao" => "if",
                "reservado" => true,
            ),
            'then' => array(
                "id" => 12,
                "descricao" => "then",
                "reservado" => true,
            ),
            'for' => array(
                "id" => 13,
                "descricao" => "for",
                "reservado" => true,
            ),
            'to' => array(
                "id" => 14,
                "descricao" => "to",
                "reservado" => true,
            ),
            'do' => array(
                "id" => 15,
                "descricao" => "do",
                "reservado" => true,
            ),
            'else' => array(
                "id" => 16,
                "descricao" => "else",
                "reservado" => true,
            ),
            'igual' => array(
                "id" => 17,
                "descricao" => "igual",
                "reservado" => false,
            ),
            'atribuicao' => array(
                "id" => 18,
                "descricao" => "atribuicao",
                "reservado" => false,
            ),
            'mais' => array(
                "id" => 19,
                "descricao" => "mais",
                "reservado" => false,
            ),
            'menos' => array(
                "id" => 20,
                "descricao" => "menos",
                "reservado" => false,
            ),
            'vezes' => array(
                "id" => 21,
                "descricao" => "vezes",
                "reservado" => false,
            ),
            'barra' => array(
                "id" => 22,
                "descricao" => "barra",
                "reservado" => false,
            ),
            'diferente' => array(
                "id" => 23,
                "descricao" => "diferente",
                "reservado" => false,
            ),
            'maior' => array(
                "id" => 24,
                "descricao" => "maior",
                "reservado" => false,
            ),
            'menor' => array(
                "id" => 25,
                "descricao" => "menor",
                "reservado" => false,
            ),
            'maior_igual' => array(
                "id" => 26,
                "descricao" => "maior_igual",
                "reservado" => false,
            ),
            'menor_igual' => array(
                "id" => 27,
                "descricao" => "menor_igual",
                "reservado" => false,
            ),
            'variavel' => array(
                "id" => 28,
                "descricao" => "variavel",
                "reservado" => false,
            ),
            'numero' => array(
                "id" => 29,
                "descricao" => "numero",
                "reservado" => false,
            ),
            'EOF' => array(
                "id" => 30,
                "descricao" => "EOF",
                "reservado" => true,
            ),
        );
    }

    /**
     * @return void
     */
    public static function setPalavrasReservadas()
    {
        foreach (self::$simbolos as $value)
        {
            if ($value['reservado'])
            {
                self::$palavrasReservadas[] = $value['descricao'];
            }
        }
    }

    /**
     * 
     * @return array
     */
    public static function getPalavrasReservadas()
    {
        return self::$palavrasReservadas;
    }

}
