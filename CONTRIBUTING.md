Manual de contribuição
======================

Ao participar deste projeto, você concorda em seguir o nosso [Código de Conduta](CODE_OF_CONDUCT.md).

## Manual do Programador

### Para clonar o projeto:

1. Acesse a URL do projeto no GitHub.
2. Clique no botão `Fork` e confirme.
3. Clone o seu fork: `git clone https://github.com/SeuUsuario/vigilantus.git`.
4. Entre no diretório (`cd`).
5. Adicione o repositório original como upstream: `git remote add upstream https://github.com/perspectivain/vigilantus.git`.
6. Siga as instruções do arquivo [README.md](README.md) para configurar o seu ambiente de desenvolvimento e testes.

### Para programar:

1. Sempre, antes de começar algo novo, certifique-se de estar no branch `master`.
2. Rode `git fetch upstream` para atualizar o seu `master` com as últimas alterações do repositório principal.
3. Crie um branch para o seu código com `git checkout -b nome-do-seu-branch`.
4. Escreva os testes!
5. Programe e faça os testes passarem!
6. Certifique-se de que todos os testes rodam (não só os seus!).
7. Commite suas mudanças com uma mensagem breve que descreva de maneira clara o que foi alterado.
8. Atualize seu branch com `git pull upstream master`.
9. Envie o seu branch para o seu repositório forkado `git push -u origin nome-do-seu-branch`.

### Para enviar o seu código:

1. Vá para o seu fork do projeto no GitHub.
2. Clique em `New pull request`.
3. Selecione o branch `nome-do-seu-branch` e compare com `perspectivain/vigilantus` `master`.
4. Escreva uma boa e breve descrição.
5. Clique em `Create pull request`.
6. Alguém vai rever o seu código e vai pedir para você corrigir ou vai mesclar com o master.
7. Caso tudo esteja certo e ele seja mesclado no master, rode os seguintes comandos para limpar o seu ambiente de desenvolvimento:

```
git checkout master
git fetch upstream
git branch -d nome-do-seu-branch
git push origin --delete nome-do-seu-branch
```

