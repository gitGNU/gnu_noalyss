create or replace function do_insert() returns void
as
$$
declare
    nCount integer;
begin
    select count(*) into nCount from menu_ref where me_file='contact.inc.php';
    if nCount = 0 then
        insert into menu_ref(ME_CODE,me_menu,me_file,me_description,me_type) values ('CONTACT','Contact','contact.inc.php','Liste des contacts','ME');
    end if;
end;
$$
language plpgsql;

select do_insert();

drop function do_insert();