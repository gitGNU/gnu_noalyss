NR_DOSSIER=$1
BACKUP_FILE=$2
createdb -h localhost -U phpcompta dossier$NR_DOSSIER
psql -h localhost -U phpcompta dossier$NR_DOSSIER -f $BACKUP_FILE
psql -h localhost -U phpcompta --command "INSERT INTO ac_users (use_login, use_active, use_pass, use_admin, use_usertype) VALUES ('stan', 1, md5('plogastel'), 1, 'user')" account_repository
psql -h localhost -U phpcompta --command "INSERT INTO ac_dossier (dos_id, dos_name, dos_description) VALUES ($NR_DOSSIER, 'Sauvages', 'Comptabilité Sauvages SPRL')" account_repository
