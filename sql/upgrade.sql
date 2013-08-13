-- si la fiche utilise le code DEPENSE PRIVEE alors ajout dans QP_DEP_PRIV
with m as (select qp_id, qp_price from quant_purchase join fiche_detail on (qp_fiche=f_id and ad_id=5) where ad_value in (select p_value from parm_code where p_code='DEP_PRIV'))
update quant_purchase as e set qp_dep_priv=(select qp_price from m where m.qp_id=e.qp_id);
-- évite les valeurs nulles dans quant_purchase
update quant_purchase set qp_dep_priv = 0 where qp_dep_priv is null;
-- update script insert_quant_purchase

CREATE OR REPLACE FUNCTION comptaproc.insert_quant_purchase(p_internal text, p_j_id numeric, p_fiche character varying, p_quant numeric, p_price numeric, p_vat numeric, p_vat_code integer, p_nd_amount numeric, p_nd_tva numeric, p_nd_tva_recup numeric, p_dep_priv numeric, p_client character varying, p_tva_sided numeric)
  RETURNS void AS
$BODY$
declare
        fid_client integer;
        fid_good   integer;
        account_priv    account_type;
        fid_good_account account_type;
begin
	select p_value into account_priv from parm_code where p_code='DEP_PRIV';
	
        select f_id into fid_client from
                fiche_detail where ad_id=23 and ad_value=upper(trim(p_client));
        select f_id into fid_good from
                 fiche_detail where ad_id=23 and ad_value=upper(trim(p_fiche));
        select ad_value into fid_good_account from fiche_detail where ad_id=5 and f_id=fid_good;

        if account_priv = fid_good_account then
		p_dep_priv=p_price;
	end if;
	
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
 $BODY$
  LANGUAGE plpgsql;

create or replace function add_parm_code() returns void as
$fct$
declare
    country_code text;
begin 
    select pr_value into country_code from parameter where pr_id='MY_COUNTRY';
    if country_code='FR' then
        insert into parm_code (p_code,p_comment,p_value) values ('DNA','Dépense non déductible','67');
        insert into parm_code  (p_code,p_comment,p_value) values ('TVA_DNA','TVA non déductible','');
        insert into parm_code  (p_code,p_comment,p_value) values ('TVA_DED_IMPOT','TVA déductible à l''impôt','');
        insert into parm_code  (p_code,p_comment,p_value) values ('COMPTE_COURANT','Poste comptable pour le compte courant','');
        insert into parm_code  (p_code,p_comment,p_value) values ('COMPTE_TVA','TVA à payer ou à recevoir','');
     end if;
end;
$fct$
language plpgsql;
