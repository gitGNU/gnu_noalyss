doxygen
cd html; sed -i "s/utf-8/utf-8/g" *
if [ ! -z "$PGUSER" ] ; then
	postgresql_autodoc -u $PGUSER --password $PGPASSWORD -h localhost -d mod1
	postgresql_autodoc  -u $PGUSER --password $PGPASSWORD  -h localhost -d account_repository
fi
cd ../../../ && dev/compose_list.sh
