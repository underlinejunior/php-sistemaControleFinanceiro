<?php
session_start();

if (!isset($_SESSION['entradas'])) {
    $_SESSION['entradas'] = [];
}
if (!isset($_SESSION['saidas'])) {
    $_SESSION['saidas'] = [];
}

if (isset($_POST['adicionar'])) {
    $descricao = trim($_POST['descricao']);
    $valor = floatval($_POST['valor']);
    $tipo = $_POST['tipo'];

    if ($tipo === "entrada") {
        if (!is_array($_SESSION['entradas'])) {
            $_SESSION['entradas'] = [];
        }
        $_SESSION['entradas'][] = $valor;
    } elseif ($tipo === "saida") {
        if (!is_array($_SESSION['saidas'])) {
            $_SESSION['saidas'] = [];
        }
        $_SESSION['saidas'][] = $valor;
    }

    $arquivo = fopen("arquivo.txt", "a");
    fwrite($arquivo, "{$descricao} - R$" . number_format($valor, 2, ",", ".") . " - {$tipo}\n");
    fclose($arquivo);
}

if  (isset($_POST['excluir'])) {
    $indiceExcluir = intval($_POST['indice']);

    $linhas = file("arquivo.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (isset($linhas[$indiceExcluir])) {
        unset($linhas[$indiceExcluir]);
        file_put_contents("arquivo.txt", implode("\n", $linhas) . "\n");
    }

    $_SESSION['entradas'] = [];
    $_SESSION['saidas'] = [];


    foreach ($linhas as $linha) {
        list($descricao, $valor, $tipo) = explode(" - ", $linha);
        $valor = floatval(str_replace(['R$', '.'], ['', ''], $valor)); 

        if ($tipo === "entrada") {
            $_SESSION['entradas'][] = $valor;
        } elseif ($tipo === "saida") {
            $_SESSION['saidas'][] = $valor;
        }
    }


    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financeiro</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .name {
            width: 80vw;
            text-align: right;
        }

        .totais {
            display: flex;
            justify-content: space-around;
            margin: 10px;
            width: 90vw;
        }

        .subtotais {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: rgb(213, 152, 112);
            width: 20vw;
            border-radius: 10px;
            padding: 10px;
        }

        .adicionar {
            margin: 20px;
            background-color: #f5c2a1;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
            width: 90vw;
            display: flex;
            justify-content: space-around;
        }

        .relatorio {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px;
            background-color: #f5c2a1;
            padding: 10px;
            border-radius: 20px;
            width: 90vw;
        }

        .linha {
            display: flex;
            justify-content: space-between;
            margin: 10px;
            width: 90%;
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }

        .linha span {
            flex: 1;
            text-align: center;
        }

        .linha button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>Controle Financeiro</h1>

    <?php
    if (!isset($_SESSION['nome'])) {
        header("Location: login.php");
        exit();
    } else {
        echo "<span class='name'> Bem vindo, " . $_SESSION['nome'] . "</span>";
    }
    ?>

    <div class="totais">
        <div class="subtotais" style="background-color: rgb(152, 213, 152);">
            <h2>Entradas</h2>
            <p style="color: green;"><?php
                // Garantindo que $_SESSION['entradas'] é sempre um array
                if (is_array($_SESSION['entradas'])) {
                    echo "R$" . number_format(array_sum($_SESSION['entradas']), 2, ",", ".");
                } else {
                    echo "R$0,00";
                }
                ?></p>
        </div>

        <div class="subtotais" style="background-color: rgb(213, 152, 152);">
            <h2>Saídas</h2>
            <p style="color: red;"><?php
                // Garantindo que $_SESSION['saidas'] é sempre um array
                if (is_array($_SESSION['saidas'])) {
                    echo "R$" . number_format(array_sum($_SESSION['saidas']), 2, ",", ".");
                } else {
                    echo "R$0,00";
                }
                ?></p>
        </div>

        <div class="subtotais" style="background-color: rgb(213, 152, 112);">
            <h2>Total</h2>
            <p style="font-weight: bold;" >
            <?php 
                // Garantindo que ambas são arrays
                $entradas = is_array($_SESSION['entradas']) ? array_sum($_SESSION['entradas']) : 0;
                $saidas = is_array($_SESSION['saidas']) ? array_sum($_SESSION['saidas']) : 0;
                echo "R$" . number_format($entradas - $saidas, 2, ",", ".");
                ?>
                </p>
        </div>
    </div>

    <div class="adicionar" style="background-color: #DBDBDBFF;">
        <form method="POST">
            <label for="descricao">Descrição:</label>
            <input type="text" name="descricao" required>
            <label for="valor">Valor:</label>
            <input type="number" step="0.01" name="valor" required>
            <input type="radio" name="tipo" value="entrada" required>Entrada
            <input type="radio" name="tipo" value="saida">Saída
            <button type="submit" name="adicionar">Adicionar</button>
        </form>
    </div>

    <div class="relatorio">
        <h2>Relatório</h2>
        <?php
        if (file_exists("arquivo.txt")) {
            $linhas = file("arquivo.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($linhas as $indice => $linha) {
                list($descricao, $valor, $tipo) = explode(" - ", $linha);
                echo "<div class='linha'>";
                echo "<span>{$descricao}</span>";
                echo "<span>{$valor}</span>";
                echo "<span style='color: " . ($tipo == "entrada" ? "green" : "red") . "'>{$tipo}</span>";
                echo "<form method='POST' style='display:inline;'><input type='hidden' name='indice' value='{$indice}'>";
                echo "<button type='submit' name='excluir'>Excluir</button></form>";
                echo "</div>";
            }
        }
        ?>
    </div>

</body>

</html>
        