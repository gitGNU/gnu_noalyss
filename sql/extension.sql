insert into menu_ref(me_code,me_menu,me_file,me_description,me_type,me_parameter) select ex_code,ex_name,ex_file,ex_desC,'PL','plugin_code='||ex_code from extension;

insert into profile_menu (me_code,me_code_dep,p_id,p_type_display) select me_code,'EXTENSION',1,'S' from menu_ref where me_type='PL';

