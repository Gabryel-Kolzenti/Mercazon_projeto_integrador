<?php
session_start();
include_once '../validarLojista.php';
include_once 'conexao.php';
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nome']) && isset($_POST['categoria']) && isset($_POST['preco']) && isset($_FILES['imagem'])) {
        $nome = clear($conn, $_POST['nome']);
        $categoria = clear($conn, $_POST['categoria']);
        $preco = clear($conn, $_POST['preco']);
        $descricao = clear($conn, $_POST['descricao']);
        $imagemEstabelecimento = clear($conn, $_POST['imagemEmpresa']);

        $nomeFoto = salvarFoto($_FILES['imagem']);
        if ($nomeFoto == 1) {
            echo "arquivo no formato incorreto ou muito grande";
        }
        if ($nomeFoto == 0) {
            echo "erro no upload de arquivo";
        } else {
            $SQL = "INSERT INTO produtos (nome, preco,categoria , imagem, id_lojista, descricao) VALUES ('$nome', '$preco','$categoria' , '$nomeFoto', '$user', '$descricao')";
            mysqli_query($conn, $SQL);
        }
    }
}
?>

<?php
// Configuração da paginação
$num_items_por_pagina = 16; // Número de itens por página
$pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página atual, padrão é 1
$offset = ($pagina_atual - 1) * $num_items_por_pagina; // Calcular o offset

// Consulta SQL com LIMIT e OFFSET para implementar a paginação
$sql = "SELECT *
        FROM produtos
        WHERE id_lojista = $user
        ORDER BY id DESC
        LIMIT $offset, $num_items_por_pagina;
";

$result = $conn->query($sql);

// Consulta SQl para aparecer os elementos favoritos no header
$sqlElementosFavoritosHeader = "SELECT id, nome, preco, categoria, imagem 
        FROM produtos 
        WHERE id_lojista = $user
        ORDER BY id DESC;
";
$resultado = $conn->query($sqlElementosFavoritosHeader);
?>

<?php

$sqlInfoUsuario = "SELECT * FROM lojistas WHERE id = $user";
$infoUsuarioResultado = $conn->query($sqlInfoUsuario);


while ($linhaUsuario = mysqli_fetch_assoc($infoUsuarioResultado)) {
    $nomeUsuario = $linhaUsuario['nome'];
    $emailUsuario = $linhaUsuario['email'];
    $enderecoUsuario = $linhaUsuario['endereco'];
    $imagemUsuario = $linhaUsuario['imagem_lojista'];
    $nomeEstabelecimento = $linhaUsuario['nome_estabelecimento'];
    $imagemEstabelecimento = $linhaUsuario['imagem_empresa'];
    $telefone = $linhaUsuario['telefone'];
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="pgPadrao.css">
    <link rel="stylesheet" href="pgLojista.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>oi amigo</title>
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
            <a href="../index.php">
                <img src="../img/img pg padrao/face.png" alt=""></a>
            <form action="../produtosBusca.php" class="pesquisaCentral" method="POST">
                <input type="text" placeholder="Busque Seus Produtos" name="nome">
                <button type="submit" name="filtro" value="preco"><img src="../img/img pg padrao/lupa.png" alt=""></button>
            </form>
            <div class="d-flex">
                <div class="dropdown">
                    <div onclick="location.href='usuario.php'" alt="" style="filter:invert(1)" class="naoClicadoPgsUsuario" id="pgsUsuario" data-bs-toggle="dropdown" aria-expanded="false">
                    </div>
                </div>

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

    <!-- Modal de Edita lojista -->


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edição das Informações</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="atualizaLojista.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="idUsuario" value=" <?php echo "$user" ?>">
                        <div class="mb-3">
                            <label for="nomeUsuario" class="form-label">Nome:</label>
                            <input type="text" class="form-control" id="nomeUsuario" name="nomeUsuario" value="<?php echo "$nomeUsuario" ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="emailUsuario" class="form-label">Email:</label>
                            <input type="text" class="form-control" id="emailUsuario" value="<?php echo "$emailUsuario" ?>" name="emailUsuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="enderecoUsuario" class="form-label">Endereço:</label>
                            <input type="text" class="form-control" id="enderecoUsuario" value="<?php echo "$enderecoUsuario" ?>" name="enderecoUsuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone:</label>
                            <input type="text" class="form-control" id="telefone" value="<?php echo "$telefone" ?>" name="telefone" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomeEstabelecimento" class="form-label">Nome Estabelecimento:</label>
                            <input type="text" class="form-control" id="nomeEstabelecimento" value="<?php echo "$nomeEstabelecimento" ?>" name="nomeEstabelecimento" required>
                        </div>
                        <div class="mb-3 d-flex" id="selecionaImagem">
                            <label for="imagem" class="form-label">Selecione sua foto:</label>
                            <input type="file" id="imagemInput" class="form-control" name="imagem" accept="image/*" style="display: none;">
                            <img src="../img/<?php echo "$imagemUsuario" ?>" alt="" id="imagemProduto" onclick="document.getElementById('imagemInput').click();">
                            <br>
                            <br>
                        </div>
                        <div id="loginBotao">
                            <input type="submit" value="Salvar">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <!-- Modal de Edita lojista -->

    <!-- Modal de cadastro de produto -->


    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form action="lojistaLojista.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Produto ou Serviço:</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria:</label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option value="Eletronicos">Eletrônicos</option>
                                <option value="Roupas">Roupas</option>
                                <option value="Eletrodomesticos">Eletrodomésticos</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço:</label>
                            <input type="number" class="form-control" id="preco" name="preco" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem:</label>
                            <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de cadastro de produto -->

    <!-- Modal de edição de produto -->

    <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form action="editaProduto.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Produto ou Serviço:</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria:</label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option value="Eletronicos">Eletrônicos</option>
                                <option value="Roupas">Roupas</option>
                                <option value="Eletrodomesticos">Eletrodomésticos</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço:</label>
                            <input type="number" class="form-control" id="preco" name="preco" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem:</label>
                            <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de edição de produto -->

    <!-- Modal exclui Produto-->

    <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Excluir Produto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="excluirScript.php" method="post" id="formExcluir" class="">
                        <h2>Deseja Excluir <b id="nomeProduto"></b>?</h2>
                        <div class="d-flex">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                            <input type="hidden" name="id" id="idProduto" value="">
                            <input style="width: 40%;" type="submit" class="btn btn-danger" value="Sim">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal exclui Produto-->

    <!-- Começo Do Conteúdo-->
    <main>
        
        <div>
            <h1>Informações e Configurações</h1>
            <div class="containerDasInfoIniciais">
            <?php $imagemEstabelecimento ?>

                <div class="d-flex" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <?php
                    if (!empty($imagemUsuario)) {
                        echo "<img src='../img/$imagemUsuario'";
                    } else {
                        echo "<img  src='../img/img pg padrao/profile1.png' alt=''>";
                    }
                    ?>
                    <br>
                    <div>
                        <h3><?php echo "$nomeUsuario" ?></h3>
                        <h5>Endereço: <?php echo "$enderecoUsuario" ?></h5>
                    </div>
                </div>
                <div class="editar" data-bs-toggle='modal' data-bs-target='#exampleModal'>
                    <img src="../img/img pgs usuario/lapis.png" alt="">
                    <p>Editar</p>
                </div>
                <a class="btn btn-danger" href="../logout.php">Deslogar</a>

            </div>
        </div>

        <h1>Seus Produtos</h1>
    </main>

    <div class="containerCards">
        <button type="button" class="adicionarItem" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <svg width="200px" height="200px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 12H18M12 6V18" stroke="#f4cb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <h2>Adicionar Produto</h2>
        </button>
        <?php

        while ($linha = mysqli_fetch_assoc($result)) {
            $nome = $linha['nome'];
            $imagem = $linha['imagem'];
            $preco = $linha['preco'];
            $categoria = $linha['categoria'];
            $id = $linha['id'];
            echo "
    <div class='card' onclick=\"location.href='../produto.php?id=$id'\">
            <div class='parteSuperiorCard'>
            <img src='../img/$imagem' alt='$nome'>
                <input type='hidden' name='idFavorito' value='$id'>
                <input type='hidden' name='nomeProduto' data-nome='$nome'>
                <input type='hidden' name='nomeCategoria' data-nome='$nome'>
                <input type='hidden' name='nomeProduto' data-nome='$nome'>
                <input type='hidden' name='nomeProduto' data-nome='$nome'>

                <input type='hidden' name='user' value='$user'>
                <button data-bs-toggle='modal' data-bs-target='#exampleModal3' >
                    <svg data-id='$id' data-nome='$nome' class='favoritaCoracao excluir coracaoFavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='red' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                    <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>
                    </svg>
                </button>

                
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
    </div>

    <!-- Paginação -->
    <nav aria-label="Paginação">
        <ul class="pagination justify-content-center">
            <?php
            // Consulta para contar o total de produtos
            $sql_count = "SELECT COUNT(*) AS total FROM usuario_favorita_produto WHERE id_usuario = $user";
            $result_count = $conn->query($sql_count);
            $row_count = $result_count->fetch_assoc();
            $total_items = $row_count['total'];

            // Calcula o número total de páginas
            $total_paginas = ceil($total_items / $num_items_por_pagina);

            // Exibe links de páginação
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<li class='page-item " . ($pagina_atual == $i ? 'active' : '') . "'><a class='page-link' href='usuario.php?pagina=$i'>$i</a></li>";
            }
            ?>
    </nav>

    <!------------------------------------------------FOOTER---------------------------------------------------->

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
                    <a href="">Contate-nos</a>
                    <a href="">Guia do Lojista</a>
                </div>

                <div>
                    <h5>Nossas redes sociais</h5>
                    <div id="redesSociais">
                        <img src="../img/img pg padrao/face.png" alt="">
                        <img src="../img/img pg padrao/instagram.png" alt="">
                    </div>
                </div>
            </div>

            <div class="logo">
                <img src="../img/img pg padrao/logo.png" alt="">
            </div>

            <div class="frasesFinaisFooter">
                <p>2024 All Rights Reserved</p>
                <a href="">Termos de uso</a>
            </div>
        </div>

    </footer>

    <!------------------------------------------------FOOTER---------------------------------------------------->


    <script>
        // Script para manipulação de botões de exclusão
        var buttonsExcluir = document.querySelectorAll('.excluir');
        buttonsExcluir.forEach(function(button) {
            button.addEventListener('click', function() {
                var id = button.getAttribute('data-id');
                var nome = button.getAttribute('data-nome');
                document.getElementById('idProduto').value = id;
                document.getElementById('nomeProduto').innerText = nome;
            });
        });
    </script>

    <iframe name="hiddenFrame" style="display:none;"></iframe> <!-- Iframe invisível -->

    <script src="scriptLojista.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>