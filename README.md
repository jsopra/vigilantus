# Vigilantus

[![Build Status](http://phpci.perspectiva.in/build-status/image/1)](http://phpci.perspectiva.in/build-status/view/1)

## Softwares necessários

* Apache 2
* PHP 5.6 (com as extensões que o Composer irá exigir quando rodar)
* Composer
* Postgres 9.4 (com a extensão Postgis)
* Redis

## Variáveis de Ambiente

Copie o arquivo `.env.example` para `.env` e preencha os valores marcados com
`YOUR_...` utilizando as credenciais obtidas nos serviços correspondentes
(SMTP, redes sociais, AWS, etc.). Essas chaves são privadas e devem ser
armazenadas apenas em um arquivo `.env` local, que **não** é versionado.

Se alguma credencial anterior estiver exposta, revogue ou rotacione-a antes de
utilizar a aplicação novamente.

O arquivo `.env` já está listado no `.gitignore` para evitar que seja enviado ao
repositório.

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

Para fazer deploy, logue-se via SSH no servidor na amazon, vá até /var/www/ e dê um git pull (branch master)

## Configurando o ambiente de testes

Consulte as instruções completas no [README.md](tests/README.md) do diretório `tests`.