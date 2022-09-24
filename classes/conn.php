<?php
$servername = "85.10.205.173"; /* nome da conexão */
$username = "amorimgabriel"; /* nome do usuario da conexãp */
$password = "27092000"; /*senha do banco de dados caso exista */
$dbname = "smart_house_1_0"; /* nome do seu banco  */

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
?>