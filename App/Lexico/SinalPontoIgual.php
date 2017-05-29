<?php

namespace App\Lexico;

use App\Codigo\Codigo;

/**
 * Classe utilizada para verificação e criação de token da categoria ponto_igual := e :
 * Token: := ou :
 * 
 * @author Fernanda Pires
 * @author Peter Clayder
 */
class SinalPontoIgual implements IToken
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
     * @param type $codigo
     */
    public function __construct($codigo)
    {
        $this->codigo = $codigo;
        $this->tipoToken = "atribuicao";
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

    /**
     * Analisa o sinal atual, todos os sinais possuem apenas 1 caracter
     * 
     * @param string $token
     * @param type $chAtual
     * @param int $idChAtual
     * @return array Retorna um array com o seguinte formato array('token' => $token, 'chAtual' => $chAtual, 'idChatual' => $idChAtual, 'relatorio' => $relatorio) se o token for valido, caso contrário retorna um array vazio.
     */
    public function gerarToken($token, $chAtual, $idChAtual)
    {

        //Teste\Teste::gerarToken("Atribuição", $chAtual, $token, $idChAtual);
        // próximo caracter 
        $dadosProxCaracter = $this->codigo->proximoCaracter($idChAtual, $chAtual);

        // atualiza os dados do caracter atual 
        $chProximo = $dadosProxCaracter['chAtual'];
        $idChProximo = $dadosProxCaracter['idChAtual'];

        /*
         * Verifica se o próximo carácter é o =
         * Se for verdadeiro o token := será formado 
         * Caso contrário será retornado :
         */
        if ($dadosProxCaracter['chAtual'] === "=")
        {
            $token = $chAtual . "=";
        } else
        {
            $token = $chAtual;
        }
        $dadosProxCaracter = $this->codigo->proximoCaracter($idChProximo, $chProximo);
        $relatorio = $this->gerarRelatorio($token);
        return array('token' => $token, 'chAtual' => $dadosProxCaracter['chAtual'], 'idChatual' => $dadosProxCaracter['idChAtual'], 'relatorio' => $relatorio);
    }

}
