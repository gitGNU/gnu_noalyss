create unique index qs_j_id on quant_sold(j_id);
create unique index qp_j_id on quant_purchase(j_id);
create unique index qf_jr_id on quant_fin(jr_id);
update jrn_def set jrn_def_code=substr(jrn_def_code,1,1)||substr(jrn_def_code,length(jrn_def_code)-1,length(jrn_def_code));
alter table operation_analytique drop column pa_id;
ALTER TABLE operation_analytique  ADD CONSTRAINT operation_analytique_oa_amount_check CHECK (oa_amount >= 0::numeric);

CREATE OR REPLACE VIEW v_table_analytic_card AS 
 SELECT po.po_id, po.pa_id, po.po_name, po.po_description, sum(
        CASE
            WHEN operation_analytique.oa_debit = true THEN operation_analytique.oa_amount * (-1)::numeric
            ELSE operation_analytique.oa_amount
        END) AS sum_amount, jrnx.f_id, jrnx.j_qcode, ( SELECT fiche_detail.ad_value
           FROM fiche_detail
          WHERE fiche_detail.ad_id = 1 AND fiche_detail.f_id = jrnx.f_id) AS name
   FROM operation_analytique
   JOIN poste_analytique po USING (po_id)
   JOIN jrnx USING (j_id)
  GROUP BY po.po_id, po.po_name, po.pa_id, jrnx.f_id, jrnx.j_qcode, ( SELECT fiche_detail.ad_value
   FROM fiche_detail
  WHERE fiche_detail.ad_id = 1 AND fiche_detail.f_id = jrnx.f_id), po.po_description
 HAVING sum(
CASE
    WHEN operation_analytique.oa_debit = true THEN operation_analytique.oa_amount * (-1)::numeric
    ELSE operation_analytique.oa_amount
END) <> 0::numeric;


CREATE OR REPLACE VIEW v_table_analytic_account AS 
 SELECT po.po_id, po.pa_id, po.po_name, po.po_description, sum(
        CASE
            WHEN operation_analytique.oa_debit = true THEN operation_analytique.oa_amount * (-1)::numeric
            ELSE operation_analytique.oa_amount
        END) AS sum_amount, jrnx.j_poste, tmp_pcmn.pcm_lib AS name
   FROM operation_analytique
   JOIN poste_analytique po USING (po_id)
   JOIN jrnx USING (j_id)
   JOIN tmp_pcmn ON jrnx.j_poste::text = tmp_pcmn.pcm_val::text
  GROUP BY po.po_id, po.po_name, po.pa_id, jrnx.j_poste, tmp_pcmn.pcm_lib, po.po_description
 HAVING sum(
CASE
    WHEN operation_analytique.oa_debit = true THEN operation_analytique.oa_amount * (-1)::numeric
    ELSE operation_analytique.oa_amount
END) <> 0::numeric;

commit;
