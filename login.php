<?php
session_start();

$message = "";

// Connessione al database
$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$dbname = "dbname";

$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);


if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Elaborazione del form di login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);


    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Verifica se l'utente esiste
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $hashedPassword, $role);
        $stmt->fetch();

        // Maggiore sicurezza
        if (password_verify($password, $hashedPassword)) {
            $_SESSION["user_id"] = $userId;
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $role;

            
            header("Location: store.php"); // Si puo' mettere quello che si vuole
            exit();
        } else {
            $message = "Password errata. Riprova.";
        }
    } else {
        $message = "Nome utente non trovato.";
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
    <title>Login</title>
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

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, input[type="password"]:focus {
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
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form action="" method="POST">
        <label for="username">Nome utente:</label>
        <input type="text" id="username" name="username" required placeholder="Inserisci il tuo nome utente"><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required placeholder="Inserisci la tua password"><br>

        <input type="submit" name="login" value="Login">
        
        <!-- Mostra il messaggio di errore, se presente -->
        <?php if (!empty($message)) { ?>
            <p class="error"><?php echo $message; ?></p>
        <?php } ?>

        <p>Non hai un account? <a href="register.php">Registrati!</a></p>
    </form>
</div>

</body>
</html>

