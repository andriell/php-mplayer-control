#!/bin/bash

killall mplayer
rm -rf "$3"
rm -rf "$2"
mkfifo "$2" -m 0644
export DISPLAY=:0.0 && mplayer -utf8 -quiet -fs -slave -input file="$2" "$1" > "$3" 2> /dev/null &
chmod 0644 "$3"
