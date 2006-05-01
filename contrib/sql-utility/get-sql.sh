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


if [ $# -le 2 ]; then
	Help
fi

case "$1" in 
	-f)
		awk '/CREATE FUNCTION/,/LANGUAGE/ { print $0;}' |pg_dump -s "$2"
esac
