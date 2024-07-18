<?php
include_once 'restrito/conexao.php';
// include_once 'validarUsuario.php';
session_start();
if(isset($_SESSION['idUser'])){
    $user = $_SESSION['idUser'];
} else if (isset($_SESSION['idLojista'])){
    $user = $_SESSION['idLojista'];
} else {
    $user = " ";
}
$_SESSION['sql'];
$_SESSION['sqlContadora'];
?>

<?php

// Configuração da paginação
$num_items_por_pagina = 16; // Número de itens por página
$pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página atual, padrão é 1
$offset = ($pagina_atual - 1) * $num_items_por_pagina; // Calcular o offset

// Consulta SQL com LIMIT e OFFSET para implementar a paginação
// $sql = "SELECT * FROM produtos WHERE id_lojista = $user LIMIT $offset, $num_items_por_pagina";
// $result = $conn->query($sql);

// função que compara a string de uma variavel com um argumento fornecido, se for true ela retorna checked, senão retorna uma string vazia. 
function checarFiltro($dado, $argumento)
{
    if (isset($dado)) {
        if ($dado == $argumento) {
            return 'checked';
        } else {
            return ' ';
        }
    } else {
        return ' ';
    }
}
function checarOrdenm($dado, $argumento)
{
    if (isset($dado)) {
        if ($dado == $argumento) {
            return 'ordemMarcada';
        } else {
            return ' ';
        }
    } else {
        return ' ';
    }
}

$categoria = "";
$marca = "";
$genero = "";
$cor = "";
$precoMaior = 0;
$precoMenor = 90000;
$ordem = 'preco';
$nome = isset($_POST['nome']) ? $_POST['nome'] : " ";

if (isset($_POST['filtro'])) {
    $precoMaior = isset($_POST['precoMaior']) ? $_POST['precoMaior'] : 0;
    $precoMenor = isset($_POST['precoMenor']) ? $_POST['precoMenor'] : 90000;
    $nome = $_POST['nome'];
    $ordem = $_POST['filtro'];

    $sql = "SELECT p.id, p.nome, p.preco, p.imagem, l.nome_estabelecimento
                FROM produtos AS p JOIN lojistas as l ON p.id_lojista = l.id
                WHERE ";
    $sqlContadora = "SELECT COUNT(id) AS total FROM produtos AS p WHERE ";

    if (isset($_POST['categoria']) && $_POST['categoria']) {
        $categoria = $_POST['categoria'];
        $sql .= "p.categoria = '$categoria' AND ";
        $sqlContadora .= "p.categoria = '$categoria' AND ";
    }
    if (isset($_POST['marca']) && $_POST['marca']) {
        $marca = $_POST['marca'];
        $sql .= "p.marca = '$marca' AND ";
        $sqlContadora .= "p.marca = '$marca' AND ";
    }
    if (isset($_POST['genero']) && $_POST['genero']) {
        $genero = $_POST['genero'];
        $sql .= "p.genero = '$genero' AND ";
        $sqlContadora .= "p.genero = '$genero' AND ";
    }
    if (isset($_POST['cor']) && $_POST['cor']) {
        $cor = $_POST['cor'];
        $sql .= "p.cor = '$cor' AND ";
        $sqlContadora .= "p.cor = '$cor' AND ";
    }
    $pesquisarNome = $nome ? "p.nome LIKE '%$nome%' AND" : "";
    $sql = $sql . "$pesquisarNome p.preco BETWEEN $precoMaior AND $precoMenor ORDER BY $ordem LIMIT $offset, $num_items_por_pagina;";
    $sqlContadora = $sqlContadora . "$pesquisarNome preco BETWEEN $precoMaior AND $precoMenor;";
    $_SESSION['sql'] = $sql;
    $_SESSION['sqlContadora'] = $sqlContadora;
}

if (isset($_POST['removeFiltro'])) {

    $categoria = "";
    $marca = "";
    $genero = "";
    $cor = "";
    $precoMaior = 0;
    $precoMenor = 90000;
    $ordem = 'preco';
    $nome = $_POST['nome'];
    $pesquisarNome = $nome ? "p.nome LIKE '%$nome%' AND" : "";

    $sql = "SELECT p.id, p.nome, p.preco, p.imagem, l.nome_estabelecimento FROM produtos AS p JOIN lojistas as l ON p.id_lojista = l.id WHERE $pesquisarNome p.preco BETWEEN 0 AND 90000 ORDER BY preco LIMIT $offset, $num_items_por_pagina;";
    $sqlContadora = "SELECT COUNT(id) AS total FROM produtos WHERE $pesquisarNome preco BETWEEN $precoMaior AND $precoMenor;";
}
?>

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
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="pgPadrao.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="produtos.css">
    <link rel="stylesheet" href="pgPadrao.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>footer</title>
</head>

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
            <form action="produtosBusca.php" class="pesquisaCentral" method="POST">
                <input type="text" placeholder="Busque Seus Produtos" name="nome" value="<?php echo $nome; ?>" onKeyUp="pegandoPesquisa()" id="buscarInput">
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
                        <input type="email" name="emailL" id="email"><br>

                        <label for="senhaL">Senha</label><br>
                        <div>
                            <img class="olho" src="img/img pg padrao/olho.png" alt="">
                            <input type="password" name="senhaL" id="senha">
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

    <!---------------------- Conteudo principal - MAIN ---------------------->

    <main class="container">
        <!-- nome, nome da loja e preço -->

        <!----------------------------- Menu de botões de filtro ----------------------------->
        <div class="menu">
            <!-- Botões de aplicar filtro e de ordenar produtos -->
            <div class="botoesMenu">
                <button class="buttonBusca" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight2"
                    aria-controls="offcanvasRight2">Ordenar</button>
                <button class="buttonBusca" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                    aria-controls="offcanvasRight" id="bFiltros">Filtros</button>        
            </div>

        </div>
        <!----------------------------- Menu de botões de filtro ----------------------------->

        <!----------------------------- Telas laterias ----------------------------->
        <form action="" method="POST">

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasRightLabel">Filtrar por</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <!-- DADOS OCULTOS DO FORM -->
                    <input type="hidden" name="nome" id="nomeProduto">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="false"
                                    aria-controls="flush-collapseOne">
                                    <h6>Categorias</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body filtrosCorpo">
                                    <input type="radio" name="categoria" id="roupa" value="roupa" class="radioFiltro"
                                        <?php echo checarFiltro($categoria, 'roupa'); ?>>

                                    <label for="roupa" class="buttonFiltroSelecao button">Roupas</label>

                                    <input type="radio" name="categoria" id="eletronico" value="eletronico"
                                        class="radioFiltro" <?php echo checarFiltro($categoria, 'eletronico'); ?>>
                                    <label for="eletronico" class="buttonFiltroSelecao button">Eletrônicos</label>

                                    <input type="radio" name="categoria" id="eletrodomestico" value="eletrodomestico"
                                        class="radioFiltro" <?php echo checarFiltro($categoria, 'eletrodomestico'); ?>>
                                    <label for="eletrodomestico"
                                        class="buttonFiltroSelecao button">Eletrodomésticos</label>

                                    <input type="radio" name="categoria" id="alimento" value="alimento"
                                        class="radioFiltro" <?php echo checarFiltro($categoria, 'alimento'); ?>>
                                    <label for="alimento" class="buttonFiltroSelecao button">Alimentos</label>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                    aria-controls="flush-collapseTwo">
                                    <h6>Marca</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body filtrosCorpo">
                                    <input type="radio" name="marca" id="havaianas" value="havaianas"
                                        class="radioFiltro" <?php echo checarFiltro($marca, 'havaianas'); ?>>
                                    <label for="havaianas" class="buttonFiltroSelecao button">Havaianas</label>

                                    <input type="radio" name="marca" id="renner" value="renner" class="radioFiltro"
                                        <?php echo checarFiltro($marca, 'renner'); ?>>
                                    <label for="renner" class="buttonFiltroSelecao button">Renner</label>

                                    <input type="radio" name="marca" id="lg" value="lg" class="radioFiltro" <?php echo
                                        checarFiltro($marca, 'lg'); ?>>
                                    <label for="lg" class="buttonFiltroSelecao button">LG</label>

                                    <input type="radio" name="marca" id="apple" value="apple" class="radioFiltro" <?php
                                    echo checarFiltro($marca, 'apple'); ?>>
                                    <label for="apple" class="buttonFiltroSelecao button">Apple</label>

                                    <input type="radio" name="marca" id="brastemp" value="brastemp" class="radioFiltro"
                                        <?php echo checarFiltro($marca, 'brastemp'); ?>>
                                    <label for="brastemp" class="buttonFiltroSelecao button">Brastemp</label>

                                    <input type="radio" name="marca" id="consul" value="consul" class="radioFiltro"
                                        <?php echo checarFiltro($marca, 'consul'); ?>>
                                    <label for="consul" class="buttonFiltroSelecao button">Consul</label>

                                    <input type="radio" name="marca" id="nestle" value="nestle" class="radioFiltro"
                                        <?php echo checarFiltro($marca, 'nestle'); ?>>
                                    <label for="nestle" class="buttonFiltroSelecao button">Nestlé</label>

                                    <input type="radio" name="marca" id="coca-cola" value="coca-cola"
                                        class="radioFiltro" <?php echo checarFiltro($marca, 'coca-cola'); ?>>
                                    <label for="coca-cola" class="buttonFiltroSelecao button">Coca-Cola</label>

                                    <input type="radio" name="marca" id="natura" value="natura" class="radioFiltro"
                                        <?php echo checarFiltro($marca, 'natura'); ?>>
                                    <label for="natura" class="buttonFiltroSelecao button">Natura</label>

                                    <input type="radio" name="marca" id="boticario" value="boticario"
                                        class="radioFiltro" <?php echo checarFiltro($marca, 'boticario'); ?>>
                                    <label for="boticario" class="buttonFiltroSelecao button">O Boticário</label>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThree" aria-expanded="false"
                                    aria-controls="flush-collapseThree">
                                    <h6>Gênero</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body filtrosCorpo">
                                    <input type="radio" name="genero" id="masculino" value="masculino"
                                        class="radioFiltro" <?php echo checarFiltro($genero, 'masculino'); ?>>
                                    <label for="masculino" class="buttonFiltroSelecao button">Masculino</label>

                                    <input type="radio" name="genero" id="feminino" value="feminino" class="radioFiltro"
                                        <?php echo checarFiltro($genero, 'feminino'); ?>>
                                    <label for="feminino" class="buttonFiltroSelecao button">Feminino</label>

                                    <input type="radio" name="genero" id="unissex" value="unissex" class="radioFiltro"
                                        <?php echo checarFiltro($genero, 'unissex'); ?>>
                                    <label for="unissex" class="buttonFiltroSelecao button">Unissex</label>

                                    <input type="radio" name="genero" id="infantil" value="infantil" class="radioFiltro"
                                        <?php echo checarFiltro($genero, 'infantil'); ?>>
                                    <label for="infantil" class="buttonFiltroSelecao button">Infantil</label>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseFour" aria-expanded="false"
                                    aria-controls="flush-collapseFour">
                                    <h6>Cor</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseFour" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body filtrosCorpo">
                                    <input type="radio" name="cor" id="preto" value="preto" class="radioFiltro" <?php
                                    echo checarFiltro($cor, 'preto'); ?>>
                                    <label for="preto" class="buttonFiltroSelecao button">Preto</label>

                                    <input type="radio" name="cor" id="branco" value="branco" class="radioFiltro" <?php
                                    echo checarFiltro($cor, 'branco'); ?>>
                                    <label for="branco" class="buttonFiltroSelecao button">Branco</label>

                                    <input type="radio" name="cor" id="vermelho" value="vermelho" class="radioFiltro"
                                        <?php echo checarFiltro($cor, 'vermelho'); ?>>
                                    <label for="vermelho" class="buttonFiltroSelecao button">Vermelho</label>

                                    <input type="radio" name="cor" id="azul" value="azul" class="radioFiltro" <?php echo
                                        checarFiltro($cor, 'azul'); ?>>
                                    <label for="azul" class="buttonFiltroSelecao button">Azul</label>

                                    <input type="radio" name="cor" id="verde" value="verde" class="radioFiltro" <?php
                                    echo checarFiltro($cor, 'verde'); ?>>
                                    <label for="verde" class="buttonFiltroSelecao button">Verde</label>

                                    <input type="radio" name="cor" id="amarelo" value="amarelo" class="radioFiltro"
                                        <?php echo checarFiltro($cor, 'amarelo'); ?>>
                                    <label for="amarelo" class="buttonFiltroSelecao button">Amarelo</label>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseFive" aria-expanded="false"
                                    aria-controls="flush-collapseFive">
                                    <h6>Preço</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseFive" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="preco">
                                    <span>Menor preço</span>
                                    <span>Maior preço</span>
                                    <input type="number" id="minPrice" value="<?php echo $precoMaior; ?>" min="0"
                                        max="90000" name="precoMaior">
                                    <input type="number" id="maxPrice" value="<?php echo $precoMenor; ?>" min="0"
                                        max="90000" name="precoMenor">
                                </div>
                            </div>
                        </div>
                    </div>
                    <label for="aplicarFiltro" class="labelSubmitFiltro">Aplicar filtros</label>
                    <input id="aplicarFiltro" type="submit" value="<?php echo $ordem ?>" class="submitFiltro"
                        name="filtro" data-bs-dismiss="offcanvas" aria-label="Close">

                    <label for="removerFiltro" class="labelSubmitFiltro">Remover filtros</label>
                    <input id="removerFiltro" type="submit" class="submitFiltro" name="removeFiltro"
                        data-bs-dismiss="offcanvas" aria-label="Close">
                </div>
            </div>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight2"
                aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasRightLabel">Ordenar por</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body ordenarCorpo">
                    <label for="maisBuscados"
                        class="ordenarButton button <?php echo checarOrdenm($ordem, 'contador_cliques DESC'); ?> ">Mais
                        buscados</label>
                    <input id="maisBuscados" type="submit" value="contador_cliques DESC" name="filtro"
                        class="ordenarInput">

                    <label for="maiorPreco"
                        class="ordenarButton button <?php echo checarOrdenm($ordem, 'preco DESC'); ?> ">Maior
                        preço</label>
                    <input id="maiorPreco" type="submit" value="preco DESC" placeholder="Maior preço" name="filtro"
                        class="ordenarInput">

                    <label for="menorPreco"
                        class="ordenarButton button <?php echo checarOrdenm($ordem, 'preco ASC'); ?>">Menor
                        preço</label>
                    <input id="menorPreco" type="submit" value="preco ASC" placeholder="Menor preço " name="filtro"
                        class="ordenarInput">

                </div>
            </div>
        </form>
        <!----------------------------- Telas laterias ----------------------------->

        <!----------------------------- Produtos exibidos na página ----------------------------->
        <div class="containerCards">

            <?php

            if(isset($_GET['categoria'])){
                $categoria = $_GET['categoria'];
                $sql = "SELECT p.id, p.nome, p.preco, p.imagem, l.nome_estabelecimento FROM produtos AS p JOIN lojistas as l ON p.id_lojista = l.id WHERE categoria = '$categoria' ORDER BY 'contador_cliques' LIMIT $offset, $num_items_por_pagina;";
                $sqlContadora = "SELECT COUNT(id) AS total FROM produtos WHERE categoria = '$categoria' ORDER BY 'contador_cliques' LIMIT $offset, $num_items_por_pagina;";
                $_SESSION['sql'] = $sql;
                $_SESSION['sqlContadora'] = $sqlContadora;
            }

            if (isset($_POST['filtro']) || isset($_POST['removeFiltro']) || isset($_GET['pagina']) || isset($_GET['categoria'])) {

                $querryDividida = explode('LIMIT', $_SESSION['sql']);
                $querryDividida[0] .= "LIMIT $offset, $num_items_por_pagina;";
                $_SESSION['sql'] = $querryDividida[0];
                // LIMIT $offset, $num_items_por_pagina;
            
                $resultado = mysqli_query($conn, $_SESSION['sql']);
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
                <form target='hiddenFrame' id='favoritar' action='favoritar.php' method='POST'>
                    <input type='hidden' name='idFavorito' value='$id'>
                    <input type='hidden' name='user' value='$user'>
                    <button type='submit' value='Favoritar' name='favoritoSubmit'>
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
            } else {
                $_SESSION['sql'] = "SELECT p.id, p.nome, p.preco, p.imagem, l.nome_estabelecimento FROM produtos AS p JOIN lojistas as l ON p.id_lojista = l.id WHERE categoria = 'roupa' ORDER BY 'contador_cliques' LIMIT 0, 16;";

                $querryDividida = explode('LIMIT', $_SESSION['sql']);
                $querryDividida[0] .= "LIMIT $offset, $num_items_por_pagina;";
                $_SESSION['sql'] = $querryDividida[0];
                // LIMIT $offset, $num_items_por_pagina;
            
                $resultado = mysqli_query($conn, $_SESSION['sql']);
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
                <form target='hiddenFrame' id='favoritar' action='favoritar.php' method='POST'>
                    <input type='hidden' name='idFavorito' value='$id'>
                    <input type='hidden' name='user' value='$user'>
                    <button type='submit' value='Favoritar' name='favoritoSubmit'>
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
            } }

            // Favoritando os produtos 
            if (isset($_POST['favoritoSubmit'])) {
                $idProduto = $_POST['idFavorito'];
                $sql = "SELECT id FROM usuario_favorita_produto WHERE id_usuario = $user AND id_produto = $idProduto";
                $resultado = $conn->query($sql);
                $numLinha = mysqli_num_rows($resultado);

                echo $sql;
    
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
            }


            ?>

        </div>
        <!----------------------------- Produtos exibidos na página ----------------------------->

        <nav aria-label="Paginação">
            <ul class="pagination justify-content-center">
                <?php

                if (isset($_POST['filtro']) || isset($_POST['removeFiltro']) || isset($_GET['pagina']) || isset($_GET['categoria'])) {
                    // echo $sqlContadora;
                    $resultadoContador = mysqli_query($conn, $_SESSION['sqlContadora']);
                    $numeroLinhas = mysqli_fetch_assoc($resultadoContador);
                    $total_paginas = ceil($numeroLinhas['total'] / $num_items_por_pagina);

                    // Exibe links de páginação
                    for ($i = 1; $i <= $total_paginas; $i++) {
                        echo "<li class='page-item " . ($pagina_atual == $i ? 'active' : '') . "'><a class='page-link' href='produtosBusca.php?pagina=$i'>$i</a></li>";
                    }
                } else {
                    // echo $sqlContadora;
                    $_SESSION['sqlContadora'] = "SELECT COUNT(id) AS total FROM produtos AS p WHERE preco BETWEEN 0 AND 90000;";
                    $resultadoContador = mysqli_query($conn, $_SESSION['sqlContadora']);
                    $numeroLinhas = mysqli_fetch_assoc($resultadoContador);
                    $total_paginas = ceil($numeroLinhas['total'] / $num_items_por_pagina);

                    // Exibe links de páginação
                    for ($i = 1; $i <= $total_paginas; $i++) {
                        echo "<li class='page-item " . ($pagina_atual == $i ? 'active' : '') . "'><a class='page-link' href='produtosBusca.php?pagina=$i'>$i</a></li>";
                    }
                }
                ?>
            </ul>
        </nav>


    </main>

    <footer>

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
                <a href="">Termos de uso</a>
            </div>
        </div>

    </footer>

    <!---------------------- Conteudo principal - MAIN ---------------------->

    <script>


        // Este código pega o valor da barra de pesquisa e o aplica em um input do tipo hidden no formulário
        var nomeInput =  document.getElementById("nomeProduto");
        var nomeDigitado = document.getElementById("buscarInput");
        // nomeInput.value = nomeDigitado.value;
        function pegandoPesquisa() {
           // console.log(nomeDigitado.value);
            nomeInput.value = nomeDigitado.value;
        }


    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

</html>
<script src="script.js"></script>

<!-- 
o que ta fazendo?

Estou fazendo a aplicação das interfaces formatadas 

o que ja fiz?
o que vou fazer?

-->