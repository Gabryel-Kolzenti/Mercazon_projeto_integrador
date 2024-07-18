<?php 
include 'validar.php';
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nome']) && isset($_POST['categoria']) && isset($_POST['preco']) && isset($_FILES['imagem'])) {
        $idProduto = 1; //gabryel tem que mudar
        $nome = clear($conn, $_POST['nome']);
        $categoria = clear($conn, $_POST['categoria']);
        $preco = clear($conn, $_POST['preco']);
        $descricao = clear($conn, $_POST['descricao']);
        $localizacao = clear($conn, $_POST['localizacao']);

        $nomeFoto = salvarFoto($_FILES['imagem'], "../img/");
        if ($nomeFoto == 1) {
            echo "arquivo no formato incorreto ou muito grande";
        }
         if ($nomeFoto == 0) {
            echo "erro no upload de arquivo";
        }
        else {
            $SQL = "INSERT INTO produtos (nome, preco,categoria , imagem, id_lojista, descricao, localizacao) VALUES ('$nome', '$preco','$categoria' , '$nomeFoto', '$idProduto', '$descricao', '$localizacao')";
            mysqli_query($conn, $SQL);
        }
    }
}

// Configuração da paginação
$num_items_por_pagina = 2; // Número de itens por página
$pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página atual, padrão é 1
$offset = ($pagina_atual - 1) * $num_items_por_pagina; // Calcular o offset

// Consulta SQL com LIMIT e OFFSET para implementar a paginação
$sql = "SELECT * FROM produtos WHERE id_lojista = 1 LIMIT $offset, $num_items_por_pagina";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Lista de Produtos</title>
</head>
<body>

<div class="container">
    <h1 class="mt-3">Lista de Produtos</h1>

    <!-- Botão para cadastrar novo produto -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Cadastrar Produto
    </button>
    <a href="index.php">Voltar</a>

    <!-- Modal de cadastro de produto -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form action="cadastroProduto.php" method="POST" enctype="multipart/form-data">
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
                            <label for="local" class="form-label">localização:</label>
                            <input type="text" class="form-control" id="local" name="localizacao" required>
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

    <!-- Modal exclui Produto-->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Excluir Produto</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="excluirScript.php" method="post" id="formExcluir">
          <h2>Deseja Excluir <b id="nomeProduto"></b>?</h2>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
          <input type="hidden" name="id" id="idProduto" value="">
          <input type="submit" class="btn btn-danger" value="Sim">
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal exclui Produto-->

    <!-- Exibição dos produtos -->
    <div class="row mt-3">
        <?php 
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $nome = $row["nome"];
                $preco = $row["preco"];
                $imagem = $row["imagem"];
                $id = $row["id"]; 
                $id_lojista = $row["id_lojista"]; 
                $categoria = $row["categoria"];

                if (!empty($imagem)) {
                    $mostra_imagem = "<img src='../img/$imagem' class='list'>";
                } else {
                    $mostra_imagem = '';
                }

                echo "
                <div class='col-md-4'>
                    <div class='card mb-4 shadow-sm'>
                        <img src='../img/$imagem' class='card-img-top' alt='Imagem do Produto'>
                        <div class='card-body'>
                            <h5 class='card-title'>$nome</h5>
                            <p class='card-text'>Preço: R$ $preco</p>
                            <div class='d-flex justify-content-between align-items-center'>
                                <div class='btn-group'>
                                    <a type='button' href='../produto.php?id=$id' class='btn btn-sm btn-outline-secondary'>
                                        Ver Produto
                                    </a>
                                    <button type='button' class='btn btn-sm btn-outline-secondary' data-bs-toggle='modal' data-bs-target='#editarProduto$id'>
                                        Editar
                                    </button>
                                    <button type='button' class='btn btn-sm btn-outline-danger excluir' data-bs-toggle='modal' data-bs-target='#exampleModal' data-id='$id' data-nome='$nome'>
                                        Excluir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "<div class='col-md-12'><p>Nenhum produto encontrado.</p></div>";
        }
        ?>
    </div>

    <!-- Paginação -->
    <nav aria-label="Paginação">
        <ul class="pagination justify-content-center">
            <?php
            // Consulta para contar o total de produtos
            $sql_count = "SELECT COUNT(*) AS total FROM produtos WHERE id_lojista = 1";
            $result_count = $conn->query($sql_count);
            $row_count = $result_count->fetch_assoc();
            $total_items = $row_count['total'];

            // Calcula o número total de páginas
            $total_paginas = ceil($total_items / $num_items_por_pagina);

            // Exibe links de páginação
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<li class='page-item " . ($pagina_atual == $i ? 'active' : '') . "'><a class='page-link' href='cadastroProduto.php?pagina=$i'>$i</a></li>";
            }
            ?>
        </ul>
    </nav>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php
$conn->close();
?>
