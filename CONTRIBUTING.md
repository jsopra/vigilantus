Manual de contribuição
======================

## Antes de começar
Este projeto está em manutenção mínima e as contribuições podem não receber resposta imediata.
Ao participar, você concorda em seguir o [Código de Conduta](CODE_OF_CONDUCT.md).

## Como clonar o projeto

1. Acesse a página do projeto no GitHub e clique em **Fork** para criar sua cópia.
2. Clone seu fork para sua máquina:
   `git clone https://github.com/seu-usuario/vigilantus.git`
3. Entre no diretório do projeto.
4. Adicione o repositório oficial como remoto:
   `git remote add upstream https://github.com/perspectivain/vigilantus.git`
5. Siga as instruções do [README.md](README.md) para configurar seu ambiente de desenvolvimento e testes.

## Fluxo de trabalho

1. Sempre inicie seu trabalho a partir do branch principal atualizado:
   `git fetch upstream` e `git checkout main` (ou `master`) e `git pull upstream main`.
2. Crie um branch para sua contribuição:
   `git checkout -b minha-funcionalidade`.
3. Escreva os testes.
4. Implemente o código e garanta que todos os testes passam.
5. Faça commits com mensagens claras que descrevam suas mudanças.
6. Sincronize seu branch com o repositório principal:
   `git fetch upstream` e `git rebase upstream/main`.
7. Envie seu branch para o seu fork:
   `git push origin minha-funcionalidade`.

## Enviando seu código

1. No GitHub, abra um Pull Request do seu branch para `perspectivain/vigilantus`.
2. Preencha uma descrição clara explicando suas mudanças e quais testes foram executados.
3. Aguarde a revisão e faça os ajustes solicitados.
4. Após o merge, limpe seu branch local e remoto:

```
git checkout main
git branch -d minha-funcionalidade
git push origin --delete minha-funcionalidade
```
