alter table tva_rate add tva_both_side integer ;
alter table tva_rate alter tva_both_side set default 0;
update tva_rate set tva_both_side=0;

----------------------------------------------------------------------
-- TVA MODIFY
----------------------------------------------------------------------
drop FUNCTION comptaproc.tva_modify(integer, text, numeric, text, text);



alter table quant_purchase add qp_vat_sided number (20,4);
alter table quant_sold add qs_vat_sided number (20,4);

alter table quant_purchase alter qp_vat_sided set default 0.0;
alter table quant_solde alter qs_vat_sided set default 0.0;
comment on column quant_purchase.qp_vat_sided is 'amount of the VAT which avoid VAT, case of the VAT which add the same amount at the deb and cred';
comment on column quant_purchase.qp_vat_sided is 'amount of the VAT which avoid VAT, case of the VAT which add the same amount at the deb and cred';

CREATE OR REPLACE FUNCTION comptaproc.tva_modify(integer, text, numeric, text, text,integer)
 RETURNS integer
AS $function$
declare
	p_tva_id alias for $1;
	p_tva_label alias for $2;
	p_tva_rate alias for $3;
	p_tva_comment alias for $4;
	p_tva_poste alias for $5;
	p_tva_both_side alias for $6;
	debit text;
	credit text;
	nCount integer;
begin
if length(trim(p_tva_label)) = 0 then
	return 3;
end if;

if length(trim(p_tva_poste)) != 0 then
	if position (',' in p_tva_poste) = 0 then return 4; end if;
	debit  = split_part(p_tva_poste,',',1);
	credit	= split_part(p_tva_poste,',',2);
	select count(*) into nCount from tmp_pcmn where pcm_val=debit::account_type;
	if nCount = 0 then return 4; end if;
	select count(*) into nCount from tmp_pcmn where pcm_val=credit::account_type;
	if nCount = 0 then return 4; end if;

end if;
update tva_rate set tva_label=p_tva_label,tva_rate=p_tva_rate,tva_comment=p_tva_comment,tva_poste=p_tva_poste,tva_both_side=p_tva_both_side
	where tva_id=p_tva_id;
return 0;
end;
$function$
LANGUAGE plpgsql;

----------------------------------------------------------------------
-- TVA INSERT
----------------------------------------------------------------------

drop FUNCTION comptaproc.tva_insert(text, numeric, text, text);

CREATE OR REPLACE FUNCTION comptaproc.tva_insert(text, numeric, text, text,integer)
 RETURNS integer
AS $function$
declare
	l_tva_id integer;
	p_tva_label alias for $1;
	p_tva_rate alias for $2;
	p_tva_comment alias for $3;
	p_tva_poste alias for $4;
	p_tva_both_side alias for $5;
	debit text;
	credit text;
	nCount integer;
begin
if length(trim(p_tva_label)) = 0 then
	return 3;
end if;

if length(trim(p_tva_poste)) != 0 then
	if position (',' in p_tva_poste) = 0 then return 4; end if;
	debit  = split_part(p_tva_poste,',',1);
	credit	= split_part(p_tva_poste,',',2);
	select count(*) into nCount from tmp_pcmn where pcm_val=debit::account_type;
	if nCount = 0 then return 4; end if;
	select count(*) into nCount from tmp_pcmn where pcm_val=credit::account_type;
	if nCount = 0 then return 4; end if;

end if;
select into l_tva_id nextval('s_tva') ;
insert into tva_rate(tva_id,tva_label,tva_rate,tva_comment,tva_poste,tva_both_side)
	values (l_tva_id,p_tva_label,p_tva_rate,p_tva_comment,p_tva_poste,p_tva_both_side);
return 0;
end;
$function$
LANGUAGE plpgsql;



DROP FUNCTION comptaproc.insert_quant_purchase(text,numeric, character varying,numeric,numeric,numeric,integer,,numeric,numeric,,numeric,numeric,character varying, numeric);
-- procedure insert_quant_purchase
CREATE OR REPLACE FUNCTION comptaproc.insert_quant_purchase(p_internal text, p_j_id numeric, p_fiche character varying, p_quant numeric, p_price numeric, p_vat numeric, p_vat_code integer, p_nd_amount numeric, p_nd_tva numeric, p_nd_tva_recup numeric, p_dep_priv numeric, p_client character varying,p_tva_sided numeric)
 RETURNS void
AS $function$
declare
        fid_client integer;
        fid_good   integer;
begin
        select f_id into fid_client from
                fiche_detail where ad_id=23 and ad_value=upper(trim(p_client));
        select f_id into fid_good from
                 fiche_detail where ad_id=23 and ad_value=upper(trim(p_fiche));
        insert into quant_purchase
                (qp_internal,
                j_id,
                qp_fiche,
                qp_quantite,
                qp_price,
                qp_vat,
                qp_vat_code,
                qp_nd_amount,
                qp_nd_tva,
                qp_nd_tva_recup,
                qp_supplier,
                qp_dep_priv,
                qp_vat_sided)
        values
                (p_internal,
                p_j_id,
                fid_good,
                p_quant,
                p_price,
                p_vat,
                p_vat_code,
                p_nd_amount,
                p_nd_tva,
                p_nd_tva_recup,
                fid_client,
                p_dep_priv,
                p_tva_sided);
        return;
end;
 $function$ 
 LANGUAGE plpgsql;

DROP FUNCTION comptaproc.insert_quant_sold(text, numeric, character varying, numeric, numeric, numeric, integer, character varying);

-- procedure insert_quant_sold
CREATE OR REPLACE FUNCTION comptaproc.insert_quant_sold(p_internal text, p_jid numeric, p_fiche character varying, p_quant numeric, p_price numeric, p_vat numeric, p_vat_code integer, p_client character varying,p_tva_sided numeric)
 RETURNS void
AS $function$
declare
        fid_client integer;
        fid_good   integer;
begin

        select f_id into fid_client from
                fiche_detail where ad_id=23 and ad_value=upper(trim(p_client));
        select f_id into fid_good from
                fiche_detail where ad_id=23 and ad_value=upper(trim(p_fiche));
        insert into quant_sold
                (qs_internal,j_id,qs_fiche,qs_quantite,qs_price,qs_vat,qs_vat_code,qs_client,qs_valid,qs_vat_sided)
        values
                (p_internal,p_jid,fid_good,p_quant,p_price,p_vat,p_vat_code,fid_client,'Y',p_tva_sided);
        return;
end;
 $function$ 
 LANGUAGE plpgsql;
