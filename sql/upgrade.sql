begin;
alter table jnt_fic_attr add column jnt_order int;
update jnt_fic_attr set jnt_order = 1;
alter table jnt_fic_attr alter jnt_order set not null;
commit;