<?php

namespace App\Lexico;

use App\Codigo\Codigo;

/**
 * Classe utilizada para verificação e criação de token da categoria sinal maior.
 * Lista de possíveis tokens:
 * 
 * 1) >
 * 2) >=
 *
 * @author Peter Clayder e Fernanda Pires
 */
class SinalMaior implements IToken
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

    /**
     * 
     * @param Codigo $codigo
     * @return void
     */
    public function __construct($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Analisa o sinal > e verifica se o próximo carácter é o sinal =
     * 
     * @param string $token
     * @param type $chAtual
     * @param int $idChAtual
     * @return array
     */
    public function gerarToken($token, $chAtual, $idChAtual)
    {

        Teste\Teste::gerarToken("maior", $chAtual, $token, $idChAtual);

        // próximo caracter 
        $dadosProxCaracter = $this->codigo->proximoCaracter($idChAtual, $chAtual);

        // recebe os dados do próximo caracter
        $chProximo = $dadosProxCaracter['chAtual'];
        $idChProximo = $dadosProxCaracter['idChAtual'];

        // se o próximo caracter for =. Então o token é >=
        if ($dadosProxCaracter['chAtual'] === "=")
        {
            $token = $chAtual . "=";
            $dadosProxCaracter = $this->codigo->proximoCaracter($idChProximo, $chProximo);
            $this->tipoToken = "maior_igual";
        } else
        {
            $this->tipoToken = "maior";
            $token = $chAtual;
        }

        $relatorio = $this->gerarRelatorio($token);

        return array('token' => $token, 'chAtual' => $dadosProxCaracter['chAtual'], 'idChatual' => $dadosProxCaracter['idChAtual'], 'relatorio' => $relatorio);
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
