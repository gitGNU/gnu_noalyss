begin;

set search_path to public,comptaproc;
alter table centralized DROP CONSTRAINT "$2";
alter table jrnx DROP CONSTRAINT "$1";

DROP VIEW vw_client ;
DROP VIEW vw_fiche_attr ;
DROP VIEW vw_fiche_def ;
DROP VIEW vw_fiche_min ;
DROP VIEW vw_poste_qcode ;
DROP VIEW vw_supplier ;

create domain account_type varchar(40);
alter table tmp_pcmn alter pcm_val_parent  type account_type;
alter table tmp_pcmn alter pcm_val  type account_type;
alter table centralized alter c_poste  type account_type;
alter table del_jrnx alter j_poste  type account_type;
alter table fiche_def alter fd_class_base  type account_type;
alter table jrnx alter j_poste  type account_type;
alter table parm_poste alter p_value  type account_type;

CREATE OR REPLACE VIEW vw_client AS 
 SELECT a.f_id, a.av_text AS name, a1.av_text AS quick_code, b.av_text AS tva_num, c.av_text AS poste_comptable, d.av_text AS rue, e.av_text AS code_postal, f.av_text AS pays, g.av_text AS telephone, h.av_text AS email
   FROM ( SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text
           FROM fiche
      JOIN fiche_def USING (fd_id)
   JOIN fiche_def_ref USING (frd_id)
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
  WHERE jnt_fic_att_value.ad_id = 1) a
   JOIN ( SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text
           FROM fiche
      JOIN fiche_def USING (fd_id)
   JOIN fiche_def_ref USING (frd_id)
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
  WHERE jnt_fic_att_value.ad_id = 13) b USING (f_id)
   JOIN ( SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text
      FROM fiche
   JOIN fiche_def USING (fd_id)
   JOIN fiche_def_ref USING (frd_id)
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
  WHERE jnt_fic_att_value.ad_id = 23) a1 USING (f_id)
   JOIN ( SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text
   FROM fiche
   JOIN fiche_def USING (fd_id)
   JOIN fiche_def_ref USING (frd_id)
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
  WHERE jnt_fic_att_value.ad_id = 5) c USING (f_id)
   JOIN ( SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text
   FROM fiche
   JOIN fiche_def USING (fd_id)
   JOIN fiche_def_ref USING (frd_id)
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
  WHERE jnt_fic_att_value.ad_id = 14) d USING (f_id)
   JOIN ( SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text
   FROM fiche
   JOIN fiche_def USING (fd_id)
   JOIN fiche_def_ref USING (frd_id)
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
  WHERE jnt_fic_att_value.ad_id = 15) e USING (f_id)
   JOIN ( SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text
   FROM fiche
   JOIN fiche_def USING (fd_id)
   JOIN fiche_def_ref USING (frd_id)
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
  WHERE jnt_fic_att_value.ad_id = 16) f USING (f_id)
   JOIN ( SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text
   FROM fiche
   JOIN fiche_def USING (fd_id)
   JOIN fiche_def_ref USING (frd_id)
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
  WHERE jnt_fic_att_value.ad_id = 17) g USING (f_id)
   LEFT JOIN ( SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text
   FROM fiche
   JOIN fiche_def USING (fd_id)
   JOIN fiche_def_ref USING (frd_id)
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
  WHERE jnt_fic_att_value.ad_id = 18) h USING (f_id)
  WHERE a.frd_id = 9;


CREATE OR REPLACE VIEW vw_fiche_attr AS 
 SELECT a.f_id, a.fd_id, a.av_text AS vw_name, b.av_text AS vw_sell, c.av_text AS vw_buy, d.av_text AS tva_code, tva_rate.tva_id, tva_rate.tva_rate, tva_rate.tva_label, e.av_text AS vw_addr, f.av_text AS vw_cp, j.av_text AS quick_code, h.av_text AS vw_description, i.av_text AS tva_num, fiche_def.frd_id
   FROM ( SELECT fiche.f_id, fiche.fd_id, attr_value.av_text
           FROM fiche
      JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
   JOIN attr_def USING (ad_id)
  WHERE jnt_fic_att_value.ad_id = 1) a
   LEFT JOIN ( SELECT fiche.f_id, attr_value.av_text
           FROM fiche
      JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
   JOIN attr_def USING (ad_id)
  WHERE jnt_fic_att_value.ad_id = 6) b ON a.f_id = b.f_id
   LEFT JOIN ( SELECT fiche.f_id, attr_value.av_text
      FROM fiche
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
   JOIN attr_def USING (ad_id)
  WHERE jnt_fic_att_value.ad_id = 7) c ON a.f_id = c.f_id
   LEFT JOIN ( SELECT fiche.f_id, attr_value.av_text
   FROM fiche
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
   JOIN attr_def USING (ad_id)
  WHERE jnt_fic_att_value.ad_id = 2) d ON a.f_id = d.f_id
   LEFT JOIN ( SELECT fiche.f_id, attr_value.av_text
   FROM fiche
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
   JOIN attr_def USING (ad_id)
  WHERE jnt_fic_att_value.ad_id = 14) e ON a.f_id = e.f_id
   LEFT JOIN ( SELECT fiche.f_id, attr_value.av_text
   FROM fiche
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
   JOIN attr_def USING (ad_id)
  WHERE jnt_fic_att_value.ad_id = 15) f ON a.f_id = f.f_id
   LEFT JOIN ( SELECT fiche.f_id, attr_value.av_text
   FROM fiche
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
   JOIN attr_def USING (ad_id)
  WHERE jnt_fic_att_value.ad_id = 23) j ON a.f_id = j.f_id
   LEFT JOIN ( SELECT fiche.f_id, attr_value.av_text
   FROM fiche
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
   JOIN attr_def USING (ad_id)
  WHERE jnt_fic_att_value.ad_id = 9) h ON a.f_id = h.f_id
   LEFT JOIN ( SELECT fiche.f_id, attr_value.av_text
   FROM fiche
   JOIN jnt_fic_att_value USING (f_id)
   JOIN attr_value USING (jft_id)
   JOIN attr_def USING (ad_id)
  WHERE jnt_fic_att_value.ad_id = 13) i ON a.f_id = i.f_id
   LEFT JOIN tva_rate ON d.av_text = tva_rate.tva_id::text
   JOIN fiche_def USING (fd_id);

CREATE OR REPLACE VIEW vw_fiche_def AS 
 SELECT jnt_fic_attr.fd_id, jnt_fic_attr.ad_id, attr_def.ad_text, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def.frd_id
   FROM fiche_def
   JOIN jnt_fic_attr USING (fd_id)
   JOIN attr_def ON attr_def.ad_id = jnt_fic_attr.ad_id;

ALTER TABLE vw_fiche_def OWNER TO trunk;
COMMENT ON VIEW vw_fiche_def IS 'all the attributs for  card family';


CREATE OR REPLACE VIEW vw_fiche_min AS 
 SELECT attr_min.frd_id, attr_min.ad_id, attr_def.ad_text, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base
   FROM attr_min
   JOIN attr_def USING (ad_id)
   JOIN fiche_def_ref USING (frd_id);

CREATE OR REPLACE VIEW vw_poste_qcode AS 
 SELECT a.f_id, a.av_text AS j_poste, b.av_text AS j_qcode
   FROM ( SELECT jnt_fic_att_value.f_id, attr_value.av_text
           FROM attr_value
      JOIN jnt_fic_att_value USING (jft_id)
     WHERE jnt_fic_att_value.ad_id = 5) a
   JOIN ( SELECT jnt_fic_att_value.f_id, attr_value.av_text
           FROM attr_value
      JOIN jnt_fic_att_value USING (jft_id)
     WHERE jnt_fic_att_value.ad_id = 23) b USING (f_id);

CREATE OR REPLACE VIEW vw_poste_qcode AS 
 SELECT a.f_id, a.av_text AS j_poste, b.av_text AS j_qcode
   FROM ( SELECT jnt_fic_att_value.f_id, attr_value.av_text
           FROM attr_value
      JOIN jnt_fic_att_value USING (jft_id)
     WHERE jnt_fic_att_value.ad_id = 5) a
   JOIN ( SELECT jnt_fic_att_value.f_id, attr_value.av_text
           FROM attr_value
      JOIN jnt_fic_att_value USING (jft_id)
     WHERE jnt_fic_att_value.ad_id = 23) b USING (f_id);

alter table centralized add CONSTRAINT fk_pcmn_val foreign key (c_poste) references tmp_pcmn (pcm_val);
alter table jrnx add CONSTRAINT fk_pcmn_val foreign key (j_poste) references tmp_pcmn (pcm_val);


commit;
select 'ERREUR MAUVAISE VERSION' from version where val!=71;