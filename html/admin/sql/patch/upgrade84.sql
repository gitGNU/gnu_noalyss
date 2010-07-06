begin;
ALTER TABLE quant_purchase
  ADD CONSTRAINT quant_purchase_qp_internal_fkey FOREIGN KEY (qp_internal)
      REFERENCES jrn (jr_internal) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE quant_sold
  ADD CONSTRAINT quant_sold_qs_internal_fkey FOREIGN KEY (qs_internal)
      REFERENCES jrn (jr_internal) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE stock_goods
  ADD CONSTRAINT stock_goods_j_id_fkey FOREIGN KEY (j_id)
      REFERENCES jrnx (j_id) MATCH SIMPLE
      ON UPDATE cascade ON DELETE cascade;
ALTER TABLE jrn_rapt
  ADD CONSTRAINT jrn_rapt_jr_id_fkey FOREIGN KEY (jr_id)
      REFERENCES jrn (jr_id) MATCH SIMPLE
      ON UPDATE cascade ON DELETE cascade;
ALTER TABLE jrn_rapt
  ADD CONSTRAINT jrn_rapt_jra_concerned_fkey FOREIGN KEY (jra_concerned)
      REFERENCES jrn (jr_id) MATCH SIMPLE    
      ON UPDATE cascade ON DELETE cascade;

ALTER TABLE attr_def ADD COLUMN ad_type text;
alter table quant_sold alter qs_internal drop not null;
alter table quant_purchase alter qp_internal drop not null;

update attr_def set ad_type='text';
update attr_def set ad_type='numeric' where ad_id in (6,7,8,11,21,22);
update attr_def set ad_type='date' where ad_id in (10);
alter sequence s_attr_def restart with 9001;
update version set val=85;
commit;
