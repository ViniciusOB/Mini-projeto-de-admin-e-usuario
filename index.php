<?php
session_start();
require_once('conexao.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o formulário de exclusão foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
    $excluirId = $_POST['excluir_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM informacoes WHERE id = ?");
        $stmt->execute([$excluirId]);
        
        // Redireciona de volta para a página após a exclusão
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Erro ao excluir informação: " . $e->getMessage();
    }
}

try {
    $stmt = $conn->query("SELECT * FROM informacoes");
    $informacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <section>
    <div class="form-box-index">
        <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>

        <h2>Informações</h2>
        <!-- Adicione um botão para gerar o PDF -->
    <form method="post" action="gerar_pdf.php">
        <input type="submit" value="Imprimir">
    </form>
        <ul>
            <?php foreach ($informacoes as $info): ?>
                <li>
                    <?php echo $info['titulo']; ?>: <?php echo $info['descricao']; ?>

                    <a href="modificar.php?id=<?php echo $info['id']; ?>" class="button">Modificar</a>
                    
                    <?php if ($_SESSION['admin']): ?>
                        <!-- Formulário de exclusão -->
                        <form method="post" action="index.php" style="display: inline;">
                            <input type="hidden" name="excluir_id" value="<?php echo $info['id']; ?>">
                            <button type="submit" class="button">Excluir</button>
                        </form>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($_SESSION['admin']): ?>
            <a href="adicionar.php" class="button">Adicionar Informação</a>
        <?php endif; ?>

        <a href="logout.php" class="button">Logout</a>
    </div>
    </section>
</body>
</html>
</body>
</html>
