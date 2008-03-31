create sequence s_tva start with 1000;
 alter table tva_rate alter tva_id set default nextval('s_tva');
 alter table form drop constraint "$1";
 alter table form add constraint   formdef_fk foreign key (fo_fr_id) references formdef(fr_id) on update cascade on delete cascade;
