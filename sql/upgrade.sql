insert into fiche_def_ref(frd_id,frd_text) values (26,'Projet');
insert into attr_min (frd_id,ad_id) values (26,1),(26,9);
CREATE OR REPLACE FUNCTION public.upgrade_repo(p_version integer)
 RETURNS void
AS $function$
declare 
        is_mono integer;
begin
        select count (*) into is_mono from information_schema.tables where table_name='repo_version';
        if is_mono = 1 then
                update repo_version set val=p_version;
        else
                update version set val=p_version;
        end if;
end;
$function$
 language plpgsql;

-- bug 
alter table action_gestion alter ag_title type text;
