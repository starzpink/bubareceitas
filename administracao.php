<?php
$classe = "Receita";
include_once './conn.php';
include_once './adm.php';

session_start();

//verificação de login
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
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.3.1/jquery.twbsPagination.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script src="javascriptReceita.js"></script>
    <link rel="stylesheet" type="text/css" href="css/administracao.css">
</head>

<body>
    <!-- barra de navegação -->
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Sair</a></li>
            </ul>
        </div>
    </nav>

    <!-- titulo e botão de criação -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Gerenciamento de Receitas</h2>
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create-item">
                        Criar Receita
                    </button>
                </div>
            </div>
        </div>

        <!-- tabela gerada pelo javascript -->
        <table class="table table-bordered table-style">
            <thead style="background-color: #252e2f; color:#bae8ee">
            </thead>
            <tbody>
            </tbody>
        </table>

        <ul id="pagination" class="pagination-sm"></ul>

        <!-- formulario de criação de receita -->
        <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Criar Receita</h4>
                    </div>
                    <div class="modal-body">
                        <form data-toggle="validator" action="insertReceita.php" method="POST">

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- formulario de edição de receita -->
        <div class="modal fade" id="edit-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Editar Receita</h4>
                    </div>
                    <div class="modal-body">
                        <form data-toggle="validator" action="updateReceita.php" method="POST">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- formulario de visualização de receita -->
        <div class="modal fade" id="view-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Visualizar Receita</h4>
                    </div>
                    <div class="modal-body">
                        <form data-toggle="validator" action="select<?php echo $classe; ?>.php" method="POST">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>