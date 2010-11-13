CREATE OR REPLACE FUNCTION comptaproc.jrnx_ins()
  RETURNS trigger AS
$BODY$
declare
n_fid bigint;
begin

NEW.j_tech_per := comptaproc.find_periode(to_char(NEW.j_date,'DD.MM.YYYY'));
if NEW.j_tech_per = -1 then
	raise exception 'Période invalide';
end if;

if NEW.j_qcode is NULL then
   return NEW;
end if;

NEW.j_qcode=trim(upper(NEW.j_qcode));

if length (NEW.j_qcode) = 0 then
    NEW.j_qcode=NULL;
    else
   select f_id into n_fid from fiche_detail  where ad_id=23 and ad_value=NEW.j_qcode;
	if NOT FOUND then
		raise exception 'La fiche dont le quick code est % n''existe pas',NEW.j_qcode;
	end if;
end if;
NEW.f_id:=n_fid;
return NEW;
end;
$BODY$
LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION comptaproc.jrn_check_periode()
  RETURNS trigger AS
$BODY$
declare
bClosed bool;
str_status text;
ljr_tech_per jrn.jr_tech_per%TYPE;
ljr_def_id jrn.jr_def_id%TYPE;
lreturn jrn%ROWTYPE;
begin
if TG_OP='UPDATE' then
	ljr_tech_per :=OLD.jr_tech_per ;
	NEW.jr_tech_per := comptaproc.find_periode(to_char(NEW.jr_date,'DD.MM.YYYY'));
	ljr_def_id   :=OLD.jr_def_id;
	lreturn      :=NEW;
	if NEW.jr_date = OLD.jr_date then
		return NEW;
	end if;
end if;

if TG_OP='INSERT' then
	NEW.jr_tech_per := comptaproc.find_periode(to_char(NEW.jr_date,'DD.MM.YYYY'));
	ljr_tech_per :=NEW.jr_tech_per ;
	ljr_def_id   :=NEW.jr_def_id;
	lreturn      :=NEW;
end if;

if TG_OP='DELETE' then
	ljr_tech_per :=OLD.jr_tech_per;
	ljr_def_id   :=OLD.jr_def_id;
	lreturn      :=OLD;
end if;

select p_closed into bClosed from parm_periode
	where p_id=ljr_tech_per;

if bClosed = true then
	raise exception 'Periode fermee';
end if;

select status into str_status from jrn_periode
       where p_id =ljr_tech_per and jrn_def_id=ljr_def_id;

if str_status <> 'OP' then
	raise exception 'Periode fermee';
end if;

return lreturn;
end;$BODY$
  LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION comptaproc.find_periode(p_date text)
  RETURNS integer AS
$BODY$

declare n_p_id int4;

begin

select p_id into n_p_id
	from parm_periode
	where
		p_start <= to_date(p_date,'DD.MM.YYYY')
		and
		p_end >= to_date(p_date,'DD.MM.YYYY');

if NOT FOUND then
	return -1;
end if;

return n_p_id;

end;$BODY$
  LANGUAGE 'plpgsql';


