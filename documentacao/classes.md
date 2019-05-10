# Classe CRUD

## Crud

A seguinte classe foi implementada pensando em divesos tipos de bancos de dados, mas nem todas as suas funcionalidades agem igualmente entre tipos de bancos de dados diferentes. Mesmo sendo pensado para funcionar igualmente entre todos os bancos, deve-se levar em consideracao os tipos de entrada que os bancos de dados recebem de forma diferente. Assim, caso algum erro seja encontrado, deve-se reparar se a funcao possui alguma excessao para algum banco em especifico.

De forma a auxiliar sua aplicacao em outros projetos, seu namespace foi definido como:

- **Crud**

Os tipos de banco de dados suportados sao:

- **mysql**
- **pgsql**

-----
-----

### Variaveis globais

- **banco**: Nome do banco de dados
- **servidor**: Endereco do banco de dados
- **usuario**: Nome do usuario do banco
- **conexao**: Dados de conexao do banco de dados apos efetuada a conexao

-----

### Funcoes publicas

#### iniciar

Cria parametros basicos da classe

> ``iniciar ( string $tipo_banco, string $nome_banco, string $servidor, string $usuario, string $senha [, string $porta ] ) : bool``

**iniciar** e a primeira funcao a ser executada pela classe. Ela escreve as variaveis globais da classe, como o tipo de banco de dados, o endereco do banco, nome de usuario e senha, conecta ao banco de dados e seleciona o banco a ser utilizado.

- **tipo_banco**
  - String com o nome do tipo de banco de dados a ser usado.
    - Tipos de banco de dados suportados: **myslq**, **pgsql**.
- **nome_banco**
  - String com nome do banco de dados a ser usado.
- **servidor**
  - String com o endereco do servidor do banco de dados.
- **usuario**
  - String com o usuario do banco de dados.
- **senha**
  - String com a senha do usuario do banco de dados.
- **porta**
  - String com porta de acesso.

A funcao retorna um booleano, sendo **true** para funcao executada normalmente e **false** caso a conexao nao seja efetuada, o banco selecionado nao seja encontrado ou o tipo de banco sao seja permitido.

-----

#### finalizar

Finaliza uso da classe.

> ``finalizar ( ) : bool``

**finalizar** finaliza o uso da classe, desconectando do banco de dados.

A funcao retorna um booleano, sendo **true** para execucao completa da mesma e **false** caso nao seja possivel desconectar do banco de dados.

-----

#### criar

Cria uma nova tabela no banco de dados.

> ``criar ( string $nome, array $parametros ) : bool``

**criar** cria uma nova tabela no banco de dados, com o respectivo nome passado na chamada de funcao e cada uma de suas colunas. A estrutura dos parametros, ou colunas, e bastante complexo, guardando o nome da coluna, seu tipo, tamanho e parametros adicionais, como ser nao-nulo.

- **nome**
  - String com o nome da tabela a ser criada
- **parametros**
  - Array de arrays com nome dos campos e suas respectivas informacoes, seguindo a seguinte estrutura: ``string $nome_do_campo, string $tipo_do_campo [, string $tamanho_do_campo, array $parametros_adicionais ]``.
    - Tipos de campos suportados: **inteiro**, **texto**, **string**, **data**
    - Parametros adicionais suportados: **nao-nulo**, **chave primaria**, **auto-incremento**

A funcao retorna um booleano, sendo **true** se a funcao for corretamente executada e **false** se o comando _CREATE_ nao for corretamente executado e a tabela nao for criada.

- _Observacao geral_:
  - Tipos de campos e parametros adicionais tambem podem ser passados pelo seu nome literal. Desta forma, o interpretador ignorara a conversao e escrevera o nome literal.
  - O retorno da funcao, em PHP, [gettype](https://www.php.net/manual/pt_BR/function.gettype.php) pode funcionar como tipo de campo de tabela em alguns bancos de dados e em alguns tipos especificos. Recomenda-se o uso com cautela.

- _Observacao para pgsql_:
  - Ao criar um campo, nao e necessario passar o tamanho de seu tipo e, certas vezes, nem mesmo deve ser passado. Por isso, para que o comando de criacao funcione corretamente nesse caso, passe dentro do array, no campo que recebe o tamanho do campo, uma string vazia.
  - Tipos de campos extras suportados: **booleano**, **double**.
  - Os tipos de campos **inteiro** e **integer** sao criados como **BIGINT** por questoes de seguranca contra estouro de tamanho no banco. Isso ocorre pois o tipo inteiro, em PHP, possui um tamanho maior que o tipo _integer_, seu aparente correspondente, no banco de dados.

-----

#### dropar

Apaga uma tabela do banco de dados.

> ``dropar ( string $nome ) : bool``

**dropar** executa o comando _DROP_ no banco de dados, deletando uma tabela. O nome da tabela e informado na string de entrada do comando.

- **nome**
  - String com nome da tabela a ser apagada

A funcao retorna um booleano, sendo **true** para a completa execucao da funcao e **false** caso o comando _DROP_ nao for corretamente executado e a tabela nao for apagada.

-----

#### listar

Lista registros de uma tabela.

> ``listar ( string $nome [, array $colunas, array $regras, string $separador ] ) : mixed``

**listar** executa o comando _SELECT_ no banco de dados, recebendo, obrigatoriamente, uma string com o nome da tabela. Opcionalmente, pode-se receber um array com os campos a serem listados, um array com as regras de selecao e uma string com o operador entre regras. Se nao estabelecidos os campos, todos sao selecionados, como tambem se nao selecionadas as regras, nenhuma sera passada.

- **nome**
  - String com nome da tabela a ser listada
- **colunas**
  - Array com nome das colunas a serem listadas.
- **regras**
  - Array de arrays de regras de filtro de listagem. Sua estrutura consistem em: ``[ string $nome_do_campo, string $operacao, string $valor_do_campo]``.
- **separador**
  - String com tipo de operador para regras de filto de listagem.
    - Tipos de operadores suportados: **e**, **ou**.

A funcao retorna um array com arrays de linhas do banco de dados. Caso haja algum erro, retorna **false**.

- _Observacao geral_:
  - Operadores tambem podem ser passados pelo seu nome literal. Desta forma, o interpretador ignorara a conversao e escrevera o nome literal.

- _Observacao para pgsql_:
  - Caso deseje chamar algum comando mais especifico nas colunas a serem listadas, e possivel fazer o mesmo apenas mudando o campo da coluna para o comando desejado. Por exemplo:
    - ``SELECT distinct(nome), cpf, idade FROM pessoas`` == ``listar('pessoas', ['distinct(nome)', 'cpf', 'idade'])``

-----

#### inserir

Insere dados em uma tabela.

> ``inserir ( string $nome, array $valores [, array $campos, string $retorno ] ) : mixed``

**inserir** executa o comando _INSERT_ no banco de dados, inserindo os dados informados em um banco de dados tambem informado. A funcao recebe, obrigatoriamente, uma string com o nome da tabela onde os dados serao inseridos e um array de valores a serem inseridos. De forma opcional, pode-se receber um array com o nome das colunas especificas que serao recebidos os dados e uma string com nome do campo a ser retornado apos a insercao.

- **nome**
  - String com nome da tabela a ser usada
- **valores**
  - Array de strings com valores a serem inseridos na tabela
- **campos**
  - Array de strings com nome dos campos que serao inseridos
- **retorno**
  - String com nome do valor que deve ser retornado apos insercao

A funcao retorna um booleano, sendo **true** para completa e correta execucao da funcao e **false** caso os valores informados nao sejam capazes de serem inseridos na tabela ou, se solicitado o retorno, retorna o campo solicitado.

- _Observacao para mysql_:
  - O parametro de entrada **retorno**, se passado, sempre retornara o ID da linha inserida, ou seja, basta passar qualquer coisa diferente de **NULL** e a funcao requisitara o retorno.
  - A operacao de retorno retorna um inteiro com o id da linha inserida ou uma string caso o tamanho do inteiro retornado seja maior que o inteiro maior possivel.

-----

#### atualizar

Atualiza registros em uma tabela.

> ``atualizar ( string $nome, array $valores [, array $regras, string $separador ] ) : bool``

**atualizar** executa o comando _UPDATE_ em uma tabela. A funcao recebe, obrigatoriamente, uma string com o nome da tabela a ser usada e um array de array de campos e valores a serem atualizados. Opcionalmente, recebe um array de strings de regras de selecao de linhas a serem atualizadas e uma string com operador entre regras.

- **nome**
  - String com nome da tabela a ser usada
- **valores**
  - Array de arrays de strings com nome dos campos e seus respectivos valores. Segue a seguinte estrutura os arrays: ``[ string $nome_do_campo, string $valor_do_campo ]``
- **regras**
  - Array de arrays de regras de filtro de listagem. Sua estrutura consistem em: ``[ string $nome_do_campo, string $operacao, string $valor_do_campo]``.
    - Tipos de operacoes suportadas: **igual**, **maior**, **menor**, **maior ou igual**, **menor ou igual**, **diferente**.
- **separador**
  - String com tipo de operador para regras de filto de listagem.
    - Tipos de operadores suportados: **e**, **ou**.

A funcao retorna um booleano, sendo **true** para o completo funcionamento da funcao e **false** se o comando _UPDATE_ nao for corretamente executado e os dados nao forem inseridos na tabela.

-----

#### deletar

Deleta dados da tabela.

> ``deletar ( string $nome, array $regras ) : bool``

**deletar** executa o comando _DELETE_ na tabela, seguindo as regras estabelecidas. Ela recebe, obrigatoriamente, uma string com o nome da tabela a ser usada e um array de regras de filtro de selecao de linhas.

- **nome**
  - String com nome da tabela a ser usada
- **regras**
  - Array de arrays de regras de filtro de listagem. Sua estrutura consistem em: ``[ string $nome_do_campo, string $operacao, string $valor_do_campo]``.

A funcao retorna um booleano, sendo **true** para a completa execucao da funcao e **false** caso o comando _DELETE_ nao for corretamente executado e os dados nao forem deletados.