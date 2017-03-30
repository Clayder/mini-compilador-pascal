<?php

namespace App\Lexico;
use App\Codigo\Codigo;

/**
 * Description of AnalisLexico
*/
class AnalisLexico
{
    /**
     * Recebe o token formado 
     * @var 
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
     * @var  
    */
    private $chAtual;
    
    /**
     * Recebe um objeto do tipo codigo
     * @var Codigo
     */
    private $codigo;
    
    /**
     * Recebe um objeto do tipo letra
     * @var Letra
     */
    private $letra;
    
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
    function setToken($token)
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
        
        switch ($this->chAtual)
        {
            case (Letra::ehLetra($this->chAtual)):

                $letra = new Letra($this->codigo);
                
                /*
                 * Cria o token do tipo letra 
                */
                $analiseLetra = $letra->analisarLetra($this->token, $this->chAtual, $this->idChAtual);
                
                /*
                 * Atualiza os dados do chAtual
                 */
                $this->setDadosChAtual($analiseLetra['idChatual'], $analiseLetra['chAtual']);
                
                $this->token = $analiseLetra['token'];
                $this->arrayTokens[] = $this->token;
                        
                break;

            default:
                break;
        }
    }

    /**
     * 
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
    function getIdChAtual()
    {
        return $this->idChAtual;
    }
    
    /**
     * 
     * @return array
     */
    function getArrayTokens()
    {
        return $this->arrayTokens;
    }
    
    /**
     * Atualiza os dados do idChAtual e chAtual
     * @param int $idChAtual
     * @param type $chAtual
     */
    public function setDadosChAtual($idChAtual, $chAtual){
        $this->idChAtual = $idChAtual;
        $this->chAtual = $chAtual;
    }





}
