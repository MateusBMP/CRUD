<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../vendor/autoload.php';
use Crud\Crud;

class FeatureTest extends TestCase {
    // Variaveis
        private $tipo_banco = 'mysql';
        private $nome_banco = 'test';
        private $servidor = '127.0.0.1';
        private $usuario = 'localhost';
        private $senha = '';

    // Funcoes basicas
        private function iniciaCrud() {
            $crud = new Crud;
            $crud->iniciar($this->tipo_banco, $this->nome_banco, $this->servidor, $this->usuario, $this->senha);
            return $crud;
        }
        private function finalizaCrud($crud) {
            $crud->finalizar();
            return true;
        }

    // Testes
        public function teste_completo() {
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

            $crud = $this->iniciaCrud();
            $this->assertTrue($crud->criar($nome, $parametros));
            $this->assertTrue($crud->inserir($nome, $valores, $campos));
            $this->assertTrue($crud->atualizar($nome, $valores_update, $regras));
            $this->assertEquals([['0' => "1", '1' => "pessoa qualquer", '2' => "pessoa_qualquer@email.com"]], $crud->listar($nome));
            $this->assertTrue($crud->dropar($nome));
            $this->finalizaCrud($crud);
        }
}
