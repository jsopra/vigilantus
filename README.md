# Vigilantus

## Softwares necessários

* Apache 2
* PHP 5.6 (com as extensões que o Composer irá exigir quando rodar)
* Composer
* Postgres 9.4 (com a extensão Postgis)
* Redis

## Configurando o ambiente de desenvolvimento

Adicione um novo virtual host ao Apache, com as variáveis de ambiente necessárias.
As configurações do apache geralmente se encontram no arquivo
`/etc/apache2/httpd.conf`.

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

Adicione ao seu arquivo `~/.profile` (ou equivalente) as seguintes variáveis de
ambiente do terminal:

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

Para que o host `vigilantus.dev` funcione, adicione a seguinte linha ao seu
arquivo `/etc/hosts`:

```
127.0.0.1 vigilantus.dev vigilantus.test.dev api.vigilantus.dev
```

**Importante**: para aplicar as configurações acima, reinicie o Apache e
recarregue as variáveis de ambiente:

```bash
sudo service apache2 reload # recarrega o Apache
source ~/.profile # carrega variáveis do terminal
```

Por fim, rode `./bin/setup` para preparar o ambiente:

```
👾 Verificando composer...
👾 Verificando composer-asset-plugin...
👾 Instalando pacotes do composer...
👾 Rodando migrations...
👾 Feito!
```

## Configurando o ambiente de produção

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

## Configurando o ambiente de testes

Consulte as instruções completas no [README.md](tests/README.md) do diretório `tests`.

## Deploy

Consulte o texto de [introdução ao deploy da Getup](https://getupcloud.com/blog/deploy-e-rollback).

Para fazer deploy manualmente, siga os seguintes passos:

```bash
git clone ssh://536a900a99fc77c093000257@vigilantus-vigilantus.getup.io/~/git/vigilantus.git/ vigilantus-deploy
git git remote add upstream git@git.perspectiva.in:perspectiva/vigilantus.git
git pull upstream master
git push
```
