create sequence s_tva start with 1000;
 alter table tva_rate alter tva_id set default nextval('s_tva');