-- Function: comptaproc.tmp_pcmn_ins()

-- DROP FUNCTION comptaproc.tmp_pcmn_ins();

CREATE OR REPLACE FUNCTION comptaproc.tmp_pcmn_ins()
  RETURNS trigger AS
$BODY$
declare
   r_record tmp_pcmn%ROWTYPE;
begin
r_record := NEW;
if  length(trim(r_record.pcm_type))=0 or r_record.pcm_type is NULL then 
   r_record.pcm_type:=find_pcm_type(NEW.pcm_val);
   return r_record;
end if;
return NEW;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
