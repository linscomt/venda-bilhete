<?php

require_once './Banco.php';
require_once './objetos.php';

class ControleVendas extends Banco {

    function obterStatusSorteio() {
        $conexao = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        $erro = 0;

        if (!mysqli_num_rows($result = mysqli_query($conexao, "SELECT `isAtivo` FROM `sorteio` WHERE `idSorteio` = 1;"))) {
            $erro++;
        }

        mysqli_close($conexao);

        if ($erro > 0) {
            return array();
        } else {
            return mysqli_fetch_row($result);
        }
    }

    function obterBilhetesVendidos() {
        $conexao = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        $erro = 0;

        if (!mysqli_num_rows($result = mysqli_query($conexao, "SELECT numero FROM `bilhete` WHERE idPagamento is not NULL;"))) {
            $erro++;
        }

        mysqli_close($conexao);

        if ($erro > 0) {
            return array();
        } else {
            $bilhetes = mysqli_fetch_all($result);

            $bilhetesVendidos = array();

            for ($i = 0; $i < count($bilhetes); $i++) {
                array_push($bilhetesVendidos, $bilhetes[$i][0]);
            }

            return $bilhetesVendidos;
        }
    }

    function isVendido($numero) {
        $conexao = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        $erro = 0;

        if (!mysqli_num_rows(mysqli_query($conexao, "SELECT numero FROM `bilhete` WHERE numero = " . $numero . " AND idPagamento is not NULL;;"))) {
            $erro++;
        }
        mysqli_close($conexao);

        if ($erro > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function alertaBilhetesJaVendidos($bilhetesVendidos) {
        echo "<script type='text/javascript'>
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Algum bilhete que você selecionou, acabou de ser vendido.'
            });
        </script>";
    }

    function obterBilhetesPeloTelefone($telefone) {
        $conexao = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        $erro = 0;

        //Obtendo os dados das pessoas que compraram usando o mesmo número de telefone
        if (!mysqli_num_rows($result = mysqli_query($conexao, "SELECT * FROM bilhete, pessoa, pagamento WHERE bilhete.idpessoa = pessoa.idpessoa and bilhete.idPagamento = pagamento.idpagamento and pessoa.telefone = '" . $telefone . "';"))) {
            $erro++;
        }

        $result = mysqli_fetch_all($result);

        return $result;
    }

    function agruparDadosBilhetePorTelefone($result) {
        $pessoas = array();
        $pagamentos = array();
        $bilhetes = array();

        for ($i = 0; $i < count($result); $i++) {
            if (!$this->verificarSePessoaExisteArray($result[$i][2], $pessoas)) {
                $pessoa = new Pessoa();
                $pessoa->setIdPessoa($result[$i][2]);
                $pessoa->setNome($result[$i][5]);
                $pessoa->setEndereco($result[$i][6]);
                $pessoa->setTelefone($result[$i][7]);
                if (strcmp($result[$i][8], "") == 0) {
                    $pessoa->setEmail("Não informado");
                } else {
                    $pessoa->setEmail($result[$i][8]);
                }
                array_push($pessoas, $pessoa);
            }

            if (!$this->verificarSePagamentoExisteArray($result[$i][3], $pagamentos)) {
                $pagamento = new Pagamento();
                $pagamento->setIdTransacao($result[$i][11]);
                $pagamento->setStatus($result[$i][12]);
                $pagamento->setStatus_detail($result[$i][13]);
                $pagamento->setDate_approved($result[$i][14]);
                $pagamento->setMetodo_pagamento($result[$i][15]);
                $pagamento->setTipo_pagamento($result[$i][16]);
                $pagamento->setValor($result[$i][17]);
                $pagamento->setIdPagamento($result[$i][3]);

                array_push($pagamentos, $pagamento);
            }

            $bilhete = new Bilhete();
            $bilhete->setNumero($result[$i][1]);
            $bilhete->setIdpessoa($result[$i][2]);
            $bilhete->setIdpagamento($result[$i][3]);
            array_push($bilhetes, $bilhete);
        }

        return array($pessoas, $pagamentos, $bilhetes);
    }

    function verificarSePessoaExisteArray($idPessoa, $pessoas) {
        for ($i = 0; $i < count($pessoas); $i++) {
            if ($pessoas[$i]->getIdPessoa() == $idPessoa) {
                return true;
            }
        }
        return false;
    }

    function verificarSePagamentoExisteArray($idPagamento, $pagamentos) {
        for ($i = 0; $i < count($pagamentos); $i++) {
            if ($pagamentos[$i]->getIdPagamento() == $idPagamento) {
                return true;
            }
        }
        return false;
    }

    function getHost() {
        return $this->host;
    }

    function getUser() {
        return $this->user;
    }

    function getPassword() {
        return $this->password;
    }

    function getDatabase() {
        return $this->database;
    }

    function setHost($host) {
        $this->host = $host;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setDatabase($database) {
        $this->database = $database;
    }

}
