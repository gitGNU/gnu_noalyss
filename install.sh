#!/bin/bash
#
#
# $Revision$
######################################################################
#  This file is part of WCOMPTA.
#  WCOMPTA is free software; you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation; either version 2 of the License, or
#  (at your option) any later version.
#  WCOMPTA is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#  You should have received a copy of the GNU General Public License
#  along with WCOMPTA; if not, write to the Free Software
#  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#

export PATH=/opt/psql732/bin:$PATH
export PGDATA=/opt/database

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
	OWNER=webcompta
	echo "Creation de la base de donnee"
	createuser -A -d $OWNER
	createdb -E latin1 -O $OWNER account_repository

	PSQL="psql  -U $OWNER account_repository" 
#	$PSQL < sql/create_repository.sql
#	$PSQL < sql/ac_pcmn.sql
	$PSQL < sql/ac_users.sql
	$PSQL < sql/ac_dossier.sql
	$PSQL < sql/jnt_use_dos.sql
	$PSQL < sql/version.sql
	$PSQL < sql/priv_user.sql
	    createdb -E latin1 -O $OWNER templ_account
	PSQL="psql -U $OWNER templ_account "
	$PSQL < sql/tmp_pcmn.sql
	$PSQL < sql/insert_pcmn.sql
	$PSQL < sql/version.sql
	$PSQL < sql/dos_pref.sql
	$PSQL < sql/journal.sql
	$PSQL < sql/user_pref.sql
	$PSQL < sql/form.sql
	$PSQL < sql/fiche.sql
	$PSQL < sql/centralized.sql
	$PSQL < sql/theme.sql
	$PSQL < sql/user_sec.sql
 
#  	if [ -w $PG_DATA/pg_hba.conf ]; then
#  		echo "host    account_repository         all         127.0.0.1         255.255.255.255   password" >> $PG_DATA/pg_hba.conf
#  		else
#  		echo "Error PG_DATA is not correct"
#  		exit 2
#  	fi

fi

# Installation des sources
export COMPTA_HOME=/home/httpd/compta
[ ! -d $COMPTA_HOME/html ] && mkdir $COMPTA_HOME/html
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
cp -f style.css $COMPTA_HOME/html
cp -f html/*.js $COMPTA_HOME/html
cp -fR addon $COMPTA_HOME/html
