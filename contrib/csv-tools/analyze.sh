#!/bin/bash
# Utility for analysing CSV files
#
# GNU Gpl Author: Dany De Bontridder
#########################################################################
Help () {
	echo "Usage is $0 start end  "
	echo "or"
	echo "Usage is $0 line  "
}

Help

FILE=argenta.csv

if [ $# -eq 2 ]; then
	START=$1
	END=$2
 	sed -ne "${START},${END}p" $FILE |
awk 'BEGIN {FS=";"}  { for ( i=1; i<NF;i++) { print "field nb"i"->"$i;}}'
fi
if [ $# -eq 1 ]; then
	START=$1
 	sed -ne "${START}p" $FILE |
awk 'BEGIN {FS=";"}  { for ( i=1; i<NF;i++) { print "field nb"i"->"$i;}}'
fi
