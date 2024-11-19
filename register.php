<?php
// Connessione al database
$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$dbname = "store_starcraft";

$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    // Ottieni i dati dal form
    $email = htmlspecialchars($_POST["email"]);
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    
    // Hash della password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Query per inserire l'utente
    $stmt = $conn->prepare("INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, 'Non VIP')");
    $stmt->bind_param("sss", $email, $username, $hashedPassword);
    
    if ($stmt->execute()) {
        $message = "Registrazione avvenuta con successo!";
    } else {
        echo "Errore durante la registrazione: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-container {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        text-align: center;
    }

    h2 {
        margin-bottom: 20px;
        color: #333;
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 8px;
        text-align: left;
    }

    input[type="email"], input[type="password"], input[type="text"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 2px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    input[type="email"]:focus, input[type="password"]:focus, input[type="text"]:focus {
        border-color: #4CAF50;
        outline: none;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 15px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    p {
        margin-top: 20px;
    }

    a {
        color: #4CAF50;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .error {
        color: red;
        font-size: 14px;
        margin-top: 10px;
    }

    .success {
        color: green;
        font-size: 14px;
        margin-top: 10px;
    }
</style>
<body>

<div class="login-container">
<h2>Modulo di Registrazione</h2>
<form action="" method="POST">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="username">Nome Utente di Minecraft:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <!-- Mostra il messaggio di errore o successo -->
    <?php if (!empty($message)): ?>
        <p class="error"><?php echo $message; ?></p>
    <?php endif; ?>
    
    <input type="submit" name="register" value="Registrati">
    <p>Clicca qui per il login: <a href="login.php">Login</a></p>
</form>
</div>

</body>
<script>
        <?php if (!empty($message)) : ?>
            // Passa il messaggio PHP alla console JavaScript
            console.log("<?php echo $message; ?>");
        <?php endif; ?>
    </script>
</html>
