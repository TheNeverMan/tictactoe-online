<?php
//arguments:
//pid - player id
//gid - game id
//result:
//string containing all moves enemy has done
require 'config.php';
// Create connection
$conn = new mysqli($servername, $username, $password);
$id = $_GET['pid'];
$game_id = $_GET['gid'];
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
if (filter_var($game_id, FILTER_SANITIZE_STRING, FILTER_FLAG_ALLOW_HEX)) {
	//cool
} else {
  die();
}
if (strlen($game_id) != 64)
{
  die();
}
// Check connection
if ($conn->connect_error) {
  die();
}
$query = "USE id18164401_ttto";
$result = $conn->query($query);
$query = "SELECT * FROM games WHERE id = '" . $game_id . "'";
$result = $conn->query($query);
if($result)
{
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      if($row['player1'] == $id)
      {
        echo $row['moves2'];
        $conn->close();
        die();
      }
      if($row['player2'] == $id)
      {
        echo $row['moves1'];
        $conn->close();
        die();
      }
    }
  }
}
$conn->close();
?>
