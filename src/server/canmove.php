<?php
//arguments:
//pid - player id
//gid - game id
//result:
//ok - player can move
//won - player has won game
//lost - player has lost game
//draw - the game is draw
//nogm - game does not exist
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
      if($row['status'] == "ended")
      {
        if($row['winner'] == $id)
        {
          echo "won";
          $conn->close();
          die();
        }
        if(strlen($row['winner']) == 64)
        {
          echo "lost";
          $conn->close();
          die();
        }
        else
        {
          echo "draw";
          $conn->close();
          die();
        }
      }
      else
      {
        if($row['moving'] == $id)
        {
          echo "ok";
          $conn->close();
          die();
        }
        die();
      }
    }
  }
  else
  {
    echo "nogm";
    $conn->close();
    die();
  }
}
$conn->close();
?>
