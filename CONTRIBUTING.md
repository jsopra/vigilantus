Manual de contribuição
======================

## Manual do Programador

Para clonar o projeto:

1. Acesse a URL do projeto no BitBucket
2. Clique no botão `Fork` e confirme.
3. Clique em `Clonar` nesse fork que foi criado.
4. Copie o comando
5. Execute o comando na sua máquina, no diretório desejado
6. Entre no diretório (`cd`)
7. Execute o comando `git remote add upstream https://SeuUsuario@bitbucket.org/perspectivain/vigilantus.git`
6. Siga as instruções do arquivo [README.md](README.md) para configurar o seu ambiente de desenvolvimento e testes

Para programar e enviar seu código:

1. Sempre, antes de começar algo novo, certifique-se de estar no branch `master`
2. Rode `git fetch upstream` para atualizar o seu `master` com as últimas alterações do repositório quente
3. Crie um branch para o seu código com um nome sucinto que descreva a atividade, com `git checkout upstream/master` (irá para o branch master do repositório quente) e `git checkout -b nome-do-seu-branch` (seu novo branch).
4. Escreva os testes!
5. Programe e faça os testes passarem!
6. Certifique-se de que todos os testes rodam (não só os seus!)
7. Commite suas mudanças com uma mensagem breve que descreva de maneira clara o que foi alterado.
8. Novamente veja se está tudo atualizado com o repositório quente com `git pull upstream master`
9. Envie o seu branco pro seu repositório forkado `git push -u origin nome-do-seu-branch`
10. Abra uma `Pull Request` no BitBucket para nos enviar o seu branch.
11. Alguém vai rever o seu código e pedir para corrigir ou mesclar com o master.
12. Caso tudo esteja certo e ele seja mesclado no master, rode os seguintes comandos para limpar o seu ambiente:

```
git checkout upstream/master
git fetch upstream
git branch -d nome-do-seu-branch
git push origin --delete nome-do-seu-branch
```

## Manual do Supervisor

Para adicionar um novo membro de projeto:

1. Vá para a página da equipe `Perspectiva`
2. Clique em `Manage team`
3. Clique em `Grupos`
4. Adicione o novo membro ao grupo `NomeDoProjeto Developers`.

Para adicionar um novo projeto:

1. Vá para a página da equipe `Perspectiva`
2. Clique em `Manage team`
3. Clique em `Grupos`
4. Crie um grupo `NomeDoProjeto Developers`
5. Assegure-se de **remover todas as permissões** (desmarque as checkboxes e marque `não`).
6. Adicione os membros do grupo.
7. Na página do projeto, clique no ícone da roda dentada
8. Clique em `Gerenciamento de Acesso`.
9. Em `Grupos`, adicione o grupo que você criou, com permissão somente `read` (ler).

## Migrando do GitHub para o BitBucket

1. Siga as instruções do manual do programador até a etapa `4`.
2. Execute os seguintes comandos, lembrando de trocar `URL_AQUI` pela URL obtida do seu fork no BitBucket

```bash
git remote remove origin
git remote add origin URL_AQUI
git push -u origin master
```