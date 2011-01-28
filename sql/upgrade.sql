create unique index qs_j_id on quant_sold(j_id);
create unique index qp_j_id on quant_purchase(j_id);
create unique index qf_jr_id on quant_fin(jr_id);
update jrn_def set jrn_def_code=substr(jrn_def_code,1,1)||substr(jrn_def_code,length(jrn_def_code)-1,length(jrn_def_code));
commit;
