<?php

class Adm {

    public $id_adm, $nome, $email, $senha, $nivel_permissao;

    function __construct($id_adm, $nome, $email, $senha, $nivel_permissao) {
        $this->id_adm = $id_adm;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha= $senha;
        $this->nivel_permissao = $nivel_permissao;
    }
    
    function validaEmailSenha($email, $senha) {
        // Verificar se o e-mail está correto
        if ($email == $this->email && $senha == $this->senha) {
                return true;
            }
    }

    function getNivelPermissao() {
        return $this->nivel_permissao;
    }

}

?>