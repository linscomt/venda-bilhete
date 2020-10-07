<?php

class Pessoa {

    private $idPessoa;
    private $nome;
    private $endereco;
    private $telefone;
    private $email;
    private $aceitaMKT;
    
    function getIdPessoa() {
        return $this->idPessoa;
    }

    function getNome() {
        return $this->nome;
    }

    function getEndereco() {
        return $this->endereco;
    }
    
    function getTelefone() {
        return $this->telefone;
    }

    function getEmail() {
        return $this->email;
    }

    function getAceitaMKT() {
        return $this->aceitaMKT;
    }
    
    function setIdPessoa($idPessoa) {
        $this->idPessoa = $idPessoa;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setEndereco($endereco) {
        $this->endereco = $endereco;
    }
    
    function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setAceitaMKT($aceitaMKT) {
        $this->aceitaMKT = $aceitaMKT;
    }

}

class Bilhete {

    private $numero;
    private $idpessoa;
    private $idpagamento;

    function getNumero() {
        return $this->numero;
    }

    function getIdpessoa() {
        return $this->idpessoa;
    }

    function getIdpagamento() {
        return $this->idpagamento;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function setIdpessoa($idpessoa) {
        $this->idpessoa = $idpessoa;
    }

    function setIdpagamento($idpagamento) {
        $this->idpagamento = $idpagamento;
    }

}

class Pagamento {

    private $idPagamento;
    private $idTransacao;
    private $status;
    private $status_detail;
    private $date_approved;
    private $metodo_pagamento;
    private $tipo_pagamento;
    private $valor;
    
    function getIdPagamento() {
        return $this->idPagamento;
    }

    function getIdTransacao() {
        return $this->idTransacao;
    }

    function getStatus() {
        return $this->status;
    }

    function getStatus_detail() {
        return $this->status_detail;
    }

    function getDate_approved() {
        return $this->date_approved;
    }

    function getMetodo_pagamento() {
        return $this->metodo_pagamento;
    }

    function getTipo_pagamento() {
        return $this->tipo_pagamento;
    }
    
    function getValor() {
        return $this->valor;
    }

    function setIdPagamento($idPagamento) {
        $this->idPagamento = $idPagamento;
    }
    
    function setIdTransacao($idTransacao) {
        $this->idTransacao = $idTransacao;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setStatus_detail($status_detail) {
        $this->status_detail = $status_detail;
    }

    function setDate_approved($date_approved) {
        $this->date_approved = $date_approved;
    }

    function setMetodo_pagamento($metodo_pagamento) {
        $this->metodo_pagamento = $metodo_pagamento;
    }

    function setTipo_pagamento($tipo_pagamento) {
        $this->tipo_pagamento = $tipo_pagamento;
    }
    
    function setValor($valor) {
        $this->valor = $valor;
    }

}