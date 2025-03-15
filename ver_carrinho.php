<?php
session_start();
if (!isset($_SESSION['tema']) && isset($_COOKIE['tema'])) {
    $_SESSION['tema'] = $_COOKIE['tema'];
}
require_once 'classes/Carrinho.php';
require_once 'config/Sql.php';
require_once 'classes/Login.php';


//criando conexao com o banco
$sql = new Sql();
$conn = $sql->getConexao();

//criando o objeto do carrinho
$carrinho = new Carrinho($conn);

try {
    $pdo = new PDO("mysql:host=localhost;dbname=ifbawebii", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $produtosNoCarrinho = [];

    if (!empty($_SESSION['carrinho'])) {
        $ids = array_keys($_SESSION['carrinho']);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE cod_produto IN ($placeholders)");
        $stmt->execute($ids);
        $produtosNoCarrinho = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <?php Login::puxarTemaCarrinho(); ?>
</head>
<body>
<header>
    <a href="Painel.php" class="login-icone" title="Login"><i class="fa-solid fa-user"></i></a>
    <a href="ver_carrinho.php" class="carrinho-icone" title="Carrinho de Compras"><i class="fa-solid fa-cart-shopping"></i></a>
</header>
<div class="container">
    <h1>Seu Carrinho</h1> 

    <?php
    if (empty($produtosNoCarrinho)) {
        echo "<p>Carrinho vazio.</p>";
        echo '<a href="index.php">Voltar às Compras</a>';
    } else {
        $totalCarrinho = 0;
        foreach ($produtosNoCarrinho as $produto) {
            $id = $produto['cod_produto'];
            $quantidade = $_SESSION['carrinho'][$id];
            $subtotal = $quantidade * $produto['valor_produto'];
            $totalCarrinho += $subtotal;
            ?>
            <div class="produto">
                <img src="<?php echo $produto['imagem_produto']; ?>" alt="<?php echo $produto['desc_produto']; ?>" />
                <div class="produto-info">
                    <p><?php echo $quantidade; ?>x <?php echo $produto['desc_produto']; ?> - R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></p>
                    <a href="ver_carrinho.php?remover=<?php echo $id; ?>">Remover</a>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="produto">
            <a href="ver_carrinho.php?limpar=true">Limpar Carrinho</a>
            <a href="index.php">Voltar às Compras</a>
            <p><strong>Total: R$ <?php echo number_format($totalCarrinho, 2, ',', '.'); ?></strong></p>
        </div>
        <?php
    }
    ?>
</div>

<?php
//remover uma unidade do produto
if (isset($_GET['remover'])) {
    $idProduto = $_GET['remover'];
    $carrinho->removerUmaUnidade($idProduto);
    
    //pra evita reenvio do form
    header('Location: ver_carrinho.php');
    exit;
}

//limpar todo o carrinho
if (isset($_GET['limpar']) && $_GET['limpar'] == 'true') {
    $carrinho->limparCarrinho();
    
    //pra evitar reenvio do form
    header('Location: ver_carrinho.php');
    exit;
}
?>

</body>
</html>
