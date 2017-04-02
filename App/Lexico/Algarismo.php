<?php

namespace App\Lexico;

use App\Codigo\Codigo;

/**
 * Classe utilizada para verificação e criação de token da categoria numero .
 *
 * @author Peter Clayder e Fernanda Pires
 */
class Algarismo implements IToken
{

    /**
     * Recebe a chave que o token pertence. 
     * Essa chave é referente ao array $simbolos da classe TabelaSimbolos 
     * @var string 
     */
    private $tipoToken;

    /**
     *
     * @var Codigo
     */
    private $codigo;

    public function __construct(Codigo $codigo)
    {
        $this->codigo = $codigo;
        $this->tipoToken = "numero";
    }

    public function gerarToken($token, $chAtual, $idChAtual)
    {

        do
        {    
            //Teste\Teste::gerarToken("Algarismo", $chAtual, $token, $idChAtual);

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

    /**
     * Implementação da interface IToken 
     * @param type $token
     * @return array Description
     */
    public function gerarRelatorio($token)
    {
        return Relatorio::get($token, $this->tipoToken);
    }

}
