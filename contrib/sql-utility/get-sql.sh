#!/bin/bash
#
#
# Author Dany De Bontridder
# Under the GPL 2 minimun
#
Help () {
	cat <<_eof
$0 [option] database
	-f function only
_eof
}


if [ $# -lt 2 ]; then
	Help
fi

case "$1" in 
	-f)
		pg_dump -s "$2"|awk '/CREATE FUNCTION/,/LANGUAGE/ { print $0;}' 
esac
