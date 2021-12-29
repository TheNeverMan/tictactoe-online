#!/bin/bash
# Tic Tac Toe Online
# TheNeverMan 2021
RETURNED_VALUE=0

play()
{
  GAME_ID=$(wget -qO- $SERVER/newgame.php?pid=$PLAYER_ID)
  CAN_MOVE=no
  YOUR_MOVES=" "
  ENEMY_MOVES=" "
  echo Joined $GAME_ID game
  while true; do
    OUT=$(wget -qO- $SERVER/isconnected.php?gid=$GAME_ID)
    if [[ $OUT == "ok" ]]; then
      break
    fi
  done
  ENEMY_IS=$(wget -qO- "$SERVER/getenemyinfo.php?pid=$PLAYER_ID&gid=$GAME_ID")
  echo "Playing with $ENEMY_IS"
  while true
  do
    echo "Waiting for your turn..."
    waitformove
    if [[ $RETURNED_VALUE == won ]]; then
      echo "You have won"
      return 0
    fi
    if [[ $RETURNED_VALUE == draw ]]; then
      echo "Draw"
      return 0
    fi
    if [[ $RETURNED_VALUE == lost ]]; then
      echo "You have lost"
      return 0
    fi
    if [[ $RETURNED_VALUE == nogm ]]; then
      echo "Missing game"
      return 0
    fi
    echo "Your turn!"
    printboard
    echo "Select tile to move (1-9):"
    MOVE=0
    ALLOWED=n
    read MOVE
    while [[ $ALLOWED == n ]]
    do
    	if [[ "" == $(echo $YOUR_MOVES | grep $MOVE) ]]; then
    		ALLOWED=y
    		if [[ "" == $(echo $ENEMY_MOVES | grep $MOVE) ]]; then
    			ALLOWED=y
    		else
    			ALLOWED=n
    		fi
    	else
    		ALLOWED=n
    	fi
    	if (( $MOVE > 9  || $MOVE < 1)); then
    		ALLOWED=n
    	fi
    	if [[ $ALLOWED == n ]]; then
    		echo "This move is not allowed, please move somewhere else:"
    		read MOVE
    	fi
    done
    YOUR_MOVES+=$(echo $MOVE)
    YOUR_MOVES+=" "
    RESULT=$(wget -qO- "$SERVER/move.php?pid=$PLAYER_ID&gid=$GAME_ID&move=$MOVE")
  done
}

waitformove()
{
  CAN_MOVE=no
  RETURNED_VALUE=no
  while true
  do
    CAN_MOVE=$(wget -qO- "$SERVER/canmove.php?pid=$PLAYER_ID&gid=$GAME_ID")
    if [[ $CAN_MOVE == won ]]; then
      RETURNED_VALUE=won
      return 0
    fi
    if [[ $CAN_MOVE == draw ]]; then
      RETURNED_VALUE=draw
      return 0
    fi
    if [[ $CAN_MOVE == lost ]]; then
      RETURNED_VALUE=lost
      return 0
    fi
    if [[ $CAN_MOVE == nogm ]]; then
      RETURNED_VALUE=nogm
      return 0
    fi
    if [[ $CAN_MOVE == ok ]]; then
      RETURNED_VALUE=ok
      return 0
    fi
    sleep 1
  done
}

printtile()
{
  if [[ "" != $(echo $YOUR_MOVES | grep $VAL) ]]; then
    echo -n X
    echo -n ' '
  elif [[ "" != $(echo $ENEMY_MOVES | grep $VAL) ]]; then
    echo -n O
    echo -n ' '
  else
    echo -n -
    echo -n ' '
  fi
}

printboard()
{
  ENEMY_MOVES=$(wget -qO- "$SERVER/getenemymoves.php?pid=$PLAYER_ID&gid=$GAME_ID")
  VAL=1
  while (($VAL < 10))
  do
    printtile
    if (($VAL % 3 == 0)); then
      echo
    fi
    ((VAL++))
  done
}

menu()
{
  RESPONSE=z
  while [[ $RESPONSE != e ]]; do
    echo "press key to choose option:"
    echo "p - play"
    echo "i - info"
    echo "r - ranking"
    echo "e - exit"
    read RESPONSE
    if [[ $RESPONSE == p ]]; then
      play
    fi
    if [[ $RESPONSE == r ]]; then
      echo PLAYER ELO
      wget -qO- $SERVER/ranking.php
    fi
    if [[ $RESPONSE == i ]]; then
      ELO=$(wget -qO- $SERVER/getplayerelo.php?pid=$PLAYER_ID)
      echo Username $USERNAME Elo $ELO
    fi
    if [[ $RESPONSE == e ]]; then
      echo Bye
      exit 0
    fi
  done
}
VERSION=1
VER_LONG=$(echo -n 0x;printf '%x\n' $VERSION)
echo "Welcome to Tic Tac Toe Online (ver. $VER_LONG)"
#check if id file is present and generate one if not
PLAYER_ID=yes
if [[ -e ./id.ttto ]]; then
  PLAYER_ID=$(cat ./id.ttto)
else
  PLAYER_ID=$(printf $(cat /dev/random | head -n2 | sha256sum))
  touch ./id.ttto
  echo -n $PLAYER_ID >> ./id.ttto
fi
#get server ip
SERVER=tictactoeonline2674.000webhostapp.com
#check connection
P=$(wget -qO- $SERVER)
if [[ $? != 0 ]]; then
  echo "Server is inaccessible, please check your internet connection and try again"
  exit 0
fi
#check ver
MIN_SUP_VER=$(wget -qO- $SERVER/minsupver.php)
LONG_MIN_SUP_VER=$(echo -n 0x;printf '%x\n' $MIN_SUP_VER)
if (( $VERSION < $MIN_SUP_VER )); then
  echo "Client is outdated, please update to version $LONG_MIN_SUP_VER"
  exit 0
fi
#get username and register if not present
USERNAME=yes
IS_USERNAME_REG=$(wget -qO- $SERVER/getplayer.php?pid=$PLAYER_ID)

if [[ -z $IS_USERNAME_REG ]]; then
  echo "No account has been found on server. Please enter username to register:"
  read USERNAME
  wget -qO- "$SERVER/addplayer.php?pid=$PLAYER_ID&un=$USERNAME"
else
  USERNAME=$(echo $IS_USERNAME_REG)
  echo Logged as $USERNAME
fi
menu
