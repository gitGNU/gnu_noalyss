update attr_def set ad_text='Compte bancaire' where ad_id=3;
ALTER TABLE mod_payment  DROP CONSTRAINT mod_payment_mp_fd_id_fkey ;
ALTER TABLE mod_payment  ADD CONSTRAINT mod_payment_mp_fd_id_fkey FOREIGN KEY (mp_fd_id)      REFERENCES fiche_def (fd_id) MATCH SIMPLE      ON UPDATE cascade ON DELETE cascade;
ALTER TABLE mod_payment  DROP CONSTRAINT mod_payment_mp_jrn_def_id_fkey ;
ALTER TABLE mod_payment  ADD CONSTRAINT mod_payment_mp_jrn_def_id_fkey FOREIGN KEY (mp_jrn_def_id)      REFERENCES jrn_def (jrn_def_id) MATCH SIMPLE ON UPDATE 	CASCADE ON DELETE CASCADE;
