#!/bin/bash

killall eom
export DISPLAY=:0.0 && eom -fs "$1" > /dev/null 2>&1 &