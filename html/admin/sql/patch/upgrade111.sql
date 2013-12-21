begin;

insert into menu_ref(me_code,me_menu,me_file, me_url,me_description,me_parameter,me_javascript,me_type,me_description_etendue)
values
('MANAGER','Administrateur','manager.inc.php',null,'Suivi des gérants, administrateurs et salariés',null,null,'ME','Suivi de vos salariés, managers ainsi que des administrateurs, pour les documents et les opérations comptables');

insert into profile_menu (me_code,me_code_dep,p_id,p_order, p_type_display,pm_default) 
values
('MANAGER','GESTION',1,25,'E',0), ('MANAGER','GESTION',2,25,'E',0);

update menu_ref set me_file='customer.inc.php' where me_code ='CUST';

update version set val=112;

commit;