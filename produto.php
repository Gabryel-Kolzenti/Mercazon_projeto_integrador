<?php
include 'restrito/conexao.php';

if (!isset($_GET['id'])) {
    header('Location: criarProduto.php');
}

$idProduto = $_GET['id'];
$sql = "SELECT * FROM produtos  WHERE id = '$idProduto'";
$resultado = $conn->query($sql); //

if ($resultado->num_rows > 0) {
    $linha = $resultado->fetch_assoc();
    $nome = $linha['nome'];
    $imagem = $linha['imagem'];
    $preco = $linha['preco'];
    $categoria = $linha['categoria'];
    $local = $linha['localizacao'];
    $descricao = $linha['descricao'];
    $id_lojista = $linha['id_lojista'];

    $lojista = "SELECT * FROM lojistas  WHERE id = '$id_lojista'";
    $resultadoLojista = $conn->query($lojista);

    if ($resultadoLojista->num_rows > 0) {
        $linha = $resultadoLojista->fetch_assoc();
        $nomeLojista = $linha['nome'];
        $emailLojista = $linha['email'];
        $descricaoLojista = $linha['descricaoLogista'];
        $enderecoLojista = $linha['endereco'];
        $imgLojista = $linha['imagem_lojista'];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="stylepgproduto.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="pgPadrao.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto Individual</title>
</head>

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
                    <div src="" alt="" class="naoClicado" id="favoritos" data-bs-toggle="dropdown" aria-expanded="false"></div>
                    <ul class="dropdown-menu">

                        <?php
                        // Exibir mensagem se houver
                        if (isset($_SESSION['msg'])) {
                            echo "<p>" . $_SESSION['msg'] . "</p>";
                            unset($_SESSION['msg']);
                        }

                        $i = 0;

                        while ($linha = mysqli_fetch_assoc($resultado)) {
                            $nome = $linha['nome'];
                            $imagem = $linha['imagem'];
                            $preco = $linha['preco'];
                            $categoria = $linha['categoria'];
                            $id = $linha['id'];


                            echo "
            <li class='produtosNoHeader'><a href='../produto.php?id=$id' class='dropdown-item d-flex'> 
                <img src='../img/$imagem' alt='$nome'>
                <div class= 'd-flex flex-column justify-content-center'>
                    <h6>$nome</h6>
                    <h6>R$ $preco</h6>
                </div>
            </a></li>
            ";
                        }
                        ?>
                        <li><a class='dropdown-item' href="usuario.php">Ver Todos</a></li>
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

                    <form action="restrito/criarProduto.php" method="post">
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
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
    <main id="main">
        <div class="imgPrincipal">
            <img src="img/<?php echo "$imagem" ?>" alt="" class="imgPrincipal">
        </div>
        <div class="preco">
            <p id="h3"><?php echo "R$ $preco" ?></p>
            <p>Vendido por: <img src="img/<?php echo $imgLojista ?>" alt="" height="30px"></p>
        </div>
        <div class="nomedata">
            <div>
                <h1><?php echo "$nome" ?></h1>
                <p id="date">Publicado em 06/06 ás 13:34</p>
            </div>
            <div></div>
        </div>
        </div>
        <div class="tr">
            <tr>__________________________________________</tr>
        </div>
        <div class="divdobtnazul">
            <button class="btngrandeazul">ESTOU INTERESSADO!</button>
        </div>
        <div class="descricao">
            <div class="d-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16" style="margin-right: 1vw; margin-left: 1.5vw">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                </svg>


                <h4>Descrição:</h4>
            </div>
            <p><?php echo $descricao ?></p>
        </div>
        <div class="descricao">
            <div class="d-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16" style="margin-right: 1vw; margin-left: 1.5vw">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                </svg>
                <h4>Localização:</h4>
            </div>
            <p><?php echo $local ?></p>
        </div>

        <!-----------------------------CARROSSEL INICIO  --------------------------------- -->
        <h2 class="interesses titulos" style="margin-bottom: 5vh;">Também pode te interessar:</h2>
        <div id='carouselExampleInterval' class='carousel slide' data-bs-ride='carousel'>
            <div class='carousel-inner'>
                <?php
                $buscaProxima = "SELECT * FROM produtos WHERE id <> '$idProduto' AND categoria = '$categoria'";
                $result = $conn->query($buscaProxima);
                $firstItem = true; // Variável para controlar o primeiro item ativo no carrossel

                while ($linha1 = mysqli_fetch_assoc($result)) {
                    $nomeBusca1 = $linha1['nome'];
                    $imagemBusca1 = $linha1['imagem'];
                    $precoBusca1 = $linha1['preco'];
                    $idBusca1 = $linha1['id'];

                    // Pegue o próximo resultado para o segundo item
                    if ($linha2 = mysqli_fetch_assoc($result)) {
                        $nomeBusca2 = $linha2['nome'];
                        $imagemBusca2 = $linha2['imagem'];
                        $precoBusca2 = $linha2['preco'];
                        $idBusca2 = $linha2['id'];
                    } else {
                        // Se não houver mais resultados, defina valores vazios para o segundo item
                        $nomeBusca2 = '';
                        $imagemBusca2 = '';
                        $precoBusca2 = '';
                        $idBusca2 = '';
                    }

                    // Defina a classe 'active' apenas para o primeiro item
                    $activeClass = $firstItem ? 'active' : '';
                ?>

                    <div class='carousel-item <?php echo $activeClass; ?>'>
                        <div class='row'>
                            <div class='col'>
                                <img src='img/<?php echo $imagemBusca1; ?>' class='d-block w-100 legenda' alt='Imagem 1'>
                                <div class='legenda'>
                                    <h5 class='legenda'><?php echo $nomeBusca1; ?></h5>
                                    <p class='legenda'>R$ <?php echo $precoBusca2; ?></p>
                                    <a class="btn-p4" href='produto.php?id=<?php echo $idBusca1; ?>'>Ver Produto</a>
                                </div>
                            </div>
                            <div class='col'>
                                <?php if (!empty($nomeBusca2)) :?>
                                    <img src='img/<?php echo $imagemBusca2; ?>' class='d-block w-100 legenda' alt='Imagem 2'>
                                    <div class='legenda'>
                                        <h5 class='legenda'><?php echo $nomeBusca2; ?></h5>
                                        <p class='legenda'>R$ <?php echo $precoBusca2; ?></p>
                                        <a class="btn-p4" href='produto.php?id=<?php echo $idBusca2; ?>'>Ver Produto</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php
                    $firstItem = false; // Após o primeiro item, definimos como false para não repetir 'active'
                }
                ?>

                <!-- Adicione mais itens conforme necessário -->

            </div>

            <!-- Botões de controle do carrossel -->
            <button class='carousel-control-prev' type='button' data-bs-target='#carouselExampleInterval' data-bs-slide='prev'>
                <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                <span class='visually-hidden'>Anterior</span>
            </button>
            <button class='carousel-control-next' type='button' data-bs-target='#carouselExampleInterval' data-bs-slide='next'>
                <span class='carousel-control-next-icon' aria-hidden='true'></span>
                <span class='visually-hidden'>Próximo</span>
            </button>
        </div>
        <h2 class="titulos">Sobre o anunciante:</h2>
        <div id="antes-anunciante">
            <div class="sobre-anunciante" id="anunciante">
                <div id="conjunto">
                    <img src="img/<?php echo "$imgLojista" ?>" alt="Foto do anunciante" class="Img-anunciante">
                    <div class="descricao-anunciante">
                        <h3 id="nome-anunciante"><?php echo "$nomeLojista" ?></h3>
                    </div>
                </div>
                <div>
                    <p><?php echo $descricaoLojista ?></p>
                </div>
            </div>
        </div>
        </div>
    </main>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>