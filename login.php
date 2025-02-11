<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Login</h1>
        <form action="login.php" method="post">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <button type="submit">Entrar</button>
            <a href="cadastro.php">Cadastrar</a>
        </form>
        <?php
        session_start();

        if (isset($_SESSION['nome']) && isset($_SESSION['senha'])) {
            echo "<p class='error'>Dica: " . htmlspecialchars($_SESSION['nome']) . " " . htmlspecialchars($_SESSION['senha']) . "</p>";
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['nome']) && isset($_POST['senha'])) {
                $nome = $_POST['nome'];
                $senha = $_POST['senha'];

                if ($nome == $_SESSION['nome'] && $senha == $_SESSION['senha']) {
                    header("Location: home.php");
                    exit();
                } else {
                    echo "<p class='error'>Usuário ou senha inválidos</p>";
                }
            }
        }
        ?>
    </div>
</body>

</html>
