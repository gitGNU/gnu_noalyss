begin;
CREATE OR REPLACE FUNCTION comptaproc.get_menu_dependency(profile_menu_id int)
  RETURNS SETOF int AS
$BODY$
declare
	i int;
	x int;
	e int;
begin
	for x in select pm_id,me_code
			from profile_menu
			where me_code_dep in (select me_code from profile_menu where pm_id=profile_menu_id)
			and p_id = (select p_id from profile_menu where pm_id=profile_menu_id)
	loop
		return next x;

	for e in select *  from comptaproc.get_menu_dependency_pm(x)
		loop
			return next e;
		end loop;

	end loop;
	return;
end;
$BODY$
LANGUAGE plpgsql;

delete from profile_menu where p_id=2 and me_code_dep='DIVPARM';
delete from profile_menu where p_id=2 and me_code_dep='MOD';
update version set val=100;

commit;