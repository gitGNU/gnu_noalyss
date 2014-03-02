begin;
update theme set the_name='Mandarine' ,the_filestyle='style-mandarine.css' where the_name='Colored';
update theme set the_name='Mobile' ,the_filestyle='style-mobile.css' where the_name='EPad';
update user_global_pref set parameter_value='Mandarine' where parameter_value='Colored';
update user_global_pref set parameter_value='Mobile' where parameter_value='EPad';
update theme set the_filestyle='style-classic.css' where the_filestyle='style.css';
update version set val=15;
commit;
