# Variáveis de Ambiente

Este projeto utiliza um arquivo `.env` para armazenar credenciais sensíveis e
configurações específicas de cada ambiente. Essas informações **não** devem ser
versionadas.

## Passo a passo

1. Copie o arquivo de exemplo:
   ```bash
   cp .env.example .env
   ```
2. Substitua todos os valores que começam com `YOUR_` pelas chaves obtidas nos
   respectivos serviços (SMTP, redes sociais, AWS, etc.).
3. Guarde o arquivo `.env` apenas em seu ambiente local ou nas ferramentas de
   deploy/CI que precisarem dele.

Se alguma credencial contida anteriormente neste repositório foi exposta,
certifique-se de **revogá-la** ou **rotacioná-la** no provedor antes de usar a
aplicação novamente.

O arquivo `.env` já está listado no `.gitignore` para evitar que seja enviado ao
repositório.
