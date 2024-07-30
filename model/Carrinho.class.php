<?php
  class Carrinho {
    function __construct(){
      //Inicia a sessão
      session_start();
    }

    function add() {
      require_once("../controller/conexao.php");
      // Checa para ver se existe algum produto selecionado
      if ($id = @$_GET['id']){
        $produtos = $mysqli->query("SELECT * FROM produtos WHERE IDPROD = $id");

        if ($quantidade = @$_GET['qnt']){
          if ($prod = mysqli_fetch_object($produtos)){
            if (@$_SESSION['produtos']){
              // Se existir um produto ele checa se este produto já está na sessão
                if (@$_SESSION['produtos'][$id]){
                  // Se ele estiver na sessão, soma a quantidade já existente com a quantidade adicionada para comparar com o estoque
                  $qnt = $_SESSION['produtos'][$id] + $quantidade;
                  // Ele checa se a quantidade em estoque é compatível com a quantidade selecionada
                  if ($qnt > 0 && $qnt <= $prod->ESTOQUE){
                    // Se a quantidade for compatível ele adiciona ao carrinho
                    $_SESSION['produtos'][$id] += $quantidade;
                  } else {
                    // Se a quantidade não for compatível ele retorna erro
                    echo 'Erro, a quantidade deve ser compatível ao estoque </br><a href="../index.php">Voltar</a>';
                    exit;
                  }
                } else {
                  // Se não existir um produto ele checa se a quantidade de estoque é compatível
                  if ($quantidade > 0 && $quantidade <= $prod->ESTOQUE){
                    $_SESSION['produtos'][$id] = $quantidade;
                  } else {
                    // Se a quantidade não for compatível ele retorna erro
                    echo 'Erro, a quantidade deve ser compatível ao estoque </br><a href="../index.php">Voltar</a>';
                    exit;
                  }
                }
            } else {
              // Se não existir nenhum produto ele cria um novo array na sessão e adiciona a quantidade
              if ($quantidade > 0 && $quantidade <= $prod->ESTOQUE){
                $_SESSION['produtos'] = array();
                $_SESSION['produtos'][$id] = $quantidade;
              } else {
                // Se a quantidade não for compatível ele retorna erro
                echo 'Erro, a quantidade deve ser compatível ao estoque </br><a href="../index.php">Voltar</a>';
                exit;
              }
            }
          }
        } else {
          echo 'Erro, nenhuma quantidade foi definida </br><a href="../index.php">Voltar</a>';
          exit;
        }
      } else {
        echo 'Erro, nenhum produto foi selecionado </br><a href="../index.php">Voltar</a>';
        exit;
      }
      // Redireciona para a inicial
      header('location: ../index.php');
    }

    function buscar(){
        require_once('controller/conexao.php');


        if (@$_SESSION['produtos']){
          echo '<ul>';
          // Cria um loop para encontrar os produtos adicionados

          for ($i=0; $i < max(array_keys($_SESSION['produtos'])); $i++) {
            if (@$_SESSION['produtos'][$i+1]){
              $quantidade = $_SESSION['produtos'][$i+1];
              $produto = $mysqli->query("SELECT p.*, c.DESCRICAO as CATEGORIA, m.DESCRICAO as MARCA FROM produtos p INNER JOIN categoria c ON c.IDCATEGORIA = p.IDCATEGORIA INNER JOIN marca m ON m.IDMARCA = p.IDMARCA WHERE IDPROD = ($i+1)");
              if ($prod = mysqli_fetch_object($produto)){
                $preco = str_replace('.',',',$prod->PRECO);
                $valortotal = str_replace('.',',', ($quantidade * $prod->PRECO));
                echo '<li>
                        <h2>'.$prod->NOME.'</h2><p>'.$prod->DESCRICAO.'</p>
                        <p><b>Categoria: </b>'.$prod->CATEGORIA.'</p>
                        <p><b>Marca: </b>'.$prod->MARCA.'</p>
                        <p><b>Estoque: </b>'.$prod->ESTOQUE.'</p>
                        <p><b>Preço: </b>R$'.$preco.'</p>
                        <p><b>Quantidade: </b>'.$quantidade.'</p>
                        <p><b>Valor total: </b>R$'.$valortotal.'</p>
                        <a href="controller/carrinho-remover.php?id='.$prod->IDPROD.'">Remover do carrinho</a>
                      </li>';
              }
            }
          }
          echo '</ul><a href="controller/produtos-pedido.php">Finalizar pedido</a>';
        }
    }

    function remover(){
      // Checa para ver se existe algum produto selecionado
      if ($id = @$_GET['id']){
        // Se estiver selecionado, ele remove o produto da sessão
        unset($_SESSION['produtos'][$id]);
      } else {
        echo '<p>Erro! Nenhum produto selecionado';
      }

      header('location: ../carrinho.php');
    }
  }
?>
