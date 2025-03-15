<?php
require_once "config/Sql.php";

class Produto extends Sql
{
    
    private $cod_produto;
    private $desc_produto;
    private $valor_produto;
    private $quantidade_estoque;
    private $imagem_produto;
    private $quantidade_carrinho;

    private $conn;

    public function __construct() {
        $sql = new Sql();
        $this->conn = $sql->getConexao();
    }

    public function setCodProduto($cod_produto) {
        $this->cod_produto = $cod_produto;
    }

    public function setDescProduto($desc_produto) {
        $this->desc_produto = $desc_produto;
    }

    public function setValorProduto($valor_produto) {
        $this->valor_produto = $valor_produto;
    }

    public function setQuantidadeEstoque($quantidade_estoque) {
        $this->quantidade_estoque = $quantidade_estoque;
    }

    public function setImagemProduto($imagem_produto) {
        $this->imagem_produto = $imagem_produto;
    }

    public function setQuantidadeCarrinho($quantidade) {
        $this->quantidade_carrinho = $quantidade;
    }

    public function getCodProduto() {
        return $this->cod_produto;
    }

    public function getDescProduto() {
        return $this->desc_produto;
    }

    public function getValorProduto() {
        return $this->valor_produto;
    }

    public function getQuantidadeEstoque() {
        return $this->quantidade_estoque;
    }

    public function getImagemProduto() {
        return $this->imagem_produto;
    }

    public function getQuantidadeCarrinho() {
        return $this->quantidade_carrinho;
    }

    public function listarProdutos() {
        $sql = "SELECT * FROM produtos";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Produto');
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    public static function obterMensagemSucesso() {
        $mensagemSucesso = isset($_SESSION['mensagemSucesso']) ? $_SESSION['mensagemSucesso'] : '';
        unset($_SESSION['mensagemSucesso']);
        return $mensagemSucesso;
    }

    function exibirDetalhesProduto() {
        echo "<h2>" . $this->getDescProduto() . "</h2>";
        echo "<img src='" . $this->getImagemProduto() . "' alt='" . $this->getDescProduto() . "' width='150'>";
        echo "<p>Preço: R$ " . number_format($this->getValorProduto(), 2, ',', '.') . "</p>";
        echo "<p>Estoque: " . $this->getQuantidadeEstoque() . "</p>";
    }
    

}
?>