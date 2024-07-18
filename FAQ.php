<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="FAQ.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercazon</title>
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

<!--PHP Login e cadastro--><?php

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
        echo "Error: " . $sql . "<br>" . $conn-> error;
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
                <a href="restrito/usuario.php">Usuário </a>
                <h9>|</h9>
                <a href="logout.php">Suporte</a>
            </div>
        </nav>

        <nav class="cabecalhoInferior">
            <img src="img/img pg padrao/face.png" alt="">

            <form action="produtosBusca.php" class="pesquisaCentral" method="POST">
                <input type="text" placeholder="Busque Seus Produtos" name="nome">
                <button type="submit" name="filtro" value="preco"><img src="img/img pg padrao/lupa.png" alt=""></button>
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


    <!-- Começo Do Conteúdo INICIO (COLOQUE O SEU CÓDIGO AQUI) -->
    <main>

    <div class="containerPrincipal">
        <h1>Perguntas Frequentes (FAQ)</h1>
        <h3>"Não consigo entrar em tal página!" "Meu produto veio com defeito!"</h3>
        <h6>Nessa página você encontrará informações sobre perguntas frequentes no nosso website. Dê uma olhada!</h6>
    </div>

    <!-- Accordion do Bootstrap -->

    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button" id="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              Meu produto veio com defeito!
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
            <div class="accordion-body" id="accordion-body">
              <p>De acordo com os nossos <a>Termos de Uso</a> nós não podemos fornecer um reembolso ou compensação. Entretanto, você pode mandar um email para nós avaliarmos a situação e tomarmos uma medida.</p>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed"  id="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              Eu não consigo entrar em tal página!
            </button>
          </h2>
          <div id="collapseTwo" class="accordion-collapse collapse"id="accordion-button" data-bs-parent="#accordionExample">
            <div class="accordion-body" id="accordion-body">
              <p>Caso a página não carregue, recarregue apertando F5 ou CTRL+R no seu teclado. No celular você pode apertar no botão de recarregar a página (que geralmente fica em cima). Também a página que você está procurando pode estar indisponível.</p>

              <img src="FAQ_imgs/reloadoTutorial.png">
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" id="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Como eu posso favoritar um produto?
            </button>
          </h2>
          <div id="collapseThree" class="accordion-collapse collapse" id="accordion-button" data-bs-parent="#accordionExample">
            <div class="accordion-body" id="accordion-body">
              <p>Entre na página de um produto e clique no ícone de coração que fica próximo ao produto. Aí pronto! O produto selecionado vai estar na página de favoritos quando você se registrar no site.</p>
              <img src="FAQ_imgs/favoriteTutorial.png">
            </div>
          </div>
        </div>
      </div>


    </main>
    <!-- Começo Do Conteúdo FIM -->

    

    <!------------------------------------------------FOOTER---------------------------------------------------->

    <footer>
        <div class="footerSuperior">
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
                    <a href="Contato.php">Contate-nos</a>
                    <a href="guiaDoLojista.php">Guia do Lojista</a>
                </div>

                <div >
                    <h5>Nossas redes sociais</h5>
                    <div class="redesSociais">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#ffffff" class="bi bi-facebook" viewBox="0 0 16 16">
                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                            </svg>
                          
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#ffffff" class="bi bi-instagram" viewBox="0 0 16 16">
                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
                            </svg>
                    </div>
                </div>
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