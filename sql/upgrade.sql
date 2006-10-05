alter table import_tmp add jr_rapt text;
create unique index fd_id_ad_id_x on jnt_fic_attr( fd_id,ad_id);
