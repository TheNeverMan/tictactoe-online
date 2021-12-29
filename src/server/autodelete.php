<?php
//this script deletes finished games
require 'config.php';
// Create connection
$conn = new mysqli($servername, $username, $password);
$query = "USE id18164401_ttto";
$result = $conn->query($query);
$query = "DELETE FROM games WHERE status = 'ended-delete'";
$conn->query($query);
$query = "UPDATE games SET status = 'ended-delete' WHERE status = 'ended'";
$conn->query($query);
$conn->close();
?>
