-- si la fiche utilise le code DEPENSE PRIVEE alors ajout dans QP_DEP_PRIV
create or replace view m as 
select qp_id, qp_price from quant_purchase join fiche_detail on (qp_fiche=f_id and ad_id=5) where ad_value in (select p_value from parm_code where p_code='DEP_PRIV');

update quant_purchase as e set qp_dep_priv=(select qp_price from m where m.qp_id=e.qp_id);

update quant_purchase as e set qp_dep_priv=(select qp_price from m where m.qp_id=e.qp_id);

update quant_purchase as e set qp_dep_priv=0 where qp_dep_priv is null;
-- évite les valeurs nulles dans quant_purchase
update quant_purchase set qp_dep_priv = 0 where qp_dep_priv is null;

drop view m;

-- update script insert_quant_purchase


CREATE OR REPLACE FUNCTION comptaproc.insert_quant_purchase(
    p_internal text, 
    p_j_id numeric, 
    p_fiche text, 
    p_quant numeric, 
    p_price numeric, 
    p_vat numeric, 
    p_vat_code integer, 
    p_nd_amount numeric, 
    p_nd_tva numeric, 
    p_nd_tva_recup numeric, 
    p_dep_priv numeric, 
    p_client text, 
    p_tva_sided numeric)
  RETURNS void AS
$BODY$
declare
        fid_client integer;
        fid_good   integer;
        account_priv    account_type;
        fid_good_account account_type;
        n_dep_priv numeric;
begin
        n_dep_priv := 0;
        select p_value into account_priv from parm_code where p_code='DEP_PRIV';
        select f_id into fid_client from
                fiche_detail where ad_id=23 and ad_value=upper(trim(p_client));
        select f_id into fid_good from
                 fiche_detail where ad_id=23 and ad_value=upper(trim(p_fiche));
        select ad_value into fid_good_account from fiche_detail where ad_id=5 and f_id=fid_good;
        if strpos( fid_good_account , account_priv ) = 1 then
                n_dep_priv=p_price;
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
                n_dep_priv,
                p_tva_sided);
        return;
end;
 $BODY$
  LANGUAGE plpgsql;


-- ajout code manquant dans parm_code
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

select add_parm_code();

drop function add_parm_code();

update parm_code set p_value='67' where p_value='6740' and p_code='DNA';

 alter table menu_ref add me_description_etendue text;

insert into menu_ref(me_code,me_menu,me_file, me_url,me_description,me_parameter,me_javascript,me_type,me_description_etendue)
values
('NAVI','Navigateur',null,null,'Menu simplifié pour retrouver rapidement un menu',null,'ask_navigator(<DOSSIER>)','ME','Le navigateur vous présente une liste de menu auquel vous avez accès et vous permet d''accèder plus rapidement au menu que vous souhaitez rapidement');

insert into profile_menu (me_code,me_code_dep,p_id,p_order, p_type_display,pm_default) 
values
('NAVI',null,1,90,'M',0), ('NAVI',null,2,90,'M',0);

insert into menu_ref(me_code,me_menu,me_file, me_url,me_description,me_parameter,me_javascript,me_type,me_description_etendue)
values
('BOOKMARK','Favori',null,null,'Raccourci vers vos menus préférés',null,'show_bookmark(<DOSSIER>)','ME','Ce menu vous présente un menu rapide des menus que vous utilisez le plus souvent');

insert into profile_menu (me_code,me_code_dep,p_id,p_order, p_type_display,pm_default) 
values
('BOOKMARK',null,1,85,'M',0), ('BOOKMARK',null,2,85,'M',0);

update menu_ref set me_menu='Impression Journaux' where me_code='PRINTJRN';
update menu_ref set me_description='Impression des journaux' where me_code='PRINTJRN';
update menu_ref set me_description='Liste du Suivi' where me_code='FOLLOW';
update menu_ref set me_javascript='popup_recherche(<DOSSIER>)' where me_code='SEARCH';

/*
 * Vue montrant toutes les possibilités
 */
CREATE OR REPLACE VIEW v_menu_description AS 
 WITH t_menu AS (
         SELECT mr.me_menu, pm.me_code, pm.me_code_dep, pm.p_type_display, pu.user_name, mr.me_file, mr.me_javascript, mr.me_description, mr.me_description_etendue
           FROM profile_menu pm
      JOIN profile_user pu ON pu.p_id = pm.p_id
   JOIN profile p ON p.p_id = pm.p_id
   JOIN menu_ref mr USING (me_code)
        )
 SELECT DISTINCT (COALESCE(v3.me_code || '/'::text, ''::text) || COALESCE(v2.me_code, ''::text)) || 
        CASE
            WHEN v2.me_code IS NULL THEN COALESCE(v1.me_code, ''::text)
            WHEN v2.me_code IS NOT NULL THEN COALESCE('/'::text || v1.me_code, ''::text)
            ELSE NULL::text
        END AS code, v1.me_code, v1.me_description, v1.me_description_etendue, v1.me_file, v1.user_name, '> '::text || v1.me_menu AS v1menu, 
        CASE
            WHEN COALESCE(v3.me_menu, ''::text) <> ''::text THEN ' > '::text || v2.me_menu
            ELSE v2.me_menu
        END AS v2menu, v3.me_menu AS v3menu, v3.p_type_display,
 coalesce(v1.me_javascript,coalesce(v2.me_javascript,v3.me_javascript)) as javascript
   FROM t_menu v1
   LEFT JOIN t_menu v2 ON v1.me_code_dep = v2.me_code
   LEFT JOIN t_menu v3 ON v2.me_code_dep = v3.me_code
  WHERE v1.p_type_display <> 'P'::text AND (COALESCE(v1.me_file, ''::text) <> ''::text OR COALESCE(v1.me_javascript, ''::text) <> ''::text);

COMMENT ON VIEW v_menu_description
  IS 'Description des menus';
 
CREATE TABLE bookmark
(
b_id serial primary key,
b_order integer default 1,
b_action text,
login text 
);
comment on table bookmark is 'Bookmark of the connected user';
/*
 * Missing update for preference !!!
 */

create table tags (
    t_id serial primary key, 
    t_tag text not null, 
    t_description text
);

create table action_tags
(
    at_id serial primary key,
    t_id integer references tags(t_id) on delete cascade on update cascade,
    ag_id integer references action_gestion(ag_id) on delete cascade on update cascade
);
/* Config tag */
insert into menu_ref(me_code,me_menu,me_file, me_url,me_description,me_parameter,me_javascript,me_type,me_description_etendue)
values
('CFGTAG','Configuration dossier','cfgtags.inc.php',null,'Configuration des dossiers',null,null,'ME','Configuration des tags ou dossiers, on l''appele tag ou dossier suivant la façon dont vous utilisez 
cette fonctionnalité. Vous pouvez en ajouter, en supprimer ou les modifier');

insert into profile_menu (me_code,me_code_dep,p_id,p_order, p_type_display,pm_default) 
values
('CFGTAG','PARAM',1,390,'E',0);