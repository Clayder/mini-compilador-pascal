<?php

namespace App\Lexico;

/**
 * Description of AnalisLexico
 */
class AnalisLexico
{

    private $token;
    private $arrayTokens;
    private $idChAtual;
    private $chAtual;
    private $tamCodigo;
    private $codigo;

    public function __construct()
    {
        $this->idChAtual = 0;
        $this->token = "";
    }

    public function getToken()
    {
        return $this->token;
    }

    function setToken($token)
    {
        $this->token = $token;
    }

    function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        $this->tamCodigo = count($this->codigo);
    }

    public function nextToken()
    {
        $this->chAtual = $this->codigo[$this->idChAtual];
        
          echo "**************";
          echo "<br />";
          echo "Entrei nextToken chAtual: $this->chAtual";
          echo "Entrei idchAtual: $this->idChAtual";
          echo "<br />";
          echo "**************";
          echo "<br />";
         

        $this->eliminaCaracterInvalidos();
        switch ($this->chAtual)
        {
            case (Letra::ehLetra($this->chAtual)):

                $this->analisarLetra();

                break;

            default:
                break;
        }
    }

    private function eliminaCaracterInvalidos()
    {
        while ($this->chAtual == " " || $this->chAtual == "\n" || $this->chAtual == "\r")
        {
            /*
            echo "**************";
            echo "<br />";
            echo "Entrei eliminarCaracter chAtual: $this->chAtual";
            echo "<br />";
            echo "Entrei idchAtual: $this->idChAtual";
            echo "<br />";
            echo "**************";
            echo "<br />";
             * 
             */
            $this->proximoCaracter();
        }
    }

    private function analisarLetra()
    {
        do
        {
            
              echo "############################";
              echo "<br />";
              echo "ch atual: " . $this->chAtual;
              echo "<br />";
              echo "token: " . $this->token;
              echo "<br />";
              echo "id: " . $this->idChAtual;
              echo "<br />";
              echo "############################";
             
            $this->token = $this->token . $this->chAtual;
             
            $this->proximoCaracter();
        }while (Letra::ehLetra($this->chAtual)  && $this->token !== "EOF"  );
        //}while (Letra::ehLetra($this->chAtual)  && !in_array($this->token, TabelaSimbolos::getPalavrasReservadas())  );
       
        $this->arrayTokens[] = $this->token;
    }

    private function proximoCaracter()
    {
        // verifico se existe o próximo caracter
        if ($this->idChAtual < $this->tamCodigo - 1)
        {
            $this->idChAtual = $this->idChAtual + 1;
            $this->chAtual = $this->codigo[$this->idChAtual];
        }
    }

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

    function getIdChAtual()
    {
        return $this->idChAtual;
    }
    
    function getArrayTokens()
    {
        return $this->arrayTokens;
    }



}
