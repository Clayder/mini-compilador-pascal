<?php

namespace App\Lexico;

/**
 * Description of AnalisLexico
 */
class AnalisLexico
{

    private $token;
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
    
    function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        $this->tamCodigo = count($this->codigo);
    }

    public function nextToken()
    {
        $this->chAtual = $this->codigo[$this->idChAtual];
        $this->eliminaCaracterInvalidos();
        switch ($this->chAtual)
        {
            case (Letra::ehLetra($this->chAtual)):
                
                $this->analisarLetras();
                
                break;

            default:
                break;
        }
    }

    private function eliminaCaracterInvalidos()
    {
        // se for inválido, vai para o próximo
        if ($this->chAtual == " ")
        {
            $this->nextCaracter();
        }
        return $this->chAtual;
    }

    private function analisarLetras()
    {
        while (Letra::ehLetra($this->chAtual))
        {
            $this->token = $this->token . $this->chAtual;
            $this->proximoCaracter();
        }
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
        
    }

}
