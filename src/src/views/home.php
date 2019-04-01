<?php

echo "START<br><br>";

require_once(__DIR__.'\..\class\crud.php');
use Crud\Crud;

$crud = new Crud;
$nome = "tabela_teste";
$parametros = [
    ["id", "inteiro", "6", ["chave primaria", "nao-nulo", "auto-incremento"]],
    ["nome", "texto", "50", ["nao-nulo"]],
    ["email", "texto", "30"]
];
$valores = ["pessoa qualquer", "p_qualquer@email.com"];
$campos = ["nome", "email"];
$valores_update = [
    ["email", "pessoa_qualquer@email.com"]
];
$regras = [
    ["nome", "igual", "pessoa qualquer"]
];
$colunas = ["nome", "email"];

if($crud->iniciar('mysql', 'test', '127.0.0.1', 'localhost', '')) {
    echo "conectou<br>";
}
if($crud->criar($nome, $parametros)) {
    echo "criou<br>";
}
if($crud->inserir($nome, $valores, $campos)) {
    echo "inseriu<br>";
}
if($crud->atualizar($nome, $valores_update, $regras)) {
    echo "atualizou<br>";
}
if($crud->listar($nome, $colunas)) {
    echo "listou<br>";
}
if($crud->dropar($nome)) {
    echo "dropou<br>";
}
if($crud->finalizar()) {
    echo "desconectou<br>";
}

echo "<br>FINISH";
