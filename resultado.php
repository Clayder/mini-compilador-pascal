<?php

if (isset($codigo))
{
    /*
      echo "<pre>";
      //print_r($codigo);
      echo "</pre>";

      echo "<pre>";
      //print_r($arrayCodigo);
      echo "</pre>";
     */

    echo "<pre>";
    print_r($lexico->getArrayTokens());
    echo "</pre>";

    echo "<pre>";
    print_r($lexico->getRelatorioTokens());
    echo "</pre>";
}


