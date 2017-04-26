#include "stdio.h"
#include "stdlib.h"
#include "string.h"
#define tam 30

//Estruturas de dados
typedef struct token
{
    int id;
    char lexema[20], desc[20];
}Token;
typedef struct simbolo
{
    int pres;
    char desc[20];
}Simbolo;

//Variaveis Globais
Simbolo dic[tam];
Token tk_atual;
FILE *arq;
char c='@';
int n_linha=1;

//Funções Analisador Léxico
void carrega_dicionario()
{
    int i=0;
    char desc[20];
    FILE *arq_dic=fopen("TabelaSimbolos.txt","rt");
    if(arq_dic==NULL){printf("Arquivo inexistente.\n");exit(0);}
    for(i=0;i<tam;i++)
    {
        fscanf(arq_dic,"%d %s\n",&dic[i].pres,dic[i].desc);
    }
    fclose(arq_dic);
}
void imprime_dicionario()
{
    int i;
    for(i=0;i<tam;i++)
    {
        printf("Id: %d - Pres: %d - Desc: %s\n",i,dic[i].pres,dic[i].desc);
    }
}
void ignora_invalidos()
{
        while(c == ' ' || c== '\n' || c== '\t' || c=='\r')
        {
            if(c=='\n')
            {
                n_linha++;
            }
            fscanf(arq,"%c",&c);
            if(feof(arq))
            {
                break;
            }
        }
}
int get_simbolo(char* descricao)
{
    int i;
    for(i=0;i<tam;i++)
    {
        if(dic[i].pres==1 && strcmp(dic[i].desc,descricao)==0)
        {
            return i;
        }
    }
    return 0;
}
int getID_simb(char* desc)
{
     int i;
    for(i=0;i<tam;i++)
    {
        if(strcmp(dic[i].desc,desc)==0)
        {
            return i;
        }
    }
    return -1;
}
void next_Token()
{
    int i;
    if(c=='@'){
        fscanf(arq,"%c",&c);
        ignora_invalidos();
    }else
    {
        ignora_invalidos();
    }
    if(feof(arq))
    {
        int simb=getID_simb("EOF");
        tk_atual.id=simb;
        sprintf(tk_atual.lexema,"%s",dic[simb].desc);
        sprintf(tk_atual.desc,"%s",dic[simb].desc);
        c='@';
    }
    else if(c=='{')
    {
        while(c!= '}' && !feof(arq))
        {
            fscanf(arq,"%c",&c);
            if(c=='\n')
            {
                n_linha++;
            }
        }
        if(c=='}')
        {
            c= '@';
        }
        next_Token();
    }
    else if((c>=97 && c<=122) || (c>=65 && c<=90))
    {
        i=0;
        do{
            tk_atual.lexema[i]=c;
            fscanf(arq,"%c",&c);
            i++;
        }while(!feof(arq) && (c>=97 && c<=122) || (c>=65 && c<=90));
        tk_atual.lexema[i]='\0';
        int simb = get_simbolo(tk_atual.lexema);
        tk_atual.id=simb;
        sprintf(tk_atual.desc,"%s",dic[simb].desc);
    }
    else if((c>=48 && c<=57))
    {
        i=0;
        do{
            tk_atual.lexema[i]=c;
            fscanf(arq,"%c",&c);
            i++;
        }while(!feof(arq) && (c>=48 && c<=57));
        tk_atual.lexema[i]='\0';
        int simb=getID_simb("numero");
        tk_atual.id=simb;
        sprintf(tk_atual.desc,"%s",dic[simb].desc);
    }
    else if(c=='(')
    {
        int simb=getID_simb("parenteses-abre");
        tk_atual.id=simb;
        sprintf(tk_atual.lexema,"%s",dic[simb].desc);
        sprintf(tk_atual.desc,"%s",dic[simb].desc);
        c='@';
    }
    else if(c==')')
    {
        int simb=getID_simb("parenteses-fecha");
        tk_atual.id=simb;
        sprintf(tk_atual.lexema,"%s",dic[simb].desc);
        sprintf(tk_atual.desc,"%s",dic[simb].desc);
        c='@';
    }
    else if(c=='-')
    {
        int simb=getID_simb("subtracao");
        tk_atual.id=simb;
        sprintf(tk_atual.lexema,"%s",dic[simb].desc);
        sprintf(tk_atual.desc,"%s",dic[simb].desc);
        c='@';
    }
    else if(c=='+')
    {
        int simb=getID_simb("adicao");
        tk_atual.id=simb;
        sprintf(tk_atual.lexema,"%s",dic[simb].desc);
        sprintf(tk_atual.desc,"%s",dic[simb].desc);
        c='@';
    }
    else if(c== '*')
    {
        int simb=getID_simb("multiplicacao");
        tk_atual.id=simb;
        sprintf(tk_atual.lexema,"%s",dic[simb].desc);
        sprintf(tk_atual.desc,"%s",dic[simb].desc);
        c='@';
    }
    else if(c=='/')
    {
        int simb=getID_simb("divisao");
        tk_atual.id=simb;
        sprintf(tk_atual.lexema,"%s",dic[simb].desc);
        sprintf(tk_atual.desc,"%s",dic[simb].desc);
        c='@';
    }
    else if(c==';')
    {
        int simb=getID_simb("ponto-e-virgula");
        tk_atual.id=simb;
        sprintf(tk_atual.lexema,"%s",dic[simb].desc);
        sprintf(tk_atual.desc,"%s",dic[simb].desc);
        c='@';
    }
    else if(c=='.')
    {
        int simb=getID_simb("ponto");
        tk_atual.id=simb;
        sprintf(tk_atual.lexema,"%s",dic[simb].desc);
        sprintf(tk_atual.desc,"%s",dic[simb].desc);
        c='@';
    }
    else if(c==',')
    {
        int simb=getID_simb("virgula");
        tk_atual.id=simb;
        sprintf(tk_atual.lexema,"%s",dic[simb].desc);
        sprintf(tk_atual.desc,"%s",dic[simb].desc);
        c='@';
    }
    else if(c=='>')
    {
        fscanf(arq,"%c",&c);
        switch(c)
        {
        case '=' :
            {
                int simb=getID_simb("maior-igual");
                tk_atual.id=simb;
                sprintf(tk_atual.lexema,"%s",dic[simb].desc);
                sprintf(tk_atual.desc,"%s",dic[simb].desc);
                c='@';
                break;
            }
        default:
            {
                int simb=getID_simb("maior");
                tk_atual.id=simb;
                sprintf(tk_atual.lexema,"%s",dic[simb].desc);
                sprintf(tk_atual.desc,"%s",dic[simb].desc);
                break;
            }
        }
    }
    else if(c=='<')
    {
        fscanf(arq,"%c",&c);
        switch(c)
        {
        case '=' :
            {
                int simb=getID_simb("menor-igual");
                tk_atual.id=simb;
                sprintf(tk_atual.lexema,"%s",dic[simb].desc);
                sprintf(tk_atual.desc,"%s",dic[simb].desc);
                c='@';
                break;
            }
        case '>' :
            {
                int simb=getID_simb("diferente");
                tk_atual.id=simb;
                sprintf(tk_atual.lexema,"%s",dic[simb].desc);
                sprintf(tk_atual.desc,"%s",dic[simb].desc);
                c='@';
                break;
            }
        default:
            {
                int simb=getID_simb("menor");
                tk_atual.id=simb;
                sprintf(tk_atual.lexema,"%s",dic[simb].desc);
                sprintf(tk_atual.desc,"%s",dic[simb].desc);
                break;
            }
        }
    }
    else if(c==':')
    {
        fscanf(arq,"%c",&c);
        switch(c)
        {
        case '=' :
            {
                int simb=getID_simb("atribuicao");
                tk_atual.id=simb;
                sprintf(tk_atual.lexema,"%s",dic[simb].desc);
                sprintf(tk_atual.desc,"%s",dic[simb].desc);
                c='@';
                break;
            }
        default:
            {
                tk_atual.id=-1;
                sprintf(tk_atual.lexema,"%s","erro lexico");
                sprintf(tk_atual.desc,"%s","erro lexico");
                printf("Erro lexico - %d\n",n_linha);
                exit(0);
                break;
            }
        }
    }
    else if(c=='=')
    {
        int simb=getID_simb("igual");
        tk_atual.id=simb;
        sprintf(tk_atual.lexema,"%s",dic[simb].desc);
        sprintf(tk_atual.desc,"%s",dic[simb].desc);
        c='@';
    }
    else
    {
            tk_atual.id=-1;
            sprintf(tk_atual.lexema,"'%c' ", c);
            sprintf(tk_atual.desc,"%s - linha: %d","Erro lexico",n_linha);
            c='@';
    }
    printf("\ntoken:%s",tk_atual.desc);
}


//Funções Analisador Sintático
void prog();
void vars();
void bloco();
void cons();
int com();
void else_opc();
void lista_ident();
void lista_ident2();
void exp();
void exp2();
void termo();
void termo2();
void fator();
void exp_rel();
void op_rel();
int ident();
int num();

void verificar_token(char* token)
{
    if(getID_simb(token)==tk_atual.id)
    {
        next_Token();
    }else
    {
        printf("\nErro sintatico - linha: %d - token esperado: %s\n",n_linha,token);
        exit(0);
    }
}
void prog()
{
    next_Token();
    vars();
    bloco();
    verificar_token("ponto");
    verificar_token("EOF");
}
void vars()
{
    if(tk_atual.id==getID_simb("var"))
    {
        verificar_token("var");
        lista_ident();
        verificar_token("ponto-e-virgula");
    }
}
void bloco()
{
    verificar_token("begin");
    cons();
    verificar_token("end");
}
void cons()
{
    if(com())
    {
        verificar_token("ponto-e-virgula");
        cons();
    }
}
int com()
{
    int iscom=0;
    if(tk_atual.id==getID_simb("print"))
    {
            iscom=1;
            verificar_token("print");
            verificar_token("parenteses-abre");
            lista_ident();
            verificar_token("parenteses-fecha");
    }
    else if(tk_atual.id==getID_simb("if"))
    {
            iscom=1;
            verificar_token("if");
            exp_rel();
            verificar_token("then");
            bloco();
            else_opc();
    }
    else if(tk_atual.id==getID_simb("read"))
    {
            iscom=1;
            verificar_token("read");
            verificar_token("parenteses-abre");
            lista_ident();
            verificar_token("parenteses-fecha");
    }
    else if(tk_atual.id==getID_simb("for"))
    {
            iscom=1;
            verificar_token("for");
            ident();
            verificar_token("atribuicao");
            exp();
            verificar_token("to");
            exp();
            verificar_token("do");
            bloco();
    }
    else if(ident())
    {
        iscom=1;
        verificar_token("atribuicao");
        exp();
    }
    return iscom;
}
void else_opc()
{
    if(tk_atual.id==getID_simb("else"))
    {
        verificar_token("else");
        bloco();
    }
}
void exp_rel()
{
    exp();
    op_rel();
    exp();
}
void op_rel()
{
    if( tk_atual.id == getID_simb("igual") ||tk_atual.id == getID_simb("maior") || tk_atual.id == getID_simb("menor") || tk_atual.id == getID_simb("diferente") || tk_atual.id == getID_simb("maior-igual") || tk_atual.id == getID_simb("menor-igual"))
    {
        next_Token();
    }
}
void lista_ident()
{
    if(ident())
    {
        lista_ident2();
    }
}
void lista_ident2()
{
    if(tk_atual.id==getID_simb("virgula"))
    {
        verificar_token("virgula");
        lista_ident();
    }
}
void exp()
{
    termo();
    exp2();
}
void exp2()
{
    if(tk_atual.id==getID_simb("adicao"))
    {
        verificar_token("adicao");
        termo();
        exp2();
    }else if(tk_atual.id==getID_simb("subtracao"))
    {
        verificar_token("adicao");
        termo();
        exp2();
    }
}
void termo()
{
    fator();
    termo2();
}
void termo2()
{
    if(tk_atual.id==getID_simb("multiplicacao"))
    {
        verificar_token("multiplicacao");
        fator();
        termo2();
    }else if(tk_atual.id==getID_simb("divisao"))
    {
        verificar_token("divisao");
        fator();
        termo2();
    }
}
void fator()
{
    if(tk_atual.id==getID_simb("parenteses-abre"))
    {
        verificar_token("parenteses-abre");
        exp();
        verificar_token("parenteses-fecha");
    }else
    {
        if(ident())
        {

        }else if(num())
        {

        }
        else
        {
            printf("Erro sintático - linha : %d\n",n_linha);
        }
    }
}
int ident()
{
    if(tk_atual.id==getID_simb("variavel"))
    {
        verificar_token("variavel");
        return 1;
    }
    return 0;
}
int num()
{
    if(tk_atual.id==getID_simb("numero"))
    {
        verificar_token("numero");
        return 1;
    }
    return 0;
}

//Função Principal
void Compilador(char* nomearq)
{
    arq = fopen(nomearq,"rt");
    if(arq==NULL){printf("Arquivo inexistente.\n");exit(0);}
    prog();
    printf("\nPrograma sem erros lexicos ou sintaticos!\n");
    fclose(arq);
}

int main()
{
    char nomearq[20];

    carrega_dicionario();
    printf("Digite o nome do arquivo a ser interpretado:\n");
    scanf("%s",nomearq);

    Compilador(nomearq);

    system("pause");
}
