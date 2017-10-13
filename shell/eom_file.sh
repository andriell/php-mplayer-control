#!/bin/bash

killall eom
export DISPLAY=:0.0 && eom -f "$1" > /dev/null 2>&1 &