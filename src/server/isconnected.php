<?php
//arguments:
//gid - game id
//result:
//ok - 2 players are connected to game
require 'config.php';
// Create connection
$conn = new mysqli($servername, $username, $password);
$game_id = $_GET['gid'];
if (filter_var($game_id, FILTER_SANITIZE_STRING, FILTER_FLAG_ALLOW_HEX)) {
	//cool
} else {
  die();
}
if (strlen($game_id) != 64)
{
  die();
}
$query = "USE id18164401_ttto";
$result = $conn->query($query);
$query = "SELECT * FROM games WHERE id = '" . $game_id . "'";
$result = $conn->query($query);
if($result)
{
  $row = $result->fetch_assoc();
  if($row['status'] != "wait")
  {
    echo "ok";
  }
}
$conn->close();
?>
