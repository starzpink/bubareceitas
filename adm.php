<?php

class Adm { //criação de classe de administrador para login

    public $id_adm, $nome, $email, $senha, $nivel_permissao;

    function __construct($id_adm, $nome, $email, $senha, $nivel_permissao) {
        $this->id_adm = $id_adm;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha= $senha;
        $this->nivel_permissao = $nivel_permissao;
    }
    
    function validaEmailSenha($email, $senha) {
        // Verifica se o e-mail e senha estão corretos
        if ($email == $this->email && $senha == $this->senha) {
                return true;
            }
    }

    function getNivelPermissao() { //obtém o nivel de permissão
        return $this->nivel_permissao;
    }

}

?>