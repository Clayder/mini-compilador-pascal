Compilador (transpilador) Pascal em PHP
=======================================

Esse sistema converte código pascal em php, seguindo todo o processo de um compilador.

Objetivo
--------

O objetivo desse projeto, foi colocar em prática o processo de compilação de um compilador.  
Através do desenvolvimento desse sistema, foi possível implementar as fases de compilação:

 1. List item 
 2. Léxica
 3. Sintática
 4. Semântica
 5. Geração de código
 6. Linguagem Utilizada: PHP 
 

Gramática utilizada
-------------------

Para facilitar o desenvolvimento,  não foi utilizado a gramática completa do Pascal.

    PROG -> CONSTANTES BLOCO .
    
    VARS -> var LISTAS_IDENT | E
    
    BLOCO -> VARS begin COMS end
    
    COMS -> COM ; COMS | E
    
    COM -> print ( LISTA_IDENT ) |
    
           if EXP_REL then BLOCO ELSE_OPC |
    
           IDENT := EXP
    
           for IDENT := EXP to EXP do BLOCO |
    
           read ( LISTA_IDENT )
    
    ELSE_OPC -> else BLOCO | E
    
    LISTA_IDENT -> IDENT, LISTA_IDENT | IDENT
    
    EXP -> EXP + TERMO |
    
           EXP - TERMO |
    
           TERMO
    
    TERMO -> TERMO * FATOR |
    
             TERMO / FATOR |
    
             FATOR
    
    FATOR -> ( EXP ) |
             IDENT |
             NUM
    
    EXP_REL -> EXP OP_REL EXP
    
    OP_REL -> = | <> | < | > | <= | >=
    
    IDENT -> CARACTER IDENT | CARACTER 
    
    CARACTER -> a | ... | z | A | ... | Z
    
    DIGITO -> 0 | ... | 9
    
    CONSTANTES -> const LISTA_CONSTANTES | E
    
    LISTA_CONSTANTES -> DEF_CONST  LISTA_CONSTANTES |
                        DEF_CONST
    
    DEF_CONST -> IDENT = NUM ;
    
    NUM_FLOAT -> NUM_INT . NUM_INT 
    
    LISTAS_IDENT -> DEF_LISTAS_IDENT  LISTAS_IDENT |
                    DEF_LISTAS_IDENT
    
    DEF_LISTAS_IDENT -> LISTA_IDENT : integer ; |
                        LISTA_IDENT : real ;
    
    NUM -> NUM_INT | NUM_FLOAT
    
    NUM_INT -> DIGITO NUM_INT2
    NUM_INT2 -> DIGITO 
    NUM_INT2 -> E



 [Acessar demo (Link)](https://www.youtube.com/watch?v=xnD8_cifEV8)
