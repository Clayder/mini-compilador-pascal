<?php

namespace App\Lexico;

use App\Codigo\Codigo;
use function PHPSTORM_META\type;

/**
 * Classe utilizada para verificação e criação de token da categoria número inteiro e real .
 *
 * @author Fernanda Pires
 * @author Peter Clayder
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
     * @var Recebe o caracter atual
     */
    private $chAtual;
    /**
     * @var recebe o id atual
     */
    private $idChAtual;
    /**
     *
     * @var Codigo
     */
    private $codigo;

    /**
     * @var recebe o token que está sendo formado
     */
    private $token;

    /**
     *
     * @param Codigo $codigo
     */
    public function __construct(Codigo $codigo)
    {
        $this->codigo = $codigo;
        $this->tipoToken = "num_int";
    }

    /**
     * Analisa a letra atual e percorre os próximos carácteres do código,
     * enquanto for algarismo.
     * O token é formado, concatenando os caracteres encontrados.
     * @param string $token
     * @param type $chAtual
     * @param int $idChAtual
     * @return array
     */
    public function gerarToken($token, $chAtual, $idChAtual)
    {
        $this->chAtual = $chAtual;
        $this->idChAtual = $idChAtual;
        do {
            //Teste\Teste::gerarToken("Algarismo", $chAtual, $token, $idChAtual);
            // forma o token
            $token = $token . $this->chAtual;
            // próximo caracter 
            $this->proximo();
            /*
             * verifico se o próximo caracter é um .
             * Se for um . temos que verificar se o próximo caracter (depois do . ) é um digito
             */
            $ehReal = $this->verificaNumeroReal($token);
            if ($ehReal) {
                $token = $token . $ehReal;
            }
            // atualiza os dados do caracter atual
            $chAtual = $this->chAtual;
            $idChAtual = $this->idChAtual;
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

    public function verificaNumeroReal($token)
    {
        $caracter = $this->chAtual;
        $idCaracter = $this->idChAtual;
        /*
        * Se ainda não tiver . ex: 999 ,então eu continuo fazendo o número real
        */
        if ($caracter === "." && !strripos($token, ".")) {
            // verifcar se o próximo é um número
            $this->proximo();
            if (is_numeric($this->chAtual)) {
                echo "entrei";
                $this->tipoToken = "num_real";
                return "." . $this->chAtual;
            } else {
                $this->chAtual = $caracter;
                $this->idChAtual = $idCaracter;
            }
        }
        return false;
    }

public
function proximo()
{
    $dadosProxCaracter = $this->codigo->proximoCaracter($this->idChAtual, $this->chAtual);
    $this->chAtual = $dadosProxCaracter['chAtual'];
    $this->idChAtual = $dadosProxCaracter['idChAtual'];
}
}
