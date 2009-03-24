begin;
 insert into parameter(pr_id,pr_value) values ('MY_CHECK_PERIODE','Y');
alter table quant_sold drop qs_valid;
 commit;
