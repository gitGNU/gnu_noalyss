alter table jrnx add f_id bigint;
alter table jrnx alter f_id set default null;
update  jrnx set f_id=(select f_id from fiche join jnt_fic_att_value using(f_id) join attr_value using(jft_id)  where ad_id=23 and av_text=jrnx.j_qcode);
alter table quant_sold alter qs_internal drop not null;
alter table quant_purchase alter qp_internal drop not null;

ALTER TABLE jrnx  ADD CONSTRAINT jrnx_f_id_fkey FOREIGN KEY (f_id)      REFERENCES fiche (f_id) MATCH SIMPLE      ON UPDATE cascade ON DELETE NO ACTION;
CREATE INDEX fki_jrnx_f_id  ON jrnx  USING btree  (f_id);

