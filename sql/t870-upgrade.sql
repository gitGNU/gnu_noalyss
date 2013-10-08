-- View: syndicat.vw_historic

DROP VIEW syndicat.vw_historic;

CREATE OR REPLACE VIEW syndicat.vw_historic AS 
         SELECT operation.o_id AS id, operation.o_date AS xdate, operation.o_label AS label, ( SELECT sum(operation_detail.od_amount) AS sum
                   FROM syndicat.operation_detail
                  WHERE operation_detail.o_id = operation.o_id) AS sum_operation, 'O'::text AS type_table, operation.sect_id, operation.jrn_def_id, operation.o_id, operation.o_receipt, 
                CASE
                    WHEN operation.o_third = 0 THEN operation.o_benef
                    ELSE operation.o_third
                END AS tiers, n.name, n.first_name, 
                CASE
                    WHEN operation.o_type = 'N'::bpchar THEN 'NOTE'::text
                    WHEN operation.o_type = 'A'::bpchar THEN 'ACHA'::text
                    WHEN operation.o_type = 'V'::bpchar THEN 'RECE'::text
                    ELSE NULL::text
                END AS type_opr, ( SELECT jrn_def.jrn_def_name
                   FROM jrn_def
                  WHERE jrn_def.jrn_def_id = operation.jrn_def_id) AS ledger_name,
		  mp_description 
           FROM syndicat.operation
      LEFT JOIN ( SELECT a.f_id, a.ad_value AS name, b.ad_value AS first_name
                   FROM fiche_detail a
              LEFT JOIN fiche_detail b ON a.f_id = b.f_id AND b.ad_id = 32
             WHERE a.ad_id = 1) n ON operation.o_benef = n.f_id OR operation.o_third = n.f_id
      left join syndicat.payment_method on (mp_id = o_payment)
UNION ALL 
         SELECT bank.b_id AS id, bank.b_date AS xdate, bank.b_libelle AS label, bank.b_amount AS sum_operation, 'B'::text AS type_table, bank.sect_id, bank.jrn_def_id, bank.o_id, ''::text AS o_receipt, bank.b_other AS tiers, n.name, n.first_name, bank_code.b_type AS type_opr, ( SELECT jrn_def.jrn_def_name
                   FROM jrn_def
                  WHERE jrn_def.jrn_def_id = bank.jrn_def_id) AS ledger_name,
                  mp_description
           FROM syndicat.bank
      JOIN syndicat.bank_code ON bank.b_code = bank_code.b_code
   LEFT JOIN ( SELECT a.f_id, a.ad_value AS name, b.ad_value AS first_name
                 FROM fiche_detail a
         LEFT JOIN fiche_detail b ON a.f_id = b.f_id AND b.ad_id = 32
           WHERE a.ad_id = 1) n ON n.f_id = bank.b_other
      left join syndicat.payment_method on (mp_id = b_payment)           

ALTER TABLE syndicat.vw_historic
  OWNER TO phpcompta;

