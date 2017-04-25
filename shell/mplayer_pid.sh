#!/bin/bash

if pgrep -x "mplayer" > /dev/null
then
    echo "Running"
else
    echo "Stopped"
fi
