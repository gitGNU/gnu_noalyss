-- Function: comptaproc.account_compute(integer)

-- DROP FUNCTION comptaproc.account_compute(integer);

CREATE OR REPLACE FUNCTION comptaproc.account_compute(p_f_id integer)
  RETURNS account_type AS
$BODY$
declare
	class_base fiche_def.fd_class_base%type;
	maxcode numeric;
	sResult account_type;
begin
	select fd_class_base into class_base
	from
		fiche_def join fiche using (fd_id)
	where
		f_id=p_f_id;
	raise notice 'account_compute class base %',class_base;
	select count (pcm_val) into maxcode from tmp_pcmn where pcm_val_parent = class_base;
	if maxcode = 0	then
		maxcode:=class_base::numeric;
	else
		select max (pcm_val) into maxcode from tmp_pcmn where pcm_val_parent = class_base;
		maxcode:=maxcode::numeric;
	end if;
	if maxcode::text = class_base then
		maxcode:=class_base::numeric*1000;
	end if;
	maxcode:=maxcode+1;
	raise notice 'account_compute Max code %',maxcode;
	sResult:=maxcode::account_type;
	return sResult;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION comptaproc.account_compute(integer) OWNER TO trunk;
