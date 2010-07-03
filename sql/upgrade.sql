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

commit;
