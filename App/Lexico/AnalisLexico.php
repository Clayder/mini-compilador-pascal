<?php

namespace App\Lexico;

use App\Codigo\Codigo;

/**
 * Description of AnalisLexico
 * @author Peter Clayder e Fernanda Pires
 */
class AnalisLexico
{

    /**
     * Recebe o token formado 
     * @var type
     */
    private $token;

    /**
     * Lista de tokens 
     * @var array
     */
    private $arrayTokens;

    /**
     * Recebe o id do caracter atual, que está sendo analisado
     * @var int 
     */
    private $idChAtual;

    /**
     * Recebe o caracter atual, que está sendo analisado
     * @var type
     */
    private $chAtual;

    /**
     * Recebe um objeto do tipo codigo
     * @var Codigo
     */
    private $codigo;
    
    /**
     * Possui um array com um relatório dos tokens gerados. 
     * Dados: id, descricao, lexema e se o token é palavra reservada ou não 
     * @var array
     */
    private $relatorioTokens;

    /**
     * 
     * @param Codigo $codigo
     */
    public function __construct(Codigo $codigo)
    {
        $this->idChAtual = 0;
        $this->token = "";
        $this->codigo = $codigo;
    }

    /**
     * 
     * @return type
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 
     * @param type $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Percorre o código criando os tokens, a partir do caracter atual (chAtual). 
     * Quando terminar de formar o token em relação ao caracter atual, 
     * o próximo token é escolhido (atualiza chAtual e idAtual, essa atualização é 
     * feita no "do while" do método analisarLetras da classe Letra) com issp método 
     * (nextToken) é finalizado. Depois de finalizar o método o programa volta para 
     * a main (compilador.php), dando continuidade na execução do “do while”. 
     * @return void
     */
    public function nextToken()
    {
        // Recebe o valor do caracter atual 
        $this->chAtual = $this->codigo->getCaracterCodigo($this->idChAtual);

        echo "**************";
        echo "<br />";
        echo "Entrei nextToken chAtual: $this->chAtual";
        echo "Entrei idchAtual: $this->idChAtual";
        echo "<br />";
        echo "**************";
        echo "<br />";

        $dadosEliminarCaracter = $this->codigo->eliminaCaracterInvalidos($this->idChAtual, $this->chAtual);

        $this->setDadosChAtual($dadosEliminarCaracter['idChAtual'], $dadosEliminarCaracter['chAtual']);

        $objGerarToken = null;
        
        switch ($this->chAtual)
        {
            case (Letra::ehLetra($this->chAtual)):
                
                $objGerarToken = new Letra($this->codigo);
                
                break;

            case (is_numeric($this->chAtual)):

                $objGerarToken = new Algarismo($this->codigo);

                break;
            
            case (Sinal::ehSinal($this->chAtual)):
                
                $objGerarToken = new Sinal($this->codigo);
                
                 break;
             
            case ($this->chAtual === "<"):
                
                $objGerarToken = new SinalMenor($this->codigo);
               
                break;

            default:
                break;
        }
        
        if($objGerarToken != null){
            $this->geracaoToken($objGerarToken);
        }else{
            echo "token invalido";
        }
    }

    public function geracaoToken(IToken $gerar)
    {
        
        $i = 0;
        
        echo "Geração Token " . $i++ . "<br />";
        
        /*
         * Cria o token 
         */
        $tokenGerado = $gerar->gerarToken($this->token, $this->chAtual, $this->idChAtual);
        
        /*
         * Atualiza os dados do chAtual
         */
        $this->setDadosChAtual($tokenGerado['idChatual'], $tokenGerado['chAtual']);

        $this->token = $tokenGerado['token'];
        $this->arrayTokens[] = $this->token;
        $this->relatorioTokens[] = $tokenGerado['relatorio'];
    }

    /**
     * @return void
     */
    public function imprime()
    {
        $qtdTokens = count($this->arrayTokens);
        for ($i = 0; $i < $qtdTokens; $i++)
        {
            if ($this->arrayTokens[$i] != " ")
            {
                echo $this->arrayTokens[$i];
                echo "<br />";
            }
        }
    }

    /**
     * 
     * @return int
     */
    public function getIdChAtual()
    {
        return $this->idChAtual;
    }

    /**
     * 
     * @return array
     */
    public function getArrayTokens()
    {
        return $this->arrayTokens;
    }

    /**
     * Atualiza os dados do idChAtual e chAtual
     * @param int $idChAtual
     * @param type $chAtual
     * @return void
     */
    public function setDadosChAtual($idChAtual, $chAtual)
    {
        $this->idChAtual = $idChAtual;
        $this->chAtual = $chAtual;
    }
    
    /**
     * 
     * @return array
     */
    public function getRelatorioTokens()
    {
        return $this->relatorioTokens;
    }



}
