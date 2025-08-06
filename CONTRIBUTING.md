Manual de contribuição
======================

## Manual do Programador

### Para clonar o projeto:

1. Acesse a página do repositório no GitHub e clique em **Fork**.
2. No seu fork, clique em **Code** e copie a URL.
3. No terminal, execute `git clone URL` e entre no diretório.
4. Adicione o repositório original como remoto `upstream`:

   ```
   git remote add upstream https://github.com/perspectivain/vigilantus.git
   ```

5. Siga as instruções do [README.md](README.md) para configurar o ambiente de desenvolvimento.

### Para programar:

1. Certifique-se de estar com o `master` atualizado.
2. Atualize seu repositório local:

   ```
   git fetch upstream
   git checkout master
   ```

3. Crie um branch a partir do `upstream/master`:

   ```
   git checkout -b nome-do-branch upstream/master
   ```

4. Escreva testes para seu código.
5. Desenvolva e execute todos os testes.
6. Faça commits com mensagens claras.
7. Mantenha seu branch sincronizado:

   ```
   git pull --rebase upstream master
   ```

8. Envie seu branch para o fork:

   ```
   git push -u origin nome-do-branch
   ```

### Para enviar o seu código:

1. Abra seu fork no GitHub e clique em **Compare & pull request**.
2. Preencha título e descrição informativos.
3. Aguarde a revisão. Responda aos comentários e faça ajustes quando solicitado.
4. Após o merge, limpe o ambiente local:

   ```
   git checkout master
   git fetch upstream
   git branch -d nome-do-branch
   git push origin --delete nome-do-branch
   ```

## Manual do Supervisor

### Para adicionar um novo membro ao projeto:

1. Acesse a organização no GitHub.
2. Nas configurações de **Teams**, inclua o novo membro no time adequado.

### Para revisar um Pull Request:

1. Abra o PR e use a aba **Files changed** para verificar as alterações.
2. Utilize comentários ou **Request changes** para sugerir melhorias.
3. Quando aprovado, clique em **Approve** e **Merge pull request**.
