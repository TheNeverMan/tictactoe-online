<?php
//arguments:
//pid - player id
//un - username
//result:
//new record in db (new player record)
require 'config.php';
// Create connection
$conn = new mysqli($servername, $username, $password);
$id = $_GET['pid'];
$username = $_GET['un'];
$id_validate = "0x" . $id;
if (! filter_var($id, FILTER_SANITIZE_STRING, FILTER_FLAG_ALLOW_HEX)) {
  die();
}
if (! filter_var($username, FILTER_SANITIZE_ENCODED)) {
  die();
}
if (strlen($username) > 32 || strlen($username) < 2)
{
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
$query = "INSERT INTO players (id, username, elo) VALUES ('" . $id . "', '" . $username . "', 1000)";
$result = $conn->query($query);
if (!$result)
{
  $conn->close();
  echo $result->error;
	die();
}
else {
  echo "ok";
}
$conn->close();
?>
