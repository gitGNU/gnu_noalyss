create or replace function comptaproc.get_letter_jnt(a bigint) returns bigint
as
$_$
declare
 nResult bigint;
begin
   select jl_id into nResult from jnt_letter join letter_deb using (jl_id) where j_id = a;
   if NOT FOUND then
	select jl_id into nResult from jnt_letter join letter_cred using (jl_id) where j_id = a;
	if NOT found then 	
		return null;
	end if;
    end if;
return nResult;
end;
$_$ language plpgsql