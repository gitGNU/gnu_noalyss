createdb -h localhost -U phpcompta dossier3
psql -h localhost -U phpcompta dossier3 -f phpcompta_11022005.sql
psql -h localhost -U phpcompta --command "INSERT INTO ac_users (use_login, use_active, use_pass, use_admin, use_usertype) VALUES ('stan', 1, md5('plogastel'), 1, 'user')" account_repository
psql -h localhost -U phpcompta --command "INSERT INTO ac_dossier (dos_id, dos_name, dos_description) VALUES (3, 'Sauvages', 'Comptabilité Sauvages SPRL')" account_repository
