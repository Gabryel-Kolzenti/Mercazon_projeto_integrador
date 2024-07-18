<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="pgPadrao.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pg Padrão</title>
</head>

<?php

if (isset($_POST['loginSubmit'])) {
    include "restrito/conexao.php";

    $email = mysqli_real_escape_string($conn, $_POST['emailL']);
    $sql = "SELECT id, email, senha, nome FROM usuarios WHERE email = '$email'";
    $resultado = $conn->query($sql);
    $numLinha = mysqli_num_rows($resultado);

    if ($numLinha > 0) {
        $linha = mysqli_fetch_array($resultado);
        $senha = mysqli_real_escape_string($conn, $_POST['senhaL']);

        if (password_verify($senha, $linha['senha'])) {

            session_start();
            $_SESSION['id'] = $linha['id'];
            $_SESSION['nome'] = $linha['nome'];
            header('location: restrito');
        } else {
            echo "<p class='aviso'> senha ou email errados. Tente novamente!</p>";
        }
    } else {
        echo "<p class='aviso'> senha ou email errados. Tente novamente!</p>";
    }

    $conn->close();
}

if (isset($_POST['cadastroSubmit'])) {

    include "restrito/conexao.php";

    $nome = $_POST['nomeC'];
    $email = $_POST['emailC'];
    $endereco = $_POST['enderecoC'];
    $data = $_POST['data_nascimentoC'];
    //para aumentar a segurança (criptografia) 
    $senha = password_hash($_POST['senhaC'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, endereco, data_nascimento, senha) VALUE ('$nome', '$email', '$endereco', '$data', '$senha')";

    if ($conn->query($sql) === TRUE) {
        header('location: cadastrado.html');
    } else {
        //para caso de erro, avisar e indicar onde foi o erro
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    //fechar a conexão, ira abrir novamente quando outro usuário entrar
    $conn->close();
}

?>

<!--PHP Login e cadastro-->
<?php

if (isset($_POST['loginSubmit'])) {
    include "restrito/conexao.php";

    $email = mysqli_real_escape_string($conn, $_POST['emailL']);
    $sql = "SELECT id, email, senha, nome FROM usuarios WHERE email = '$email'";
    $resultado = $conn->query($sql);
    $numLinha = mysqli_num_rows($resultado);

    if ($numLinha > 0) {
        $linha = mysqli_fetch_array($resultado);
        $senha = mysqli_real_escape_string($conn, $_POST['senhaL']);

        if (password_verify($senha, $linha['senha'])) {

            session_start();
            $_SESSION['id'] = $linha['id'];
            $_SESSION['nome'] = $linha['nome'];
            header('location: restrito');
        } else {
            echo "<p class='aviso'> senha ou email errados. Tente novamente!</p>";
        }
    } else {
        echo "<p class='aviso'> senha ou email errados. Tente novamente!</p>";
    }

    $conn->close();
}

if (isset($_POST['cadastroSubmit'])) {

    include "restrito/conexao.php";

    $nome = $_POST['nomeC'];
    $email = $_POST['emailC'];
    $endereco = $_POST['enderecoC'];
    $data = $_POST['data_nascimentoC'];
    //para aumentar a segurança (criptografia) 
    $senha = password_hash($_POST['senhaC'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, endereco, data_nascimento, senha) VALUE ('$nome', '$email', '$endereco', '$data', '$senha')";

    if ($conn->query($sql) === TRUE) {
        header('location: cadastrado.html');
    } else {
        //para caso de erro, avisar e indicar onde foi o erro
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    //fechar a conexão, ira abrir novamente quando outro usuário entrar
    $conn->close();
}

?>

<!--PHP Login e cadastro-->

<body>

    <header>
        <nav class="cabecalhoSuperior">
            <div class="d-flex">
                <a href="">Central Do Vendedor</a>
                <h9>|</h9>
                <a href="">Suporte</a>
            </div>
        </nav>

        <nav class="cabecalhoInferior">
            <img src="img/img pg padrao/face.png" alt="">
            <form action="" class="pesquisaCentral">
                <input type="text" placeholder="Busque Seus Produtos" >
                <button><img src="img/img pg padrao/lupa.png" alt=""></button>
            </form>
            <div class="d-flex">
                <img src="img/img pg padrao/userProfileProtótipo.png" class="loginButton" alt="" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                <div class="dropdown">
                    <div src="" alt="" class="naoClicado" id="favoritos" data-bs-toggle="dropdown"
                        aria-expanded="false"></div>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Itens</a></li>
                        <li><a class="dropdown-item" href="#">Favoritados</a></li>
                        <li><a class="dropdown-item" href="#">Pelo Usuário</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Modal de login -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Login</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <p>Entre Com</p>
                    </div>

                    <div class="imagensLoginCadastro">
                        <img src="img/img pg padrao/face.png" alt="">
                        <img src="img/img pg padrao/google.png" alt="">
                    </div>

                    <form action="index.php" method="post">
                        <label for="emailL">E-mail</label><br>
                        <input type="email" name="emailL" id="emailL"><br>

                        <label for="senhaL">Senha</label><br>
                        <div>
                            <img class="olho" src="img/img pg padrao/olho.png" alt="">
                            <input type="password" name="senhaL" id="senhaL">
                        </div>


                        <a href="">Esqueceu Sua Senha?</a>

                        <br>
                        <br>
                        <div id="loginBotao">
                            <input name="loginSubmit" type="submit" value="Login">
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <!--Botão pro modal de cadastro-->
                    <p>Não Possui Conta?</p>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <u>Cadastre-Se</u>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de login -->

    <!-- Modal de cadastro-->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Cadastro</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="index.php" method="POST">
                        <label for="nomeCa">Nome Completo</label><br>
                        <input type="text" name="nomeC" id="nomeCa"><br>

                        <label for="emailC">E-mail</label><br>
                        <input type="email" name="emailC" id="emailC"><br>

                        <label for="senhaC">Senha</label><br>
                        <input type="number" name="senhaC" id="senhaC"><br>

                        <label for="enderecoC">Endereço</label><br>
                        <input type="text" name="enderecoC" id="enderecoC"><br>

                        <label for="dataNcC">Data de Nascimento</label><br>
                        <input type="date" name="data_nascimentoC" id="dataNcC"><br>

                        <br>

                        <div id="loginBotao">
                            <input name="cadastroSubmit" type="submit" value="Cadastre-se">
                        </div>

                    </form>

                    <div class="modal-footer">
                        <!--Botão pro modal de login-->
                        <p>Já Possui Conta?</p>

                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <u>Logue-se</u>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal de cadastro-->

    <!-- Começo Do Conteúdo-->
    <main>

    </main>
    <!-- Começo Do Conteúdo-->

    <div class="containerCards">

        <div class="card" onclick="location.href='index.html'" href="">
            <div class="parteSuperiorCard">
                <img src="img/img pg padrao/armario.jfif" alt="">
                <svg class="favoritaCoracao coracaoDesfavoritado" xmlns="http://www.w3.org/2000/svg" width="16"
                    height="16" fill="#004F90" viewBox="0 0 16 16">
                    <path
                        d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15" />
                </svg>
            </div>
            <div class="parteInferiorCard">
                <h4>Armárioooooooooooooooooooooooo</h4>
                <h6>Nome da Loja</h6>
                <h6>R$ 999,99</h6>
                <a class="btn-p4" href="">Ver Produto</a>
            </div>
        </div>
    </div>


    <!------------------------------------------------FOOTER---------------------------------------------------->

    <footer>
        <div class="footerSuperior">
            <div>
                <img src="img/img pg padrao/subFooterImagem1.png" alt="">
                <h6>Quer anunciar seus produtos?</h6>
                <a href="">Clique Aqui</a>
            </div>

            <div>
                <img src="img/img pg padrao/subFooterImagem2.png" alt="">
                <h6>Tem alguma dúvida?</h6>
                <a href="">Acesse nosso FAQ</a>
            </div>

            <div>
                <img src="img/img pg padrao/subFooterimagem3.png" alt="">
                <h6>Está com problemas?</h6>
                <a href="">Fale conosco</a>
            </div>
        </div>

        <div class="footerInferior">
            <div class="linksPaginasFooter">
                <div>
                    <h5>Comprar</h5>
                    <a href="">Todos Produtos</a>
                    <a href="">Lojas Cradastradas</a>
                    <a href="">Anuncie Aqui</a>
                </div>

                <div>
                    <h5>Sobre</h5>
                    <a href="">Nossa História</a>
                    <a href="">Quem Somos?</a>
                </div>

                <div>
                    <h5>Ajuda</h5>
                    <a href="">Contate-nos</a>
                    <a href="">Guia do Lojista</a>
                </div>

                <div>
                    <h5>Nossas redes sociais</h5>
                    <div id="redesSociais">
                        <img src="img/img pg padrao/face.png" alt="">
                        <img src="img/img pg padrao/instagram.png" alt="">
                    </div>
                </div>
            </div>

            <div class="logo">
                <img src="img/img pg padrao/logo.png" alt="">
            </div>

            <div class="frasesFinaisFooter">
                <p>2024 All Rights Reserved</p>
                <a href="">Termos de uso</a>
            </div>
        </div>

    </footer>

    <!------------------------------------------------FOOTER---------------------------------------------------->


    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>