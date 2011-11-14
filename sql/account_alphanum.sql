-- Function: comptaproc.account_auto(integer)

-- DROP FUNCTION comptaproc.account_auto(integer);

CREATE OR REPLACE FUNCTION comptaproc.account_alphanum()
  RETURNS boolean AS
$BODY$
declare
	l_auto bool;
begin
	l_auto := true;
	select pr_value into l_auto from parameter where pr_id='MY_ALPHANUM';
	if l_auto = 'N' or l_auto is null then
		l_auto:=false;
	end if;
	return l_auto;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
