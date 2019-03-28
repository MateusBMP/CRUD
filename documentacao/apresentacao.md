# Classe CRUD

## Apresentacao

CRUD em PHP puro, estruturado em forma de classes. Sua estrutura permite rapida implementacao em diferentes aplicacoes PHP, apenas exigindo o conhecimento da documentacao de suas funcoes publicas, ou seja, funcionalidades. Sua documentacao foi inteiramente escrita usando [Markdown](https://markdownguide.org).

O projeto possui testes simples, usando o PHPUnit, que podem ser encontrados no diretorio _\\src\\tests\\_ e foi desenvolvido em Windows 10. Para uso simplificado, ou seja, apenas das classes principais, leve em consideracao apenas a versao do PHP presente nos requisitos de sistema.

-----

## Executando o sistema

Usando o [XAMPP](https://www.apachefriends.org) e posicionando a pasta _\\src\\src\\_ do projeto na pasta htdocs, pode-se acessar a pagina inicial pelo endereco:

- <http://localhost/crud/views/home.php>

Como o projeto e baseado em uma classe e em testes de suas funcoes, sua pagina inicial serve apenas de interacao para todo o projeto. Ou seja, a pagina inicial independe ao bom funcionamento da aplicacao.

Para executar os testes, execute os seguintes comandos via terminal (Windows) no diretorio _\\src\\_ do projeto:

- ``$ .\test.bat``

-----

## Configurando o sistema

O projeto foi estruturado em um diretorio especifico na raiz do Windows e, por isso, recomenda-se, principalmente se utilizando o [FreeFileSync](https://freefilesync.org), sua criacao no diretorio _C:\\dev\\crud\\_. Isso serve para uso completo do projeto pois, se usando somente as classes principais do mesmo, apenas leve em consideracao a versao do PHP exigida nos requisitos de sistema e basta usar o diretorio _\\src\\src\\class\\_ na raiz do projeto.

Parte dos diretorios podem nao ser criados normalmente, por isso, se necessario, crie os seguintes diretorios, seguindo a raiz do projeto:

- _\\src\\src\\inputs\\_
- _\\src\\src\\outputs\\_

Arquivos executaveis nao podem ser enviados para algumas pastas ou, por questoes de seguranca, nao sao compartilhados, por isso, ao abrir o diretorio do projeto pelo terminal, deve-se executar os seguintes comandos:

- ``$ cd src\``
- ``$ composer update``

Se necessario, use o comando ``composer install`` no lugar do comando ``composer update``. Esses comandos inserem as bibliotecas minimas a boa implementacao do projeto, como o [PHPUnit](https://phpunit.de), no diretorio _\\src\\_ seguindo a partir da raiz do projeto.

Para executar testes de forma facil, ou seja, usando o comando ``.\test.bat``, crie, se necessario, este mesmo arquivo no diretorio _\\src\\_, contendo as seguintes linhas de codigo:

> ``vendor/bin/phpunit``

O FreeFileSync facilita o processo de sincronizacao do projeto com suas diferentes pastas, como no diretorio _\\htdocs\\_ do XAMPP. Para seu bom funcionamento, se necessario, deve-se criar as seguintes pastas:

- _\\backup\\_ dentro do diretorio do projeto
- _\\htdocs\\crud\\_ dentro do diretorio do XAMPP