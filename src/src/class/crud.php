<?php

namespace Crud;

class Crud {
    // ----- VARIAVEIS GLOBAIS -----
        private $banco = NULL;
        private $servidor = NULL;
        private $usuario = NULL;
        private $conexao = NULL;

    // ----- FUNCOES PUBLICAS -----
        public function iniciar($tipo_banco, $nome_banco, $servidor, $usuario, $senha, $porta = NULL){
            $this->banco = $tipo_banco;
            $conexao = $this->conecta($servidor, $nome_banco, $usuario, $senha, $porta);
            if($conexao == false) {
                $this->banco = NULL;
                return false;
            } else {
                $this->servidor = $servidor;
                $this->usuario = $usuario;
                $this->conexao = $conexao;
                return true;
            }
        }
        public function finalizar() {
            $retorno = $this->desconecta();
            return $retorno;
        }
        public function criar($nome, $parametros) {
            $parametros_string = $this->formataParametros($parametros);
            $retorno = $this->criaTabela($nome, $parametros_string);
            return $retorno;
        }
        public function dropar($nome) {
            $retorno = $this->dropaTabela($nome);
            return $retorno;
        }
        public function listar($nome, $colunas = NULL, $regras = NULL, $separador = NULL){
            if($colunas == NULL) {
                $colunas_string = "*";
            } else {
                $colunas_string = $this->formataColunas($colunas);
            }
            if($regras == NULL) {
                $regras_string = NULL;
            } else {
                if($separador == NULL) {
                    $separador_string = "AND";
                } else {
                    $separador_string = $this->operadores($separador, "logico");
                }
                $regras_string = $this->formataWhereRegras($regras, $separador_string);
            }
            $lista = $this->listaTabela($colunas_string, $nome, $regras_string);
            return $lista;
        }
        public function inserir($nome, $valores, $campos = NULL, $retorno_id = false) {
            if($campos == NULL) {
                $campos_string = "";
            } else {
                $campos_string = $this->formataCampos($campos);
            }
            $valores_string = $this->formataValores($valores);
            $retorno = $this->insereTabela($nome, $campos_string, $valores_string, $retorno_id);
            return $retorno;
        }
        public function atualizar($nome, $valores, $regras = NULL, $separador = NULL){
            if($regras == NULL) {
                $regras_string = NULL;
            } else {
                if($separador == NULL) {
                    $separador_string = "AND";
                } else {
                    $separador_string = $this->operadores($separador, "logico");
                }
                $regras_string = $this->formataWhereRegras($regras, $separador_string);
            }
            $valores_string = $this->formataSetValores($valores);
            $retorno = $this->atualizaTabela($nome, $valores_string, $regras_string);
            return $retorno;
        }
        public function deletar($nome, $regras = NULL){
            if($regras == NULL) {
                $regras_string = NULL;
            } else {
                $regras_string = $this->formataRegras($regras);
            }
            $retorno = $this->deletaTabela($nome, $regras_string);
            return $retorno;
        }

    // ----- FUNCOES PRIVADAS -----
        // Formatadores de entrada para string
            private function formataParametros($parametros) {
                if($this->banco == "mysql") {
                    $string = "";
                    $tamanho_max = count($parametros);
                    $tamanho_atual = 1;
                    foreach($parametros as $linha) {
                        $posicao = 0;
                        foreach($linha as $campo) {
                            if($posicao == 0) {
                                $string .= $campo;
                            } else if($posicao == 1) {
                                $string .= $this->tipos($campo);
                            } else if($posicao == 2) {
                                $string .= "(".$campo.")";
                            } else if($posicao == 3) {
                                $tamanho_max_campo = count($campo);
                                $tamanho_atual_campo = 1;
                                foreach($campo as $p_adicional) {
                                    $string .= $this->parametrosAdicionais($p_adicional);
                                    if($tamanho_atual_campo < $tamanho_max_campo) {
                                        $string .= " ";
                                    }
                                    $tamanho_atual_campo++;
                                }
                            }
                            $string .= " ";
                            $posicao++;
                        }
                        if($tamanho_atual < $tamanho_max) {
                            $string .= ",".PHP_EOL;
                        }
                        $tamanho_atual++;
                    }
                    return $string;
                } else if($this->banco == 'pgsql') {
                    $string = "";
                    $tamanho_max = count($parametros);
                    $tamanho_atual = 1;
                    foreach($parametros as $linha) {
                        $posicao = 0;
                        foreach($linha as $campo) {
                            if($posicao == 0) {
                                $string .= $campo;
                            } else if($posicao == 1) {
                                $string .= $this->tipos($campo);
                            } else if($posicao == 2) {
                                if($campo != '') {
                                    $string .= "(".$campo.")";
                                }
                            } else if($posicao == 3) {
                                $tamanho_max_campo = count($campo);
                                $tamanho_atual_campo = 1;
                                foreach($campo as $p_adicional) {
                                    $string .= $this->parametrosAdicionais($p_adicional);
                                    if($tamanho_atual_campo < $tamanho_max_campo) {
                                        $string .= " ";
                                    }
                                    $tamanho_atual_campo++;
                                }
                            }
                            $string .= " ";
                            $posicao++;
                        }
                        if($tamanho_atual < $tamanho_max) {
                            $string .= ",".PHP_EOL;
                        }
                        $tamanho_atual++;
                    }
                    return $string;
                }
            }
            private function formataColunas($colunas) {
                if($this->banco == "mysql" || $this->banco == 'pgsql') {
                    $string = "";
                    $tamanho_max = count($colunas);
                    $tamanho_atual = 1;
                    foreach($colunas as $coluna) {
                        $string .= $coluna;
                        if($tamanho_atual < $tamanho_max) {
                            $string .= ", ";
                        }
                        $tamanho_atual++;
                    }
                    return $string;
                }
            }
            private function formataWhereRegras($regras, $operador) {
                if($this->banco == "mysql" || $this->banco == 'pgsql') {
                    $string = "";
                    $tamanho_max = count($regras);
                    $tamanho_atual = 1;
                    foreach($regras as $regra) {
                        $posicao = 0;
                        $string .= "(";
                        foreach($regra as $valor) {
                            if($posicao == 0) {
                                $string .= $valor." ";
                            } else if($posicao == 1) {
                                $string .= $this->operadores($valor, "comparacao")." ";
                            } else {
                                $string .= "'".$valor."'";
                            }
                            $posicao++;
                        }
                        $string .= ")";
                        if($tamanho_atual < $tamanho_max) {
                            $string .= " ".$operador." ";
                        }
                        $tamanho_atual++;
                    }
                    return $string;
                }
            }
            private function formataCampos($campos) {
                if($this->banco == "mysql" || $this->banco == 'pgsql') {
                    $string = "";
                    $tamanho_max = count($campos);
                    $tamanho_atual = 1;
                    $string .= "(";
                    foreach($campos as $coluna) {
                        $string .= $coluna;
                        if($tamanho_atual < $tamanho_max) {
                            $string .= ", ";
                        }
                        $tamanho_atual++;
                    }
                    $string .= ")";
                    return $string;
                }
            }
            private function formataValores($valores) {
                if($this->banco == "mysql" || $this->banco == 'pgsql') {
                    $string = "";
                    $tamanho_max = count($valores);
                    $tamanho_atual = 1;
                    $string .= "(";
                    foreach($valores as $valor) {
                        $string .= "'".$valor."'";
                        if($tamanho_atual < $tamanho_max) {
                            $string .= ", ";
                        }
                        $tamanho_atual++;
                    }
                    $string .= ")";
                    return $string;
                }
            }
            private function formataSetValores($valores) {
                if($this->banco == "mysql" || $this->banco == 'pgsql') {
                    $string = "";
                    $tamanho_max = count($valores);
                    $tamanho_atual = 1;
                    foreach($valores as $valor) {
                        $posicao = 0;
                        foreach($valor as $campo) {
                            if($posicao == 0) {
                                $string .= $campo." ";
                            } else {
                                $string .= "= '".$campo."'";
                            }
                            $posicao++;
                        }
                        if($tamanho_atual < $tamanho_max) {
                            $string .= ", ";
                        }
                        $tamanho_atual++;
                    }
                    return $string;
                }
            }

        // Interpretadores de conversao de tipos
            private function tipos($tipo) {
                if($this->banco == "mysql") {
                    if($tipo == "inteiro") {
                        return "INT";
                    } else if($tipo == "texto") {
                        return "TEXT";
                    } else if($tipo == "string") {
                        return "VARCHAR";
                    } else if($tipo == "data") {
                        return "DATE";
                    } else {
                        return $tipo;
                    }
                } else if($this->banco == 'pgsql') {
                    if($tipo == "inteiro" || $tipo == 'integer') {
                        return "BIGINT";
                    } else if($tipo == "texto") {
                        return "TEXT";
                    } else if($tipo == "string") {
                        return "VARCHAR";
                    } else if($tipo == "data") {
                        return "TIMESTAMP";
                    } else if($tipo == 'booleano') {
                        return "BOOLEAN";
                    } else if($tipo == 'double') {
                        return "DOUBLE PRECISION";
                    } else {
                        return $tipo;
                    }
                }
            }
            private function parametrosAdicionais($parametro) {
                if($this->banco == "mysql") {
                    if($parametro == "nao-nulo") {
                        return "NOT NULL";
                    } else if($parametro == "chave primaria") {
                        return "PRIMARY KEY";
                    } else if($parametro == "auto-incremento") {
                        return "AUTO_INCREMENT";
                    } else {
                        return $parametro;
                    }
                } else if($this->banco == 'pgsql') {
                    if($parametro == "nao-nulo") {
                        return "NOT NULL";
                    } else if($parametro == "chave primaria") {
                        return "PRIMARY KEY";
                    } else {
                        return $parametro;
                    }
                }
            }
            private function operadores($operador, $tipo) {
                if($this->banco == "mysql" || $this->banco == 'pgsql') {
                    if($tipo == "logico") {
                        if($operador == "e") {
                            return "AND";
                        } else if($operador == "ou") {
                            return "OR";
                        } else {
                            return $operador;
                        }
                    } else if($tipo == "comparacao") {
                        if($operador == "igual") {
                            return "=";
                        } else if($operador == "maior") {
                            return ">";
                        } else if($operador == "menor") {
                            return "<";
                        } else if($operador == "maior ou igual") {
                            return ">=";
                        } else if($operador == "menor ou igual") {
                            return "<=";
                        } else if($operador == "diferente") {
                            return "<>";
                        } else {
                            return $operador;
                        }
                    }
                }
            }

        // Operacoes de banco de dados
            private function conecta($servidor, $banco, $usuario, $senha, $porta) {
                if($this->banco == 'mysql') {
                    if($porta != NULL) {
                        $servidor .= ':' . $porta;
                    }
                    $conexao = mysqli_connect($servidor, $usuario, $senha);
                    if ($conexao->connect_error) {
                        return false;
                    } else {
                        $retorno = mysqli_select_db($conexao, $banco);
                        if($retorno == true) {
                            return $conexao;
                        } else {
                            return false;
                        }
                    }
                } else if($this->banco == 'pgsql') {
                    $con_string = 'host='.$servidor.' dbname='.$banco.' user='.$usuario.' password='.$senha;
                    if($porta != NULL) {
                        $con_string .= ' port='.$porta;
                    }
                    $conexao = pg_connect($con_string);
                    if(pg_connection_status($conexao) != PGSQL_CONNECTION_OK) {
                        return false;
                    } else {
                        return $conexao;
                    }
                }
                return false;
            }
            private function desconecta() {
                if($this->banco == "mysql") {
                    $retorno = mysqli_close($this->conexao);
                    return $retorno;
                } else if($this->banco == 'pgsql') {
                    $retorno = pg_close($this->conexao);
                    return $retorno;
                }
                return false;
            }
            private function criaTabela($nome, $parametros) {
                if($this->banco == 'mysql') {
                    $comando = "CREATE TABLE ".$nome." (".PHP_EOL . $parametros . PHP_EOL.");";
                    $retorno = mysqli_query($this->conexao, $comando);
                    return $retorno;
                } else if($this->banco == 'pgsql') {
                    $comando = "CREATE TABLE IF NOT EXISTS ".$nome." (".PHP_EOL . $parametros . PHP_EOL.");";
                    $retorno = pg_query($this->conexao, $comando);
                    if(!$retorno) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
            private function dropaTabela($nome) {
                if($this->banco == 'mysql') {
                    $comando = "DROP TABLE ".$nome;
                    $retorno = mysqli_query($this->conexao, $comando);
                    return $retorno;
                } else if($this->banco == 'pgsql') {
                    $comando = "DROP TABLE IF EXISTS ".$nome;
                    $retorno = pg_query($this->conexao, $comando);
                    if(!$retorno) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
            private function listaTabela($colunas, $nome, $regras) {
                if($this->banco == "mysql") {
                    $comando = "SELECT ".$colunas." FROM `".$nome."`";
                    if($regras != NULL) {
                        $comando .= PHP_EOL."WHERE ".$regras.";";
                    }
                    $retorno = mysqli_query($this->conexao, $comando);
                    if($retorno == false) {
                        return false;
                    } else {
                        $retorno = mysqli_fetch_all($retorno);
                        return $retorno;
                    }
                } else if($this->banco == 'pgsql') {
                    $comando = "SELECT ".$colunas." FROM ".$nome."";
                    if($regras != NULL) {
                        $comando .= PHP_EOL."WHERE ".$regras.";";
                    }
                    $retorno = pg_query($this->conexao, $comando);
                    if($retorno == false) {
                        return false;
                    } else {
                        $retorno = pg_fetch_all($retorno);
                        return $retorno;
                    }
                }
            }
            private function insereTabela($nome, $campos, $valores, $retorno_id = false){
                if($this->banco == 'mysql') {
                    $comando = "INSERT INTO ".$nome." ".$campos . PHP_EOL."VALUES ".$valores.";";
                    if(mysqli_query($this->conexao, $comando)) {
                        if($retorno_id) {
                            return mysqli_insert_id($this->conexao);
                        } else {
                            return true;
                        }
                    } else {
                        return false;
                    }
                } else if($this->banco == 'pgsql') {
                    $comando = "INSERT INTO ".$nome." ".$campos . PHP_EOL."VALUES ".$valores.";";
                    $retorno = pg_query($this->conexao, $comando);
                    if(!$retorno) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
            private function atualizaTabela($nome, $valores, $regras) {
                if($this->banco == 'mysql') {
                    $comando = "UPDATE ".$nome . PHP_EOL."SET ".$valores;
                    if($regras != NULL) {
                        $comando .= PHP_EOL."WHERE ".$regras;
                    }
                    $retorno = mysqli_query($this->conexao, $comando);
                    return $retorno;
                } else if($this->banco == 'pgsql') {
                    $comando = "UPDATE ".$nome . PHP_EOL."SET ".$valores;
                    if($regras != NULL) {
                        $comando .= PHP_EOL."WHERE ".$regras;
                    }
                    $retorno = pg_query($this->conexao, $comando);
                    if(!$retorno) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
            private function deletaTabela($nome, $regras) {
                if($this->banco == 'mysql') {
                    $comando = "DELETE FROM ".$nome;
                    if($regras != NULL) {
                        $comando .= PHP_EOL."WHERE ".$regras;
                    }
                    $retorno = mysqli_query($this->conexao, $comando);
                    return $retorno;
                } else if($this->banco == 'pgsql') {
                    $comando = "DELETE FROM ".$nome;
                    if($regras != NULL) {
                        $comando .= PHP_EOL."WHERE ".$regras;
                    }
                    $retorno = pg_query($this->conexao, $comando);
                    if(!$retorno) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
}