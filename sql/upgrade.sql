CREATE OR REPLACE FUNCTION comptaproc.letter_compare(p_jl bigint)
  RETURNS numeric AS
$BODY$
declare
 nCred numeric(20,4);
 nDeb numeric(20,4);
begin
	if p_jl = -1 then
		return 0.0;
	end if;
	select coalesce(sum(j_montant),0) into nCred from letter_cred join jrnx using (j_id) where jl_id=p_jl;
	select coalesce(sum(j_montant),0) into nDeb from letter_deb join jrnx using (j_id) where jl_id=p_jl;


	return nDeb-nCred;
end;
$BODY$
  LANGUAGE plpgsql;