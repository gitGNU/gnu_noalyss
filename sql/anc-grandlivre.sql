insert into menu_ref(me_code,me_menu,me_file,me_description,me_type) 
values ('CSV:AncGrandLivre','Impression Grand-Livre',null,null,'PR');

insert into profile_menu(me_code,me_code_dep,p_id,p_type_display,pm_default)
values ( 'CSV:AncGrandLivre', NULL, 1 , 'P', 0);

