begin;
ALTER TABLE quant_purchase
  ADD CONSTRAINT quant_purchase_qp_internal_fkey FOREIGN KEY (qp_internal)
      REFERENCES jrn (jr_internal) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE quant_sold
  ADD CONSTRAINT quant_sold_qs_internal_fkey FOREIGN KEY (qs_internal)
      REFERENCES jrn (jr_internal) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

commit;
