# Classe CRUD

## Apresentacao

CRUD em PHP puro, estruturado em forma de classes. Sua estrutura permite rapida implementacao em diferentes aplicacoes PHP, apenas exigindo o conhecimento da documentacao de suas funcoes publicas, ou seja, funcionalidades. Sua documentacao foi inteiramente escrita usando [Markdown](https://markdownguide.org).

O projeto possui testes simples, usando o PHPUnit, que podem ser encontrados no diretorio _\\src\\tests\\_. Para uso simplificado, ou seja, apenas das classes principais, leve em consideracao apenas a versao do PHP presente nos requisitos de sistema.

-----

## Executando o sistema

Usando o [XAMPP](https://www.apachefriends.org) e posicionando a pasta _\\src\\src\\_ do projeto na pasta htdocs, pode-se acessar a pagina inicial pelo endereco:

- <http://localhost/crud/views/home.php>

Como o projeto e baseado em uma classe e em testes de suas funcoes, sua pagina inicial serve apenas de interacao para todo o projeto. Ou seja, a pagina inicial independe ao bom funcionamento da aplicacao.

-----

## Configurando o sistema

O projeto foi estruturado

Arquivos executaveis nao podem ser enviados para algumas pastas ou, por questoes de seguranca, nao sao compartilhados, por isso, ao abrir a pasta do projeto pelo terminal, deve-se executar os seguintes comandos:

- ``$ cd src\``
- ``$ composer update``

Se necessario, use o comando ``composer install`` no lugar do comando ``composer update``. Esses comandos inserem as bibliotecas minimas a boa implementacao do projeto, como o [PHPUnit](https://phpunit.de).

Para executar testes de forma facil, ou seja, usando o comando ``.\test.bat``, crie, se necessario, este mesmo arquivo no diretorio _\\src\\_, contendo as seguintes linhas de codigo:

> ``vendor/bin/phpunit``

O [FreeFileSync](https://freefilesync.org) facilita o processo de sincronizacao do projeto com suas diferentes pastas, como no diretorio _\\htdocs\\_ do XAMPP. Para seu bom funcionamento, se necessario, deve-se criar as seguintes pastas:

- _\\backup\\_ dentro do diretorio do projeto
- _\\htdocs\\crud\\_ dentro do diretorio do XAMPP