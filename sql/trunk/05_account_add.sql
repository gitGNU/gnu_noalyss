-- Function: comptaproc.account_add(account_type, character varying)

-- DROP FUNCTION comptaproc.account_add(account_type, character varying);

CREATE OR REPLACE FUNCTION comptaproc.account_add(p_id account_type, p_name character varying)
  RETURNS void AS
$BODY$
declare
	nParent tmp_pcmn.pcm_val_parent%type;
	nCount integer;
begin
	select count(*) into nCount from tmp_pcmn where pcm_val=p_id;
	if nCount = 0 then
		nParent=account_parent(p_id);
		insert into tmp_pcmn (pcm_val,pcm_lib,pcm_val_parent)
			values (p_id, p_name,nParent);
	end if;
return;
end ;
$BODY$
LANGUAGE 'plpgsql';
