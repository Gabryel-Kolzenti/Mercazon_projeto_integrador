<?php 

include 'restrito/conexao.php';

if (!isset($_GET['id'])) {
header('Location: cadastroProduto.php');
}

$idProduto = $_GET['id'];
$sql = "SELECT * FROM produtos WHERE id = '$idProduto'";
$resultado = $conn->query($sql);//

if ($resultado->num_rows > 0) {
    $linha = $resultado->fetch_assoc();
    $nome = $linha['nome'];
    $imagem = $linha['imagem'];
    $preco = $linha['preco'];
    $categoria = $linha['categoria'];
    }
?>

<h1><?php echo "$nome" ?></h1>
<img src="img/<?php echo"$imagem" ?>" alt="">
<h3><?php echo "$preco" ?></h3>
<h3><?php echo "$categoria" ?></h3>
<a href="restrito/cadastroProduto.php">Voltar</a>

<?php

?>