begin;
update attr_def set ad_text='Compte bancaire' where ad_id=3;
ALTER TABLE mod_payment  DROP CONSTRAINT mod_payment_mp_fd_id_fkey ;
ALTER TABLE mod_payment  ADD CONSTRAINT mod_payment_mp_fd_id_fkey FOREIGN KEY (mp_fd_id)      REFERENCES fiche_def (fd_id) MATCH SIMPLE      ON UPDATE cascade ON DELETE cascade;
ALTER TABLE mod_payment  DROP CONSTRAINT mod_payment_mp_jrn_def_id_fkey ;
ALTER TABLE mod_payment  ADD CONSTRAINT mod_payment_mp_jrn_def_id_fkey FOREIGN KEY (mp_jrn_def_id)      REFERENCES jrn_def (jrn_def_id) MATCH SIMPLE ON UPDATE 	CASCADE ON DELETE CASCADE;
ALTER TABLE fiche_def ADD COLUMN fd_description text;
update fiche_def set fd_description='Achats de marchandises' where fd_id=1;
update fiche_def set fd_description='Catégorie qui contient la liste des clients' where fd_id=2;
update fiche_def set fd_description='Catégorie qui contient la liste des comptes financiers: banque, caisse,...' where fd_id=3;
update fiche_def set fd_description='Catégorie qui contient la liste des fournisseurs' where fd_id=4;
update fiche_def set fd_label='Services & Biens Divers',fd_description='Catégorie qui contient la liste des charges diverses' where fd_id=5;
update fiche_def set fd_description='Catégorie qui contient la liste des prestations, marchandises... que l''on vend ' where fd_id=6;
update version set val=107;

commit;

