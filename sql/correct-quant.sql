CREATE OR REPLACE FUNCTION correct_quant_purchase() returns void
as
$BODY$
declare
	r_invalid quant_purchase;
	s_QuickCode text;
	b_j_debit bool;
	r_new record;
	r_jrnx record;
begin

for r_invalid in select * from quant_purchase where qp_valid='A'
loop

-- get qcode 
select j_qcode into s_QuickCode from vw_poste_qcode where f_id=r_invalid.qp_fiche;
raise notice 'qp_id % Quick code is %',r_invalid.qp_id,s_QuickCode;

-- get deb or cred
select j_debit,j_grpt,j_jrn_def,j_montant into r_jrnx from jrnx where j_id=r_invalid.j_id;
if NOT FOUND then
	raise notice 'error not found jrnx %',r_invalid.j_id;
	update quant_purchase set qp_valid='Y' where qp_id=r_invalid.qp_id;
	continue;
end if;
raise notice 'j_debit % , j_grpt % ,j_jrn_def  % qp_price %',r_jrnx.j_debit,r_jrnx.j_grpt,r_jrnx.j_jrn_def ,r_invalid.qp_price;

select jr_internal,j_id,j_montant into r_new
	from jrnx join jrn on (j_grpt=jr_grpt_id)
	where 
	j_jrn_def=r_jrnx.j_jrn_def
	and j_id not in (select j_id from  quant_purchase)
	and j_qcode=s_QuickCode
	and j_montant=r_jrnx.j_montant
	and j_debit != r_jrnx.j_debit;

if NOT FOUND then
	raise notice 'error not found %', r_invalid.j_id;
	update quant_purchase set qp_valid='Y' where qp_id=r_invalid.qp_id;
	continue;     
end if;
raise notice 'j_id % found amount %',r_new.j_id,r_new.j_montant;

-- insert into quant_purchase
insert into quant_purchase (qp_internal,j_id,qp_fiche,qp_quantite,qp_price,qp_vat,qp_nd_amount,qp_nd_tva_recup,qp_valid,qp_dep_priv,qp_supplier,qp_vat_code)
values (r_new.jr_internal,r_invalid.j_id,r_invalid.qp_fiche,(r_invalid.qp_quantite * (-1)),r_invalid.qp_price * (-1),r_invalid.qp_vat*(-1),r_invalid.qp_nd_amount*(-1),r_invalid.qp_nd_tva_recup*(-1) ,'Y',r_invalid.qp_dep_priv*(-1),r_invalid.qp_supplier,r_invalid.qp_vat_code);

update quant_purchase set qp_valid='Y' where qp_id=r_invalid.qp_id;
end loop;
return;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;

