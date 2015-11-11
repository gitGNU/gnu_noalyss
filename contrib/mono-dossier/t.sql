create or replace function insert_menu() 
returns void as
$BODY$
declare 
    n_count integer := 0;
begin
    select count(*) into n_count from menu_ref where me_code='CONTACT';
    if n_count = 0 then
        insert into menu_ref(me_code,
                    me_file,
                    me_menu,
                    me_description,me_type,me_description_etendue)
        values     
        ('CONTACT',
        'contact.inc.php',
          'Contact','Liste de vos contacts','ME','Liste de vos contacts normalement liée à des fiches de sociétés');
    end if;
    
    select count(*) into n_count from profile_menu where me_code='CONTACT' and p_id=1;
    if n_count = 0 then
        insert into profile_menu(me_code,me_code_dep,p_id,p_order,p_type_display,pm_default,pm_id_dep) select 'CONTACT','GESTION',1,22,'E',0,(select pm_id from profile_menu where me_code='GESTION' and p_id=1);
    end if;

    select count(*) into n_count from profile_menu where me_code='CONTACT' and p_id=2;
    if n_count = 0 then
        insert into profile_menu(me_code,me_code_dep,p_id,p_order,p_type_display,pm_default,pm_id_dep) select 'CONTACT','GESTION',2,22,'E',0,(select pm_id from profile_menu where me_code='GESTION' and p_id=2);
    end if;
end;
$BODY$
language plpgsql;

select insert_menu();
