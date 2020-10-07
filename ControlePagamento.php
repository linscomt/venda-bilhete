<?php

require_once './Banco.php';
require_once './objetos.php';

class ControlePagamento extends Banco {


    function registrarPessoaBilhete(Pessoa $pessoa, $bilhetes) {
        //Conexão para registrar a venda
        $conexao = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        mysqli_autocommit($conexao, FALSE);
        $erro = 0;

        //Registrando dados da pessoa
        $sqlPessoa = "INSERT INTO `pessoa`(`nomeCompleto`, `endereco`, `telefone`, `email`, `isMkt`) VALUES ('" . $pessoa->getNome() . "','" . $pessoa->getEndereco() . "', '" . $pessoa->getTelefone() . "','" . $pessoa->getEmail() . "','" . boolval($pessoa->getAceitaMKT()) . "');";
        if (!mysqli_query($conexao, $sqlPessoa)) {
            $erro++;
        }

        //obtendo id da pessoa cadastrada
        $sqlObterId = "SELECT LAST_INSERT_ID();";
        if (!$id = mysqli_query($conexao, $sqlObterId)) {
            $erro++;
        }

        $idPessoaCriada = intval($id->fetch_row()[0]);

        //Registrando bilhetes
        for ($i = 0; $i < count($bilhetes); $i++) {
            $sqlPessoa = "INSERT INTO `bilhete`(`numero`, `idpessoa`) VALUES ('" . $bilhetes[$i] . "','" . $idPessoaCriada . "');";
            if (!mysqli_query($conexao, $sqlPessoa)) {
                $erro++;
            }
        }

        if ($erro == 0) {
            mysqli_commit($conexao);
            mysqli_close($conexao);
            return $idPessoaCriada;
        } else {
            mysqli_rollback($conexao);
            echo "Ocorreu um erro em sua requisição. Tente novamente ou entre em contato com o suporte.";
            mysqli_close($conexao);
            return false;
        }
    }

    function removerPessoaBilhete($idPessoa) {
        $idPessoa = intval($idPessoa);
        //Conexão para registrar a venda
        $conexao = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        mysqli_autocommit($conexao, FALSE);
        $erro = 0;

        //Excluindo bilhete da pessoa
        $sql = "DELETE FROM bilhete WHERE idpessoa = '" . $idPessoa . "';";
        if (!mysqli_query($conexao, $sql)) {
            $erro++;
        }

        //Excluindo dados da pessoa
        $sql = "DELETE FROM pessoa WHERE idpessoa = '" . $idPessoa . "';";
        if (!mysqli_query($conexao, $sql)) {
            $erro++;
        }

        if ($erro == 0) {
            mysqli_commit($conexao);
            mysqli_close($conexao);
            return true;
        } else {
            mysqli_rollback($conexao);
            echo "Ocorreu um erro em sua requisição. Tente novamente ou entre em contato com o suporte.";
            mysqli_close($conexao);
            return false;
        }
    }

    function registrarDados($idPessoa, Pagamento $pagamento, $valor) {
        //Conexão para registrar a venda
        $conexao = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        mysqli_autocommit($conexao, FALSE);
        $erro = 0;

        //Registrando dados do pagamento
        $sqlPessoa = "INSERT INTO `pagamento`(`idTransacao`, `status` ,`status_detail`, `date_approved`, `metodo_pagamento`, `tipo_pagamento`, `valor`) VALUES"
                . " ('" . $pagamento->getIdTransacao() . "','" . $pagamento->getStatus() . "','" . $pagamento->getStatus_detail() . "','" . $pagamento->getDate_approved() . "','" . $pagamento->getMetodo_pagamento() . "','" . $pagamento->getTipo_pagamento() . "','" . $valor . "');";
        if (!mysqli_query($conexao, $sqlPessoa)) {
            $erro++;
            echo mysqli_error($conexao);
        }

        //obtendo id do pagamento cadastrado
        $sqlObterId = "SELECT LAST_INSERT_ID();";
        if (!$id = mysqli_query($conexao, $sqlObterId)) {
            $erro++;
            echo mysqli_error($conexao);
        }
        
        $idPagamento = intval($id->fetch_row()[0]);

        $sql = "UPDATE `bilhete` SET idPagamento = '" . $idPagamento . "' WHERE idpessoa = '" . intval($idPessoa) . "'";
        if (!mysqli_query($conexao, $sql)) {
            $erro++;
            echo mysqli_error($conexao);
        }
        
        //obtendo id da transacao o cadastrado
        $sqlObterId = "SELECT idTransacao FROM pagamento WHERE idPagamento = '". $idPagamento ."';";
        if (!$idT = mysqli_query($conexao, $sqlObterId)) {
            $erro++;
            echo mysqli_error($conexao);
        }
        
        $idTransacao = intval($idT->fetch_row()[0]);

        if ($erro == 0) {
            mysqli_commit($conexao);
            mysqli_close($conexao);
            return $idTransacao;
        } else {
            mysqli_rollback($conexao);
            echo "Ocorreu um erro em sua requisição. Tente novamente ou entre em contato com o suporte.";
            mysqli_close($conexao);
            return false;
        }
    }

    function obterBilhetesTransacao($idTransacao) {
        $conexao = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        $erro = 0;

        if (!mysqli_num_rows($result = mysqli_query($conexao, "SELECT bilhete.numero FROM bilhete, pagamento WHERE bilhete.idPagamento = pagamento.idpagamento AND pagamento.idTransacao = '" . $idTransacao . "';"))) {
            $erro++;
        }

        mysqli_close($conexao);

        if ($erro > 0) {
            return array();
        } else {
            $bilhetes = mysqli_fetch_all($result);

            $bilhetesTransacao = array();

            for ($i = 0; $i < count($bilhetes); $i++) {
                array_push($bilhetesTransacao, $bilhetes[$i][0]);
            }

            return $bilhetesTransacao;
        }
    }

    function obterCompradorTransacao($idTransacao) {
        $conexao = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        $erro = 0;

        if (!mysqli_num_rows($result = mysqli_query($conexao, "SELECT DISTINCT pessoa.nomeCompleto, pessoa.endereco, pessoa.telefone, pessoa.email FROM bilhete, pagamento, pessoa WHERE bilhete.idPagamento = pagamento.idpagamento AND pagamento.idTransacao = '" . $idTransacao . "' AND bilhete.idpessoa = pessoa.idpessoa;"))) {
            $erro++;
        }

        mysqli_close($conexao);

        if ($erro > 0) {
            return array();
        } else {
            $pessoaResult = mysqli_fetch_all($result);

            $pessoa = new Pessoa();

            for ($i = 0; $i < count($pessoaResult); $i++) {
                $pessoa->setNome($pessoaResult[$i][0]);
                $pessoa->setEndereco($pessoaResult[$i][1]);
                $pessoa->setTelefone($pessoaResult[$i][2]);
                if (strcmp($pessoaResult[$i][3], "") == 0) {
                    $pessoa->setEmail("Não informado");
                } else {
                    $pessoa->setEmail($pessoaResult[$i][3]);
                }
            }

            return $pessoa;
        }
    }

    function obterPagamentoTransacao($idTransacao) {
        $conexao = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        $erro = 0;

        if (!mysqli_num_rows($result = mysqli_query($conexao, "SELECT * FROM pagamento WHERE pagamento.idTransacao = '" . $idTransacao . "';"))) {
            $erro++;
        }

        mysqli_close($conexao);

        if ($erro > 0) {
            return array();
        } else {
            $pagamentoResult = mysqli_fetch_all($result);

            $pagamento = new Pagamento();

            for ($i = 0; $i < count($pagamentoResult); $i++) {
                $pagamento->setIdTransacao($pagamentoResult[$i][1]);
                $pagamento->setStatus($pagamentoResult[$i][2]);
                $pagamento->setDate_approved($pagamentoResult[$i][4]);
                $pagamento->setMetodo_pagamento($pagamentoResult[$i][5]);
                $pagamento->setValor($pagamentoResult[$i][7]);
            }

            return $pagamento;
        }
    }
    
    function atualizarPagamento($idTransacao) {
        
        
    }

}
