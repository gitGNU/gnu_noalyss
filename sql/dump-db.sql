A="pg_dump -d -U phpcompta "
B=`date '+%Y%m%d-%H%M%S'`
cp account_repository.sql $B-account_repository.sql
cp demo.sql $B-demo.sql
cp mod-be.sql $B-mod-be.sql

$A account_repository > account_repository.sql
$A dossier1 > demo.sql
$A mod1 > mod-be.sql

