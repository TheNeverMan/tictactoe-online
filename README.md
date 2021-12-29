# tictactoe-online
Tictactoe Online (known as ttto) is small, multiplatform and simple game that can be played with friends over internet.
# How to play
First you need to download the game. Navigate to Releases tab and select newewst version for your operating system (Windows or Linux). Linux version requires wget, coreutils and bash to be installed. Windows version comes with busybox.
## Launch game
### Linux
Navigate to directory where you have downloaded game, unpack it and give execution permission to script:
```
chmod +x ttto.sh
```
Then launch game with:
```
./ttto.sh
```
Game will create file id.ttto in this directory. DO NOT DELETE, RENAME OR MODIFY THIS FILE OR YOUR GAME PROGRESS WILL BE LOST.
### Windows
Navigate to directory where you have downloaded the game and unpack the archive. Then double-click start_sh.bat file that got unpacked. Do not delete other files that got unpacked, without them game is not going to work. Game will create file id.ttto in this directory. DO NOT DELETE, RENAME OR MODIFY THIS FILE OR YOUR GAME PROGRESS WILL BE LOST.
## Playing
After first game launch game will attempt to connect with server and ask you for your username. Username must not contain spaces, special characters and be more than 32 chars long. Now enter it and press enter. Then the menu will show up.
### Menu
Menu looks like this:
```
Logged as (your username here)
press key to choose option:
p - play
i - info
r - ranking
e - exit
```
to choose an option you need to press key and press enter after it.
### Options description
* Ranking - if you select ranking option, you will see all players ranked by their elo.
* Info - Info option will print your username and elo.
* Exit - this will exit the game.
* Play - this option makes you join game.
### Gameplay
After you select "p" option, you will see this text:
```
Joined (game id here) game
```
This means that new game has been created and you can wait till someone joins.
When second player joins game, the game starts. Information about your enemy gets displayed:
```
Playing with (your enemy username) (ELO (his/her elo))
Waiting for your turn...
```
Game randomly selects starting player. If it is you board with current moves gets displayed:
```
Your turn!
- - - 
- - - 
- - - 
Select tile to move (1-9):
```
Now you can choose tile to move. Your moves are displayed as X and enemy moves are O. Tiles are numbered from 1 to 9, 1 is topleft corner and 9 is downright corner:
```
1 2 3
4 5 6
7 8 9
```
If you want to move to topleft corner select 1:
```
Your turn!
- - - 
- - - 
- - - 
Select tile to move (1-9):
1
Waiting for your turn...
```
after your enemy move, board may look like this:
```
Your turn!
- - - 
- - - 
- - - 
Select tile to move (1-9):
1
Waiting for your turn...
Your turn!
X - - 
- O - 
- - - 
Select tile to move (1-9):
```
Game will end when one player wins or when all 9 tiles are filled.
