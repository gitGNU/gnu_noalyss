begin;

insert into tva_rate values (5,'0%',0, 'Pas soumis à la TVA',null);

update fiche_def_ref set frd_class_base=2400 where frd_id=7;

update  version set val=8;
-- banque n'a pas de gestion stock
delete from jnt_fic_attr where fd_id=1 and ad_id=19;
-- client n'a pas de gestion stock
delete from jnt_fic_attr where fd_id=2 and ad_id=19;
-- default periode for phpcompta
 update user_pref set pref_periode=40 where pref_user='phpcompta';
-- version 8
update version set val=8;
commit;
