-- Function: comptaproc.fiche_account_parent(integer)

-- DROP FUNCTION comptaproc.fiche_account_parent(integer);

CREATE OR REPLACE FUNCTION comptaproc.fiche_account_parent(p_f_id integer)
  RETURNS poste_comptable AS
$BODY$
declare
ret tmp_pcmn.pcm_val%TYPE;
begin
	select fd_class_base into ret from fiche_def join fiche using (fd_id) where f_id=p_f_id;
	if not FOUND then
		raise exception '% N''existe pas',p_f_id;
	end if;
	return ret;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION comptaproc.fiche_account_parent(integer) OWNER TO trunk;
