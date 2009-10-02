begin;
 insert into parameter(pr_id,pr_value) values ('MY_CHECK_PERIODE','Y');
 alter table jrn add jr_mt text ;
 update jrn set jr_mt=  extract (microseconds from jr_tech_date);
 create   index x_mt on jrn(jr_mt);
 DROP FUNCTION insert_quant_purchase(text, numeric, character varying, numeric, numeric,numeric, integer, numeric, numeric, numeric, character varying);
 DROP FUNCTION insert_quant_sold(text, character varying, numeric, numeric, numeric, integer, character varying);
alter table groupe_analytique add constraint fk_pa_id foreign key(pa_id)  references plan_analytique(pa_id) on delete cascade;
alter table stock_goods add constraint fk_stock_good_f_id foreign key(f_id)  references fiche(f_id) ;
-- for belgium
insert into parm_code values ('SUPPLIER',440,'Poste par défaut pour les fournisseurs'); 
-- for french
-- insert into parm_code values ('SUPPLIER',400,'Poste par défaut pour les fournisseurs'); 
drop table invoice;
-- Function: account_parent(poste_comptable)

DROP FUNCTION account_parent(poste_comptable);

CREATE  FUNCTION account_parent(p_account poste_comptable)
  RETURNS poste_comptable AS
$BODY$
declare
	nParent tmp_pcmn.pcm_val_parent%type;
	sParent varchar;
	nCount integer;
begin
	sParent:=to_char(p_account,'9999999999999999');
	sParent:=trim(sParent::text);
	nParent:=0;
	while nParent = 0 loop
		select count(*) into nCount
		from tmp_pcmn
		where
		pcm_val = to_number(sParent,'9999999999999999');
		if nCount != 0 then
			nParent:=to_number(sParent,'9999999999999999');
			exit;
		end if;
		sParent:= substr(sParent,1,length(sParent)-1);
		if length(sParent) <= 0 then	
			raise exception 'Impossible de trouver le compte parent pour %',p_account;
		end if;
	end loop;
	raise notice 'account_parent : Parent is %',nParent;
	return nParent;
end;
$BODY$
LANGUAGE 'plpgsql' VOLATILE; 

alter table document drop column d_state;
alter table action_gestion drop column f_id_exp;
alter table action_gestion set ag_title type text;
ALTER TABLE action_gestion ADD COLUMN ag_hour time with time zone;
ALTER TABLE action_gestion ADD COLUMN ag_priority integer;
ALTER TABLE action_gestion ALTER COLUMN ag_priority SET DEFAULT 2;
ALTER TABLE action_gestion ADD COLUMN ag_dest text;
ALTER TABLE action_gestion ADD COLUMN ag_owner text;
ALTER TABLE action_gestion ADD COLUMN ag_contact int8;




CREATE OR REPLACE FUNCTION action_gestion_ins_upd()
  RETURNS trigger AS
$BODY$
begin
NEW.ag_title := substr(NEW.ag_title,1,70);
NEW.ag_hour := substr(NEW.ag_hour,1,10);
return NEW;
end;
$BODY$
LANGUAGE 'plpgsql' VOLATILE;

CREATE TRIGGER action_gestion_t_insert_update
  BEFORE INSERT OR UPDATE
  ON action_gestion
  FOR EACH ROW
  EXECUTE PROCEDURE action_gestion_ins_upd();

COMMENT ON TRIGGER action_gestion_t_insert_update ON action_gestion IS 'Truncate the column ag_title to 70 char';

ALTER TABLE action_gestion   ADD COLUMN ag_state integer;
update action_gestion set f_id_dest =  f_id_exp where f_id_exp != 0;

alter table action drop column f_id_dest;
UPDATE document_state    SET s_value= 'Clôturé' WHERE s_id=1;
UPDATE document_state    SET s_value= 'A suivre' WHERE s_id=2;
UPDATE document_state    SET s_value= 'A faire' WHERE s_id=3;
UPDATE document_state    SET s_value= 'Abandonné' WHERE s_id=4;


CREATE TABLE action_detail
(
  ad_id serial,
  f_id int8,
  ad_text text,
  ad_pu numeric(20,4) DEFAULT 0,
   ad_quant numeric(20,4) DEFAULT 0,
  ad_tva_id integer DEFAULT 0,
  ad_tva_amount numeric(20,4) DEFAULT 0,
  ad_total_amount numeric(20,4) DEFAULT 0,
  ag_id integer NOT NULL DEFAULT 0,
  CONSTRAINT action_detail_pkey PRIMARY KEY (ad_id),
  CONSTRAINT action_detail_ag_id_fkey FOREIGN KEY (ag_id)
      REFERENCES action_gestion (ag_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
);

COMMENT ON TABLE action_detail IS 'Detail of action_gestion, see class Action_Detail';

commit;

