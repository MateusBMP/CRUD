# Classe CRUD

[![versao](https://img.shields.io/github/tag/mateusbmp/crud.svg?color=light&label=versao)](https://github.com/MateusBMP/CRUD) [![PHP minimo](https://img.shields.io/badge/php-%5E7.2-blue.svg)](http://www.php.net)

## Apresentacao

CRUD em PHP puro, estruturado em forma de classes. Sua estrutura permite rapida implementacao em diferentes aplicacoes PHP, apenas exigindo o conhecimento da documentacao de suas funcoes publicas, ou seja, funcionalidades. Sua documentacao foi inteiramente escrita usando [Markdown](https://markdownguide.org).

Para mais detalhes sobre a documentacao do projeto, acesse o diretorio _\\documentacao\\_ na raiz do mesmo.

## Instalacao

Como a biblioteca foi desenvolvida a partir do Composer, pode-se usa-la ao requerir o seguinte no arquivo _composer.json_ no campo _require_:

> "mateusbmp/crud": "^1.0"

Desta forma, para chamar a classe no projeto importe a classe ``Crud\Crud``, ou seja:

> ``use Crud\Crud``

O mesmo usa diversos comandos de banco da dados e, por isso, necessida das seguintes bibliotecas:

- mysqli
- pdo_mysql
- pgsql
- pdo_pgsql

Algumas podem ja pertencerem nativamente ao PHP e, desta forma, basta descomentar as linhas com o comando _extension_ presentes no _php.ini_. Para as nao existentes, baixe o pacote necessario e siga a instalacao dos mesmo no php como qualquer outro pacote, adicionando os arquivos no diretorio solicitado e adicionando-o ao arquivo _php.ini_.

## Desenvolvedores

- [Mateus Pereira](https://github.com/MateusBMP)
- [Jadde Freitas](https://github.com/Jaddefreitas)