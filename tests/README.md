Testando
========

RODANDO OS TESTES
-----------------

Rode os testes unitários com `vendor/bin/codecept run unit`

Para testes de aceitação, instale e rode o Selenium2 com 
`java -jar selenium-server-standalone-2.37.0.jar`, e então rode os testes com
`vendor/bin/codecept run acceptance`

Você também pode rodar o teste somente para um arquivo, mas não esqueça de rodar
para todos antes de comitar: `vendor/bin/codecept run tests/unit/models/BairroTipoTest.php`

Consulte o [manual do Codeception](http://codeception.com) para saber mais.

COMO FUNCIONAM OS TESTES
------------------------

O script excluirá o esquema `public` do seu banco de testes, então rodará todas
as migrations, carregará as fixtures e exportará o resultado em um arquivo SQL
(o modelo de esquema limpo). Antes de cada método de teste unitário ou arquivo
de teste de aceitação, ele recarregará o banco de dados com esse esquema limpo
que foi gerado antes de começar, garantindo que você tenha os testes
completamente isolados um do outro.

Caso o seu teste falhe, experimente conferir os logs na pasta `tests/_logs`. O
Codeception até mesmo tira uma screenshot nos testes de aceitação.

CONFIGURANDO O AMBIENTE DE TESTES
---------------------------------

Instale o [Composer](http://getcomposer.org/) e rode `composer install` na
   raiz do projeto.

Configure o Apache com as variáveis de ambiente necessárias:

```apache
NameVirtualHost 127.0.0.1
<VirtualHost 127.0.0.1>
    ServerName vigilantustest
    DocumentRoot /var/www/vigilantus/web
    SetEnv VIGILANTUS_ENV test
    SetEnv VIGILANTUS_TEST_DB_DSN "pgsql:host=localhost;dbname=vigilantus_test"
    SetEnv VIGILANTUS_TEST_DB_USERNAME postgres
    SetEnv VIGILANTUS_TEST_DB_PASSWORD postgres
</VirtualHost>
```

Configure o seu arquivo `.profile` ou equivalente com as variáveis de ambiente necessárias:

```bash
export VIGILANTUS_TEST_DB_DSN='pgsql:host=localhost;dbname=vigilantus_test'
export VIGILANTUS_TEST_DB_USERNAME='postgres'
export VIGILANTUS_TEST_DB_PASSWORD='qwerty'
```

Configure o seu arquivo `hosts`:

```
127.0.0.1   vigilantustest
```

IMPORTANTE: Reinicie o Apache.

Gere as classes `Guy` do Codeception: `vendor/bin/codecept build`

Instale o browser **Mozilla Firefox** e baixe o **Selenium Server Standalone**
para poder rodar testes de aceitação.
