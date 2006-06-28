drop function action_gestion_replace_newline() cascade;


create or replace function action_gestion_replace_newline () 
returns trigger
as
$body$
declare 
	modified action_gestion%rowtype;
	begin
	modified:=NEW;
	modified.ag_comment:=replace(modified.ag_comment,'%0A','');
	modified.ag_comment:=replace(modified.ag_comment,'%0D','');
	return modified;
end;
$body$ language plpgsql;


create  trigger tr_ag_comment_replace  before insert or update on action_gestion
for each row execute procedure action_gestion_replace_newline();
