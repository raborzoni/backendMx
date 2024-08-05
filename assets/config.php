<?php
$config = array(
    'host' => 'ftp.harmonico.org',
    'port' => 21, // Porta do servidor SMTP
    'username' => 'ramon@harmonico.org',
    'password' => 'KLGW85B74X0TF5NLIU'
);

$database = "harmonic_mxdev";
$servername = "69.164.198.18";
$username = "harmonic_messenger";
$password = "COCOTINHA2024BENTO";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

return $config;
?>
