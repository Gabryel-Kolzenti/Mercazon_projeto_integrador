<?php
include_once "restrito/conexao.php";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <style>
        @charset "utf-8";

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .card {
            border-radius: 4px;
            background-color: #c5c5c5;
            width: 300px;
        }

        .cardImg img {
            height: 30vh;
            width: 100%;
            border-radius: 4px;
        }

        .cardTextos {
            padding: 3%;
        }

        .cardDinheiro {
            font-size: 1.6rem;
        }

        .cardH4 {
            all: unset;
            font-size: 1.4rem;
        }

        .cardDescricao {
            font-size: 1.1rem;
            min-height: 20vh;
        }

        .grupo-cardLocal {
            display: flex;
        }

        .cardLocal {
            font-size: .9rem;
            margin-top: 2%;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100vw;
            height: auto;
            
        }

        .produtos {
            display: grid;
            grid-template-columns: auto auto auto;
            grid-template-rows: auto auto auto auto;
            gap: 20px;
        }

        .cartao {
            width: 100px;
            border: solid 1px black;
        }

        .cartao img {
            width: 200px;
        }

        .busca{
            margin: 0px 80px;
            height: 50px;
            
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Busca de produtos</title>
</head>

<body>

    <header>
        <h1>busca de produtos</h1>
    </header>

    <main>

    <div class="busca">

    </div>


        <div class="produtos">
            <!-- Buscando os produtos mais clicados para atlea incial -->
            <?php

            $sql = 'SELECT id, nome, preco, categoria, imagem FROM produtos ORDER BY contador_cliques DESC LIMIT 4;';
            $resultado = $conn->query($sql);
            while ($linha = mysqli_fetch_assoc($resultado)) {
                $nome = $linha['nome'];
                $imagem = $linha['imagem'];
                $preco = $linha['preco'];
                $categoria = $linha['categoria'];
                $id = $linha['id'];

                echo "

        <div class='card'>
        <div class='cardImg'>
            <img src='img/$imagem' alt'$nome'>
        </div>

        <div class='cardTextos'>
            <p class='cardDinheiro'><b>R$ 1000,00</b></p>
            <h4 class='cardH4'>$nome | $categoria </h4><br><br>            
            <p class='cardDescricao'>Descrição: Produto muito usado. Não compre pois é um clickbait para hackear sua conta e descbrir onde você mora, mas eu não me importo se tu se importa.</p><br>
            <div class='grupo-cardLocal'>
                <p class='cardLocal'>Rua, Bairro, Porto Alegre</p>
            </div>
            <form action='' method=POST>
                    <input type='hidden' name='idFavorito' value='$id'>
                    <input type='submit' value='favoritar' name='favoritoSubmit'>
            </form>
        </div>
    </div>";
            }

            ?>

            <!-- Favoritando os produtos -->
            <?php
            if (isset($_POST['favoritoSubmit'])) {
                $idProduto = $_POST['idFavorito'];
                $sql = "SELECT id FROM usuario_favorita_produto WHERE id_usuario = $user AND id_produto = $idProduto";
                $resultado = $conn->query($sql);
                $numLinha = mysqli_num_rows($resultado);

                if ($numLinha > 0) {
                    $sql = "DELETE FROM usuario_favorita_produto WHERE id_usuario = $user AND id_produto = $idProduto;";
                    if ($conn->query($sql)) {
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
    </main>

</body>

</html>