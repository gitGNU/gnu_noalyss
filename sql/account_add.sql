-- Function: comptaproc.account_add(account_type, character varying)

DROP FUNCTION comptaproc.account_add(account_type, character varying);

CREATE OR REPLACE FUNCTION comptaproc.account_add(p_id account_type, p_name character varying)
  RETURNS text AS
$BODY$
declare
	nParent tmp_pcmn.pcm_val_parent%type;
	nCount integer;
	sReturn text;
begin
	sReturn:= format_account(p_id);
	select count(*) into nCount from tmp_pcmn where pcm_val=sReturn;
	if nCount = 0 then
		nParent=account_parent(p_id);
		insert into tmp_pcmn (pcm_val,pcm_lib,pcm_val_parent)
			values (p_id, p_name,nParent) returning pcm_val into sReturn;
	end if;
return sReturn;
end ;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION comptaproc.account_add(account_type, character varying) OWNER TO dany;
