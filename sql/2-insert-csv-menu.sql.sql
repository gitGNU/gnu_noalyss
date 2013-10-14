insert into menu_ref(me_code,me_menu,me_file, me_url,me_description,me_parameter,me_javascript,me_type,me_description_etendue)
values
('CSV:Reconciliation','Export opérations rapprochées','export_rec_csv.php',null,'Export opérations rapprochées en CSV',null,null,'PR','');

insert into profile_menu (me_code,me_code_dep,p_id,p_order, p_type_display,pm_default) 
values
('CSV:Reconciliation',null,1,0,'P',0), ('CSV:Reconciliation',null,2,null,'P',0);
