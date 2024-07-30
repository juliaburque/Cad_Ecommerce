<?php
  class Produtos {
    function buscar(){
      require_once('controller/conexao.php');

      $produtos = $mysqli->query("SELECT p.*, c.DESCRICAO as CATEGORIA, m.DESCRICAO as MARCA FROM produtos p INNER JOIN categoria c ON c.IDCATEGORIA = p.IDCATEGORIA INNER JOIN marca m ON m.IDMARCA = p.IDMARCA ORDER BY IDPROD DESC");

      echo '<ul>';
      while ($prod = mysqli_fetch_object($produtos)){
        echo '<li>
                <h2>'.$prod->NOME.'</h2><p>'.$prod->DESCRICAO.'</p>
                <p><b>Categoria: </b>'.$prod->CATEGORIA.'</p>
                <p><b>Marca: </b>'.$prod->MARCA.'</p>
                <p><b>Estoque: </b>'.$prod->ESTOQUE.'</p>
                <p><b>Valor: </b>R$'.str_replace('.',',',$prod->PRECO).'</p>
                <p><b>Status: </b>'.($status = $prod->ESTOQUE > 0 ? "Disponível":"Indisponível").'</p>
                <a id="adicionar" href="controller/carrinho-add.php?id='.$prod->IDPROD.'">Adicionar ao carrinho</a>
              </li>';
      }
      echo '</ul>';
    }

    function pedido(){
      require_once('../controller/conexao.php');
      session_start();

      $pedido = $mysqli->query("INSERT INTO pedido(IDCLI, IDFOR, STATUS) VALUES (1,1,'E')");
      if ($pedido){
        $id = mysqli_insert_id($mysqli);
        $u = 1;
        for ($i=0; $i < max(array_keys($_SESSION['produtos'])); $i++) {
          if (@$_SESSION['produtos'][$i+1]){
            $produto = $i+1;
            $quantidade = $_SESSION['produtos'][$i+1];
            //Adiciona produtos a tabela item pedido
            $mysqli->query("INSERT INTO itempedido(IDPED, IDPROD, SEQ, QTDE) VALUES ($id, $produto, $u, $quantidade)");
            $mysqli->query("UPDATE produtos SET ESTOQUE = ESTOQUE - $quantidade WHERE IDPROD = $produto");
            $u++;
          }
        }
        unset($_SESSION['produtos']);

        header('location: ../pedido.php?pedido='.$id);
      } else {
        echo "Erro! Ocorreu um problema ao adicionar o pedido";
      }
    }

    function resumo(){
      session_start();
      require_once('controller/conexao.php');

      if ($id = @$_GET['pedido']){
        $pedido = $mysqli->query("SELECT i.*,p.*,i.QTDE * p.PRECO AS TOTAL, c.DESCRICAO as CATEGORIA, m.DESCRICAO as MARCA FROM itempedido i INNER JOIN produtos p ON i.IDPROD = p.IDPROD INNER JOIN categoria c ON p.IDCATEGORIA = c.IDCATEGORIA INNER JOIN marca m ON m.IDMARCA = p.IDMARCA WHERE i.IDPED = $id");
        $total = 0;

        echo "<b>Cod. Pedido: </b><p>$id</p>";
        echo "<br>";
        echo "<h2>Produtos</h2>";
        while ($ped = mysqli_fetch_object($pedido)){
          echo "<p>Nome: $ped->NOME</p>";
          echo "<p>Descrição: $ped->DESCRICAO</p>";
          echo "<p>Categoria: $ped->CATEGORIA</p>";
          echo "<p>Marca: $ped->MARCA</p>";
          echo "<p>Preço: R$".str_replace('.',',',$ped->PRECO)."</p>";
          echo "<p>Quantidade: $ped->QTDE</p>";
          echo "<br>";
          $total += $ped->TOTAL;
        }
        echo "<b>Valor total: </b>";
        echo "<p>R$".str_replace('.',',',$total)."</p>";
      } else {
        echo "Erro! Nenhum pedido foi selecionado";
      }
    }

    function inserirProduto(){

      require_once('../controller/conexao.php');
      session_start();

      $produto = $mysqli->query("INSERT INTO PRODUTOS (IDCATEGORIA,IDMARCA,IDPROD,DESCRICAO,ESTOQUE,NOME,PRECO) VALUES(1,1,4,'Computador Portátil',10,'Ultrabook',4000)");

      if($produto){
        $id = mysqli_insert_id($mysqli);

        unset($_SESSION['produtos']);

        header('location:../pedido.php?pedido='.$id);

      }else
      {
        echo "Erro! Adicionar o produto";
      }
      
    }

    function inserirCliente(){

      require_once('../controller/conexao.php');
      session_start();

      $produto = $mysqli->query("INSERT INTO CLIENTE (IDCLI, NOME, LOGIN, SENHA, DTNASC, ENDERECO, SEXO, CPF, RG, TELEFONE, CELULAR) VALUES(2,'Jose Carlos','Jose','123456','1979-03-22','Rua Brasil, 100', 1, '0123456789','0123456789','99999-9999','88888-8888')");

      if($produto){
        $id = mysqli_insert_id($mysqli);

        unset($_SESSION['produtos']);

        header('location:../pedido.php?pedido='.$id);

      }else
      {
        echo "Erro! Adicionar o cliente";
      }




    } 

  }
?>
