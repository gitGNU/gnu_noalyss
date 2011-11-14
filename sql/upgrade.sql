\set ECHO all
\set ON_ERROR_ROLLBACK
\set ON_ERROR_STOP
begin;
drop table public.import_tmp;
drop table public.format_csv_banque;
insert into parameter values ('MY_ALPHANUM','N');
delete from action where ac_id not in (800,805,910);

/*
script SQL to run
*/
\i account_alphanum.sql
\i account_compute.sql
\i account_insert.sql
\i account-update.sql
-- \i change-pcmn-to-alphanum.sql
\i format_account.sql
\i tmp_pcmn_alphanum_ins_upd.sql
\i tmp_pcmn_ins.sql
\i trigger.tmp_pcmn.sql
\i account_add.sql
\i object-6.0.sql
\i extension.sql
\i ajax-direct-form.sql
-- for account repository
-- \i change_account_repo.sql
-- \i style-epad.sql

create unique index qcode_idx on fiche_detail (ad_value) where ad_id=23;
commit;
