--﻿drop view v_quant_detail;
create or replace view v_quant_detail as
with quant as 
	(select 
		j_id,
		qp_fiche as fiche_id,
		qp_supplier as tiers, 
		qp_vat as vat_amount,
		qp_price as price,
		qp_vat_code as vat_code,
		qp_dep_priv as dep_priv,
		qp_nd_tva as nd_tva,
		qp_nd_tva_recup as nd_tva_recup,
		qp_nd_amount	as nd_amount,
		qp_vat_sided as vat_sided
		from quant_purchase
	union all	
	select 
		j_id,
		qs_fiche,
		qs_client,
		qs_vat,
		qs_price,
		qs_vat_code,
		0,
		0,
		0,
		0,
		qs_vat_sided
	from 
		quant_sold
)	
select 
	jr_id,quant.tiers,jrn_def_name,jrn_def_type,name,jr_comment,jr_montant,sum(price) as price,vat_code,sum(vat_amount) as vat_amount,sum(dep_priv) as dep_priv,sum(nd_tva) as nd_tva,sum(nd_tva_recup) as nd_tva_recup,sum(nd_amount) as nd_amount,vat_sided,tva_label
from 
jrn
join jrnx on (jrnx.j_grpt=jrn.jr_grpt_id)
join quant using (j_id)
left join vw_fiche_name on (tiers=vw_fiche_name.f_id)
join jrn_def on (jrn_def_id=jr_def_id)
join tva_rate on (tva_id=vat_code)
group by
jr_id,quant.tiers,jr_comment,jr_montant,vat_code,vat_sided,name,jrn_def_name,jrn_def_type,tva_label;