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

export PATH=/opt/psql732/bin:$PATH
export PGDATA=/opt/database
version=3
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
DB=`psql -l`
if [ $? -ne 0 ]; then
	echo "problem avec Postgres" 
	exit
fi

# Repository exist ?
REPO=`echo $DB|grep account_repository|wc -l`
if [ $REPO -eq 0 ]; then
	OWNER=phpcompta
	echo "Creation de la base de donnee"
	createuser -A -d $OWNER
	createdb -U $OWNER $OWNER
	createdb -E latin1 -U $OWNER account_repository

	PSQL="psql  -U $OWNER account_repository" 
#	$PSQL < sql/create_repository.sql
#	$PSQL < sql/ac_pcmn.sql
	$PSQL < sql/ac_users.sql
	$PSQL < sql/ac_dossier.sql
	$PSQL < sql/jnt_use_dos.sql
	$PSQL < sql/version.sql
	$PSQL < sql/priv_user.sql
	$PSQL < sql/theme.sql
	$PSQL < sql/modele.sql
	    createdb -E latin1 -U $OWNER mod1
	PSQL="psql -U $OWNER mod1 "
	$PSQL < sql/tmp_pcmn.sql
	$PSQL < sql/insert_pcmn.sql
	$PSQL < sql/version.sql
	$PSQL < sql/dos_pref.sql
	$PSQL < sql/journal.sql
	$PSQL < sql/user_pref.sql
	$PSQL < sql/form.sql
	$PSQL < sql/fiche.sql
	$PSQL < sql/centralized.sql
	$PSQL < sql/user_sec.sql
	$PSQL < sql/jrn_action.sql
 
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

DB=`psql -l -t|awk '/mod/ {print $1} /dossier/ {print $1;}'`
for i in `echo $DB`
do
	echo $i
	while [ 1 ]; do
	vers_db=`psql -U phpcompta  -t -c 'select val from version;' $i `
	if [ $version -ne $vers_db ] ; then
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
export COMPTA_HOME=/home/httpd/compta
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
