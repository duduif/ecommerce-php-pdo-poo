<?php
session_start();



if (!isset($_SESSION['usuario_logado'])) {
    header("Location: Painel.php"); 
    exit();
}

if (!isset($_SESSION['tema']) && isset($_COOKIE['tema'])) {
    $_SESSION['tema'] = $_COOKIE['tema'];
}
require_once "config/Sql.php";
require_once "classes/Produto.php";
require_once "classes/Carrinho.php";
require_once 'classes/Login.php';


$sql = new Sql();
$conn = $sql->getConexao();

$produtoObj = new Produto();
$produtos = $produtoObj->listarProdutos();

$carrinho = new Carrinho($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["adicionar"])) {
    $idProduto = $_POST["adicionar"];
    $quantidade = $_POST["quantidade"];

    $carrinho->adicionarAoCarrinho($idProduto, $quantidade);

    $_SESSION['mensagemSucesso'] = "Produto adicionado com sucesso!";
    $_SESSION['produtoAdicionado'] = $idProduto;

    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Produtos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <?php Login::puxarTemaCatalogo(); ?>
    <?php Login::puxarTitulo(); ?>

</head>
<body>
<header>
    <a href="Painel.php" class="login-icone" title="Login"><i class="fa-solid fa-user"></i></a>
    <a href="ver_carrinho.php" class="carrinho-icone" title="Carrinho de Compras"><i class="fa-solid fa-cart-shopping"></i></a>
</header>
 
<div class="wrapper">
    <svg>
        <text x="50%" y="50%" dy=".35em" text-anchor="middle">
            EduX Store
        </text>
    </svg>
</div>

 
<div class="produtos-container">
    <?php 
    $mensagemSucesso = Produto::obterMensagemSucesso();
    $produtoAdicionado = isset($_SESSION['produtoAdicionado']) ? $_SESSION['produtoAdicionado'] : ''; 
    unset($_SESSION['produtoAdicionado']);
    
    foreach ($produtos as $produto): 
    ?>
        <div class="produto">
        <?php $produto->exibirDetalhesProduto(); ?>
            <form method="post">
                <input type="hidden" name="adicionar" value="<?php echo $produto->getCodProduto(); ?>">
                <button type="submit">Adicionar ao carrinho</button>
            </form>

            <?php if ($produto->getCodProduto() == $produtoAdicionado): ?>
                <p class="mensagem-sucesso"><?php echo $mensagemSucesso; ?></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
