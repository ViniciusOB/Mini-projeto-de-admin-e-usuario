<?php
session_start();
require_once('conexao.php');

if (!isset($_SESSION['id']) || !$_SESSION['admin']) {
    header("Location: login.php");
    exit();
}

$error = '';

if (isset($_GET['id'])) {
    $infoId = $_GET['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM informacoes WHERE id = ?");
        $stmt->execute([$infoId]);
        
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Erro ao excluir informação: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Informação</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Excluir Informação</h2>

        <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <a href="index.php">Voltar</a>
    </div>
</body>
</html>
