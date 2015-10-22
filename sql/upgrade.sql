DROP TRIGGER fiche_detail_upd_trg ON fiche_detail;

CREATE TRIGGER fiche_detail_upd_trg
  after UPDATE
  ON fiche_detail
  FOR EACH ROW
  EXECUTE PROCEDURE comptaproc.fiche_detail_qcode_upd();

insert into menu_ref(me_code,me_file,me_menu,me_description,me_type) 
values ('RAW:receipt','export_receipt.php','Exporte la pièce','export la pièce justificative d''une opération','PR');

insert into profile_menu (me_code,p_id,p_type_display) select 'RAW:receipt',p_id,'P' from profile where p_id > 0;


insert into menu_ref(me_code,me_file,me_menu,me_description,me_type) 
values ('RAW:document','export_document.php','Export le document','exporte le document d''un événement','PR');

insert into profile_menu (me_code,p_id,p_type_display) select 'RAW:document',p_id,'P' from profile where p_id > 0;

insert into menu_ref(me_code,me_file,me_menu,me_description,me_type) 
values ('RAW:document_template','export_document_template.php','Exporte le modèle de document','export le modèle de document utilisé dans le suivi','PR');

insert into profile_menu (me_code,p_id,p_type_display) select 'RAW:document_template',p_id,'P' from profile where p_id > 0;


delete from PROFILE_USER where pu_id in (select b.pu_id 
	from profile_user as a , profile_user as b 
	where 
	upper(a.user_name) = b.user_name and a.pu_id <> b.pu_id );



CREATE OR REPLACE FUNCTION comptaproc.trg_profile_user_ins_upd()
  RETURNS trigger AS
$BODY$

begin

NEW.user_name := lower(NEW.user_name);
return NEW;

end;
$BODY$
language plpgsql;

CREATE TRIGGER profile_user_ins_upd
  BEFORE INSERT OR UPDATE
  ON profile_user
  FOR EACH ROW
  EXECUTE PROCEDURE comptaproc.trg_profile_user_ins_upd();
COMMENT ON TRIGGER profile_user_ins_upd ON profile_user IS 'Force the column user_name to lowercase';



delete from user_sec_jrn where uj_id in (select b.uj_id
	from user_sec_jrn  as a , user_sec_jrn  as b 
	where 
	upper(a.uj_login) = b.uj_login and a.uj_id<> b.uj_id);


update user_sec_jrn set uj_login = lower(uj_login);

ALTER TABLE user_sec_jrn
  ADD CONSTRAINT uniq_user_ledger UNIQUE(uj_login , uj_jrn_id );
COMMENT ON CONSTRAINT uniq_user_ledger ON user_sec_jrn IS 'Create an unique combination user / ledger';

CREATE OR REPLACE FUNCTION comptaproc.trg_user_sec_jrn_ins_upd()
  RETURNS trigger AS
$BODY$

begin

NEW.uj_login:= lower(NEW.uj_login);
return NEW;

end;
$BODY$
language plpgsql;


CREATE TRIGGER user_sec_jrn_after_ins_upd
  BEFORE INSERT OR UPDATE
  ON user_sec_jrn
  FOR EACH ROW
  EXECUTE PROCEDURE comptaproc.trg_user_sec_jrn_ins_upd();
COMMENT ON TRIGGER user_sec_jrn_ins_upd ON user_sec_jrn IS 'Force the column uj_login to lowercase';


delete from user_sec_act where ua_id in (select b.ua_id
	from user_sec_act as a , user_sec_act  as b 
	where 
	upper(a.ua_login) = b.ua_login and a.ua_id<> b.ua_id);

update user_sec_act set ua_login = lower(ua_login);

CREATE OR REPLACE FUNCTION comptaproc.trg_user_sec_act_ins_upd()
  RETURNS trigger AS
$BODY$

begin

NEW.ua_login:= lower(NEW.ua_login);
return NEW;

end;
$BODY$
language plpgsql;


CREATE TRIGGER user_sec_act_ins_upd
  BEFORE INSERT OR UPDATE
  ON user_sec_act
  FOR EACH ROW
  EXECUTE PROCEDURE comptaproc.trg_user_sec_act_ins_upd();
COMMENT ON TRIGGER user_sec_act_ins_upd ON user_sec_act IS 'Force the column ua_login to lowercase';

update todo_list set use_login = lower(use_login);

CREATE OR REPLACE FUNCTION comptaproc.trg_todo_list_ins_upd()
  RETURNS trigger AS
$BODY$

begin

NEW.use_login:= lower(NEW.use_login);
return NEW;

end;
$BODY$
language plpgsql;


CREATE TRIGGER todo_list_ins_upd
  BEFORE INSERT OR UPDATE
  ON todo_list
  FOR EACH ROW
  EXECUTE PROCEDURE comptaproc.trg_todo_list_ins_upd();
COMMENT ON TRIGGER todo_list_ins_upd ON todo_list IS 'Force the column use_login to lowercase';



delete from todo_list_shared where id in (select b.id
	from todo_list_shared as a , todo_list_shared as b 
	where 
	upper(a.use_login) = b.use_login and a.id<> b.id);

update todo_list_shared set use_login = lower(use_login);

CREATE OR REPLACE FUNCTION comptaproc.trg_todo_list_shared_ins_upd()
  RETURNS trigger AS
$BODY$

begin

NEW.use_login:= lower(NEW.use_login);
return NEW;

end;
$BODY$
language plpgsql;


CREATE TRIGGER todo_list_shared_ins_upd
  BEFORE INSERT OR UPDATE
  ON todo_list_shared
  FOR EACH ROW
  EXECUTE PROCEDURE comptaproc.trg_todo_list_shared_ins_upd();
COMMENT ON TRIGGER todo_list_shared_ins_upd ON todo_list_shared IS 'Force the column ua_login to lowercase';

CREATE OR REPLACE FUNCTION comptaproc.action_gestion_ins_upd()
  RETURNS trigger AS
$BODY$
begin
NEW.ag_title := substr(trim(NEW.ag_title),1,70);
NEW.ag_hour := substr(trim(NEW.ag_hour),1,5);
NEW.ag_owner := lower(NEW.ag_owner);
return NEW;
end;
$BODY$
LANGUAGE plpgsql;

alter table quant_sold add column qs_unit numeric(20,4) default 0;
update quant_sold set qs_unit = qs_price / qs_quantite;

CREATE OR REPLACE FUNCTION comptaproc.insert_quant_sold(p_internal text, p_jid numeric, p_fiche character varying, p_quant numeric, p_price numeric, p_vat numeric, p_vat_code integer, p_client character varying, p_tva_sided numeric, p_price_unit numeric)
  RETURNS void AS
$BODY$
declare
        fid_client integer;
        fid_good   integer;
begin

        select f_id into fid_client from
                fiche_detail where ad_id=23 and ad_value=upper(trim(p_client));
        select f_id into fid_good from
                fiche_detail where ad_id=23 and ad_value=upper(trim(p_fiche));
        insert into quant_sold
                (qs_internal,j_id,qs_fiche,qs_quantite,qs_price,qs_vat,qs_vat_code,qs_client,qs_valid,qs_vat_sided,qs_unit)
        values
                (p_internal,p_jid,fid_good,p_quant,p_price,p_vat,p_vat_code,fid_client,'Y',p_tva_sided,p_price_unit);
        return;
end;
 $BODY$
  LANGUAGE plpgsql;
