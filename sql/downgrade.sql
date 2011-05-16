alter table operation_analytique add column pa_id bigint;
ALTER TABLE operation_analytique  DROP CONSTRAINT operation_analytique_oa_amount_check;
drop type anc_table_card_type cascade;
drop type anc_table_account_type cascade;
