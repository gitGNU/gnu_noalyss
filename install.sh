#!/bin/bash 
#
#
# $Revision$
######################################################################
#  This file is part of PhpCompta.
#  PhpCompta is free software; you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation; either version 2 of the License, or
#  (at your option) any later version.
#  PhpCompta is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#  You should have received a copy of the GNU General Public License
#  along with PhpCompta; if not, write to the Free Software
#  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#
. ./setenv.sh

echo "The file install.log will be created with 
      the log of the installation, just check if
      it finished with the message
      installation ok"
# Output the log to install log
exec 3>install.log 2>&3 1>&3
#set -xv

version=4
VerifOutil() {
	RESULT="Verif. $1"
	A=`type $1 2> /dev/null` 
	if [ $? -ne 0 ]; then
		echo $RESULT"...Failed"
		exit
	else
		echo "$RESULT...Success"
	fi
}

#Verification des outil
echo "Verification des outil"
echo "----------------------"
VerifOutil "psql"
VerifOutil "createdb"
# Base de données
DB=`psql -l -U $OWNER `
if [ $? -ne 0 ]; then
	echo "problem avec Postgres" 
	exit
fi

# Repository exist ?
REPO=`echo $DB|grep account_repository|wc -l`
if [ $REPO -eq 0 ]; then
	echo "Creation de la base de donnee"
	createdb -U $OWNER $OWNER
	createdb -E latin1 -U $OWNER account_repository

	# Create the repository
	PSQL="psql  -U $OWNER account_repository" 
	$PSQL -e -f sql/account_repository.sql
	
	#create the template for Belgian accountancy
        createdb -E latin1 -U $OWNER mod1
	PSQL="psql -U $OWNER mod1 "
	$PSQL -e -f sql/mod-be.sql

	# Create the demo database
	createdb -E latin1 -U $OWNER dossier1
	PSQL="psql -U $OWNER dossier1 "
	$PSQL -e -f sql/demo.sql
#
 
#  	if [ -w $PG_DATA/pg_hba.conf ]; then
#  		echo "host    account_repository         all         127.0.0.1         255.255.255.255   password" >> $PG_DATA/pg_hba.conf
#  		else
#  		echo "Error PG_DATA is not correct"
#  		exit 2
#  	fi

fi
# Test si la version de la db doit être mise à jour
A=`psql -U phpcompta  -t -c 'select val from version;' account_repository `

OWNER=phpcompta

DB=`psql -l -U phpcompta -t|awk '/mod/ {print $1} /dossier/ {print $1;}'`
for i in `echo $DB`
do
	echo $i
	while [ 1 ]; do
	vers_db=`psql -U phpcompta  -t -c 'select val from version;' $i `
	if [ "$version" -ne $vers_db ] ; then
		ver_file="sql/update/"`echo $i |sed 's/[0-9]*//g'`$vers_db".sql"
		ver_file=`echo $ver_file|tr ' ' '_'`
		echo "Applying patch for $i $ver_file"
		psql -U $OWNER  $i -f $ver_file
	else 
		echo "ok"
		break
	fi
	done

done
# Installation des sources
[ ! -d $COMPTA_HOME ] && mkdir $COMPTA_HOME
[ ! -d $COMPTA_HOME/html ] && mkdir $COMPTA_HOME/html
[ ! -d $COMPTA_HOME/html/image ] && mkdir $COMPTA_HOME/html/image
[ ! -d $COMPTA_HOME/include ] && mkdir $COMPTA_HOME/include
echo "Installing source"
cp -fr include/*.php  $COMPTA_HOME/include
cp -fr html/*.html  $COMPTA_HOME/html
for i in include/*.php ;do
    A=`basename $i`
   sed "s/echo_debug\S*(\"/echo_debug(\"include\/$A:\".__LINE__.'--'.\"/g" $i > $COMPTA_HOME/include/`basename $i`
done
for i in html/*.php ;do
    A=`basename $i`
    sed "s/echo_debug.*(\"/echo_debug(\"html\/$A:\".__LINE__.'--'.\"/g" $i > $COMPTA_HOME/html/`basename $i`
done
cp -f style*.css $COMPTA_HOME/html
cp -f html/*.js $COMPTA_HOME/html
cp -fR addon $COMPTA_HOME/html
cp -fR html/image/* $COMPTA_HOME/html/image
echo "Installation OK"
exec 3<&-

