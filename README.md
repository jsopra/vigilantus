# Vigilantus

## Softwares necessários

* Apache 2
* PHP 5.6 (com as extensões que o Composer irá exigir quando rodar)
* Composer
* Postgres 9.4 (com a extensão Postgis)
* Redis

## Configurando o ambiente de desenvolvimento

Adicione um novo virtual host ao Apache. As configurações do apache geralmente
se encontram no arquivo `/etc/apache2/httpd.conf`.

```apache
<VirtualHost vigilantus.dev>
    ServerName vigilantus.dev
    DocumentRoot /var/www/vigilantus/web
</VirtualHost>
```

Para que o host `vigilantus.dev` funcione, adicione a seguinte linha ao seu
arquivo `/etc/hosts`:

```
127.0.0.1 vigilantus.dev vigilantus.test.dev api.vigilantus.dev
```

**Importante**: para aplicar as configurações acima, reinicie o Apache:

```bash
sudo service apache2 reload # recarrega o Apache
```

Por fim, rode `./bin/setup` para preparar o ambiente. Ele alertará sobre
quaisquer passos adicionais necessários.

## Configurando o ambiente de produção

Instale e configure o RHC, seguindo a [introdução ao deploy da Getup](https://getupcloud.com/blog/deploy-e-rollback).

Para fazer o deploy manualmente, siga os seguintes passos:

```bash
git clone ssh://536a900a99fc77c093000257@vigilantus-vigilantus.getup.io/~/git/vigilantus.git/ vigilantus-deploy
git git remote add upstream git@git.perspectiva.in:perspectiva/vigilantus.git
git pull upstream master
git push
```

## Configurando o ambiente de testes

Consulte as instruções completas no [README.md](tests/README.md) do diretório `tests`.
