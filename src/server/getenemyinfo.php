<?php
//arguments:
//pid - player id
//gid - game id
//result:
//formatted string containing enemy username and enemy elo
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
        $query2 = "SELECT * FROM players WHERE id = '" . $row['player2'] . "'";
        $result2 = $conn->query($query2);
        $row2 = $result2->fetch_assoc();
        if($result2)
        {
          echo $row2['username'] . " (ELO " . $row2['elo'] . ")";
        }
        $conn->close();
        die();
      }
      if($row['player2'] == $id)
      {
        $query2 = "SELECT * FROM players WHERE id = '" . $row['player1'] . "'";
        $result2 = $conn->query($query2);
        $row2 = $result2->fetch_assoc();
        if($result2)
        {
          echo $row2['username'] . " (ELO " . $row2['elo'] . ")";
        }
        $conn->close();
        die();
      }
    }
  }
}
$conn->close();
?>
