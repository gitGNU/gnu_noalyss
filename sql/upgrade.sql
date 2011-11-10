drop table public.import_tmp;
drop table public.format_csv_banque;
insert into parametre values ('MY_ALPHANUM','N');

/*
script SQL to run
account_alphanum.sql
account_compute.sql
account_insert.sql
account-update.sql
change-pcmn-to-alphanum.sql
format_account.sql
tmp_pcmn_alphanum_ins_upd.sql
tmp_pcmn_ins.sql
trigger.tmp_pcmn.sql
account_add.sql
object-6.0.sql
-- for account repository
epad-style.sql
extension.sql
-- for account repository
change_account_repo.sql
*/
create unique index qcode_idx on fiche_detail (ad_value) where ad_id=23;
