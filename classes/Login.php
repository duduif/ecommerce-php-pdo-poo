<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class Login
{
    private $usuario;
    private $senha;

    public function __construct($usuario, $senha)
    {
        $this->usuario = $usuario;
        $this->senha = $senha;
    }

    public function autenticar()
    {
        //limpa a sessao ao fazer login pra garantir que os dados do usuario anterior sejam apagados
        session_unset();
        session_destroy();
        session_start();

        //salva o tema escolhido pelo usuario na sessao e no cookie
        if (isset($_POST['tema'])) {
            $_SESSION['tema'] = $_POST['tema'];
            setcookie('tema', $_POST['tema'], time() + (86400 * 30), "/");
        } elseif (isset($_COOKIE['tema'])) {
            $_SESSION['tema'] = $_COOKIE['tema'];
        } else {
            $_SESSION['tema'] = 'claro';
            setcookie('tema', 'claro', time() + (86400 * 30), "/");
        }

        //limpa o carrinho ao fazer login pra garantir que cada usuario tenha seu proprio carrinho
        $_SESSION['carrinho'] = [];
        $_SESSION['usuario_logado'] = true;
        header("Location: index.php");
        exit();
    }


    public static function puxarTemaCatalogo()
    {
        $tema = isset($_SESSION['tema']) ? $_SESSION['tema'] : (isset($_COOKIE['tema']) ? $_COOKIE['tema'] : 'claro');

        if ($tema === 'claro') {
            echo '<link rel="stylesheet" type="text/css" href="assets/css/catalogo_claro.css">';
        } else {
            echo '<link rel="stylesheet" type="text/css" href="assets/css/catalogo_escuro.css">';
        }
    }

    public static function puxarTemaCarrinho()
    {
        $tema = isset($_SESSION['tema']) ? $_SESSION['tema'] : (isset($_COOKIE['tema']) ? $_COOKIE['tema'] : 'claro');

        if ($tema === 'claro') {
            echo '<link rel="stylesheet" type="text/css" href="assets/css/carrinho_claro.css">';
        } else {
            echo '<link rel="stylesheet" type="text/css" href="assets/css/carrinho_escuro.css">';
        }
    }

    public static function puxarTitulo()
    {
?>
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Russo+One&display=swap");

            svg {
                font-family: "Russo One", sans-serif;
                width: 100%;
                height: 100%;
            }

            svg text {
                animation: stroke 6s infinite alternate;
                stroke-width: 2;
                stroke: #4C0B70;
                font-size: 77px;
            }

            @keyframes stroke {
                0% {
                    fill: rgba(0, 0, 0, 0);
                    stroke: rgba(76, 11, 112, 1);
                    stroke-dashoffset: 25%;
                    stroke-dasharray: 0 50%;
                    stroke-width: 2;
                }

                70% {
                    fill: rgba(0, 0, 0, 0);
                    stroke: rgba(76, 11, 112, 1);
                }

                80% {
                    fill: rgba(0, 0, 0, 0);
                    stroke: rgba(76, 11, 112, 1);
                    stroke-width: 3;
                }

                100% {
                    fill: rgba(0, 0, 0, 1);
                    stroke: rgba(76, 11, 112, 0);
                    stroke-dashoffset: -25%;
                    stroke-dasharray: 50% 0;
                    stroke-width: 0;
                }
            }

            .wrapper {
                background-color: transparent;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 150px;
            }
        </style> <?php
                }
            }


            //verifica se o formulario foi enviado
            if (isset($_POST['login'])) {
                $login = new Login($_POST['email'], $_POST['pswd']);
                $login->autenticar();
            }
                    ?>