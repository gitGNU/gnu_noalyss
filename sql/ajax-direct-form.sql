-- Function: comptaproc.get_pcm_tree(account_type)

DROP FUNCTION comptaproc.get_menu_tree(text);

CREATE OR REPLACE FUNCTION comptaproc.get_menu_tree(p_code text)
  RETURNS SETOF text AS
$BODY$
declare
	i record;
	e RECORD;
begin
	for i in select me_code from profile_menu where me_code_dep=p_code
	loop
		return next i;
		for e in select get_pcm_tree from get_menu_tree(i)
		loop
			return next e;
		end loop;

	end loop;
	return;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;
