<?php
//arguments:
//pid - player id
//gid - game id
//move - move that will be played
//result:
//ok - if move is valid
require 'config.php';
// Create connection
$conn = new mysqli($servername, $username, $password);
$id = $_GET['pid'];
$game_id = $_GET['gid'];
$move = $_GET['move'];
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
if(filter_var($move,FILTER_VALIDATE_INT))
{
  if($move > 9 || $move < 1)
  {
    die();
  }
}
else
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
  $row = $result->fetch_assoc();
  //check if player is really moving
  if($row['moving'] == $id)
  {
    //now we can check has move been done already (we can't allowe double moves)
    if( strpos( $row['moves1'], strval($move) ) !== false) {
      die();
    }
    if( strpos( $row['moves2'], strval($move) ) !== false) {
      die();
    }
    //now we can move
    if($row['player1'] == $id)
    {
      $is_player_1 = true;
      $moves = $row['moves1'];
      $total = $row['total'];
      $total = $total . "+";
      $moves = $moves . " " . strval($move);
      $query = "UPDATE games SET moves1 = '" . $moves . "', total = '" . $total . "' WHERE id = '" . $game_id . "'";
    }
    else
    {
      $is_player_1 = false;
      $moves = $row['moves2'];
      $total = $row['total'];
      $total = $total . "+";
      $moves = $moves . " " . strval($move);
      $query = "UPDATE games SET moves2 = '" . $moves . "', total = '" . $total . "' WHERE id = '" . $game_id . "'";
    }
    $conn->query($query);
    //check if game has been won or sth
    $query = "SELECT * FROM games WHERE id = '" . $game_id . "'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $wonby1 = false;
    $wonby2 = false;
    if( (strpos($row['moves1'], strval(1)) !== false) && (strpos($row['moves1'], strval(2)) !== false) && (strpos($row['moves1'], strval(3)) !== false))
    {
      $wonby1 = true;
    }
    if( (strpos($row['moves1'], strval(1)) !== false) && (strpos($row['moves1'], strval(4)) !== false) && (strpos($row['moves1'], strval(7)) !== false))
    {
      $wonby1 = true;
    }
    if( (strpos($row['moves1'], strval(4)) !== false) && (strpos($row['moves1'], strval(5)) !== false) && (strpos($row['moves1'], strval(6)) !== false))
    {
      $wonby1 = true;
    }
    if( (strpos($row['moves1'], strval(2)) !== false) && (strpos($row['moves1'], strval(5)) !== false) && (strpos($row['moves1'], strval(8)) !== false))
    {
      $wonby1 = true;
    }
    if( (strpos($row['moves1'], strval(3)) !== false) && (strpos($row['moves1'], strval(6)) !== false) && (strpos($row['moves1'], strval(9)) !== false))
    {
      $wonby1 = true;
    }
    if( (strpos($row['moves1'], strval(7)) !== false) && (strpos($row['moves1'], strval(8)) !== false) && (strpos($row['moves1'], strval(9)) !== false))
    {
      $wonby1 = true;
    }
    if( (strpos($row['moves1'], strval(1)) !== false) && (strpos($row['moves1'], strval(5)) !== false) && (strpos($row['moves1'], strval(9)) !== false))
    {
      $wonby1 = true;
    }
    if( (strpos($row['moves1'], strval(3)) !== false) && (strpos($row['moves1'], strval(5)) !== false) && (strpos($row['moves1'], strval(7)) !== false))
    {
      $wonby1 = true;
    }
    //second player
    if( (strpos($row['moves2'], strval(1)) !== false) && (strpos($row['moves2'], strval(2)) !== false) && (strpos($row['moves2'], strval(3)) !== false))
    {
      $wonby2 = true;
    }
    if( (strpos($row['moves2'], strval(1)) !== false) && (strpos($row['moves2'], strval(4)) !== false) && (strpos($row['moves2'], strval(7)) !== false))
    {
      $wonby2 = true;
    }
    if( (strpos($row['moves2'], strval(4)) !== false) && (strpos($row['moves2'], strval(5)) !== false) && (strpos($row['moves2'], strval(6)) !== false))
    {
      $wonby2 = true;
    }
    if( (strpos($row['moves2'], strval(2)) !== false) && (strpos($row['moves2'], strval(5)) !== false) && (strpos($row['moves2'], strval(8)) !== false))
    {
      $wonby2 = true;
    }
    if( (strpos($row['moves2'], strval(3)) !== false) && (strpos($row['moves2'], strval(6)) !== false) && (strpos($row['moves2'], strval(9)) !== false))
    {
      $wonby2 = true;
    }
    if( (strpos($row['moves2'], strval(7)) !== false) && (strpos($row['moves2'], strval(8)) !== false) && (strpos($row['moves2'], strval(9)) !== false))
    {
      $wonby2 = true;
    }
    if( (strpos($row['moves2'], strval(1)) !== false) && (strpos($row['moves2'], strval(5)) !== false) && (strpos($row['moves2'], strval(9)) !== false))
    {
      $wonby2 = true;
    }
    if( (strpos($row['moves2'], strval(3)) !== false) && (strpos($row['moves2'], strval(5)) !== false) && (strpos($row['moves2'], strval(7)) !== false))
    {
      $wonby2 = true;
    }
    //add elo and end game
    $query_p_elo = "SELECT * FROM players WHERE id = '" . $id . "'";
    $result_p_elo = $conn->query($query_p_elo);
    $player_elo = 1000;
    $enemy_elo = 1000;
    if($result_p_elo)
    {
      $row2 = $result_p_elo->fetch_assoc();
      $player_elo = $row2['elo'];
    }
    if($id == $row['player2'])
    {
      $enemy_id = $row['player1'];
    }
    else
    {
      $enemy_id = $row['player2'];
    }
    $query_e_elo = "SELECT * FROM players WHERE id = '" . $enemy_id . "'";
    $result_e_elo = $conn->query($query_e_elo);
    if($result_e_elo)
    {
      $row2 = $result_e_elo->fetch_assoc();
      $enemy_elo = $row2['elo'];
    }
    $diff = abs($player_elo - $enemy_elo) + 400;
    if($wonby1 == false && $wonby2 == false)
    {
      //draw
      if(strlen($row['total']) >= 9)
      {
        $querydraw = "UPDATE games SET status = 'ended', winner = 'draw' WHERE id = '" . $game_id . "'";
        $res = $conn->query($querydraw);
        echo "ok";
        $conn->close();
        die();
      }
      $query = "UPDATE games SET moving = '" . $enemy_id . "' WHERE id = '" . $game_id . "'";
      $conn->query($query);
      $conn->close();
      echo "ok";
      die();
    }
    if($wonby1 == true)
    {
      if($is_player_1 == true)
      {
        //player currently moving has won the game
        //echo "$player_elo $enemy_elo $diff";
        $player_elo = $player_elo + ($diff / 10 * $enemy_elo / $player_elo);
        $enemy_elo = $enemy_elo - ($diff / 11 * $enemy_elo / $player_elo);
      }
      else
      {
        //player currently moving has lost the game
        //jecho "$player_elo $enemy_elo $diff";
        $player_elo = $player_elo - ($diff / 11 * $player_elo / $enemy_elo);
        $enemy_elo = $enemy_elo + ($diff / 10 * $player_elo / $enemy_elo);
      }
      $query = "UPDATE games SET status = 'ended', winner = '" . $row['player1'] . "' WHERE id = '" . $game_id . "'";
      $conn->query($query);
    }
    if($wonby2 == true)
    {
      if($is_player_1 == true)
      {
        //player currently moving has lost the game
        //e//cho "$player_elo $enemy_elo $diff";
        $player_elo = $player_elo - ($diff / 11 * $player_elo / $enemy_elo);
        $enemy_elo = $enemy_elo + ($diff / 10 * $player_elo / $enemy_elo);
      }
      else
      {
        //player currently moving has won the game
        //echo "$player_elo $enemy_elo $diff";
        $player_elo = $player_elo + ($diff / 10 * $enemy_elo / $player_elo);
        $enemy_elo = $enemy_elo - ($diff / 11 * $enemy_elo / $player_elo);
      }
      $query = "UPDATE games SET status = 'ended', winner = '" . $row['player2'] . "' WHERE id = '" . $game_id . "'";
      $conn->query($query);
    }
    $query = "UPDATE players SET elo = $player_elo WHERE id = '$id'";
    $conn->query($query);
    $query = "UPDATE players SET elo = $enemy_elo WHERE id = '$enemy_id'";
    $conn->query($query);
    $conn->close();
    echo "ok";
    die();
  }
}
$conn->close();
?>
