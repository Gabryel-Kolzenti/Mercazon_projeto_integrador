<?php
session_start();
include_once '../validarUsuario.php';
include_once 'conexao.php';
?>

<?php
// Configuração da paginação
$num_items_por_pagina = 16; // Número de itens por página
$pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página atual, padrão é 1
$offset = ($pagina_atual - 1) * $num_items_por_pagina; // Calcular o offset

// Consulta SQL com LIMIT e OFFSET para implementar a paginação
$sql = "SELECT p.id, p.nome, p.preco, p.categoria, p.imagem 
        FROM produtos AS p
        JOIN usuario_favorita_produto AS ufp ON p.id = ufp.id_produto
        WHERE ufp.id_usuario = $user
        ORDER BY ufp.id DESC
        LIMIT $offset, $num_items_por_pagina;
";

$result = $conn->query($sql);

// Consulta SQl para aparecer os elementos favoritos no header
$sqlElementosFavoritosHeader = "SELECT p.id, p.nome, p.preco, p.categoria, p.imagem 
        FROM produtos AS p
        JOIN usuario_favorita_produto AS ufp ON p.id = ufp.id_produto
        WHERE ufp.id_usuario = $user
        ORDER BY ufp.id DESC
        LIMIT 3;
";
$resultado = $conn->query($sqlElementosFavoritosHeader);
?>

<?php

$sqlInfoUsuario = "SELECT * FROM usuarios WHERE id = $user";
$infoUsuarioResultado = $conn->query($sqlInfoUsuario);


while ($linhaUsuario = mysqli_fetch_assoc($infoUsuarioResultado)) {
    $nomeUsuario = $linhaUsuario['nome'];
    $emailUsuario = $linhaUsuario['email'];
    $enderecoUsuario = $linhaUsuario['endereco'];
    $dataUsuario = $linhaUsuario['data_nascimento'];
    $imagemUsuario = $linhaUsuario['imagem_usuario'];
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
    <link rel="stylesheet" href="pgUsuario.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>oi amigo</title>
</head>

<body>

    <header>
    <nav class="cabecalhoSuperior">
            <div class="d-flex">
                <a href="usuario.php">Usuário</a>
                <h9>|</h9>
                <a href="../contato.php">Suporte</a>
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

    <!-- Modal de Edita produto -->

   
    <div  class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edição das Informações</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="atualizaUsuario.php" method="POST" enctype="multipart/form-data">
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
                            <label for="dataUsuario" class="form-label">Data de nascimento:</label>
                            <input type="date" class="form-control" id="dataUsuario" value="<?php echo "$dataUsuario" ?>" name="dataUsuario" required>
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


    <!-- Modal de Edita produto -->

    <!-- Modal de Edita foto -->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Selecione sua foto</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="atualizaUsuario.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="idUsuario" value=" <?php echo "$user" ?>">
                        <div class="mb-3 d-flex" id="selecionaImagem">
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

    <!-- Modal de Edita foto -->

    <!-- Começo Do Conteúdo-->
    <main>
        <div>
            <h1>Configurações</h1>
            <div class="containerDasInfoIniciais">
           
                <div class="d-flex"  data-bs-toggle="modal">
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
                <div class="editar" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <img  src="../img/img pgs usuario/lapis.png" alt="">
                    <p>Editar</p>
                </div>
                <a class="btn btn-danger" href="../logout.php">Deslogar</a>

            </div>
        </div>

        <h1>Produtos Favoritos</h1>
    </main>

    <div class="containerCards">
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
            <form target='hiddenFrame' action='favoritar.php' method='POST'>
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
            <a href="../produtosBusca.php">Todos Produtos</a>
            <a href="../guiaDoLojista.php">Anuncie Aqui</a>
        </div>

        <div>
            <h5>Sobre</h5>
            <a href="#">Nossa História</a>
            <a href="#">Quem Somos?</a>
        </div>

        <div>
            <h5>Ajuda</h5>
            <a href="../Contato.php">Contate-nos</a>
            <a href="../guiaDoLojista.php">Guia do Lojista</a>
        </div>

        <div>
            <h5>Nossas redes sociais</h5>
            <div id="redesSociais">
                <img src="../img/img pg padrao/face.png" alt="">
                <img src="../img/img pg padrao/instagram.png" alt="">
            </div>
        </div>
    </div>

    <div class="frasesFinaisFooter">
        <p>2024 All Rights Reserved</p>
        <a href="#">Termos de uso</a>
    </div>
</div>

</footer>

    <!------------------------------------------------FOOTER---------------------------------------------------->


            <iframe name="hiddenFrame" style="display:none;"></iframe> <!-- Iframe invisível -->

            <script src="script.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>