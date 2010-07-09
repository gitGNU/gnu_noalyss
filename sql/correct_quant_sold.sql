CREATE OR REPLACE FUNCTION correct_quant_sale() returns void
as
$BODY$
declare
	r_invalid quant_sold;
	s_QuickCode text;
	b_j_debit bool;
	r_new record;
	r_jrnx record;
begin

for r_invalid in select * from quant_sold where qs_valid='A'
loop

-- get qcode 
select j_qcode into s_QuickCode from vw_poste_qcode where f_id=r_invalid.qs_fiche;
raise notice 'qp_id % Quick code is %',r_invalid.qs_id,s_QuickCode;

-- get deb or cred
select j_debit,j_grpt,j_jrn_def,j_montant into r_jrnx from jrnx where j_id=r_invalid.j_id;
if NOT FOUND then
	update quant_sold set qs_valid='Y' where qs_id=r_invalid.qs_id;
	raise notice 'error not found jrnx %',r_invalid.j_id;
	continue;
end if;
raise notice 'j_debit % , j_grpt % ,j_jrn_def  % qs_price %',r_jrnx.j_debit,r_jrnx.j_grpt,r_jrnx.j_jrn_def ,r_invalid.qs_price;

select jr_internal,j_id,j_montant into r_new
	from jrnx join jrn on (j_grpt=jr_grpt_id)
	where 
	j_jrn_def=r_jrnx.j_jrn_def
	and j_id not in (select j_id from  quant_sold)
	and j_qcode=s_QuickCode
	and j_montant=r_jrnx.j_montant
	and j_debit != r_jrnx.j_debit;

if NOT FOUND then
   update quant_sold set qs_valid='Y' where qs_id=r_invalid.qs_id;
	raise notice 'error not found %', r_invalid.j_id;
	continue;
end if;
raise notice 'j_id % found amount %',r_new.j_id,r_new.j_montant;

-- insert into quant_sold

 insert into quant_sold (qs_internal,j_id,qs_fiche,qs_quantite,qs_price,qs_vat,qs_valid,qs_client,qs_vat_code)
 values (r_new.jr_internal,r_invalid.j_id,r_invalid.qs_fiche,(r_invalid.qs_quantite * (-1)),r_invalid.qs_price * (-1),r_invalid.qs_vat*(-1),'Y',r_invalid.qs_client,r_invalid.qs_vat_code);
 update quant_sold set qs_valid='Y' where qs_id=r_invalid.qs_id;
end loop;
return;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;