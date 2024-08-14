<?php
$classe = "Receita";
include_once './conn.php';
include_once './adm.php';
session_start();

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $consulta = mysqli_query($conn, "select id_adm, nome, email, senha, nivel_permissao from adm where email = '" . $email . "'");
    $dados = mysqli_fetch_assoc($consulta);
    $adm = null;
    if ($dados != null) {
        $adm = new Adm($dados["id_adm"], $dados["nome"], $dados["email"], $dados["senha"], $dados['nivel_permissao']);
    }

    if ($adm != null && $adm->validaEmailSenha($email, md5($senha))) {
        $_SESSION['adm'] = $adm;
    } else {
        $_SESSION['msg'] = "E-mail ou senha incorretos.";
        header("Location: login.php");
        exit;
    }
} else if (!isset($_SESSION['adm'])) {
    $_SESSION['msg'] = "É necessário logar antes de acessar a área de administrador.";
    header("Location: login.php");
    exit;
}

$adm = $_SESSION['adm'];

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Área do Administrador</title>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.3.1/jquery.twbsPagination.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">        
        <script src="javascript<?php echo $classe; ?>.js"></script>
        <link rel="stylesheet" type="text/css" href="css/administracao.css">
    </head>
    <body> 
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Sair</a></li>
                    </ul>
                </div>
            </nav>

        <?php if (!empty($classe)) { ?>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 margin-tb">                    
                        <div class="pull-left">
                            <h2>Cadastro de <?php echo $classe; ?></h2>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create-item">
                                Criar <?php echo $classe; ?>
                            </button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-style">
                    <thead style="background-color: #252e2f; color:#bae8ee">
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <ul id="pagination" class="pagination-sm"></ul>
                <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="myModalLabel">Criar <?php echo $classe; ?></h4>
                            </div>
                            <div class="modal-body">
                                <form data-toggle="validator" action="insert<?php echo $classe; ?>.php" method="POST">

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="edit-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="myModalLabel">Editar <?php echo $classe; ?></h4>
                            </div>
                            <div class="modal-body">
                                <form data-toggle="validator" action="update<?php echo $classe; ?>.php" method="POST">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="view-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="myModalLabel">Visualizar <?php echo $classe; ?></h4>
                            </div>
                            <div class="modal-body">
                                <form data-toggle="validator" action="select<?php echo $classe; ?>.php" method="POST">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </body>
</html>
