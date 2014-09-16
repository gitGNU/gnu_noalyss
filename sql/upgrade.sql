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

INSERT INTO menu_ref(me_code, me_menu, me_file,   me_type,me_description_etendue)VALUES ('ANCKEY', 'Clef de répartition',  'anc_key.inc.php','ME','Permet de gèrer les clefs de répartition en comptabilité analytique');

insert into profile_menu(me_code,p_id,p_type_display,pm_default,me_code_dep,p_order) values ('ANCKEY',1,'E',0,'ANC',15);
insert into profile_menu(me_code,p_id,p_type_display,pm_default,me_code_dep,p_order) values ('ANCKEY',2,'E',0,'ANC',15);

create table key_distribution (
    kd_id serial primary key,
    kd_name text,
    kd_description text);

create table key_distribution_ledger (
    kl_id serial primary key,
    kd_id bigint not null references key_distribution(kd_id) on update cascade on delete cascade,
    jrn_def_id bigint not null references jrn_def(jrn_def_id) on update cascade on delete cascade
    );

create table key_distribution_detail(
    ke_id serial primary key,
    kd_id bigint not null references key_distribution(kd_id) on update cascade on delete cascade,
    ke_row  integer not null,
    ke_percent numeric(20,4) not null 

    );

create table key_distribution_activity
(
    ka_id serial primary key,
    ke_id  bigint not null  references key_distribution_detail(ke_id) on update cascade on delete cascade,
    po_id bigint  references poste_analytique(po_id) on update cascade on delete cascade,
    pa_id bigint not null references plan_analytique(pa_id) on update cascade on delete cascade
);

comment on table key_distribution is 'Distribution key for analytic';
comment on table key_distribution_ledger is 'Legder where the distribution key can be used' ;
comment on table key_distribution_detail is 'Row of activity and percent';
comment on table key_distribution_activity is 'activity (account) linked to the row';
