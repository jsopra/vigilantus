# Testes da Aplicação

Os testes do diretório `codeception` foram desenvolvidos com o
[Framework de testes Codeception ](http://codeception.com/).

# Tipos de testes

* **Acceptance** (aceitação): são testes que simulam o comportamento do usuário
  navegando nas telas do sistema. Utilizado em conjunto com o Selenium, e permite
  testar o comportamento do JavaScript.
* **Functional** (funcional): simula requisições/respostas aos controllers web
  do sistema. Ao invés de clicar e preencher campos pelo nome que aparece na tela,
  ele usa os nomes e IDs dos elementos. É mais rápido, mas não permite testar o
  JavaScript.
* **Unit** (unitário): testa o comportamento da menor unidade de código: uma classe.

Como o Yii2 utiliza o pattern ActiveRecord, todos os nossos testes são testes de
integração, pois raramente um modelo funcionará desacoplado do banco de dados.

# Organização

```yaml
codeception: # diretório dos testes
  _output # logs, screenshots e relatórios de cobertura de código
  _pages #classes que representam páginas do sistema
  acceptance # testes de aceitação (com Selenium)
  bin # executáveis para o ambiente de testes
  config # configurações do ambiente de testes
  fixtures # não é utilizado
  functional # testes funcionais (sem Selenium/JavaScript), não é utilizado
  templates # não é utilizado
  unit #testes unitários
factories # factories escritas para a biblioteca Phactory
```

# Configuração

Crie a base de testes conforme as configurações `VIGILANTUS_TEST_DB_DSN_HOST`,
`VIGILANTUS_TEST_DB_DSN_DBNAME`, `VIGILANTUS_TEST_DB_USERNAME` e
`VIGILANTUS_TEST_DB_PASSWORD`.

# Execução

1. Rode o script que atualiza as migrations e roda os testes:

```bash
php ./codeception/bin/yii migrate
../vendor/bin/codecept run unit
```

2. Para rodar os testes de aceitação, você precisa ter o selenium instalado e
rodando em outra janela do terminal:

```bash
selenium-server -p 4444
```

Por favor consulte o [tutorial do Codeception](http://codeception.com/docs/01-Introduction)
para mais detalhes sobre como escrever e rodar os testes unitários, funcionais
e de aceitação.
