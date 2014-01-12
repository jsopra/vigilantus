Vigilantus
==========

Descrição do software

SOBRE OS AMBIENTES
------------------

A variável `VIGILANTUS_ENV` indica qual é o ambiente atual.

Ao programar, a URL `http://vigilantus` aponta para o ambiente de
desenvolvimento e a URL `http://vigilantustest` aponta para o ambiente de testes.

Desta forma, ao rodar os testes estaremos utilizando um banco de dados diferente,
porém a partir do mesmo diretório.


CONFIGURANDO O AMBIENTE DE DESENVOLVIMENTO E PRODUÇÃO
-----------------------------------------------------

– 1. Instale o [Composer](http://getcomposer.org/) e rode `composer install` na
   raiz do projeto.

– 2. Configure o Apache com as variáveis de ambiente necessárias:

```apache
NameVirtualHost 127.0.0.1
<VirtualHost 127.0.0.1>
    ServerName vigilantus
    DocumentRoot /var/www/vigilantus/web
    SetEnv VIGILANTUS_ENV development
    SetEnv VIGILANTUS_DB_DSN "pgsql:host=localhost;dbname=vigilantus_development"
    SetEnv VIGILANTUS_DB_USERNAME postgres
    SetEnv VIGILANTUS_DB_PASSWORD postgres
</VirtualHost>
```

– 3. Configure o seu arquivo `.profile` ou equivalente com as variáveis de ambiente necessárias:

```bash
export VIGILANTUS_DB_DSN='pgsql:host=localhost;dbname=vigilantus_development'
export VIGILANTUS_DB_USERNAME='postgres'
export VIGILANTUS_DB_PASSWORD='qwerty'
```

– 4. Configure o seu arquivo `hosts`:

```
127.0.0.1   vigilantus
```

– 5. IMPORTANTE: Reinicie o Apache.

– 6. Rode as migrations com `php yii migrate`

CONFIGURANDO O AMBIENTE DE TESTES
---------------------------------

– 1. Instale o [Composer](http://getcomposer.org/) e rode `composer install` na
   raiz do projeto.

– 2. Configure o Apache com as variáveis de ambiente necessárias:

```apache
NameVirtualHost 127.0.0.1
<VirtualHost 127.0.0.1>
    ServerName vigilantustest
    DocumentRoot /var/www/vigilantus/web
    SetEnv VIGILANTUS_ENV test
    SetEnv VIGILANTUS_DB_DSN "pgsql:host=localhost;dbname=vigilantus_test"
    SetEnv VIGILANTUS_DB_USERNAME postgres
    SetEnv VIGILANTUS_DB_PASSWORD postgres
</VirtualHost>
```

– 3. Configure o seu arquivo `.profile` ou equivalente com as variáveis de ambiente necessárias:

```bash
export VIGILANTUS_TEST_DB_DSN='pgsql:host=localhost;dbname=vigilantus_test'
export VIGILANTUS_TEST_DB_USERNAME='postgres'
export VIGILANTUS_TEST_DB_PASSWORD='qwerty'
```

– 4. Configure o seu arquivo `hosts`:

```
127.0.0.1   vigilantustest
```

– 5. IMPORTANTE: Reinicie o Apache.

– 6. Gere as classes `Guy` do Codeception: `vendor/bin/codecept build`

– 7. Rode os testes com `vendor/bin/codecept run unit`
