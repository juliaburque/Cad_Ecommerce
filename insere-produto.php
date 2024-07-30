<?php
include_once('controller/conexao.php');

$categoria     = $_POST['seleciona_categoria'];
$marca         = $_POST['seleciona_marca'];
$nome_produto  = $_POST['nome'];
$descricao     = $_POST['descricao'];
$estoque       = $_POST['estoque'];
$preco         = $_POST['preco'];

$grava_produto = "INSERT INTO produtos (`IDCATEGORIA`, `IDMARCA`, `NOME`, `DESCRICAO`, `ESTOQUE`, `PRECO`) VALUES ('$categoria','$marca ','$nome_produto','$descricao','$estoque','$preco')";

$result_gravacao = mysqli_query($mysqli, $grava_produto);

if(mysqli_affected_rows($mysqli) != 0){
    echo "
        <META HTTP-EQUIV=REFRESH CONTENT = 'o;URL=produtos.php'>
        <script type=\"text/javascript\">
            alert('Produto cadastrado com sucesso');
        </script>
    ";
}else{
    echo "
        <META HTTP-EQUIV=REFRESH CONTENT = 'o;URL=produtos.php'>
        <script type=\"text/javascript\">
            alert('Produto não cadastrado');
        </script>
    ";
}

?>