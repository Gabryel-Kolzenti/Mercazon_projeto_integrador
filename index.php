<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="pgInicial.css">
    <link rel="stylesheet" href="pgPadrao.css">
    <link rel="stylesheet" href="pgInicial-2.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
</head>

<!--PHP Login e cadastro-->
<?php
session_start();
$user = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : -1; 
// Código referente ao login do usuário
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
            echo "
                    <script>
            alert('senha ou email errados. Tente novamente!');
                    </script>";
        }
    } else {
        echo "<script>
            alert('senha ou email errados. Tente novamente!');
                    </script>";
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
        echo "<script>
            alert('Este e-mail ja está em uso. Tente com outro');
                    </script>";
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
            echo "<script>
            alert('Houve um problema no cadastro. Por favor, tente mais tarde.');
                    </script>";
        }
    }
    // fechar a conexão, ira abrir novamente quando outro usuário entrar
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
            header('location: restrito/lojistaLojista.php');
        } else {
            echo "<script>
            alert('senha ou email errados. Tente novamente!');
                    </script>";
        }
    } else {
        echo "<script>
            alert('senha ou email errados. Tente novamente!');
                </script>";
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
        $fotoUsuario = salvarFoto($_FILES['imagemUsuario'], "img/");

        switch ($fotoUsuario) {
            case 0:
                echo "<script>
                    alert('Houve um erro no upload da imagem de usuário. Tente novamente mais tarde.');
                    </script>";
                break;
            case 1:
                echo "<script>
                    alert('a imagem do lojista arquivo esta em um formato não aceito ou é muito grande.<br>Aceitamos arquivos nos seguintes formatos: JPEG, PNG ou SVG.<br>O tamanho limite para imagens é de 1.5mb');
                    </script>";
                break;
            default:

                $fotoEmpresa = salvarFoto($_FILES['imagemEmpresa'], "img/");

                switch ($fotoEmpresa) {
                    case 0:
                        echo "<script>
                    alert('Houve um erro no upload da imagem de usuário. Tente novamente mais tarde.');
                    </script>";
                        break;
                    case 1:
                        echo "<script>
                    alert('a imagem do lojista arquivo esta em um formato não aceito ou é muito grande.<br>Aceitamos arquivos nos seguintes formatos: JPEG, PNG ou SVG.<br>O tamanho limite para imagens é de 1.5mb');
                    </script>";
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
                            echo "<script>
                                    alert('Houve um erro na realização do cadastro. Tente novamente mais tarde.');
                                </script>";
                        }
                }
        }
    }
    $conn->close();
}
?>
<!--PHP Login e cadastro-->

<body>

    <header>
        <nav class="cabecalhoSuperior">
            <div class="d-flex">
                <a href="restrito/usuario.php">Usuário</a>
                <h9>|</h9>
                <a href="contato.php">Suporte</a>
            </div>
        </nav>

        <nav class="cabecalhoInferior">
            <img src="img/img pg inicial/logoAmareloEscuro.png" alt="" data-aos="zoom-in">

            <form action="produtosBusca.php" class="pesquisaCentral" method="POST">
                <input type="text" placeholder="Busque Seus Produtos" name="nome">
                <button type="submit" name="filtro" value="preco"><img src="img/img pg padrao/lupa.png" alt=""></button>
            </form>

            <div class="d-flex">


            <?php if(isset($_SESSION['idUser'])) {
                    include_once "restrito/conexao.php";
                    $id = $_SESSION['idUser'];
                    $sql = "SELECT imagem_usuario FROM usuarios WHERE id = $id;";
                    $resultado = $conn->query($sql);
                    $linha = mysqli_fetch_assoc($resultado);
                    $imagemLogin = $linha['imagem_usuario'];

                    echo "<a href='restrito/usuario.php'> <img src='img/$imagemLogin' class='loginButton' data-bs-toggle='modal'> </a>";

                } else if (isset($_SESSION['idLojista'])) {
                    include_once "restrito/conexao.php";
                    $id = $_SESSION['idLojista'];
                    $sql = "SELECT imagem_lojista FROM lojistas WHERE id = $id;";
                    $resultado = $conn->query($sql);
                    $linha = mysqli_fetch_assoc($resultado);
                    $imagemLogin = $linha['imagem_lojista'];

                    echo "<a href='restrito/cadastroProduto.php'> <img src='img/$imagemLogin' class='loginButton' data-bs-toggle='modal'> </a>";

                } else{
                    echo "<img src='img/img pg padrao/userProfileProtótipo.png' class='loginButton' data-bs-toggle='modal'
                    data-bs-target='#exampleModal'>";

                } ?>

                <div class="dropdown">
                    <div src="" alt="" class="naoClicado" id="favoritos" data-bs-toggle="dropdown"
                        aria-expanded="false"></div>
                    <ul class="dropdown-menu" data-aos="fade-down">
                        <li><a class="dropdown-item" href="#">Itens</a></li>
                        <li><a class="dropdown-item" href="#">Favoritados</a></li>
                        <li><a class="dropdown-item" href="#">Pelo Usuário</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main id="main">


        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
            <div id="carouselExampleDark" class="carousel carousel-dark slide">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="3"
                        aria-label="Slide 4"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="10000">
                        <img src="img/img pg inicial/slogan.png" class="d-block" alt="...">
                    </div>
                    <div class="carousel-item" data-bs-interval="10000">
                        <img src="img/img pg inicial/sobre_mercazon.png" class="d-block" alt="...">
                    </div>
                    <div class="carousel-item" data-bs-interval="10000">
                        <img src="img/img pg inicial/dia_do_eletronico.png" class="d-block" alt="...">
                    </div>
                    <div class="carousel-item" data-bs-interval="10000">
                        <img src="img/img pg inicial/cadastrar-se.png" class="d-block" alt="...">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
                    data-bs-slide="prev">
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
                    data-bs-slide="next">
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <h3 class="h3 h3Categorias">Procure Por Uma Categoria</h3>

        <!-- Este carrosel foi obitido usando o Chat GPT (que, por sua vez, utlizo recursos do BootStrap), mas foi formatado e estilizado pelos devs do site -->
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-bs-touch="true"
            id="carrosselCategoriasId">
            <!-- data-ride="carousel", isso daí faz o carrosel andar sozinho, por dentro da div -->
            <div class="carousel-inner" id="carrosel_categorias">
                <div class="carousel-item active">
                    <div class="row">
                        <!--a div row define uma linha do carrosel (equivalente a uma imagem do carrosel)-->
                        <!--O BootStrap utiliza um sistema de grid que possui 12 colunas. O col-3 diz que a coluna (itens dentro dessa div) ocuparão um espaço equivalente a 3/12 (ou 1/4, um quarto) da linha (row, que é o elemento pai), se fosse, por exemplo, col-4, ele ocuparia um espaço de 4/12 (ou 1/3, um terço) da linha-->
                        <div class="col-3 categoriaCarroussel">
                            <!-- <a href="produtosBusca.php?categoria=roupa" class="linkCategoria"> -->
                            <a href="produtosBusca.php?categoria=roupa" class="linkCategoria">
                                <svg fill="#000000" width="16vw" height="16vh" viewBox="-1 0 19 19"
                                    xmlns="http://www.w3.org/2000/svg" class="cf-icon-svg">
                                    <path
                                        d="m15.867 7.593-1.534.967a.544.544 0 0 1-.698-.118l-.762-.957v7.256a.476.476 0 0 1-.475.475h-7.79a.476.476 0 0 1-.475-.475V7.477l-.769.965a.544.544 0 0 1-.697.118l-1.535-.967a.387.387 0 0 1-.083-.607l2.245-2.492a2.814 2.814 0 0 1 2.092-.932h.935a2.374 2.374 0 0 0 4.364 0h.934a2.816 2.816 0 0 1 2.093.933l2.24 2.49a.388.388 0 0 1-.085.608z" fill="#4ba6e2"/>
                                </svg>
                            </a>
                            <p class="pCatego">Roupas</p>
                        </div>

                        <div class="col-3 categoriaCarroussel">
                            <svg fill="#000000" height="800px" width="800px" version="1.1" id="Layer_1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    viewBox="0 0 512 512" xml:space="preserve">
                                        <path d="M385.58,169.702v-5.356c0-20.679-9.932-39.037-25.284-50.569V82.173h44.247V44.247h-44.247v-6.321 C360.296,17.067,343.23,0,322.37,0c-20.859,0-37.926,17.067-37.926,37.926v6.321H151.704c-43.988,0-80.006,34.743-82.073,78.227 c-32.729,8.407-56.989,38.161-56.989,73.476c0,41.825,34.027,75.852,75.852,75.852s75.852-34.027,75.852-75.852 c0-35.214-24.123-64.896-56.709-73.401c1.968-22.591,20.974-40.377,44.067-40.377h132.741v31.605 c-15.352,11.532-25.284,29.889-25.284,50.568v5.356c-66.465,25.501-113.778,89.979-113.778,165.31 c0,55.376,25.811,107.217,69.531,140.643V512h214.914v-36.344c43.72-33.428,69.531-85.268,69.531-140.643 C499.358,259.681,452.045,195.203,385.58,169.702z M385.58,347.654h-126.42v-37.926h126.42V347.654z" fill="#4ba6e2"/>
                            </svg>
                            <p class="pCatego">Cosméticos</p>
                        </div>
                        <div class="col-3 categoriaCarroussel">
                            <svg fill="#000000" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px" viewBox="0 0 244 260" xml:space="preserve">
                                <path d="M151.1,82c-2.6-4.9-6.5-8.9-11.3-11.7l43.5-61.3L173.6,2l-45.6,64.2c-1.2-0.1-2.4-0.2-3.7-0.2c-1.3,0-2.6,0.1-3.9,0.3 L86.8,14.9l-10,6.6l31.9,48.8c-4.7,2.8-8.6,6.9-11.2,11.7H2v160h23.9v16h192v-16H242V82H151.1z M185.9,218h-160V106h160V218z M213.9,218c-5.5,0-10-4.5-10-10s4.5-10,10-10s10,4.5,10,10S219.4,218,213.9,218z M213.9,179.8c-5.5,0-10-4.5-10-10s4.5-10,10-10 s10,4.5,10,10S219.4,179.8,213.9,179.8z" fill="#4ba6e2"/>
                            </svg>
                            <p class="pCatego">Eletrônicos</p>
                        </div>
                        <div class="col-3 categoriaCarroussel">
                            <svg fill="#000000" width="800px" height="800px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title>ionicons-v5-l</title><path d="M368,128h.09"/>
                                <path d="M479.55,96H388.49l8.92-35.66,38.32-13.05c8.15-2.77,13-11.43,10.65-19.71a16,16,0,0,0-20.54-10.73l-47,16a16,16,0,0,0-10.36,11.27L355.51,96H224.45c-8.61,0-16,6.62-16.43,15.23A16,16,0,0,0,224,128h2.75l1,8.66A8.3,8.3,0,0,0,236,144h0c39,0,73.66,10.9,100.12,31.52A121.9,121.9,0,0,1,371,218.07a123.4,123.4,0,0,1,10.12,29.51,7.83,7.83,0,0,0,3.29,4.88,72,72,0,0,1,26.38,86.43,7.92,7.92,0,0,0-.15,5.53A96,96,0,0,1,416,376c0,22.34-7.6,43.63-21.4,59.95a80.12,80.12,0,0,1-28.78,21.67,8,8,0,0,0-4.21,4.37,108.19,108.19,0,0,1-17.37,29.86l0,0a2.5,2.5,0,0,0,1.9,4.11h49.21a48.22,48.22,0,0,0,47.85-44.14L477.4,128H480a16,16,0,0,0,16-16.77C495.58,102.62,488.16,96,479.55,96Z" fill="#4ba6e2"/>
                                <path d="M108.69,320a23.87,23.87,0,0,1,17,7l15.51,15.51a4,4,0,0,0,5.66,0L162.34,327a23.87,23.87,0,0,1,17-7H375.92a8,8,0,0,0,8.08-7.92V312a40.07,40.07,0,0,0-32-39.2c-.82-29.69-13-54.54-35.51-72C295.67,184.56,267.85,176,236,176H164c-68.22,0-114.43,38.77-116,96.8A40.07,40.07,0,0,0,16,312h0a8,8,0,0,0,8,8Z" fill="#FFBE00"/>
                                <path d="M185.94,352a8,8,0,0,0-5.66,2.34l-22.14,22.15a20,20,0,0,1-28.28,0l-22.14-22.15a8,8,0,0,0-5.66-2.34H32.66A15.93,15.93,0,0,0,16.9,365.17,65.22,65.22,0,0,0,16,376c0,30.59,21.13,55.51,47.26,56,2.43,15.12,8.31,28.78,17.16,39.47C93.51,487.28,112.54,496,134,496H266c21.46,0,40.49-8.72,53.58-24.55,8.85-10.69,14.73-24.35,17.16-39.47,26.13-.47,47.26-25.39,47.26-56a65.22,65.22,0,0,0-.9-10.83A15.93,15.93,0,0,0,367.34,352Z" fill="#4ba6e2"/>
                            </svg>
                            <p class="pCatego">Lanches</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row">
                        <div class="col-3 categoriaCarroussel">
                            <svg fill="#000000" width="800px" height="800px" viewBox="0 0 15 15" id="confectionery" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13,4a1,1,0,0,0-1-1,1,1,0,0,0-2,0V5.0673A3.4808,3.4808,0,0,0,4.3583,9H2a1,1,0,0,0,0,2,1,1,0,0,0,1,1,1,1,0,0,0,2,0V9.9326A3.4807,3.4807,0,0,0,10.6417,6H13a1,1,0,0,0,0-2ZM7.5,9.9925A2.484,2.484,0,0,1,6.3184,5.319a1.0809,1.0809,0,0,1,.5459.307A2.1243,2.1243,0,0,1,7.25,7.0117l.001.9561A2.5821,2.5821,0,0,0,7.76,9.7031a1.5462,1.5462,0,0,0,.2591.2333A2.4861,2.4861,0,0,1,7.5,9.9925ZM8.6815,9.681a1.0813,1.0813,0,0,1-.5458-.307A2.1243,2.1243,0,0,1,7.75,7.9883l-.001-.9561A2.5821,2.5821,0,0,0,7.24,5.2969a1.5557,1.5557,0,0,0-.2592-.2334A2.4843,2.4843,0,0,1,8.6815,9.681Z" fill="#4ba6e2"/>
                            </svg>
                            <p class="pCatego">Doces</p>
                        </div>
                        <div class="col-3 categoriaCarroussel">
                            <svg fill="#000000" height="800px" width="800px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 511.997 511.997" xml:space="preserve">
                                <path d="M508.4,340.149l-85.3-128c-5.2-7.8-14.9-11.3-23.9-8.6c-9,2.7-15.1,11-15.1,20.4v42.7h-21.3v-42.7 c0-11.8-9.5-21.3-21.3-21.3H320v-85.3c0-11.8-9.5-21.3-21.3-21.3s-21.3,9.6-21.3,21.3v85.3h-42.7v-128H256 c11.8,0,21.3-9.6,21.3-21.3s-9.5-21.3-21.3-21.3h-42.7H64H21.3c-11.8-0.1-21.3,9.4-21.3,21.2s9.5,21.3,21.3,21.3h21.3v128 c-11.8,0-21.3,9.6-21.3,21.3v144.6c-13,11.9-21.3,28.7-21.3,47.5c0,35.3,28.7,64,64,64c27.8,0,51.2-17.9,60.1-42.7h7.9 c8.8,24.8,32.3,42.7,60.1,42.7c27.8,0,51.2-17.9,60.1-42.7h7.9c8.8,24.8,32.3,42.7,60.1,42.7c35.3,0,64-28.7,64-64 c0-18.8-8.3-35.6-21.3-47.4v-59.3H384v42.7c0,11.8,9.5,21.3,21.3,21.3h85.3c7.9,0,15.1-4.3,18.8-11.3 C513.2,355.049,512.8,346.649,508.4,340.149z M64,437.249c-11.8,0-21.3-9.6-21.3-21.3c0-11.8,9.6-21.3,21.3-21.3 s21.3,9.6,21.3,21.3S75.8,437.249,64,437.249z M85.3,74.649H192v128H85.3V74.649z M192,437.249c-11.8,0-21.3-9.6-21.3-21.3 c0-11.8,9.6-21.3,21.3-21.3s21.3,9.6,21.3,21.3S203.8,437.249,192,437.249z M256,330.649H128c-11.8,0-21.3-9.6-21.3-21.3 c0-11.7,9.5-21.3,21.3-21.3h128c11.8,0,21.3,9.6,21.3,21.3C277.3,321.049,267.8,330.649,256,330.649z M320,437.249 c-11.8,0-21.3-9.6-21.3-21.3c0-11.8,9.6-21.3,21.3-21.3c11.7,0,21.3,9.6,21.3,21.3S331.8,437.249,320,437.249z" fill="#4ba6e2"/>
                            </svg>
                            <p class="pCatego">Brinquedos</p>
                        </div>
                        <div class="col-3 categoriaCarroussel">
                            <svg fill="#000000" width="800px" height="800px" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <path d="M6.9023438 3.9980469C5.2863438 3.9980469 3.9726562 5.3440469 3.9726562 6.9980469L3.9726562 14L32.033203 14L32.033203 7C32.033203 5.346 30.720516 4 29.103516 4L6.9023438 3.9980469 z M 9 7C9.553 7 10 7.448 10 8L10 11C10 11.552 9.553 12 9 12C8.447 12 8 11.552 8 11L8 8C8 7.448 8.447 7 9 7 z M 3.9726562 16L3.9726562 40.001953C3.9726562 41.655953 5.2872969 43.001953 6.9042969 43.001953L8 43.001953L8 45C8 45.552 8.447 46 9 46L13 46C13.553 46 14 45.552 14 45L14 43.001953L22 43L22 23C22 20.791 23.791 19 26 19L32.033203 19L32.033203 16L3.9726562 16 z M 9 19C9.553 19 10 19.448 10 20L10 24C10 24.552 9.553 25 9 25C8.447 25 8 24.552 8 24L8 20C8 19.448 8.447 19 9 19 z M 27 21C25.346 21 24 22.346 24 24L24 43C24 44.654 25.346 46 27 46L43 46C44.654 46 46 44.654 46 43L46 24C46 22.346 44.654 21 43 21L27 21 z M 38 24C38.552 24 39 24.448 39 25C39 25.552 38.552 26 38 26C37.448 26 37 25.552 37 25C37 24.448 37.448 24 38 24 z M 42 24C42.552 24 43 24.448 43 25C43 25.552 42.552 26 42 26C41.448 26 41 25.552 41 25C41 24.448 41.448 24 42 24 z M 35 29C38.309 29 41 31.691 41 35C41 38.309 38.309 41 35 41C31.691 41 29 38.309 29 35C29 31.691 31.691 29 35 29 z M 35 31 A 4 4 0 0 0 35 39 A 4 4 0 0 0 35 31 z" fill="#4ba6e2"/>
                            </svg>
                            <p class="pCatego" style="word-break: break-word;">Eletrodomésticos</p>
                        </div>
                        <div class="col-3 categoriaCarroussel">
                            <svg fill="#000000" height="800px" width="800px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 503.467 503.467" xml:space="preserve">
                                    <path d="M76.8,51.2c-4.719,0-8.533,3.823-8.533,8.533V153.6c0,11.11-7.159,20.489-17.067,24.03V59.733 c0-4.71-3.814-8.533-8.533-8.533s-8.533,3.823-8.533,8.533v117.888c-9.907-3.533-17.067-12.911-17.067-24.021V59.733 c0-4.71-3.814-8.533-8.533-8.533S0,55.023,0,59.733V153.6c0,20.599,14.686,37.837,34.133,41.805v44.74 c-14.677,3.814-25.6,17.058-25.6,32.922v153.6c0,18.816,15.309,34.133,34.133,34.133S76.8,445.483,76.8,426.667v-153.6 c0-15.863-10.923-29.107-25.6-32.922v-44.74c19.447-3.968,34.133-21.205,34.133-41.805V59.733 C85.333,55.023,81.519,51.2,76.8,51.2z M42.667,315.733c-4.71,0-8.533-3.823-8.533-8.533s3.823-8.533,8.533-8.533 c4.71,0,8.533,3.823,8.533,8.533S47.377,315.733,42.667,315.733z" fill="#4ba6e2"/>
                                    <path d="M486.4,243.678V51.2c0-3.448-2.074-6.562-5.265-7.885c-3.183-1.314-6.861-0.597-9.301,1.852 c-23.629,23.62-36.634,55.031-36.634,88.439v52.215c0,19.32,4.565,38.656,13.201,55.927l1.613,3.226 c-8.934,6.161-14.814,16.444-14.814,28.092v153.6c0,18.816,15.309,34.133,34.133,34.133s34.133-15.317,34.133-34.133v-153.6 C503.467,260.488,496.555,249.6,486.4,243.678z M452.267,133.606c0-21.009,5.956-41.096,17.067-58.342v163.669h-3.26 l-2.406-4.813c-7.458-14.916-11.401-31.616-11.401-48.299V133.606z M469.333,315.733c-4.71,0-8.533-3.823-8.533-8.533 s3.823-8.533,8.533-8.533s8.533,3.823,8.533,8.533S474.044,315.733,469.333,315.733z" fill="#4ba6e2"/>
                                    <path d="M256,187.731c-46.259,0-85.333,39.083-85.333,85.333s39.074,85.333,85.333,85.333s85.333-39.083,85.333-85.333 S302.259,187.731,256,187.731z" fill="#FFBE00"/>
                                    <path d="M256,110.752c-89.404,0-162.133,72.738-162.133,162.133c0,89.404,72.73,162.133,162.133,162.133 s162.133-72.73,162.133-162.133C418.133,183.49,345.404,110.752,256,110.752z M256,375.465c-56.465,0-102.4-45.935-102.4-102.4 c0-56.465,45.935-102.4,102.4-102.4c56.465,0,102.4,45.935,102.4,102.4C358.4,329.53,312.465,375.465,256,375.465z" fill="#FFBE00"/>
                            </svg>
                            <p class="pCatego">Marmitas</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row">
                        <div class="col-3 categoriaCarroussel">
                            <svg fill="#000000" width="800px" height="800px" viewBox="0 0 14 14" role="img" focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path d="M 11.285714,1 2.7142857,1 C 1.7674107,1 1,1.76741 1,2.71429 l 0,8.57142 C 1,12.23259 1.7674107,13 2.7142857,13 l 8.5714283,0 C 12.232589,13 13,12.23259 13,11.28571 L 13,2.71429 C 13,1.76741 12.232589,1 11.285714,1 Z m -6.8571426,9.42857 c -0.4733035,0 -0.8571428,-0.38384 -0.8571428,-0.85714 0,-0.4733 0.3838393,-0.85714 0.8571428,-0.85714 0.4733036,0 0.8571429,0.38384 0.8571429,0.85714 0,0.4733 -0.3838393,0.85714 -0.8571429,0.85714 z m 0,-2.57143 C 3.9552679,7.85714 3.5714286,7.4733 3.5714286,7 c 0,-0.4733 0.3838393,-0.85714 0.8571428,-0.85714 0.4733036,0 0.8571429,0.38384 0.8571429,0.85714 0,0.4733 -0.3838393,0.85714 -0.8571429,0.85714 z m 0,-2.57143 c -0.4733035,0 -0.8571428,-0.38383 -0.8571428,-0.85714 0,-0.4733 0.3838393,-0.85714 0.8571428,-0.85714 0.4733036,0 0.8571429,0.38384 0.8571429,0.85714 0,0.47331 -0.3838393,0.85714 -0.8571429,0.85714 z m 5.1428572,5.14286 c -0.4733036,0 -0.8571429,-0.38384 -0.8571429,-0.85714 0,-0.4733 0.3838393,-0.85714 0.8571429,-0.85714 0.4733034,0 0.8571424,0.38384 0.8571424,0.85714 0,0.4733 -0.383839,0.85714 -0.8571424,0.85714 z m 0,-2.57143 C 9.098125,7.85714 8.7142857,7.4733 8.7142857,7 c 0,-0.4733 0.3838393,-0.85714 0.8571429,-0.85714 0.4733034,0 0.8571424,0.38384 0.8571424,0.85714 0,0.4733 -0.383839,0.85714 -0.8571424,0.85714 z m 0,-2.57143 c -0.4733036,0 -0.8571429,-0.38383 -0.8571429,-0.85714 0,-0.4733 0.3838393,-0.85714 0.8571429,-0.85714 0.4733034,0 0.8571424,0.38384 0.8571424,0.85714 0,0.47331 -0.383839,0.85714 -0.8571424,0.85714 z" fill="#4ba6e2"/>
                            </svg>
                            <p class="pCatego">Jogos</p>
                        </div>
                        <div class="col-3 categoriaCarroussel">
                            <svg fill="#000000" width="800px" height="800px" viewBox="-13.57 0 65.148 65.148" xmlns="http://www.w3.org/2000/svg">
                                <g id="_35" data-name="35" transform="translate(-295.999 -1082.927)">
                                    <path id="Path_134" data-name="Path 134" d="M307.427,1116.274c-.355-1.459-.716-2.787-1.069-3.836-.049-.145-.1-.254-.146-.391l.008-2.478h.1a1.758,1.758,0,0,0,1.9-1.561l.043-19.056s0-1.038-.034-1.393a10.026,10.026,0,0,0-.241-1.423,3.014,3.014,0,0,0-2.727-2.335,3.12,3.12,0,0,0-2.833,2.235,5.345,5.345,0,0,0-.178.87c-.039.509-.081,1.31-.081,1.31l-.048,19.776a1.652,1.652,0,0,0,1.473,1.5l-.005,2.827,0,.784c-.789,1.541-2.1,4.211-3.408,7.42-2.08,5.087-4.167,11.5-4.185,16.879a12.98,12.98,0,0,0,1.263,6.064,7.9,7.9,0,0,0,4.486,3.841,7.732,7.732,0,0,0,1.324.346,2.8,2.8,0,0,0,.548.212c.108.03.257.032.381.053l.005.005a.951.951,0,0,0,.49.151.836.836,0,0,0,.329-.1,2.711,2.711,0,0,0,1.175-.364,10.079,10.079,0,0,0,3.822-1.529c2.169-1.438,4.128-4.251,4.131-9.206C313.965,1132.188,312.257,1125.57,307.427,1116.274Zm.344,4.144c.863,4.14,1.586,8.35,1.866,10.012.074.448.117.711.122.741a54.5,54.5,0,0,1,.594,6.822,11.76,11.76,0,0,1-1.351,6.079,6.907,6.907,0,0,1-1.553,1.732,11.015,11.015,0,0,0,.9-4.741c.005-.82-.039-1.628-.072-2.393-.15-3.314-.377-6.6-.825-9.9-.433-3.169-.989-6.316-1.574-9.456-.111-1.708-.236-3.491-.381-5.4.47.848.956,1.71,1.376,2.514C307.177,1117.693,307.483,1119.027,307.771,1120.418Zm-2.123,26.458a7.623,7.623,0,0,0,.3-1.042,45.932,45.932,0,0,0,.621-8.924c.008-3.177-.109-7.28-.386-12.465.241,1.465.5,2.928.707,4.4.443,3.275.673,6.546.82,9.849.034.771.073,1.57.071,2.369a9.1,9.1,0,0,1-1.385,5.458A5.31,5.31,0,0,1,305.648,1146.876Zm.356-9.971c-.012,5.975-.458,8.785-.943,9.928a2.09,2.09,0,0,1-.109.2,4.439,4.439,0,0,1-.81.132s-.007-.005-.014,0c-.038,0-.078-.015-.116-.018-.071-.091-.126-.174-.14-.194v0l-.022-.033-.024-.03a1.557,1.557,0,0,1-.29-.672,9.655,9.655,0,0,1-.233-1.433c-.122-1.171-.171-2.735-.169-4.548.019-7.075.863-17.95,1.45-24.7q.372,1.923.728,3.852C305.816,1127.092,306.014,1132.775,306,1136.905Zm-1.167-23.058-.072-.371,0-.005.124,0c.03.369.053.722.08,1.083C304.926,1114.323,304.884,1114.084,304.837,1113.847Zm-.77,1.081c-.59,6.692-1.48,17.992-1.5,25.31a28.746,28.746,0,0,0,.42,6.121,2.331,2.331,0,0,0,.315.7c-.047-.011-.1-.009-.145-.021a4.9,4.9,0,0,1-1.722-2.49,15.28,15.28,0,0,1-.711-3.339,26.12,26.12,0,0,1-.167-3.182c0-1.359.079-2.719.147-4.079a87.485,87.485,0,0,1,1.33-10.165c.346-2.061.716-4.113,1.131-6.152C303.461,1116.728,303.733,1115.823,304.067,1114.928Zm-2.592,8.761a88.218,88.218,0,0,0-1.337,10.23c-.067,1.359-.14,2.728-.145,4.107a27.014,27.014,0,0,0,.166,3.25,16,16,0,0,0,.743,3.461,7.452,7.452,0,0,0,.668,1.394,5.3,5.3,0,0,1-1.062-1.278,13.938,13.938,0,0,1-1.687-7.226c.013-4.48,1.355-9.317,2.322-13.069.156-.606.332-1.193.493-1.789C301.582,1123.076,301.525,1123.383,301.475,1123.689Zm4.424-10.213h.186c.07.232.141.469.213.72C306.165,1113.958,306.037,1113.719,305.9,1113.476Zm-.672-27.836c.58,0,1.049.652,1.047,1.456s-.472,1.452-1.051,1.452-1.048-.653-1.046-1.456S304.649,1085.64,305.227,1085.64Zm-7.465,57.566a12.435,12.435,0,0,1-1.2-5.8c.01-4.582,1.59-10.04,3.37-14.716,1.059-2.788,2.185-5.285,3.091-7.175-.15.655-.272,1.313-.406,1.969-.745,2.292-1.421,4.592-2.022,6.932-.965,3.75-2.329,8.622-2.342,13.211a14.53,14.53,0,0,0,1.764,7.515,6.155,6.155,0,0,0,.855,1.14A7.2,7.2,0,0,1,297.762,1143.206Zm11.746,2.4a9.733,9.733,0,0,1-2.292,1.116,7.66,7.66,0,0,0,2.271-2.359,12.3,12.3,0,0,0,1.432-6.373,55.027,55.027,0,0,0-.6-6.9v-.01s-.53-3.3-1.291-7.266c-.319-1.666-.678-3.448-1.052-5.161,3.973,8.124,5.427,14.034,5.413,18.217C313.373,1141.686,311.519,1144.268,309.508,1145.609Z" fill="#FFBE00"/>
                                    <path id="Path_135" data-name="Path 135" d="M333.938,1098.082a1.645,1.645,0,0,0,.061-.283c.015-1.43-2.1-2.652-4.918-2.962l1.2-9.776a2.373,2.373,0,0,0-2.144-2.134,1.927,1.927,0,0,0-1.071.337.648.648,0,0,0-.076.049,2.572,2.572,0,0,0-.487.445,1.053,1.053,0,0,0-.07.092,2.336,2.336,0,0,0-.323.527.843.843,0,0,0-.028.081,1.532,1.532,0,0,0-.126.551l.79,9.794c-2.91.2-5.141,1.381-5.156,2.839a1.427,1.427,0,0,0,.064.329l-.008-.026c-.013-.081-.051-.157-.056-.234l0,0,0,.208c0,.009,0,.019,0,.024l0,.014-.254,19.9c-.091,5.952-.167,11.479-.172,13.555l-.011.835h.021c-.01.067-.016.134-.021.2-.014,1.418,2.068,2.627,4.859,2.952l-1.137,9.635a1.647,1.647,0,0,0,.107.555c.01.027.015.057.027.083a2.654,2.654,0,0,0,.3.533c.023.034.051.07.073.1a2.59,2.59,0,0,0,.467.458.458.458,0,0,0,.081.054,1.914,1.914,0,0,0,1.052.376,2.373,2.373,0,0,0,2.216-2.055l-.834-9.692c2.931-.2,5.182-1.377,5.2-2.847a1.324,1.324,0,0,0-.017-.2l.017-.005.437-34.537-.005-.005A1.367,1.367,0,0,1,333.938,1098.082Zm-6.181,2.6a12.108,12.108,0,0,0,1.232-.045.034.034,0,0,1-.016.005c-.393.03-.8.046-1.216.04a12.138,12.138,0,0,1-1.225-.076h-.016A12.424,12.424,0,0,0,327.757,1100.679Zm6.039-2.175c-.024.046-.067.091-.1.136.036-.05.076-.1.1-.152C333.8,1098.493,333.8,1098.5,333.8,1098.5Zm-.554.658c.057-.05.117-.095.169-.146a.072.072,0,0,1-.011.015C333.354,1099.077,333.291,1099.117,333.242,1099.162Zm-.432.34c-.066.044-.148.08-.218.122l-.013.005c.081-.045.173-.088.244-.137C332.82,1099.5,332.815,1099.5,332.81,1099.5Zm-.759.406c-.091.04-.2.071-.289.109-.015.007-.035.013-.047.018.116-.046.245-.087.354-.137C332.061,1099.9,332.057,1099.908,332.051,1099.908Zm-.907.335c-.122.034-.258.062-.383.091-.038.01-.078.019-.119.028.175-.043.358-.079.522-.13C331.159,1100.237,331.149,1100.237,331.144,1100.243Zm-1.032.244c-.173.033-.362.049-.543.071l-.2.027c.259-.027.52-.058.765-.1C330.124,1100.481,330.12,1100.487,330.112,1100.487Zm-3.861.077c-.291-.042-.583-.083-.857-.138l-.025-.008c.316.069.651.118,1,.159C326.325,1100.572,326.289,1100.567,326.251,1100.564Zm-1.273-.248c-.208-.052-.421-.1-.614-.165-.01,0-.02-.009-.03-.012.239.079.5.144.759.2C325.054,1100.337,325.016,1100.324,324.978,1100.316Zm-1.1-.352c-.137-.056-.286-.106-.415-.167a.288.288,0,0,0-.029-.015c.165.076.353.147.537.218A.871.871,0,0,0,323.883,1099.964Zm-.9-.441c-.084-.05-.182-.1-.263-.152a.115.115,0,0,0-.023-.02c.1.073.233.141.355.21C323.031,1099.548,323.008,1099.538,322.985,1099.523Zm-.669-.492c-.053-.052-.122-.1-.173-.149,0-.008-.007-.013-.012-.025a1.931,1.931,0,0,0,.225.2C322.344,1099.051,322.326,1099.041,322.316,1099.031Zm-.426-.506-.02-.027a1.74,1.74,0,0,1-.1-.154.109.109,0,0,1-.008-.029A1.675,1.675,0,0,0,321.89,1098.525Z" fill="#4ba6e2"/>
                                </g>
                            </svg>
                            <p class="pCatego">Utensílios</p>
                        </div>
                        <div class="col-3 categoriaCarroussel">
                            <svg fill="#000000" height="800px" width="800px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 503.607 503.607" xml:space="preserve">
                                <path d="M377.705,243.41h-8.393v-16.787h8.393c4.633,0,8.393-3.752,8.393-8.393s-3.76-8.393-8.393-8.393h-8.393v-58.754 c0-20.262-14.445-37.225-33.574-41.111V92.328c0-6.471-2.526-12.322-6.547-16.787c4.02-4.465,6.547-10.315,6.547-16.787 s-2.526-12.322-6.547-16.787c4.02-4.465,6.547-10.316,6.547-16.787c0-13.883-11.298-25.18-25.18-25.18H176.262 c-13.883,0-25.18,11.298-25.18,25.18c0,6.471,2.526,12.322,6.547,16.787c-4.021,4.465-6.547,10.315-6.547,16.787 s2.526,12.322,6.547,16.787c-4.021,4.465-6.547,10.316-6.547,16.787v17.643c-19.129,3.886-33.574,20.849-33.574,41.111v167.869 c0,20.262,14.445,37.225,33.574,41.111v17.643c0,6.471,2.526,12.322,6.547,16.787c-4.021,4.465-6.547,10.316-6.547,16.787 c0,6.471,2.526,12.322,6.547,16.787c-4.021,4.465-6.547,10.316-6.547,16.787c0,6.471,2.526,12.322,6.547,16.787 c-4.021,4.465-6.547,10.316-6.547,16.787c0,13.883,11.298,25.18,25.18,25.18h134.295c13.883,0,25.18-11.298,25.18-25.18 c0-6.471-2.526-12.322-6.547-16.787c4.02-4.465,6.547-10.315,6.547-16.787c0-6.471-2.526-12.322-6.547-16.787 c4.02-4.465,6.547-10.316,6.547-16.787c0-6.471-2.526-12.322-6.547-16.787c4.02-4.465,6.547-10.316,6.547-16.787v-17.643 c19.129-3.886,33.574-20.849,33.574-41.111v-58.754h8.393c4.633,0,8.393-3.752,8.393-8.393 C386.098,247.162,382.338,243.41,377.705,243.41z M176.262,16.787h134.295c4.625,0,8.393,3.76,8.393,8.393 s-3.769,8.393-8.393,8.393H176.262c-4.625,0-8.393-3.76-8.393-8.393S171.637,16.787,176.262,16.787z M167.869,92.328 c0-4.633,3.769-8.393,8.393-8.393h134.295c4.625,0,8.393,3.76,8.393,8.393v16.787H167.869V92.328z M310.557,453.246H176.262 c-4.625,0-8.393-3.76-8.393-8.393s3.769-8.393,8.393-8.393h134.295c4.625,0,8.393,3.76,8.393,8.393 S315.182,453.246,310.557,453.246z M318.951,377.705c0,4.633-3.769,8.393-8.393,8.393H176.262c-4.625,0-8.393-3.76-8.393-8.393 v-16.787h151.082V377.705z M327.344,310.557c0,4.642-3.76,8.393-8.393,8.393H167.869c-4.633,0-8.393-3.752-8.393-8.393V159.475 c0-4.642,3.76-8.393,8.393-8.393h151.082c4.633,0,8.393,3.752,8.393,8.393V310.557z" fill="#4ba6e2"/>
                                <path d="M251.803,176.262c0,4.642-3.76,8.393-8.393,8.393s-8.393-3.752-8.393-8.393v-8.393h-58.754v58.754h8.393 c4.633,0,8.393,3.752,8.393,8.393c0,4.642-3.76,8.393-8.393,8.393h-8.393v58.754h58.754v-8.393c0-4.642,3.76-8.393,8.393-8.393 s8.393,3.752,8.393,8.393v8.393h58.754V243.41h-8.393c-4.633,0-8.393-3.752-8.393-8.393c0-4.642,3.76-8.393,8.393-8.393h8.393 v-58.754h-58.754V176.262z M276.984,235.016c0,4.642-3.76,8.393-8.393,8.393h-25.18c-4.633,0-8.393-3.752-8.393-8.393v-33.574 c0-4.642,3.76-8.393,8.393-8.393s8.393,3.752,8.393,8.393v25.18h16.787C273.223,226.623,276.984,230.375,276.984,235.016z" fill="#FFBE00"/>
                            </svg>
                            <p class="pCatego">Acessórios</p>
                        </div>
                        <div class="col-3 categoriaCarroussel">
                            <svg fill="#000000" height="800px" width="800px" version="1.1" id="Icons" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve">
                                <path d="M29,25H3c-0.6,0-1,0.4-1,1s0.4,1,1,1h26c0.6,0,1-0.4,1-1S29.6,25,29,25z" fill="#FFBE00"/>
                                <path d="M25.6,15.6l-5.5-0.7C16.6,14.4,14,11.5,14,8c0-0.6-0.4-1-1-1H3C2.4,7,2,7.4,2,8v15c0,0.6,0.4,1,1,1h26c0.6,0,1-0.4,1-1v-2.5 C30,18,28.1,15.9,25.6,15.6z M8,18c-1.7,0-3-1.3-3-3s1.3-3,3-3s3,1.3,3,3S9.7,18,8,18z M28,22h-7c0-1.6,0.7-3.1,1.9-4.2l0.6-0.5 l1.9,0.2c1.5,0.2,2.6,1.5,2.6,3V22z" fill="#4ba6e2"/>
                            </svg>
                            <p class="pCatego">Calçados</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- A seguir, os botões para mexer o carrosel (irão ficar nas telas de pc) (tirar da tela de celular quando for confirmado que funciona o touch screen) -->

            <!-- <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev" id="side-btn-left">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next" id="side-btn-right">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a> -->
        </div>


        <h3 class="h3 h3LojasPerto">Lojas perto de você</h3>


        <div class="lojasPerto">
            <div class="lojasPerto-item">
                <img src="imgs/monochrome-image-8598798_640.jpg" class="d-block" alt="Imagem 2">
                <p class="pLojasPerto">Loja do Marcos</p>
            </div>

            <div class="lojasPerto-item">
                <img src="imgs/monochrome-image-8598798_640.jpg" class="d-block" alt="Imagem 2">
                <p class="pLojasPerto">Loja do Marcos</p>
            </div>
        </div>

        <button class="bLojasPerto" id="btn-p3" onclick="location.href='lojas_gerais.html'" data-aos="fade-right">Ver Todas as Lojas</button>

        <h3 class="h3 h3MaisBuscados">Produtos Mais Buscados</h3>

        <div class="containerCards produtosMaisBuscados">


            <?php

            include_once "restrito/conexao.php";
            
            $sql = "SELECT p.id, p.contador_cliques, p.nome, p.preco, p.imagem, l.nome_estabelecimento
            FROM produtos AS p JOIN lojistas as l ON p.id_lojista = l.id ORDER BY contador_cliques DESC LIMIT 8;";
            
            $resultado = $conn->query($sql);
            
            while ($linha = mysqli_fetch_assoc($resultado)) {
                $nome = $linha['nome'];
                $imagem = $linha['imagem'];
                $preco = $linha['preco'];
                $nomeLoja = $linha['nome_estabelecimento'];
                $id = $linha['id'];
            
                echo "
                <div class='card' onclick=\"location.href='produto.php?id=$id'\">
                <div class='parteSuperiorCard'>
                <img src='img/$imagem' alt='$nome'>
                <form target='hiddenFrame' action='restrito/favoritar.php' method='POST'>
                    <input type='hidden' name='idFavorito' value='$id'>
                    <input type='hidden' name='user' value='$user'>
                    <button type='submit' value='Favoritar' name='favoritoSubmit style='z-index: 100 !important''>
                        <svg class='favoritaCoracao coracaoFavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='red' class='bi bi-heart-fill' viewBox='0 0 16 16'>
                        <path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314' />
                        </svg>
                    </button>
                </form>
    
        </div>
        <div class='parteInferiorCard'>
            <h4>$nome</h4>
            <h6>Nome da Loja</h6>
            <h6>R$ $preco</h6>
            <a class='btn-p4' href=''>Ver Produto</a>
        </div>
    </div>";
            }

            ?>
  <iframe name="hiddenFrame" style="display:none;"></iframe> <!-- Iframe invisível -->

        </div>


        <!-- Cadastre-se com imagem de fundo -->

        <?php
        //Isso aqui diz que: se o usuário estiver logado, vai escrever 'User Logado' (na versão final não vai escrever nada), do contrário, vai ter a estrutura do cadastroMaisImg.
        if (isset($_SESSION['idUser'])) {
        
            include_once "restrito/conexao.php";
        
            $sql = "SELECT p.id, p.contador_cliques, p.nome, p.preco, p.preco_promocao, p.imagem, 100 - (p.preco_promocao * 100) / p.preco AS porcentagem, l.nome_estabelecimento FROM produtos AS p JOIN lojistas as l ON p.id_lojista = l.id WHERE promocao_ativa = 1 ORDER BY contador_cliques DESC LIMIT 8;";
        
            $resultado = $conn->query($sql);
        
            echo "<div class='containerCards produtosMaisBuscados'>";
        
            while ($linha = mysqli_fetch_assoc($resultado)) {
                $nome = $linha['nome'];
                $imagem = $linha['imagem'];
                $preco = $linha['preco'];
                $nomeLoja = $linha['nome_estabelecimento'];
                $porcentagem = intval($linha['porcentagem']);
                $pormocao = $linha['preco_promocao'];
                $id = $linha['id'];
        
                echo "
                <div class='card' onclick=\"location.href='produto.php?id=$id'\">
                <div class='parteSuperiorCard'>
                <img src='../img/$imagem' alt='$nome'>
                <form target='hiddenFrame' action='restrito/favoritar.php' method='POST'>
                    <input type='hidden' name='idFavorito' value='$id'>
                    <input type='hidden' name='user' value='$user'>
                    <button type='submit' value='Favoritar' name='favoritoSubmit' style='z-index: 100 !important'>
                        <svg class='favoritaCoracao coracaoFavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='red' class='bi bi-heart-fill' viewBox='0 0 16 16'>
                        <path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314' />
                        </svg>
                    </button>
                </form>
    
        </div>
        <div class='parteInferiorCard'>
            <h4>$nome</h4>
            <h6>Nome da Loja</h6>
            <h6>R$ $preco</h6>
            <a class='btn-p4' href=''>Ver Produto</a>
        </div>
    </div>";
            }
            echo "</div>";
        
        } else {
            echo '<div class="cadastroMaisimg">
                        <p>Quer receber os melhores descontos da lojinha da esquina?</p>
                        <button id="btn-p2" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Cadastre-se</button>
                        </div>';
        }
        ?>


            <div class="perguntasEmbaixo">
                <div class="perguntasEmbaixo_child">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16">
                        <path
                            d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z" />
                    </svg>

                    <div class="perguntasEmbaixo_child-textos">
                        <p>Quer anunciar seus produtos?</p>

                        <a href="guiaDoLojista.php">Clique aqui</a>
                    </div>
                </div>

                <div class="perguntasEmbaixo_child">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-exclamation-triangle"
                        viewBox="0 0 16 16">
                        <path
                            d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z" />
                        <path
                            d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                    </svg>

                    <div class="perguntasEmbaixo_child-textos">
                        <p>Está com algum problema?</p>

                        <a href="FAQ.php">Acesse o FAQ</a>
                    </div>
                </div>

                <div class="perguntasEmbaixo_child">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-chat-dots"
                        viewBox="0 0 16 16">
                        <path
                            d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                        <path
                            d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318l-.003.011a11 11 0 0 1-.244.637c-.079.186.074.394.273.362a22 22 0 0 0 .693-.125m.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6-3.004 6-7 6a8 8 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a11 11 0 0 0 .398-2" />
                    </svg>

                    <div class="perguntasEmbaixo_child-textos">
                        <p>Você tem alguma dúvida?</p>

                        <a href="Contato.php">Fale conosco</a>
                    </div>
                </div>
            </div>

    </main>


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
                        <input type="email" name="email" id="emailL"><br>

                        <label for="senhaL">Senha</label><br>
                        <div>
                            <img class="olho" src="img/img pg padrao/olho.png" alt="">
                            <input type="password" name="senha" id="senhaL">
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
                    <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <u>Cadastre-Se</u>
                    </button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalLojistaLogin">
                        <u>Entrar como lojista</u>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de login -->
    <!-- Modal de login lojista -->
    <div class="modal fade" id="modalLojistaLogin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Entrar como lojista</h1>
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
                        <input type="email" name="email" id="emailL"><br>

                        <label for="senhaL">Senha</label><br>
                        <div>
                            <img class="olho" src="img/img pg padrao/olho.png" alt="">
                            <input type="password" name="senha" id="senhaL">
                        </div>


                        <a href="">Esqueceu Sua Senha?</a>

                        <br>
                        <br>
                        <div id="loginBotao">
                            <input name="loginLojista" type="submit" value="Login">
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <!--Botão pro modal de cadastro-->
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalLojistaCadastro">
                        <u>Cadastre-Se como lojista</u>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de login lojista -->

    <!-- Modal de cadastro lojista -->
    <div class="modal fade" id="modalLojistaCadastro" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastre-se como lojista!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <p>Cadatre-se com</p>
                    </div>

                    <div class="imagensLoginCadastro">
                        <img src="img/img pg padrao/face.png" alt="">
                        <img src="img/img pg padrao/google.png" alt="">
                    </div>

                    <form action="index.php" method="post" enctype="multipart/form-data">

                        <label for="nomeCadastroLojista">Nome</label>
                        <input type="text" name="nome" id="nomeCadastroLojista">
                        <label for="nomeEstabelecimento">Nome do seu estabelecimento</label>
                        <input type="text" name="nomeEstabelecimento" id="nomeEstabelecimento">
                        <label for="enderecoLojista">Endereço do seu estabelecimento</label>
                        <input type="text" name="endereco" id="enderecoLojista">
                        <label for="emailLojista">E-mail</label>
                        <input type="email" name="email" id="emailLojista">
                        <label for="telefone">Telefone</label>
                        <input type="text" name="telefone" id="telefone">

                        <label for="imagemLojista">Sua foto</label>
                        <input type="file" name="imagemUsuario" placeholder="imagem_usuario" accept="image/*"
                            id="imagemLojista">
                        <label for="imagemEstabelecimentoLojista">Foto do seu estabelecimento</label>
                        <input type="file" name="imagemEmpresa" placeholder="imagem_empresa" accept="image/*"
                            id="imagemEstabelecimentoLojista">

                        <label for="senhaL">Senha</label><br>
                        <div>
                            <img class="olho" src="img/img pg padrao/olho.png" alt="">
                            <input type="password" name="senha" id="senhaL">
                        </div>
                        <br>
                        <br>
                        <div id="loginBotao">
                            <input name="cadastroLojistaSubmit" type="submit" value="Cadastre-se">
                        </div>
                    </form>

                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    <!-- Modal de cadastro lojista -->

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
                        <input type="text" name="nome" id="nomeCa"><br>

                        <label for="emailC">E-mail</label><br>
                        <input type="email" name="email" id="emailC"><br>

                        <label for="senhaC">Senha</label><br>
                        <input type="password" name="senha" id="senhaC"><br>

                        <label for="enderecoC">Endereço</label><br>
                        <input type="text" name="endereco" id="enderecoC"><br>

                        <label for="dataNcC">Data de Nascimento</label><br>
                        <input type="date" name="data_nascimento" id="dataNcC"><br>
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

    <footer>

        <div class="footerInferior">
            <div class="linksPaginasFooter">
                <div>
                    <h5>Comprar</h5>
                    <a href="produtosBusca.php">Todos Produtos</a>
                    <a href="guiaDoLojista.php">Anuncie Aqui</a>
                </div>

                <div>
                    <h5>Sobre</h5>
                    <a href="#">Nossa História</a>
                    <a href="#">Quem Somos?</a>
                </div>

                <div>
                    <h5>Ajuda</h5>
                    <a href="Contato.php">Contate-nos</a>
                    <a href="guiaDoLojista.php">Guia do Lojista</a>
                </div>

                <div>
                    <h5>Nossas redes sociais</h5>
                    <div id="redesSociais">
                        <img src="img/img pg padrao/face.png" alt="">
                        <img src="img/img pg padrao/instagram.png" alt="">
                    </div>
                </div>
            </div>

            <div class="frasesFinaisFooter">
                <p>2024 All Rights Reserved</p>
                <a href="#">Termos de uso</a>
            </div>
        </div>

    </footer>

    <iframe name="hiddenFrame" style="display:none;"></iframe> <!-- Iframe invisível -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>

<!-- <a href="produtosBusca.php?categoria=roupa" class="linkCategoria">
<img src="imgs/monochrome-image-8598798_640.jpg" class="d-block foto" alt="Imagem 1" id="imagemProduto"> -->