insert into menu_ref(me_code,me_menu,me_file,me_description,me_type) 
values ('CSV:AncGrandLivre','Impression Grand-Livre',null,null,'PR');
CREATE TABLE profile_menu (
    pm_id integer NOT NULL,
    me_code text,
    me_code_dep text,
    p_id integer,
    p_order integer,
    p_type_display text NOT NULL,
    pm_default integer
);

insert into profile_menu(me_code,me_code_dep,p_id,pm_default)
values ( 'CSV:AncGrandLivre', NULL, 1 , 'P', 0);

