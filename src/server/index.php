<?php
require 'config.php';
// Create connection
$conn = new mysqli($servername, $username, $password);
$query = "USE id18164401_ttto";
$result = $conn->query($query);
$query = "SELECT * FROM players";
$result = $conn->query($query);
$player_count = $result->num_rows;
$query = "SELECT * FROM games WHERE status = 'play'";
$result = $conn->query($query);
$game_count = $result->num_rows;
?>
<html>
<head>
  <title> TicTacToe Online </title>
</head>
<body bgcolor = "grey">
  <center>
    <h1> TicTacToe Online </h1>
    This is TicTacToe Online (ttto) backend server made by TheNeverMan. <br>
    If you want to play the game, use link down below: <br>
    <a href="https://github.com/TheNeverMan/tictactoe-online"> Github page of the project </a>
    <br>
    We have currently <?php echo $player_count ?> players registered and <?php echo $game_count ?> games are being played right now. <br>
    Current player ranking by elo: <br>
    <?php
    $query = "SELECT username, elo FROM players ORDER BY elo DESC";
    $result = $conn->query($query);
    $index = 1;
    while($row = $result->fetch_assoc()) {
        echo "$index. ";
        echo $row['username'] . " " . $row['elo'];
        echo "<br>";
        $index++;
    }
    $conn->close();
    ?>
  </center>
</body>
</html>
