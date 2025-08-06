# Vigilantus

[![Build Status](http://phpci.perspectiva.in/build-status/image/1)](http://phpci.perspectiva.in/build-status/view/1)

## Descrição

Vigilantus é uma plataforma web de código aberto voltada ao monitoramento de
focos do mosquito *Aedes aegypti* e à gestão de denúncias ambientais. O sistema
centraliza o registro de ocorrências, auxilia na tomada de decisão e permite o
acompanhamento de ações de combate.

### Problema resolvido

O projeto surgiu para apoiar municípios no enfrentamento a doenças
transmitidas pelo mosquito, oferecendo uma forma simples de registrar e
acompanhar inspeções, denúncias e resultados de vistoria.

### Público-alvo

- Cidadãos que desejam denunciar possíveis focos do mosquito;
- Equipes de vigilância em saúde que analisam e tratam as ocorrências;
- Gestores que acompanham indicadores e planejamento das ações.

### Exemplos de uso

- Um morador informa um foco do mosquito por meio do sistema;
- Um agente de saúde registra o andamento da vistoria em campo;
- A gestão municipal acompanha relatórios consolidados de ocorrências.

## Documentação adicional

- Consulte a pasta [`docs/`](docs) para materiais complementares;
- As instruções de testes encontram-se em [`tests/README.md`](tests/README.md);
- Atualmente não há diagrama de arquitetura público no repositório.

## Contribuindo

Contribuições são bem-vindas! Leia o guia de contribuição em
[`CONTRIBUTING.md`](CONTRIBUTING.md) e envie *pull requests* com melhorias.

## Licença

Este projeto é distribuído sob a licença [MIT](LICENSE).

## Créditos

Desenvolvido pela equipe Vigilantus e comunidade de colaboradores.
Principais autores:

- Juliano Baggio di Sopra;
- Alan Willms;
- Juliano Sopra;
- Gabriel;
- Cristian de Oliveira;
- Theylor Fiorentin;
- Gabriel Mocelin.

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

Para fazer deploy, logue-se via SSH no servidor na amazon, vá até /var/www/ e dê um git pull (branch master)

## Configurando o ambiente de testes

Consulte as instruções completas no [README.md](tests/README.md) do diretório `tests`.

## Adicionando novas cidades

Os municípios disponíveis na aplicação são configurados no arquivo
[`config/municipios.yaml`](config/municipios.yaml). Cada chave do arquivo
representa o *slug* usado na URL e contém:

- `nome` e `uf` do município;
- `latitude` e `longitude` para centralizar o mapa;
- `aliases` opcionais que apontam antigos caminhos a serem redirecionados.

Para cadastrar uma nova cidade, acrescente um novo bloco seguindo o formato
dos exemplos existentes e, se necessário, inclua *aliases* para URLs antigas.
Após salvar o arquivo, as rotas passarão a reconhecer automaticamente a nova
configuração.
