<?php
session_start();
require_once('conexao.php');


if (!isset($_SESSION['id']) || !$_SESSION['admin']) {
    header("Location: login.php");
    exit();
}


$error = '';
$info = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $infoId = $_POST['info_id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];


    try {
        $stmt = $conn->prepare("UPDATE informacoes SET titulo = ?, descricao = ? WHERE id = ?");
        $stmt->execute([$titulo, $descricao, $infoId]);
       
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Erro ao modificar informação: " . $e->getMessage();
    }
}


if (isset($_GET['id'])) {
    $infoId = $_GET['id'];
    try {
        $stmt = $conn->prepare("SELECT * FROM informacoes WHERE id = ?");
        $stmt->execute([$infoId]);
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
       
        // Verificar se $info não é nulo antes de usá-lo
        if (!$info) {
            $error = "Nenhuma informação encontrada para o ID especificado.";
        }
    } catch (PDOException $e) {
        $error = "Erro ao recuperar informação: " . $e->getMessage();
    }
} else {
    $error = "ID da informação não especificado.";
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Modificar Informação</title>
    <link rel="stylesheet" href="styles.css">
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
        <h2>Modificar Informação</h2>


        <?php if (!empty($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php elseif (isset($info['id'])) : ?>
            <form method="post" action="modificar.php">
                <input type="hidden" name="info_id" value="<?php echo $info['id']; ?>">


                <label for="titulo">Título:</label>
                <input type="text" name="titulo" value="<?php echo $info['titulo']; ?>" required>


                <label for="descricao">Descrição:</label>
                <textarea name="descricao" rows="4" required><?php echo $info['descricao']; ?></textarea>


                <button type="submit">Modificar</button>
            </form>
        <?php endif; ?>


        <a href="index.php">Voltar</a>
    </div>
</body>
</html>
