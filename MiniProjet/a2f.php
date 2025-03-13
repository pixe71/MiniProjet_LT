<?php
session_start();
require 'db.php';

if (!isset($_SESSION['temp_user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['temp_user'];

$code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
$expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

$stmt = $pdo->prepare("INSERT INTO auth_codes (user_id, code, expires_at) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $code, $expires_at]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM auth_codes WHERE user_id = ? AND code = ? AND expires_at > NOW() AND used = 0");
    $stmt->execute([$user_id, $_POST['code']]);
    
    if ($stmt->fetch()) {
        $pdo->prepare("UPDATE auth_codes SET used = 1 WHERE code = ?")->execute([$_POST['code']]);
        $_SESSION['user_id'] = $user_id;
        unset($_SESSION['temp_user']);
        header("Location: index.php");
        exit();
    } else {
        $error = "Code invalide ou expiré";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification 2FA</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>
    <div class="login-container">
        <div class="qr-container">
            <div id="qrcode"></div>
        </div>
        
        <form method="POST">
            <h2>QRCode d'authentification</h2>
            <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
            <input type="text" name="code" placeholder="Code à 6 chiffres" required pattern="\d{6}">
            <button type="submit">Vérifier</button>
        </form>
    </div>

    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "<?= $code ?>",
            width: 128,
            height: 128
        });
    </script>
</body>
</html>
