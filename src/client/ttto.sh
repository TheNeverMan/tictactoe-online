#!/bin/bash
# Tic Tac Toe Online
# TheNeverMan 2021
RETURNED_VALUE=0
VERSION=5
SERVER=tictactoeonline2674.000webhostapp.com

play()
{
  GAME_ID=$(busybox wget -qO- "$SERVER/newgame.php?pid=$PLAYER_ID")
  CAN_MOVE=no
  YOUR_MOVES=" "
  ENEMY_MOVES=" "
  busybox echo "Joined $GAME_ID game"
  busybox echo "Board:"
  busybox echo "1 2 3"
  busybox echo "4 5 6"
  busybox echo "7 8 9"
  busybox echo "Good luck!"
  while busybox true
  do
    OUT=$(busybox wget -qO- "$SERVER/isconnected.php?gid=$GAME_ID")
    if busybox [[ "$OUT" == "ok" ]]; then
      break
    fi
  done
  ENEMY_IS=$(busybox wget -qO- "$SERVER/getenemyinfo.php?pid=$PLAYER_ID&gid=$GAME_ID")
  busybox echo -e "Playing with $ENEMY_IS"
  while busybox true
  do
    echo "Waiting for your turn..."
    waitformove
    if busybox [[ "$RETURNED_VALUE" == "won" ]]; then
      echo "You have won"
      return 0
    fi
    if busybox [[ "$RETURNED_VALUE" == "draw" ]]; then
      echo "Draw"
      return 0
    fi
    if busybox [[ "$RETURNED_VALUE" == "lost" ]]; then
      echo "You have lost"
      return 0
    fi
    if busybox [[ "$RETURNED_VALUE" == "nogm" ]]; then
      echo "Missing game"
      return 0
    fi
    echo "Your turn!"
    printboard
    echo "Select tile to move (1-9):"
    MOVE=0
    ALLOWED=n
    read -r MOVE
    #reduce move to one char (no need for more)
    MOVE=$(echo "$MOVE" | busybox head -c 1)
    while busybox [[ "$ALLOWED" == n ]]
    do
      Y=$(echo "$YOUR_MOVES" | busybox grep "$MOVE")
      E=$(echo "$ENEMY_MOVES" | busybox grep "$MOVE")
    	if busybox [[ "" == "$Y" ]]; then
    		ALLOWED=y
    		if busybox [[ "" == "$E" ]]; then
    			ALLOWED=y
    		else
    			ALLOWED=n
    		fi
    	else
    		ALLOWED=n
    	fi
    	if [ "$MOVE" -gt 9 ]; then
        ALLOWED=n
      fi
      if [ "$MOVE" -lt 1 ]; then
    		ALLOWED=n
    	fi
    	if busybox [[ "$ALLOWED" == n ]]; then
    		echo "This move is not allowed, please move somewhere else:"
    		read -r MOVE
    	fi
    done
    YOUR_MOVES=$YOUR_MOVES" "$MOVE
    RESULT=$(busybox wget -qO- "$SERVER/move.php?pid=$PLAYER_ID&gid=$GAME_ID&move=$MOVE")
    RESULT_EXIT_CODE=$?
    if busybox [[ "$RESULT_EXIT_CODE" != 0 ]]; then
      busybox echo "Command Result: $RESULT_EXIT_CODE $RESULT"
      busybox echo "Something went wrong..."
      busybox echo " Please check internet connection..."
    fi
  done
}

waitformove()
{
  CAN_MOVE=no
  RETURNED_VALUE=no
  while busybox true
  do
    CAN_MOVE=$(busybox wget -qO- "$SERVER/canmove.php?pid=$PLAYER_ID&gid=$GAME_ID")
    if busybox [[ "$CAN_MOVE" == "won" ]]; then
      RETURNED_VALUE=won
      return 0
    fi
    if busybox [[ "$CAN_MOVE" == "draw" ]]; then
      RETURNED_VALUE=draw
      return 0
    fi
    if busybox [[ "$CAN_MOVE" == "lost" ]]; then
      RETURNED_VALUE=lost
      return 0
    fi
    if busybox [[ "$CAN_MOVE" == "nogm" ]]; then
      RETURNED_VALUE=nogm
      return 0
    fi
    if busybox [[ "$CAN_MOVE" == "ok" ]]; then
      RETURNED_VALUE=ok
      return 0
    fi
    busybox sleep 1
  done
}

printtile()
{
  Y=$(echo "$YOUR_MOVES" | busybox grep "$VAL")
  E=$(echo "$ENEMY_MOVES" | busybox grep "$VAL")
  if busybox [[ "" != "$Y" ]]; then
    echo -n X
    echo -n ' '
  elif busybox [[ "" != "$E" ]]; then
    echo -n O
    echo -n ' '
  else
    echo -n -
    echo -n ' '
  fi
}

printboard()
{
  ENEMY_MOVES=$(busybox wget -qO- "$SERVER/getenemymoves.php?pid=$PLAYER_ID&gid=$GAME_ID")
  VAL=1
  while [ "$VAL" -lt 10 ]
  do
    printtile
    if busybox [[ $(($VAL % 3)) == 0 ]]; then
      echo
    fi
    VAL=$((VAL+1))
  done
}

menu()
{
  RESPONSE=z
  while busybox [[ "$RESPONSE" != e ]]; do
    echo "press key to choose option:"
    echo "p - play"
    echo "i - info"
    echo "r - ranking"
    echo "a - ranks"
    echo "v - version"
    echo "e - exit"
    read -r RESPONSE
    if busybox [[ "$RESPONSE" == p ]]; then
      play
    fi
    if busybox [[ "$RESPONSE" == a ]]; then
      echo "Available Ranks:"
      busybox echo -e "\e[1;34mPremium \e[0m- you can ask me for it"
      busybox echo -e "\e[1;33mAdmin \e[0m- you can't get it"
      busybox echo -e "\e[1;32mTester\\Dev \e[0m- you need to be tester to get it"
      busybox echo -e "\e[1;31mSpecial \e[0m- this is special rank"
    fi
    if busybox [[ "$RESPONSE" == r ]]; then
      echo PLAYER ELO
      ranking_to_print=$(busybox wget -qO- "$SERVER/ranking.php")
      busybox echo -e "$ranking_to_print"
    fi
    if busybox [[ "$RESPONSE" == i ]]; then
      ELO=$(busybox wget -qO- "$SERVER/getplayerelo.php?pid=$PLAYER_ID")
      busybox echo -e "Username $USERNAME Elo $ELO"
    fi
    if busybox [[ "$RESPONSE" == v ]]; then
      echo "Tic Tac Toe Online Client version $VER_LONG"
    fi
    if busybox [[ "$RESPONSE" == e ]]; then
      echo Bye
      exit 0
    fi
  done
}

VER_LONG=$(echo -n 0x;busybox printf '%x\n' "$VERSION")
echo "Welcome to Tic Tac Toe Online (ver. $VER_LONG)"
#check if id file is present and generate one if not
PLAYER_ID=yes
if busybox [[ -e ./id.ttto ]]; then
  PLAYER_ID=$(busybox cat ./id.ttto)
else
  PLAYER_ID=$(busybox printf $(busybox date | busybox head -n2 | busybox sha256sum))
  busybox touch ./id.ttto
  echo -n "$PLAYER_ID" >> ./id.ttto
fi

#check connection
P=$(busybox wget -qO- "$SERVER")
if busybox [[ $? != 0 ]]; then
  echo "Server is inaccessible, please check your internet connection and try again"
  read -r A
  exit 0
  echo "$A"
fi

#show server
busybox echo "$SERVER is selected server"

#check ver
MIN_SUP_VER=$(busybox wget -qO- "$SERVER/minsupver.php")
LONG_MIN_SUP_VER=$(busybox echo -n 0x;busybox printf '%x\n' "$MIN_SUP_VER")
if [ "$VERSION" -lt "$MIN_SUP_VER" ]; then
  echo "Client is outdated, please update to version $LONG_MIN_SUP_VER"
  read -r A
  exit 0
fi
#get username and register if not present
USERNAME=yes
IS_USERNAME_REG=$(busybox wget -qO- "$SERVER/getplayer.php?pid=$PLAYER_ID")

if busybox [[ -z "$IS_USERNAME_REG" ]]; then
  echo "No account has been found on server. Please enter username to register (username must not contains spaces or special characters, also it must be less than 32 chars long):"
  read -r USERNAME
  USERNAME=$(busybox echo "$USERNAME" | busybox awk '{ gsub (" ","", $0); print}')
  USERNAME=$(busybox echo "$USERNAME" | busybox head -c 32)
  busybox wget -qO- "$SERVER/addplayer.php?pid=$PLAYER_ID&un=$USERNAME"
else
  USERNAME=$IS_USERNAME_REG
  busybox echo -e "Logged as $USERNAME"
fi
#messages
echo "Messages:"
busybox wget -qO- "$SERVER/getmessages.php"
menu
