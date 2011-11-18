alter table tva_rate add tva_both_side integer ;
alter table tva_rate alter tva_both_side set default 0;
update tva_rate set tva_both_side=0;