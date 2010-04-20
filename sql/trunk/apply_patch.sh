export PGPASSWORD=dany
export PGUSER=trunk

 for i in 01_create_schema.sql 02_alter_function.sql  03_change_pcm_to_alphanum.sql  04_function_to_change.sql  05_account_add.sql  05_account_compute.sql  05_account_insert.sql  05_account_parent.sql  05_card_class_base.sql  05_fiche_account_parent.sql  05_find_pcmn_type.sql ; do 
	echo "execute $i"
	# psql -U trunk trunk_testdossier13 -f $i  
	cat $i >> upgrade72.sql
done 2>&1 |tee apply_log
