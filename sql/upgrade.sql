update jrnx set f_id = (select f_id from vw_poste_qcode where vw_poste_qcode.j_qcode=jrnx.j_qcode) where j_qcode is not null;
CREATE OR REPLACE FUNCTION comptaproc.jrnx_ins()
  RETURNS trigger AS
$BODY$
declare
n_fid bigint;
begin

if NEW.j_qcode is NULL then
   return NEW;
end if;

NEW.j_qcode=trim(upper(NEW.j_qcode));

if length (NEW.j_qcode) = 0 then
    NEW.j_qcode=NULL;
    else
   select f_id into n_fid from fiche join jnt_fic_att_value using (f_id) join attr_value using(jft_id) where ad_id=23 and av_text=NEW.j_qcode;
        if NOT FOUND then 
                raise exception 'La fiche dont le quick code est % n''existe pas',NEW.j_qcode;
        end if;
end if;
NEW.f_id:=n_fid;
return NEW;
end;
$BODY$
LANGUAGE plpgsql;