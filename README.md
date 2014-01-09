Vigilantus
================================

Descrição do software


CONFIGURANDO O AMBIENTE DE DESENVOLVIMENTO E PRODUÇÃO
-----------------------------------------------------

1. Instale o [Composer](http://getcomposer.org/) e rode `composer install` na
   raiz do projeto.

2. Configure o Apache com as variáveis de ambiente necessárias:

   zxxxx

3. Configure o terminal com as variáveis de ambiente necessárias:


4. Só deixe exposto o diretório `web`.

CONFIGURANDO O AMBIENTE DE TESTES
---------------------------------

1. Siga os passos para configurar um ambiente de dev/prod.

2. Altere a variável de ambiente `ENVIRONMENT` para `test`.

3. Assegure-se de ter configurado o Apache com as seguintes opções:

   xxxx

4. Gere as classes `Guy` do Codeception: `vendor/bin/codecept build`

5. Rode os testes com `vendor/bin/codecept run`