
CREATE OR REPLACE FUNCTION comptaproc.tmp_pcmn_alphanum_ins_upd()
  RETURNS trigger AS
$BODY$
declare
   r_record tmp_pcmn%ROWTYPE;
begin
r_record := NEW;
r_record.pcm_val:=format_account(NEW.pcm_val);

return r_record;
end;
$BODY$
LANGUAGE plpgsql;
