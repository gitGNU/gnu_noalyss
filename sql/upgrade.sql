begin;
CREATE TABLE groupe_analytique
(
  ga_id varchar(10) NOT NULL,
  ga_description text,
  CONSTRAINT pk_ga_id PRIMARY KEY (ga_id)
) ;


CREATE OR REPLACE FUNCTION group_analytic_ins_upd()
  RETURNS "trigger" AS
$BODY$
declare 
name text;
begin
raise notice 'poste_analytique_write';
name:=upper(NEW.ga_id);
name:=trim(name);
name:=replace(name,' ','');
NEW.ga_id:=name;
return NEW;
end;$BODY$
  LANGUAGE 'plpgsql' VOLATILE;

CREATE OR REPLACE FUNCTION group_analytique_del()
  RETURNS "trigger" AS
$BODY$
begin
update poste_analytique set ga_id=null
where ga_id=OLD.ga_id;
return OLD;
end;$BODY$
  LANGUAGE 'plpgsql' VOLATILE;

CREATE OR REPLACE FUNCTION poste_analytique_ins_upd()
  RETURNS "trigger" AS
$BODY$declare
name text;
rCount record;

begin
name:=upper(NEW.po_name);
name:=trim(name);
name:=replace(name,' ','');		
NEW.po_name:=name;

if NEW.ga_id is NULL then
return NEW;
end if;

if length(trim(NEW.ga_id)) = 0 then
  NEW.ga_id:=NULL;
  return NEW;
end if;
select ga_id from groupe_analytique where ga_id=NEW.ga_id;
if NOT FOUND then
   raise exception' Inexistent Group Analytic %',NEW.ga_id;
end if;
return NEW;
end;$BODY$
  LANGUAGE 'plpgsql' VOLATILE;

CREATE OR REPLACE FUNCTION plan_analytic_ins_upd()
  RETURNS "trigger" AS
$BODY$
declare
   name text;
begin
   name:=upper(NEW.pa_name);
   name:=trim(name);
   name:=replace(name,' ','');
   NEW.pa_name:=name;
return NEW;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;

CREATE TRIGGER t_poste_analytique_ins_upd
  BEFORE INSERT OR UPDATE
  ON poste_analytique
  FOR EACH ROW
  EXECUTE PROCEDURE poste_analytique_ins_upd();

CREATE TRIGGER t_plan_analytique_ins_upd
  BEFORE INSERT OR UPDATE
  ON plan_analytique
  FOR EACH ROW
  EXECUTE PROCEDURE plan_analytic_ins_upd();

CREATE TRIGGER t_group_analytic_del
  AFTER DELETE
  ON groupe_analytique
  FOR EACH ROW
  EXECUTE PROCEDURE group_analytique_del();

CREATE TRIGGER t_group_analytic_ins_upd
  BEFORE INSERT OR UPDATE
  ON groupe_analytique
  FOR EACH ROW
  EXECUTE PROCEDURE group_analytic_ins_upd();


drop TRIGGER t_upper_pa_name on plan_analytique;
drop TRIGGER t_upper_po_name on poste_analytique;
drop function upper_pa_name();
drop function upper_po_name();

CREATE TABLE bud_hypothese
(
  bh_id int4 NOT NULL,
  bh_name text NOT NULL,
  bh_saldo numeric(20,4) DEFAULT 0,
  bh_description text,
  pa_id int4,
  CONSTRAINT pk_bud_hypo PRIMARY KEY (bh_id),
  CONSTRAINT fk_bud_hypo_pa_id FOREIGN KEY (pa_id)
      REFERENCES plan_analytique (pa_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
) 
WITHOUT OIDS;

commit;	
