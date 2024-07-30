<!DOCTYPE html>
<html>
  <head>
    <!-- Material disponibilizado pelo professor Anderson Macedo -->
    <meta charset="utf-8">
    <title>Produtos</title>
    <link rel="stylesheet" href="css/style.css" media="screen" title="no title" charset="utf-8">
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
  </head>
  <body>
    <header>
      <div class="center">
        <h1 style="text-align: center">Programação Web II - Pedido de compra</h1>
        <a href="carrinho.php" target="_blank">Carrinho</a>
      </div>
    </header>
    <section id="produtos">
        <div class="center">
          <?php require_once('controller/produtos-busca.php'); ?>
        </div>
    </section>
  </body>
</html>
