<?php
echo "<h2>Test Configurazione Ambiente</h2>";

// === TEST CONFIGURAZIONE PHP ===
echo "<h3>Configurazione PHP</h3>";
echo "Host SMTP: <b>" . ini_get('SMTP') . "</b><br>";
echo "Porta SMTP: <b>" . ini_get('smtp_port') . "</b><br>";
echo "Sendmail Path: <b>" . ini_get('sendmail_path') . "</b><br>";
echo "Estensione mysqli: <b>" . (extension_loaded('mysqli') ? 'Caricata' : 'Non caricata') . "</b><br>";
echo "<hr>";

// === TEST CONNESSIONE DATABASE ===
echo "<h3>Test Connessione MySQL</h3>";
$db_host = getenv('DB_HOST') ;
$db_user = getenv('DB_USER') ;
$db_pass = getenv('DB_PASSWORD') ;
$db_name = getenv('DB_NAME') ;

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    echo "<p style='color: red;'>Errore connessione: " . $mysqli->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>Connessione riuscita</p>";
    echo "Server: <b>" . $mysqli->server_info . "</b><br>";
    echo "Database: <b>" . $db_name . "</b><br>";
    
    $result = $mysqli->query("SELECT DATABASE() as db, NOW() as now");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "Database attivo: <b>" . $row['db'] . "</b><br>";
        echo "Timestamp server: <b>" . $row['now'] . "</b><br>";
    }
    
    $mysqli->close();
}
echo "<hr>";

// === TEST INVIO MAIL ===
echo "<h3>Test Invio Email</h3>";
$to = "test@esempio.it";
$subject = "Test email - " . date('H:i:s');
$message = "<h1>Test</h1><p>Email catturata da Mailpit.</p>";

$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=utf-8';
$headers[] = 'From: app@test.local';

if (mail($to, $subject, $message, implode("\r\n", $headers))) {
    echo "<p style='color: green;'>Invio riuscito</p>";
    echo "Mailbox: <a href='http://localhost:8025' target='_blank'>http://localhost:8025</a>";
} else {
    echo "<p style='color: red;'>Errore invio</p>";
}
?>