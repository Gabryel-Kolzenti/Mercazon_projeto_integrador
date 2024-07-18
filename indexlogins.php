<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .aviso {
            color: red;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>

<body>

    <h2>login usuário</h2>
    <form action="" method="POST">
        <input type="text" name="email" placeholder="Insira seu e-mail" required> <br> <br>
        <input type="text" name="senha" placeholder="insira sua senha" required> <br> <br>
        <input type="submit" value="Validar login" name="loginSubmit">
    </form>


    <h2>login lojista</h2>
    <form action="" method="POST">
        <input type="text" name="email" placeholder="Insira seu e-mail" required> <br> <br>
        <input type="text" name="senha" placeholder="insira sua senha" required> <br> <br>
        <input type="submit" value="Validar login" name="loginLojista">
    </form>


    <?php

    // código referente ao login do usuário
    if (isset($_POST['loginSubmit'])) {
        include_once "restrito/conexao.php";

        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $sql = "SELECT id, email, senha, nome FROM usuarios WHERE email = '$email'";
        $resultado = $conn->query($sql);
        $numLinha = mysqli_num_rows($resultado);

        if ($numLinha > 0) {
            $linha = mysqli_fetch_array($resultado);
            $senha = mysqli_real_escape_string($conn, $_POST['senha']);

            if (password_verify($senha, $linha['senha'])) {

                session_start();
                $_SESSION['idUser'] = $linha['id'];
                $_SESSION['nome'] = $linha['nome'];
                header('location: restrito/usuario.php');

            } else {
                echo "<p class='aviso'> senha ou email errados. Tente novamente!</p>";
            }
        } else {
            echo "<p class='aviso'> senha ou email errados. Tente novamente!</p>";
        }

        $conn->close();
    }

    // Código referente ao login do lojista
    if (isset($_POST['loginLojista'])) {

        include_once "restrito/conexao.php";

        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $sql = "SELECT id, senha, nome FROM lojistas WHERE email = '$email'";
        $resultado = $conn->query($sql);
        $numLinha = mysqli_num_rows($resultado);

        if ($numLinha > 0) {
            $linha = mysqli_fetch_array($resultado);
            $senha = mysqli_real_escape_string($conn, $_POST['senha']);

            if (password_verify($senha, $linha['senha'])) {

                session_start();
                $_SESSION['idLojista'] = $linha['id'];
                $_SESSION['nome'] = $linha['nome'];
                header('location: restrito/index.php');

            } else {
                echo "<p class='aviso'> senha ou email errados. Tente novamente!</p>";
            }
        } else {
            echo "<p class='aviso'> senha ou email errados. Tente novamente!</p>";
        }

        $conn->close();
    }



    ?>


</body>

</html>