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
    SetEnv ENVIRONMENT development
    SetEnv DB_DSN_HOST "pgsql:host=localhost"
    SetEnv DB_DSN_DBNAME "dbname=vigilantus_development"
    SetEnv DB_USERNAME postgres
    SetEnv DB_PASSWORD postgres
    SetEnv COOKIES_KEY umastringsecreta
    SetEnv REDIS_DATABASE 'ASDF'
    SetEnv REDIS_PASSWORD 'ASDF'
    SetEnv REDIS_PORT 'QWER'
    SetEnv REDIS_HOST 'XyZ-vigilantus.getup.io'
    SetEnv GEARMAN_IP 'localhost'
    SetEnv GEARMAN_PORT '4730'
    SetEnv GEARMAN_JOB_KEY 'n2398n289fn2nf'
</VirtualHost>
```

Adicione ao seu arquivo `~/.profile` (ou equivalente) as seguintes variáveis de
ambiente do terminal:

```bash
export VIGILANTUS_DB_DSN='pgsql:host=localhost;dbname=vigilantus_development'
export DB_USERNAME='postgres'
export DB_PASSWORD='qwerty'
export DB_DSN_HOST="pgsql:host=localhost"
export DB_DSN_DBNAME="dbname=vigilantus_development"
export REDIS_DATABASE='ASDF'
export REDIS_PASSWORD='ASDF'
export REDIS_PORT='QWER'
export REDIS_HOST='XyZ-vigilantus.getup.io'
export ABSOLUTE_URL='http://vigilantus/'
export GEARMAN_IP='localhost'
export GEARMAN_PORT='4730'
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
rhc set-env REDIS_PASSWORD="XXXXXXXX" -a vigilantus
# Setting environment variable(s) ... done
```

Listar as variáveis existentes:

```bash
rhc env list vigilantus
# DB_DSN_HOST="pgsql:host=123-vigilantus.getup.io"
# DB_DSN_DBNAME="dbname=vigilantus"
# COOKIES_KEY=ASDF
# DB_PASSWORD=ASDF
# DB_USERNAME=ASDF
# ENVIRONMENT=production
# REDIS_DATABASE=ASDF
# REDIS_PASSWORD=ASDF
# REDIS_PORT=QWER
# REDIS_HOST=XyZ-vigilantus.getup.io
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
