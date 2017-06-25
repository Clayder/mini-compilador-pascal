<?php
/**
 * Created by PhpStorm.
 * User: clayder
 * Date: 24/06/17
 * Time: 12:45
 */

namespace App\GeradorCodigo;

class GeradorCodigo
{
    private static $arrayCodigo = array();
    private static $tabulacao = "";

    /**
     * @param $token
     * @param bool $pulaLinha
     */
    public static function addToken($token, $pulaLinha = false){
        self::$arrayCodigo[0] = "<?php";
        if($pulaLinha){
            self::$arrayCodigo[] = $token;
        }else{
            /*
             * Retiro a ultima posição array e armazeno na variavel $ultimaLinha
             * Obs: A última posição eh deletada
             */
            $ultimaLinha = array_pop(self::$arrayCodigo);
            $ultimaLinha = $ultimaLinha . " ".$token;
            self::$arrayCodigo[] = $ultimaLinha;
        }

        /*
         * $linha = "";
        foreach ($token as $item){
           $linha = $linha . " " . $item;
       }
        self::$arrayCodigo[] = $linha;
         */
    }

    /**
     * @return array
     */
    public static function getArrayCodigo()
    {
        return self::$arrayCodigo;
    }

    public static function imprimir(){
        $escrita = "";
        foreach (self::$arrayCodigo as $codigo){
            if($codigo === "{"){
                $escrita .= " \n ";
                $escrita .= self::$tabulacao;
                $escrita .= $codigo;
                self::addTabulacao();
            }elseif($codigo ==="}"){
                $escrita .= " \n ";
                self::removerTabulacao();
                $escrita .= self::$tabulacao;
                $escrita .= $codigo;
                self::removerTabulacao();
            }else{
                $escrita .= " \n ";
                $escrita .= self::$tabulacao;
                $escrita .= $codigo;
            }
        }
        return $escrita;
    }

    public static function escrever(){
        $arquivo = 'codigoGerado/codigo.php';
        $file = fopen($arquivo, 'w+');
        fwrite($file, self::imprimir());
        fclose($file);
    }

    public static function apagarDadosArquivo(){
        $arquivo = 'codigoGerado/codigo.php';
        $file = fopen($arquivo, 'w+');
        fwrite($file, " ");
        fclose($file);
    }

    public static function addTabulacao(){
        self::$tabulacao = self::$tabulacao . " \t ";
    }

    public static function removerTabulacao(){
        $array = explode(" ", self::$tabulacao);
        array_pop($array);
        self::$tabulacao = implode(" ", $array);
    }


}