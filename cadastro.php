<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
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
            padding: 30px;
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
            text-align: center;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
    <script>
        function formatarData(input) {
            let valor = input.value.replace(/\D/g, "");
            if (valor.length > 8) valor = valor.slice(0, 8);
            
            if (valor.length >= 2 && valor.length <= 4) {
                valor = valor.replace(/(\d{2})/, "$1/");
            } else if (valor.length > 4) {
                valor = valor.replace(/(\d{2})(\d{2})/, "$1/$2/");
            }
            input.value = valor;
        }
    </script>
</head>

<body>
    <div class="container">
        <h1>Cadastro</h1>
        <form action="" method="post">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="data_nascimento">Data de Nascimento (DDMMAAAA):</label>
            <input type="text" name="data_nascimento" id="data_nascimento" required oninput="formatarData(this)" minlength='10' maxlength="10">

            <button type="submit">Cadastrar</button>
        </form>
        <?php
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!empty($_POST['nome']) && !empty($_POST['data_nascimento'])) {
                $_SESSION['nome'] = $_POST['nome'];
                $_SESSION['senha'] = preg_replace("/\D/", "", $_POST['data_nascimento']);
                header('Location: login.php');
                exit();
            } else {
                echo "<p class='error'>Preencha todos os campos</p>";
            }
        }
        ?>
    </div>
</body>

</html>
