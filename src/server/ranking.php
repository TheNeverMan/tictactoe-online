<?php
//arguments:
//none
//result:
//player names ranked by elo
require 'config.php';
// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
  die();
}
$query = "USE id18164401_ttto";
$result = $conn->query($query);
$query = "SELECT ranks, username, elo FROM players ORDER BY elo DESC";
$result = $conn->query($query);
while($row = $result->fetch_assoc()) {
    echo "\\" . $row['ranks'] . $row['username'] . "\\e[0m" . " " . $row['elo'];
    echo "\\n";
}
$conn->close();
?>
