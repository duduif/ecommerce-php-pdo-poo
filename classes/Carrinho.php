<?php
class Carrinho {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
public function adicionarAoCarrinho($idProduto) {
    $sql = "SELECT * FROM produtos WHERE cod_produto = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$idProduto]);
    $produtoArray = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produtoArray) {
        $estoque = $produtoArray['quantidade_estoque'];

        if ($estoque > 0) {
            if (!isset($_SESSION['carrinho'][$idProduto])) {
                $_SESSION['carrinho'][$idProduto] = 1;
            } else {
                $_SESSION['carrinho'][$idProduto] += 1;
            }

            //atualiza o estoque no banco
            $sql = "UPDATE produtos SET quantidade_estoque = quantidade_estoque - 1 WHERE cod_produto = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idProduto]);
        }
    }
}


    public function listarCarrinho() {
        return isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
    }

    public function removerUmaUnidade($idProduto) {
        //verifica se o produto existe no carrinho
        if (isset($_SESSION['carrinho'][$idProduto])) {
            // Atualiza o banco de dados para devolver uma unidade ao estoque
            try {
                $pdo = new PDO("mysql:host=localhost;dbname=ifbawebii", "root", "");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque + 1 WHERE cod_produto = ?");
                $stmt->execute([$idProduto]);
            } catch (PDOException $e) {
                echo "Erro ao atualizar o estoque: " . $e->getMessage();
            }
    
            //verifeca se a quantidade é maior que 1,se nao for, remove o produto do carrinho
            if ($_SESSION['carrinho'][$idProduto] > 1) {
                $_SESSION['carrinho'][$idProduto]--;
            } else {
                unset($_SESSION['carrinho'][$idProduto]);
            }
        }
    }
    

    public function limparCarrinho() {
        //verifica se o carrinho nao esta vazio
        if (!empty($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $idProduto => $quantidade) {
                try {
                    //atualiza o estoque no banco pra devolver as unidades
                    $pdo = new PDO("mysql:host=localhost;dbname=ifbawebii", "root", "");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque + ? WHERE cod_produto = ?");
                    $stmt->execute([$quantidade, $idProduto]);
                } catch (PDOException $e) {
                    echo "Erro ao atualizar o estoque: " . $e->getMessage();
                }
            }
        }
    
        //limpa o carrinho da sessao
        unset($_SESSION['carrinho']);
    }
    


}
