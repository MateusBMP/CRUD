<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../vendor/autoload.php';
use Crud\Crud;

class UnitTest extends TestCase {
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
        public function teste_iniciar_finalizar() {
            $crud = new Crud;
            $retorno = $crud->iniciar($this->tipo_banco, $this->nome_banco, $this->servidor, $this->usuario, $this->senha);
            $this->assertTrue($retorno);
            $retorno = $crud->finalizar();
            $this->assertTrue($retorno);
        }
        public function teste_criar() {
            $nome = "tabela_teste";
            $parametros = [
                ["id", "inteiro", "6", ["chave primaria", "nao-nulo", "auto-incremento"]],
                ["nome", "texto", "50", ["nao-nulo"]],
                ["email", "texto", "30"]
            ];
            $crud = $this->iniciaCrud();
            $retorno = $crud->criar($nome, $parametros);
            $this->assertTrue($retorno);
            $this->finalizaCrud($crud);
        }
        public function teste_inserir() {
            $nome = "tabela_teste";
            $valores = ["pessoa qualquer", "p_qualquer@email.com"];
            $campos = ["nome", "email"];
            $crud = $this->iniciaCrud();
            $retorno = $crud->inserir($nome, $valores, $campos);
            $this->assertTrue($retorno);
            $this->finalizaCrud($crud);
        }
        public function teste_atualizar() {
            $nome = "tabela_teste";
            $valores = [
                ["email", "pessoa_qualquer@email.com"]
            ];
            $regras = [
                ["nome", "igual", "pessoa qualquer"]
            ];
            $crud = $this->iniciaCrud();
            $retorno = $crud->atualizar($nome, $valores, $regras);
            $this->assertTrue($retorno);
            $this->finalizaCrud($crud);
        }
        public function teste_listar() {
            $nome = "tabela_teste";
            $colunas = ["nome", "email"];
            $saida_esperada = [
                ['0' => "pessoa qualquer", '1' => "pessoa_qualquer@email.com"]
            ];
            $crud = $this->iniciaCrud();
            $retorno = $crud->listar($nome, $colunas);
            $this->assertEquals($saida_esperada, $retorno);
            $this->finalizaCrud($crud);
        }
        public function teste_dropar() {
            $nome =  "tabela_teste";
            $crud = $this->iniciaCrud();
            $retorno = $crud->dropar($nome);
            $this->assertTrue($retorno);
            $this->finalizaCrud($crud);
        }
}
