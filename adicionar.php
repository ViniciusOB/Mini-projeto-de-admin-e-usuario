<?php
session_start();
require_once('conexao.php');

if (!isset($_SESSION['id']) || !$_SESSION['admin']) {
    header("Location: login.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];

    try {
        $stmt = $conn->prepare("INSERT INTO informacoes (titulo, descricao) VALUES (?, ?)");
        $stmt->execute([$titulo, $descricao]);
        
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Erro ao adicionar informação: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Informação</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }


        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }


        h2 {
            text-align: center;
            color: #333;
        }


        form {
            margin-top: 20px;
        }


        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }


        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }


        textarea {
            resize: vertical;
        }


        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }


        button:hover {
            background-color: #45a049;
        }


        .error {
            color: #ff0000;
            margin-bottom: 15px;
        }


        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #333;
            text-decoration: none;
        }


        a:hover {
            color: #4caf50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Adicionar Informação</h2>

        <form method="post" action="adicionar.php">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" required>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" rows="4" required></textarea>

            <button type="submit">Adicionar</button>
        </form>

        <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <a href="index.php">Voltar</a>
    </div>
</body>
</html>
