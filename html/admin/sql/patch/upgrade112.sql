begin;

ALTER TABLE operation_analytique ADD COLUMN oa_jrnx_id_source bigint;
COMMENT ON COLUMN operation_analytique.oa_jrnx_id_source IS 'jrnx.j_id source of this amount, this amount is computed from an amount giving a ND VAT.Normally NULL  is there is no ND VAT.';

ALTER TABLE operation_analytique ALTER COLUMN oa_signed SET DEFAULT 'Y'::bpchar;
COMMENT ON COLUMN operation_analytique.oa_signed IS 'Sign of the amount';

update version set val=113;

commit;