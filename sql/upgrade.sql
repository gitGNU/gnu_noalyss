begin;
alter  table action_gestion add column ag_ref_ag_id int4;
alter table action_gestion add column f_id_dest int4;
alter table action_gestion add column f_id_exp int4;

update  action_gestion set f_id_dest=f_id;
update  action_gestion set f_id_exp=0;

alter table action_gestion drop column f_id;

alter table action_gestion alter f_id_dest set not null;
alter table action_gestion alter f_id_exp set not null;

commit;