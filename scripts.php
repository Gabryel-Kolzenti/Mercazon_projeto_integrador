<!DOCTYPE html>
<html lang="pt-br">

<head>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        .aviso {
            color: red;
        }

        img {
            width: 220px;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>

<body>

    <!-- <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="nome" placeholder="Nome"> <br> <br>
        <input type="text" name="nomeEstabelecimento" placeholder="nome - loja"> <br> <br>
        <input type="text" name="endereco" placeholder="endereço"> <br> <br>
        <input type="email" name="email" placeholder="email"> <br> <br>
        <input type="text" name="senha" placeholder="senha"> <br> <br>
        <input type="text" name="telefone" placeholder="telefone"> <br> <br>
        <label> Foto do usuario </label> <br> <br>
        <input type="file" name="imagemUsuario" placeholder="imagem_usuario" accept="image/*"> <br> <br>
        <label> Foto do lojista </label> <br> <br>
        <input type="file" name="imagemEmpresa" placeholder="imagem_empresa" accept="image/*"> <br> <br>

        <input type="submit" value="Validar login" name="cadastroLojistaSubmit">
    </form> -->

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="email" name="email" placeholder="email"> <br> <br>
        <input type="text" name="senha" placeholder="senha"> <br> <br>



        <input type="submit" value="Validar login" name="loginLojista">
    </form>


    <?php

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

    // codigo referente ao cadastro do lojista
    if (isset($_POST['cadastroLojistaSubmit'])) {

        include_once "restrito/conexao.php";
        $email = clear($conn, $_POST['email']);

        $sql = "SELECT id FROM lojistas WHERE email = '$email'";
        $resultado = mysqli_query($conn, $sql);
        $numLinha = mysqli_num_rows($resultado);

        if ($numLinha > 0) {

            echo "<p class='aviso'>Este e-mail ja está em uso.</p>";

        } else {
            $fotoUsuario = salvarFoto($_FILES['imagemUsuario']);

            switch ($fotoUsuario) {
                case 0:
                    echo "<p class='aviso'> Houve um erro no upload da imagem do lojista</p>";
                    break;
                case 1:
                    echo "<p class='aviso'> a imagem do lojista arquivo esta em um formato não aceito ou é muito grande. <br>Aceitamos arquivos nos seguintes formatos: JPEG, PNG ou SVG. <br>Imagens com tamanho limite de 1.5mb </p>";
                    break;
                default:

                    $fotoEmpresa = salvarFoto($_FILES['imagemEmpresa']);

                    switch ($fotoEmpresa) {
                        case 0:
                            echo "<p class='aviso'> Houve um erro no upload da imagem da empresa</p>";
                            break;
                        case 1:
                            echo "<p class='aviso'> a imagem da empresa arquivo esta em um formato não aceito ou é muito grande. <br>Aceitamos arquivos nos seguintes formatos: JPEG, PNG ou SVG. <br>Imagens com tamanho limite de 1.5mb </p>";
                            break;
                        default:


                            $endereco = CLEAR($conn, $_POST['endereco']);
                            $senha = password_hash(CLEAR($conn, $_POST['senha']), PASSWORD_DEFAULT);
                            $telefone = CLEAR($conn, $_POST['telefone']);
                            $nome = CLEAR($conn, $_POST['nome']);
                            $nomeEstabelecimento = CLEAR($conn, $_POST['nomeEstabelecimento']);

                            $sql = "INSERT INTO lojistas (nome, nome_estabelecimento, endereco, email, senha, telefone, imagem_empresa, imagem_lojista) VALUES ('$nome', '$nomeEstabelecimento', '$endereco', '$email', '$senha', '$telefone', '$fotoEmpresa', '$fotoUsuario')";

                            if (mysqli_query($conn, $sql)) {

                                $sql = "SELECT id, nome FROM lojistas WHERE email  = '$email'";

                                $resultado = mysqli_query($conn, $sql);
                                $linha = mysqli_fetch_array($resultado);

                                session_start();
                                $_SESSION['idLojista'] = $linha['id'];
                                $_SESSION['nome'] = $linha['nome'];
                                header('location: restrito/index.php');
                            } else {
                                echo " <p class='aviso'>Houve um erro no cadastro</p>";
                            }
                    }
            }
        }
        $conn->close();
    }

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

    // código referente ao cadastro do usuário
    if (isset($_POST['cadastroSubmit'])) {

        include_once "restrito/conexao.php";

        $email = clear($conn, $_POST['email']);

        $sql = "SELECT id FROM usuarios WHERE email = '$email'";
        $resultado = mysqli_query($conn, $sql);
        $numLinha = mysqli_num_rows($resultado);

        if ($numLinha > 0) {
            echo "<p class='aviso'>Este e-mail ja está em uso.</p>";
        } else {
            $nome = clear($conn, $_POST['nome']);
            $endereco = clear($conn, $_POST['endereco']);
            $data = clear($conn, $_POST['data_nascimento']);
            $senha = clear($conn, $_POST['senha']);
            //para aumentar a segurança (criptografia) 
            $senha = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (nome, email, endereco, data_nascimento, senha) VALUE ('$nome', '$email', '$endereco', '$data', '$senha')";

            if ($conn->query($sql) === TRUE) {

                $sql = "SELECT id, nome FROM usuarios WHERE email = '$email'";
                $resultado = mysqli_query($conn, $sql);
                $linha = mysqli_fetch_array($resultado);

                session_start();
                $_SESSION['idUser'] = $linha['id'];
                $_SESSION['nome'] = $linha['nome'];
                header('location: restrito/usuario.php');
            } else {
                //para caso de erro, avisar e indicar onde foi o erro
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        // fechar a conexão, ira abrir novamente quando outro usuário entrar
        $conn->close();
    }

    // Função para contabilizar os cliques de produto.
    if (isset($_SESSION['idProduto'])) {
        // inciamos uma sessão e buscamos uma variavel chamada idProduto, se ela existir, incrementamos um ao contador de cliques daquele produto com o id correspondete. A variavel de sessão $_SESSION['idProduto'] deve estar declarada para que a operação ocorra.
    
        include_once "restrito/conexao.php";
        $idProduto = $_SESSION['idProduto'];
        $sql = "UPDATE produtos SET contador_cliques = (contador_cliques + 1) WHERE id = $idProduto;";
        $conn->query($sql);
        $conn->close();
    }

    // código para favoritar um produto - a variavel user vem do arquivo validarUsuario e a variavel idProduto vem de um forms do produto   
    if (isset($_POST['favoritoSubmit'])) {
        $idProduto = $_POST['idFavorito'];
        $sql = "SELECT id FROM usuario_favorita_produto WHERE id_usuario = $user AND id_produto = $idProduto";
        $resultado = $conn->query($sql);
        $numLinha = mysqli_num_rows($resultado);

        if ($numLinha > 0) {
            $sql = "DELETE FROM usuario_favorita_produto WHERE id_usuario = $user AND id_produto = $idProduto;";
            if($conn->query($sql)){
                echo "<script>
                     alert('O produto não está mais na sua lista de favoritos');
                    </script>";
            } else {
                echo " <script>
                     alert('houve um problema para favoritar o produto. Tente novamente mais tarde');
                    </script>";
            }
        } else {
            $sql = "INSERT INTO usuario_favorita_produto (id_usuario, id_produto) VALUES ('$user', '$idProduto');";
            if ($conn->query($sql)) {
                echo "  <script>
                     alert('produto favoritado');
                    </script>";
            } else {
                echo " <script>
                     alert('houve um problema para favoritar o produto. Tente novamente mais tarde');
                    </script>";
            }
        }
        $conn->close();
    }
    ?>

    <!-- código para buscar os produtos mais clicados -->
    <?php
    include_once 'restrito/conexao.php';

    $sql = 'SELECT id, nome, preco, categoria, imagem FROM produtos ORDER BY contador_cliques DESC LIMIT 4;';
    $resultado = $conn->query($sql);
    while ($linha = mysqli_fetch_assoc($resultado)) {
        $nome = $linha['nome'];
        $imagem = $linha['imagem'];
        $preco = $linha['preco'];
        $categoria = $linha['categoria'];
        $id = $linha['id'];

        echo "
            <div class='cartao'>
                <h6>$nome</h6>
                <p>$categoria</p>
                <img src='img/$imagem' alt'$nome'>
                <h6>R$ $preco</h6>
                <button>ver mais</button>
                    <form action='' method=POST>
                    <input type='hidden' name='id_favorito' value='$id'>
                    <input type='submit' value='favoritar' name='favoritoSubmit'>
                </form>
            </div>";
    }

    ?>

    <!-- Código responsavel por inciar uma sessão no site. esta sessão vai permitir que o usuário favorite até 5 produtos sem a necessidade de logar no sistema -->
    <!-- Trabalhar mais depois. Ideia é boa, mas falta compreender melhor a lógica -->
    <?php

    // session_start();
    
    // if(!isset($_SESSION['userLogado'])){
    //     $_SESSION['userLogado'] = false;
    //     echo "sessão iniciada";
    // }
    
    // if($_SESSION['userLogado'] == false){
    //     if (!isset($_SESSION["favoritos"])) {
    //         $_SESSION['favoritos'] = array();
    //         echo "favorito criado";
    //     }
    
    //     if (isset($_POST['favoritoSubmit']) && sizeof($_SESSION['favoritos']) < 5) {
    //         array_push($_SESSION['favoritos'], $_POST['id_favorito']);
    //         echo "favorito adicionado";
    //     }
    
    //     if (sizeof($_SESSION['favoritos']) >= 5) {
    //         echo "tamanho limite de favoritos atingido. Para favoritar mais se cadastre no sistema.";
    //     }
    // }
    
    ?>

</body>

</html>