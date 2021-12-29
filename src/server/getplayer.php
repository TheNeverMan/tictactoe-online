<?php
//arguments:
//pid - player id
//result:
//username asociated with id
require 'config.php';
// Create connection
$conn = new mysqli($servername, $username, $password);
$id = $_GET['pid'];
$id_validate = "0x" . $id;
if (filter_var($id, FILTER_SANITIZE_STRING, FILTER_FLAG_ALLOW_HEX)) {
	//cool
} else {
  die();
}
if (strlen($id) != 64)
{
  die();
}
// Check connection
if ($conn->connect_error) {
  die();
}
$query = "USE id18164401_ttto";
$result = $conn->query($query);
$query = "SELECT username FROM players WHERE id = '" . $id . "'";
$result = $conn->query($query);
if (!$result)
{
	die();
}
while($row = $result->fetch_assoc()) {
    echo $row['username'];
}
$conn->close();
?>
