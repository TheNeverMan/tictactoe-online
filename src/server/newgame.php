<?php
//arguments:
//pid - player id
//result:
//game id that player can connect to
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
//check if player is in other games
$query = "SELECT * FROM games WHERE player1 = '" . $id . "'";
$result = $conn->query($query);
if($result)
{
  //update all games he is first player to win of second player
  while($row = $result->fetch_assoc()) {
    $query = "UPDATE games SET status = 'ended', winner = '" . $row['player2'] . "' WHERE id = '" . $row['id'] . "'";
    $conn->query($query);
  }
}
$query = "SELECT * FROM games WHERE player2 = '" . $id . "'";
$result = $conn->query($query);
if($result)
{
  //update all games he is second player to win of first player
  while($row = $result->fetch_assoc()) {
    $query = "UPDATE games SET status = 'ended', winner = '" . $row['player1'] . "' WHERE id = '" . $row['id'] . "'";
    $conn->query($query);
  }
}

//now check games where status is waiting
$query = "SELECT * FROM games WHERE status = 'wait'";
$result = $conn->query($query);
if($result)
{
  //now we can join them
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $player1 = $row['player1'];
  $player2 = $id;
  $gid = $row['id'];
  //choose first player
  if(rand(0,100) > 50)
  {
    $first = $player1;
  }
  else {
    $first = $player2;
  }
  $moving = $first;
  $query = "UPDATE games SET player2 = '" . $player2 . "', first = '" . $first . "', moving = '" . $moving . "', status = 'play' WHERE id = '" . $gid . "'";
  $result = $conn->query($query);
  $conn->close();
  echo "$gid";
  die();
}
else
{
  //create new game
  $gid = hash('sha256', rand() * rand());
  $query = "INSERT INTO games (id, player1, player2, first, moving, moves1, moves2, total, winner, status) VALUES ('" . $gid . "', '" . $id . "', ' ', ' ', ' ', ' ', ' ', ' ', ' ', 'wait' )";
  $conn->query($query);
  $conn->close();
  echo "$gid";
  die();
}
}

$conn->close();
?>
