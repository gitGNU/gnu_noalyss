drop function if exists get_pcm_tree(account_type);

create or replace function get_pcm_tree(source account_type) returns setof account_type
as
$_$
declare
	i account_type;
	e account_type;
begin
	for i in select pcm_val from tmp_pcmn where pcm_val_parent=source
	loop
		return next i;
		for e in select get_pcm_tree from get_pcm_tree(i)
		loop
			return next e;	
		end loop;

	end loop;
	return;
end;
$_$
language plpgsql;