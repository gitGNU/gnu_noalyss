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

#set -xv

version=5

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
DB=`psql -h localhost -l -U $OWNER `
if [ $? -ne 0 ]; then
	echo "problem avec Postgres" 
	exit
fi

# Repository exist ?
REPO=`echo $DB|grep account_repository|wc -l`
ON_ERROR_STOP="True"
if [ $REPO -eq 0 ]; then
	echo "Creation de la base de donnee"
	createdb -h localhost -U $OWNER $OWNER
	createdb -h localhost -E latin1 -U $OWNER account_repository

	# Create the repository
	PSQL="psql -h localhost -U $OWNER account_repository" 
	$PSQL  -f html/admin/sql/account_repository/schema.sql || exit 1
  $PSQL  -f html/admin/sql/account_repository/data.sql || exit 1
	
	#create the template for Belgian accountancy
  createdb -h localhost -E latin1 -U $OWNER mod1
	PSQL="psql -h localhost -U $OWNER mod1 "
	$PSQL  -f html/admin/sql/mod1/schema.sql || exit 1
  $PSQL  -f html/admin/sql/mod1/data.sql || exit 1

	# Create the demo database
	createdb -h localhost -E latin1 -U $OWNER dossier1
	PSQL="psql -h localhost -U $OWNER dossier1 "
  $PSQL  -f html/admin/sql/dossier1/schema.sql || exit 1
  $PSQL  -f html/admin/sql/dossier1/data.sql || exit 1
fi

echo "***************"
echo "Installation OK"
echo "***************"

