NR_DOSSIER=$1
BACKUP_FILE=$2
USER_NAME=$3
dropdb -h localhost -U phpcompta dossier$NR_DOSSIER
createdb -h localhost -U phpcompta dossier$NR_DOSSIER
psql -h localhost -U phpcompta dossier$NR_DOSSIER -f $BACKUP_FILE
psql -h localhost -U phpcompta -f html/admin/sql/patch/upgrade4.sql dossier$NR_DOSSIER
psql -h localhost -U phpcompta --command "INSERT INTO ac_users (use_login, use_active, use_pass, use_admin, use_usertype) VALUES ('$USER_NAME', 1, md5('plogastel'), 1, 'user')" account_repository
psql -h localhost -U phpcompta --command "INSERT INTO ac_dossier (dos_id, dos_name, dos_description) VALUES ($NR_DOSSIER, 'Sauvages', 'Comptabilité Sauvages SPRL')" account_repository
psql -h localhost -U phpcompta --command "INSERT INTO jnt_use_dos (use_id, dos_id) SELECT use_id,$NR_DOSSIER from ac_users WHERE use_login='$USER_NAME'" account_repository
psql -h localhost -U phpcompta -f html/admin/sql/patch/upgrade4.sql dossier$NR_DOSSIER
