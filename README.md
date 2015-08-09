# Vigilantus

## REQUISITOS DE TECNOLOGIA

* **PHP 5.6**, com as extensões:
** **PHP Redis**
** **PHP PDO Postgres**
* **Postgres 9.4**, com as extensões:
** **Postgis**
* **Redis**
* **Composer**
* **Codeception**

## SOBRE OS AMBIENTES

A variável `VIGILANTUS_ENV` indica qual é o ambiente atual.

Ao programar, a URL `http://vigilantus.dev` aponta para o ambiente de
desenvolvimento e a URL `http://vigilantus.test.dev` aponta para o ambiente de testes.

Desta forma, ao rodar os testes estaremos utilizando um banco de dados diferente,
porém a partir do mesmo diretório.


## CONFIGURANDO O AMBIENTE DE DESENVOLVIMENTO

Instale o [Composer](http://getcomposer.org/) e rode `composer install` na
   raiz do projeto.

Configure o Apache com as variáveis de ambiente necessárias:

```apache
<VirtualHost vigilantus.dev>
    ServerName vigilantus.dev
    DocumentRoot /var/www/vigilantus/web
    SetEnv VIGILANTUS_ENV development
    SetEnv VIGILANTUS_DB_DSN_HOST "pgsql:host=localhost"
    SetEnv VIGILANTUS_DB_DSN_DBNAME "dbname=vigilantus_development"
    SetEnv VIGILANTUS_DB_USERNAME postgres
    SetEnv VIGILANTUS_DB_PASSWORD postgres
    SetEnv VIGILANTUS_COOKIES_KEY umastringsecreta
    SetEnv VIGILANTUS_REDIS_DB_DATABASE 'ASDF'
    SetEnv VIGILANTUS_REDIS_DB_PASSWORD 'ASDF'
    SetEnv VIGILANTUS_REDIS_DB_PORT 'QWER'
    SetEnv VIGILANTUS_REDIS_HOST 'XyZ-vigilantus.getup.io'
    SetEnv OPENSHIFT_GEARMAN_IP 'localhost'
    SetEnv OPENSHIFT_GEARMAN_PORT '4730'
    SetEnv GEARMAN_JOB_KEY 'n2398n289fn2nf'
</VirtualHost>
```

Adicione também o caminho para a API:

```apache
<VirtualHost api.vigilantus.dev:80>
    ServerName api.vigilantus.dev
    DocumentRoot /var/www/vigilantus/api/web
    SetEnv VIGILANTUS_ENV development
    SetEnv VIGILANTUS_DB_DSN_HOST "pgsql:host=localhost"
    SetEnv VIGILANTUS_DB_DSN_DBNAME "dbname=vigilantus_development"
    SetEnv VIGILANTUS_DB_USERNAME postgres
    SetEnv VIGILANTUS_DB_PASSWORD postgres
    SetEnv VIGILANTUS_COOKIES_KEY umastringsecreta
    SetEnv VIGILANTUS_REDIS_DB_DATABASE 'ASDF'
    SetEnv VIGILANTUS_REDIS_DB_PASSWORD 'ASDF'
    SetEnv VIGILANTUS_REDIS_DB_PORT 'QWER'
    SetEnv VIGILANTUS_REDIS_HOST 'XyZ-vigilantus.getup.io'
    SetEnv OPENSHIFT_GEARMAN_IP 'localhost'
    SetEnv OPENSHIFT_GEARMAN_PORT '4730'
    SetEnv GEARMAN_JOB_KEY 'n2398n289fn2nf'
</VirtualHost>
```

Configure o seu arquivo `.profile` ou equivalente com as variáveis de ambiente
necessárias:

```bash
export VIGILANTUS_DB_DSN='pgsql:host=localhost;dbname=vigilantus_development'
export VIGILANTUS_DB_USERNAME='postgres'
export VIGILANTUS_DB_PASSWORD='qwerty'
export VIGILANTUS_DB_DSN_HOST="pgsql:host=localhost"
export VIGILANTUS_DB_DSN_DBNAME="dbname=vigilantus_development"
export VIGILANTUS_REDIS_DB_DATABASE='ASDF'
export VIGILANTUS_REDIS_DB_PASSWORD='ASDF'
export VIGILANTUS_REDIS_DB_PORT='QWER'
export VIGILANTUS_REDIS_HOST='XyZ-vigilantus.getup.io'
export VIGILANTUS_BASE_PATH='http://vigilantus/'
export OPENSHIFT_GEARMAN_IP='localhost'
export OPENSHIFT_GEARMAN_PORT='4730'
export GEARMAN_JOB_KEY='n2398n289fn2nf'
```

Configure o seu arquivo `hosts`:

```
127.0.0.1 vigilantus.dev vigilantus.test.dev api.vigilantus.dev
```

IMPORTANTE: Reinicie o Apache e recarregue as variáveis de ambiente `source ~/.profile`.

Rode as migrations com `php yii migrate`.

## CONFIGURANDO O AMBIENTE DE PRODUÇÃO

Usando o [OpenShift RHC](https://www.openshift.com/developers/rhc-client-tools-install) faça:

Adicionar variável de ambiente:

```bash
rhc set-env VIGILANTUS_REDIS_DB_PASSWORD="XXXXXXXX" -a vigilantus
# Setting environment variable(s) ... done
```

Listar as variáveis existentes:

```bash
rhc env list vigilantus
# VIGILANTUS_DB_DSN_HOST="pgsql:host=123-vigilantus.getup.io"
# VIGILANTUS_DB_DSN_DBNAME="dbname=vigilantus"
# VIGILANTUS_COOKIES_KEY=ASDF
# VIGILANTUS_DB_PASSWORD=ASDF
# VIGILANTUS_DB_USERNAME=ASDF
# VIGILANTUS_ENV=production
# VIGILANTUS_REDIS_DB_DATABASE=ASDF
# VIGILANTUS_REDIS_DB_PASSWORD=ASDF
# VIGILANTUS_REDIS_DB_PORT=QWER
# VIGILANTUS_REDIS_HOST=XyZ-vigilantus.getup.io
```

CONFIGURANDO O AMBIENTE DE TESTES
---------------------------------

Consulte as instruções completas no [README.md](tests/README.md) do diretório `tests`.

DEPLOY
------

Veja uma [Introdução de deploy da getup](https://getupcloud.com/blog/deploy-e-rollback).

Para fazer deploy manualmente, siga os seguintes passos:

```bash
git clone ssh://536a900a99fc77c093000257@vigilantus-vigilantus.getup.io/~/git/vigilantus.git/ vigilantus-deploy
git git remote add upstream git@git.perspectiva.in:perspectiva/vigilantus.git
git pull upstream master
git push
```
