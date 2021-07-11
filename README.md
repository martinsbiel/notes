<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Notes
Projeto Laravel e VueJs que consiste em um sistema de controle de notas/lembretes. Crie, gerencie e exporte suas notas em um sistema muito completo, sinta-se livre para contribuir. :)

## Como usar
- Clone o repositório com `git clone`
- Abra o arquivo `.env` e edite com as credenciais do seu banco de dados
- Execute `composer install` para instalar as dependências relacionadas ao PHP
- Execute `php artisan key:generate` para gerar uma chave para a aplicação
- Execute `php artisan jwt:secret` para gerar a chave que será usada pelo JWT
- Execute `php artisan migrate` para executar as migrações do banco de dados
- Execute `npm install` para instalar as dependências relacionadas ao VueJs
- Execute `npm run dev`
- Execute `npm run watch`
- Execute `php artisan serve` para servir a aplicação
- Pronto, agora é só acessar pelo seu navegador

## Aviso
Existe um problema conhecido de acordo com a [issue #1](https://github.com/martinsbiel/notes/issues/1) que requer ao usuário fazer logout e login novamente após se registrar para que o token JWT seja gerado. O conserto está a caminho. :D
