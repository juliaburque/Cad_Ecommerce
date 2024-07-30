<?php
include('controller/conexao.php');

$descricao = $_POST['descricao'];

echo "<h3>Descrição: $descricao </h3></br>";

$cad_marca = "INSERT INTO marca(`DESCRICAO`) VALUES ('$descricao')";

if(mysqli_query($mysqli, $cad_marca)){
    echo "<h1>marca cadastrada com sucesso!</h1></br>";
}else{
    echo"Erro: ". $cad_marca. "</br>";
    mysqli_error($mysqli);

}mysqli_close($mysqli);
?>
