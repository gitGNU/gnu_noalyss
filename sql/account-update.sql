-- Function: comptaproc.account_update(integer, account_type)

-- DROP FUNCTION comptaproc.account_update(integer, account_type);

CREATE OR REPLACE FUNCTION comptaproc.account_update(p_f_id integer, p_account account_type)
  RETURNS integer AS
$BODY$
declare
	nMax fiche.f_id%type;
	nCount integer;
	nParent tmp_pcmn.pcm_val_parent%type;
	sName varchar;
	first text;
	second text;
begin

	if length(trim(p_account)) != 0 then
		-- 2 accounts in card separated by comma 
		if position (',' in p_account) = 0 then
			select count(*) into nCount from tmp_pcmn where pcm_val=p_account;
			if nCount = 0 then
			select ad_value into sName from
				fiche_detail
				where
				ad_id=1 and f_id=p_f_id;
			nParent:=account_parent(p_account);
			insert into tmp_pcmn(pcm_val,pcm_lib,pcm_val_parent) values (p_account,sName,nParent);
		end if;
		else
		raise info 'presence of a comma';
		-- there is 2 accounts separated by a comma
		first := split_part(p_account,',',1);
		second := split_part(p_account,',',2);
		-- check there is no other coma
		raise info 'first value % second value %', first, second;

		if  position (',' in first) != 0 or position (',' in second) != 0 then
			raise exception 'Too many comas, invalid account';
		end if;
		-- check that both account are in PCMN
		
		end if;
	else
		-- account is null
		update fiche_detail set ad_value=null where f_id=p_f_id and ad_id=5 ;
	end if;
	
	update fiche_detail set ad_value=p_account where f_id=p_f_id and ad_id=5 ;

return 0;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION comptaproc.account_update(integer, account_type) OWNER TO dany;
