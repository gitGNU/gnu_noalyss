#!/bin/bash
# Utility for analysing CSV files
#
# GNU Gpl Author: Dany De Bontridder
#########################################################################
Help () {
	echo "Usage is $0 start end < file "
	echo "or"
	echo "Usage is $0 line < file "
}

if [ $# -eq 2 ]; then
	START=$1
	END=$2
 	sed -ne "${START},${END}p" ACI20-janv.-06.csv |
awk 'BEGIN {FS=";"}  { for ( i=1; i<NF;i++) { print "field nb"i"->"$i;}}'
fi
if [ $# -eq 1 ]; then
 	sed -ne "${START}p" ACI20-janv.-06.csv |
awk 'BEGIN {FS=";"}  { for ( i=1; i<NF;i++) { print "field nb"i"->"$i;}}'
fi