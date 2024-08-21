<?php
session_start();
require_once('conexao.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();

        // Verificar o número de linhas
        $rowCount = $stmt->rowCount();

        if ($rowCount == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['id'] = $row['id'];
            $_SESSION['nome'] = $row['nome'];
            $_SESSION['admin'] = $row['admin'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Credenciais inválidas";
        }
    } catch (PDOException $e) {
        echo "Erro na consulta: " . $e->getMessage();
    }
}
?>

<!-- Formulário de login HTML -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<section>
        <div class="form-box">
            <div class="form-value">
                <form method="post" action="login.php">
                    <h2>Login</h2>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="senha" required>
                        <label for="senha">Password</label>
                    </div>
                    <div class="forget">
                        <label for=""><input type="checkbox">Remember Me  <a href="#">Forget Password</a></label>
                      
                    </div>
                    <button type="submit" name="login">Log in</button>
                    <div class="register">
                        <p>Don't have a account <a href="#">Register</a></p>
                    </div>
                </form>
                <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
