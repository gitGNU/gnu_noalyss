begin;
 insert into parameter(pr_id,pr_value) values ('MY_CHECK_PERIODE','Y');
 alter table quant_sold drop qs_valid;
 alter table jrn add jr_mt text ;
 update jrn set jr_mt=  extract (microseconds from jr_tech_date);
 create   index x_mt on jrn(jr_mt);
 DROP FUNCTION insert_quant_purchase(text, numeric, character varying, numeric, numeric,numeric, integer, numeric, numeric, numeric, character varying);
 DROP FUNCTION insert_quant_sold(text, character varying, numeric, numeric, numeric, integer, character varying);
 commit;
