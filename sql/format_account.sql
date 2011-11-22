CREATE OR REPLACE FUNCTION comptaproc.format_account(p_account account_type)
  RETURNS account_type AS
$BODY$

declare

sResult account_type;

begin
sResult := lower(p_account);

sResult := translate(sResult,'éèêëàâäïîüûùöô','eeeeaaaiiuuuoo');
sResult := translate(sResult,' $€µ£%.+-/\!(){}(),;_&|"#''^','');

if not sResult similar to '^[[:alnum:]_]+$' then
	raise exception 'Invalid character in %',p_account;
end if;

return upper(sResult);

end;
$BODY$
LANGUAGE plpgsql;
  
COMMENT ON FUNCTION comptaproc.format_account(account_type) IS 'format the accounting :
- upper case
- remove space and special char.
';
