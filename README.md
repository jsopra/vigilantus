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


CONFIGURANDO O AMBIENTE DE DESENVOLVIMENTO
-----------------------------------------------------

Instale o [Composer](http://getcomposer.org/) e rode `composer install` na
   raiz do projeto.

Configure o Apache com as variáveis de ambiente necessárias:

```apache
NameVirtualHost 127.0.0.1
<VirtualHost 127.0.0.1>
    ServerName vigilantus
    DocumentRoot /var/www/vigilantus/web
    SetEnv VIGILANTUS_ENV development
    SetEnv VIGILANTUS_DB_DSN "pgsql:host=localhost;dbname=vigilantus_development"
    SetEnv VIGILANTUS_DB_USERNAME postgres
    SetEnv VIGILANTUS_DB_PASSWORD postgres
    SetEnv VIGILANTUS_COOKIES_KEY umastringsecreta
</VirtualHost>
```

Configure o seu arquivo `.profile` ou equivalente com as variáveis de ambiente necessárias:

```bash
export VIGILANTUS_DB_DSN='pgsql:host=localhost;dbname=vigilantus_development'
export VIGILANTUS_DB_USERNAME='postgres'
export VIGILANTUS_DB_PASSWORD='qwerty'
```

Configure o seu arquivo `hosts`:

```
127.0.0.1   vigilantus
```

IMPORTANTE: Reinicie o Apache.

Rode as migrations com `php yii migrate`

CONFIGURANDO O AMBIENTE DE PRODUÇÃO
-----------------------------------------------------

Usando o [OpenShift RHC](https://www.openshift.com/developers/rhc-client-tools-install) faça:

```adicionar
$ rhc set-env VIGILANTUS_REDIS_DB_PASSWORD="XXXXXXXX" -a vigilantus
Setting environment variable(s) ... done

```listar
$ rhc env list vigilantus
VIGILANTUS_BD_DNS=pgsql:host=XXXX-vigilantus.getup.io;dbname=XXZZ
VIGILANTUS_COOKIES_KEY=ASDF
VIGILANTUS_DB_PASSWORD=ASDF
VIGILANTUS_DB_USERNAME=ASDF
VIGILANTUS_ENV=production
VIGILANTUS_REDIS_DB_DATABASE=ASDF
VIGILANTUS_REDIS_DB_PASSWORD=ASDF
VIGILANTUS_REDIS_DB_PORT=QWER
VIGILANTUS_REDIS_HOST=XyZ-vigilantus.getup.io


CONFIGURANDO O AMBIENTE DE TESTES
---------------------------------

Consulte as instruções completas no [README.md](tests/README.md) do diretório `tests`.
