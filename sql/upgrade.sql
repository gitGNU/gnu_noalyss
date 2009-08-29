begin;
 insert into parameter(pr_id,pr_value) values ('MY_CHECK_PERIODE','Y');
 alter table jrn add jr_mt text ;
 update jrn set jr_mt=  extract (microseconds from jr_tech_date);
 create   index x_mt on jrn(jr_mt);
 DROP FUNCTION insert_quant_purchase(text, numeric, character varying, numeric, numeric,numeric, integer, numeric, numeric, numeric, character varying);
 DROP FUNCTION insert_quant_sold(text, character varying, numeric, numeric, numeric, integer, character varying);
 commit;
alter table groupe_analytique add constraint fk_pa_id foreign key(pa_id)  references plan_analytique(pa_id) on delete cascade;
alter table stock_goods add constraint fk_stock_good_f_id foreign key(f_id)  references fiche(f_id) ;

drop table invoice;
