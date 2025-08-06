# Vigilantus

[![Build Status](http://phpci.perspectiva.in/build-status/image/1)](http://phpci.perspectiva.in/build-status/view/1)

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

## Ambiente com Docker

O projeto possui um `Dockerfile` e um `docker-compose.yml` para facilitar o setup.

1. Copie o arquivo `.env.example` para `.env` e ajuste as variáveis conforme o ambiente.
2. Ajuste os parâmetros regionais em `config/redirects.json`. Um exemplo está disponível em `config/redirects.example.json`.
3. Inicie os containers:

```bash
docker-compose up --build
```

A aplicação estará disponível em `http://localhost:8080`.

### Sobrescrevendo configurações

Os redirecionamentos de cidades são carregados de `config/redirects.json`. Para
alterar os valores, edite este arquivo ou monte outro arquivo de configuração no
compose:

```yaml
volumes:
  - ./meus-redirects.json:/var/www/html/config/redirects.json:ro
```

Assim, parâmetros regionais não ficam hardcoded no código.

## Configurando o ambiente de produção

Para fazer deploy, logue-se via SSH no servidor na amazon, vá até /var/www/ e dê um git pull (branch master)

## Configurando o ambiente de testes

Consulte as instruções completas no [README.md](tests/README.md) do diretório `tests`.