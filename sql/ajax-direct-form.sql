create type menu_tree as (code text,description text);
 
create or replace function comptaproc.get_profile_menu(login text)
returns setof  menu_tree
as
$BODY$
declare
	a menu_tree;
	e menu_tree;
begin
for a in select me_code,me_description from v_all_menu where user_name=login 
	and me_code_dep is null and me_type <> 'PR' and me_type <>'SP'
loop
		return next a;
	
		for e in select * from get_menu_tree(a.code,login)
		loop 
			return next e;
		end loop;
	
	end loop;
return;
end;
$BODY$ language plpgsql;




CREATE OR REPLACE FUNCTION comptaproc.get_menu_tree(p_code text,login text)
  RETURNS SETOF menu_tree AS
$BODY$
declare
	i menu_tree;
	e menu_tree;
	a text;
	x v_all_menu%ROWTYPE;
begin
	for x in select *  from v_all_menu where me_code_dep=p_code::text and user_name=login::text
	loop	
		if x.me_code_dep is not null then
			i.code := x.me_code_dep||'/'||x.me_code;
		else
			i.code := x.me_code;
		end if;

		i.description := x.me_description;
		
		return next i;
		
	for e in select *  from get_menu_tree(x.me_code,login)
		loop
			e.code:=x.me_code_dep||'/'||e.code;
			return next e;
		end loop;

	end loop;
	return;
end;
$BODY$
  LANGUAGE plpgsql;
	
