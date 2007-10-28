
SET client_encoding = 'LATIN1';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;


SELECT pg_catalog.setval('action_gestion_ag_id_seq', 1, false);



SELECT pg_catalog.setval('bilan_b_id_seq', 5, true);



SELECT pg_catalog.setval('document_d_id_seq', 1, false);



SELECT pg_catalog.setval('document_modele_md_id_seq', 1, false);



SELECT pg_catalog.setval('document_seq', 1, false);



SELECT pg_catalog.setval('document_state_s_id_seq', 3, true);



SELECT pg_catalog.setval('document_type_dt_id_seq', 10, false);



SELECT pg_catalog.setval('historique_analytique_ha_id_seq', 1, false);



SELECT pg_catalog.setval('s_jnt_id', 60, true);



SELECT pg_catalog.setval('op_def_op_seq', 7, true);



SELECT pg_catalog.setval('op_predef_detail_opd_id_seq', 16, true);



SELECT pg_catalog.setval('s_oa_group', 4, true);



SELECT pg_catalog.setval('plan_analytique_pa_id_seq', 1, true);



SELECT pg_catalog.setval('poste_analytique_po_id_seq', 1, true);



SELECT pg_catalog.setval('s_attr_def', 27, true);



SELECT pg_catalog.setval('s_cbc', 1, false);



SELECT pg_catalog.setval('s_central', 5, true);



SELECT pg_catalog.setval('s_central_order', 12, true);



SELECT pg_catalog.setval('s_centralized', 1, false);



SELECT pg_catalog.setval('s_currency', 1, true);



SELECT pg_catalog.setval('s_fdef', 7, true);



SELECT pg_catalog.setval('s_fiche', 43, true);



SELECT pg_catalog.setval('s_fiche_def_ref', 16, true);



SELECT pg_catalog.setval('s_form', 1, true);



SELECT pg_catalog.setval('s_formdef', 1, true);



SELECT pg_catalog.setval('s_grpt', 6, true);



SELECT pg_catalog.setval('s_idef', 1, false);



SELECT pg_catalog.setval('s_internal', 5, true);



SELECT pg_catalog.setval('s_invoice', 1, false);



SELECT pg_catalog.setval('s_isup', 1, false);



SELECT pg_catalog.setval('s_jnt_fic_att_value', 76, true);



SELECT pg_catalog.setval('s_jrn', 1, false);



SELECT pg_catalog.setval('s_jrn_1', 1, false);



SELECT pg_catalog.setval('s_jrn_2', 1, false);



SELECT pg_catalog.setval('s_jrn_3', 1, false);



SELECT pg_catalog.setval('s_jrn_4', 1, false);



SELECT pg_catalog.setval('s_jrn_def', 5, false);



SELECT pg_catalog.setval('s_jrn_op', 1, false);



SELECT pg_catalog.setval('s_jrn_rapt', 1, true);



SELECT pg_catalog.setval('s_jrnaction', 5, true);



SELECT pg_catalog.setval('s_jrnx', 1, false);



SELECT pg_catalog.setval('s_periode', 91, true);



SELECT pg_catalog.setval('s_quantity', 2, true);



SELECT pg_catalog.setval('s_stock_goods', 1, false);



SELECT pg_catalog.setval('s_user_act', 1, false);



SELECT pg_catalog.setval('s_user_jrn', 4, true);



SELECT pg_catalog.setval('seq_doc_type_1', 1, false);



SELECT pg_catalog.setval('seq_doc_type_2', 1, false);



SELECT pg_catalog.setval('seq_doc_type_3', 1, false);



SELECT pg_catalog.setval('seq_doc_type_4', 1, false);



SELECT pg_catalog.setval('seq_doc_type_5', 1, false);



SELECT pg_catalog.setval('seq_doc_type_6', 1, false);



SELECT pg_catalog.setval('seq_doc_type_7', 1, false);



SELECT pg_catalog.setval('seq_doc_type_8', 1, false);



SELECT pg_catalog.setval('seq_doc_type_9', 1, false);



INSERT INTO "action" (ac_id, ac_description) VALUES (4, 'Impression');
INSERT INTO "action" (ac_id, ac_description) VALUES (6, 'Mise à jour Plan Comptable');
INSERT INTO "action" (ac_id, ac_description) VALUES (7, 'Gestion Journaux');
INSERT INTO "action" (ac_id, ac_description) VALUES (8, 'Paramètres');
INSERT INTO "action" (ac_id, ac_description) VALUES (10, 'Centralise');
INSERT INTO "action" (ac_id, ac_description) VALUES (16, 'Voir le stock');
INSERT INTO "action" (ac_id, ac_description) VALUES (17, 'Modifie le stock');
INSERT INTO "action" (ac_id, ac_description) VALUES (20, 'Voir la balance des comptes');
INSERT INTO "action" (ac_id, ac_description) VALUES (21, 'Import et export des écritures d''ouverture');
INSERT INTO "action" (ac_id, ac_description) VALUES (28, 'Module Suivi Document');
INSERT INTO "action" (ac_id, ac_description) VALUES (22, 'Module Client');
INSERT INTO "action" (ac_id, ac_description) VALUES (24, 'Module Fournisseur');
INSERT INTO "action" (ac_id, ac_description) VALUES (26, 'Module Administration');
INSERT INTO "action" (ac_id, ac_description) VALUES (30, 'Module Gestion');
INSERT INTO "action" (ac_id, ac_description) VALUES (1, 'Lecture du Grand-Livre');
INSERT INTO "action" (ac_id, ac_description) VALUES (31, 'Gestion des périodes comptables');
INSERT INTO "action" (ac_id, ac_description) VALUES (3, 'Lecture des fiches');
INSERT INTO "action" (ac_id, ac_description) VALUES (15, 'Ajout de fiche et modification');
INSERT INTO "action" (ac_id, ac_description) VALUES (5, 'Création et modifications des rapports');
INSERT INTO "action" (ac_id, ac_description) VALUES (50, 'Definir les Plans Analytiques et les postes');
INSERT INTO "action" (ac_id, ac_description) VALUES (51, 'Impression CA');
INSERT INTO "action" (ac_id, ac_description) VALUES (52, 'Operations Diverses CA');






INSERT INTO attr_def (ad_id, ad_text) VALUES (1, 'Nom');
INSERT INTO attr_def (ad_id, ad_text) VALUES (2, 'Taux TVA');
INSERT INTO attr_def (ad_id, ad_text) VALUES (3, 'Numéro de compte');
INSERT INTO attr_def (ad_id, ad_text) VALUES (4, 'Nom de la banque');
INSERT INTO attr_def (ad_id, ad_text) VALUES (5, 'Poste Comptable');
INSERT INTO attr_def (ad_id, ad_text) VALUES (6, 'Prix vente');
INSERT INTO attr_def (ad_id, ad_text) VALUES (7, 'Prix achat');
INSERT INTO attr_def (ad_id, ad_text) VALUES (8, 'Durée Amortissement');
INSERT INTO attr_def (ad_id, ad_text) VALUES (9, 'Description');
INSERT INTO attr_def (ad_id, ad_text) VALUES (10, 'Date début');
INSERT INTO attr_def (ad_id, ad_text) VALUES (11, 'Montant initial');
INSERT INTO attr_def (ad_id, ad_text) VALUES (12, 'Personne de contact ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (13, 'numéro de tva ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (14, 'Adresse ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (16, 'pays ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (17, 'téléphone ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (18, 'email ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (19, 'Gestion stock');
INSERT INTO attr_def (ad_id, ad_text) VALUES (20, 'Partie fiscalement non déductible');
INSERT INTO attr_def (ad_id, ad_text) VALUES (21, 'TVA non déductible');
INSERT INTO attr_def (ad_id, ad_text) VALUES (22, 'TVA non déductible récupérable par l''impôt');
INSERT INTO attr_def (ad_id, ad_text) VALUES (23, 'Quick Code');
INSERT INTO attr_def (ad_id, ad_text) VALUES (24, 'Ville');
INSERT INTO attr_def (ad_id, ad_text) VALUES (25, 'Société');
INSERT INTO attr_def (ad_id, ad_text) VALUES (26, 'Fax');
INSERT INTO attr_def (ad_id, ad_text) VALUES (27, 'GSM');
INSERT INTO attr_def (ad_id, ad_text) VALUES (15, 'code postal');



INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 2);
INSERT INTO attr_min (frd_id, ad_id) VALUES (2, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (2, 2);
INSERT INTO attr_min (frd_id, ad_id) VALUES (3, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (3, 2);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 3);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 4);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 13);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 14);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 15);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 16);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 17);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 18);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 13);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 14);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 15);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 16);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 17);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 18);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 13);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 14);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 16);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 17);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 18);
INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 6);
INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 7);
INSERT INTO attr_min (frd_id, ad_id) VALUES (2, 6);
INSERT INTO attr_min (frd_id, ad_id) VALUES (2, 7);
INSERT INTO attr_min (frd_id, ad_id) VALUES (3, 7);
INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 19);
INSERT INTO attr_min (frd_id, ad_id) VALUES (2, 19);
INSERT INTO attr_min (frd_id, ad_id) VALUES (14, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 4);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 10);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 4);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 10);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (10, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (10, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (11, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (11, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (12, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (12, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (13, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (13, 9);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 8);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 9);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 10);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 11);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 11);
INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 15);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 15);
INSERT INTO attr_min (frd_id, ad_id) VALUES (15, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (15, 9);
INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (2, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (3, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (10, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (11, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (12, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (13, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (14, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (15, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 24);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 24);
INSERT INTO attr_min (frd_id, ad_id) VALUES (14, 24);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 17);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 18);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 25);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 26);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 27);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 23);






INSERT INTO bilan (b_id, b_name, b_file_template, b_file_form, b_type) VALUES (5, 'Comptes de résultat', 'document/fr_fr/fr_plan_abrege_perso_cr1000.rtf', 'document/fr_fr/fr_plan_abrege_perso_cr1000.form', 'rtf');
INSERT INTO bilan (b_id, b_name, b_file_template, b_file_form, b_type) VALUES (1, 'Bilan français', 'document/fr_fr/fr_plan_abrege_perso_bil10000.ods', 'document/fr_fr/fr_plan_abrege_perso_bil10000.form', 'ods');












INSERT INTO document_state (s_id, s_value) VALUES (1, 'Envoyé');
INSERT INTO document_state (s_id, s_value) VALUES (2, 'Brouillon');
INSERT INTO document_state (s_id, s_value) VALUES (3, 'A envoyer');
INSERT INTO document_state (s_id, s_value) VALUES (4, 'Reçu');



INSERT INTO document_type (dt_id, dt_value) VALUES (1, 'Document Interne');
INSERT INTO document_type (dt_id, dt_value) VALUES (2, 'Bons de commande client');
INSERT INTO document_type (dt_id, dt_value) VALUES (3, 'Bon de commande Fournisseur');
INSERT INTO document_type (dt_id, dt_value) VALUES (4, 'Facture');
INSERT INTO document_type (dt_id, dt_value) VALUES (5, 'Lettre de rappel');
INSERT INTO document_type (dt_id, dt_value) VALUES (6, 'Courrier');
INSERT INTO document_type (dt_id, dt_value) VALUES (7, 'Proposition');
INSERT INTO document_type (dt_id, dt_value) VALUES (8, 'Email');
INSERT INTO document_type (dt_id, dt_value) VALUES (9, 'Divers');






INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (3, 51, 'Banque', true, 4);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (2, 410, 'Client', true, 9);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (5, 60, 'S & B D', true, 3);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (1, 603, 'Marchandises', true, 2);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (4, 400, 'Fournisseur', true, 8);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (6, 701, 'Vente', true, 1);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (7, NULL, 'Personne de contact', true, 16);



INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (7, 'Matériel à amortir', 2400);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (13, 'Dépenses non admises', 674);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (14, 'Administration des Finances', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (15, 'Autres fiches', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (4, 'Banque', 51);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (5, 'Prêt > a un an', 27);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (8, 'Fournisseurs', 400);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (6, 'Prêt < a un an', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (16, 'Contact', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (1, 'Vente Service', 706);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (2, 'Achat Marchandises', 603);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (3, 'Achat Service et biens divers', 60);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (9, 'Clients', 410);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (10, 'Salaire Administrateur', 644);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (11, 'Salaire Ouvrier', 641);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (12, 'Salaire Employé', 641);



INSERT INTO form (fo_id, fo_fr_id, fo_pos, fo_label, fo_formula) VALUES (1, 1, 1, 'Banque', '[5%]');



INSERT INTO format_csv_banque (name, include_file) VALUES ('Dexia', 'dexia_be.inc.php');



INSERT INTO formdef (fr_id, fr_label) VALUES (1, 'Liquidité');












INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (1, 5, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (1, 1, 2);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (1, 2, 3);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (1, 6, 4);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (1, 7, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 5, 6);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 1, 7);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 12, 8);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 13, 9);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 14, 10);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 15, 11);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 16, 12);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 17, 13);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 18, 14);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 5, 15);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 1, 16);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 3, 17);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 4, 18);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 12, 19);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 13, 20);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 14, 21);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 15, 22);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 16, 23);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 17, 24);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 18, 25);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 5, 26);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 1, 27);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 12, 28);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 13, 29);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 14, 30);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 15, 31);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 16, 32);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 17, 33);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 18, 34);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (5, 5, 35);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (5, 1, 36);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (5, 2, 37);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (5, 7, 38);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 5, 39);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 1, 40);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 2, 41);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 6, 42);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 7, 43);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 19, 44);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (5, 23, 45);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 23, 46);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 23, 47);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 23, 48);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 23, 49);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (1, 23, 50);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 24, 51);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 24, 52);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (7, 1, 54);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (7, 17, 55);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (7, 18, 56);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (7, 25, 57);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (7, 26, 58);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (7, 27, 59);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (7, 23, 60);






INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (2, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (4, 'Voir Impayés', 'Voir toutes les factures non payées', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (1, 'Nouvelle', 'Création d''une facture', 'user_jrn.php', 'action=insert_vente&blank', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (10, 'Nouveau', 'Encode un nouvel achat (matériel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (12, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (14, 'Voir Impayés', 'Voir toutes les factures non payées', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (20, 'Nouveau', 'Encode un nouvel achat (matériel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'FIN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (22, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'FIN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (30, 'Nouveau', NULL, 'user_jrn.php', 'action=new&blank', 'FR', 'ODS');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (32, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'ODS');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (40, 'Soldes', 'Voir les soldes des comptes en banques', 'user_jrn.php', 'action=solde', 'FR', 'FIN');



INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (4, 'Opération Diverses', NULL, NULL, NULL, NULL, 5, 5, false, NULL, 'ODS', 'ODS-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (1, 'Financier', '5* ', '5*', '3,2,4', '3,2,4', 5, 5, false, NULL, 'FIN', 'FIN-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (3, 'Achat', '6*', '4*', '5', '4', 1, 3, true, 'échéance', 'ACH', 'ACH-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (2, 'Vente', '4*', '7*', '2', '6', 2, 1, true, 'échéance', 'VEN', 'VEN-01');






INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('FIN', 'Financier');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('VEN', 'Vente');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('ACH', 'Achat');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('ODS', 'Opérations Diverses');



INSERT INTO jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per, j_qcode) VALUES (1, '2007-01-01', 299.0000, 410001, 2, NULL, 2, true, NULL, true, NULL, 'phpcompta', '2007-10-20 15:26:27.256503', 79, 'C1');
INSERT INTO jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per, j_qcode) VALUES (2, '2007-01-01', 250.0000, 701002, 2, NULL, 2, false, NULL, true, NULL, 'phpcompta', '2007-10-20 15:26:27.256503', 79, 'VS2');
INSERT INTO jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per, j_qcode) VALUES (3, '2007-01-01', 49.0000, 445, 2, NULL, 2, false, NULL, true, NULL, 'phpcompta', '2007-10-20 15:26:27.256503', 79, NULL);
INSERT INTO jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per, j_qcode) VALUES (4, '2007-01-01', 200.0000, 60001, 3, NULL, 3, true, NULL, true, NULL, 'phpcompta', '2007-10-20 15:27:40.208594', 79, 'SDB1');
INSERT INTO jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per, j_qcode) VALUES (5, '2007-01-01', 39.2000, 445, 3, NULL, 3, true, NULL, true, NULL, 'phpcompta', '2007-10-20 15:27:40.208594', 79, NULL);
INSERT INTO jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per, j_qcode) VALUES (6, '2007-01-01', 239.2000, 400001, 3, NULL, 3, false, NULL, true, NULL, 'phpcompta', '2007-10-20 15:27:40.208594', 79, 'F1');
INSERT INTO jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per, j_qcode) VALUES (7, '2007-01-01', 299.0000, 51001, 4, NULL, 1, true, NULL, true, NULL, 'phpcompta', '2007-10-20 15:29:13.874993', 79, 'BQ1');
INSERT INTO jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per, j_qcode) VALUES (8, '2007-01-01', 299.0000, 410001, 4, NULL, 1, false, NULL, true, NULL, 'phpcompta', '2007-10-20 15:29:13.874993', 79, 'C1');
INSERT INTO jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per, j_qcode) VALUES (9, '2007-01-01', 300.0000, 101, 5, NULL, 4, true, NULL, true, NULL, 'phpcompta', '2007-10-20 15:34:35.839868', 79, NULL);
INSERT INTO jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per, j_qcode) VALUES (10, '2007-01-01', 300.0000, 455, 5, NULL, 4, false, NULL, true, NULL, 'phpcompta', '2007-10-20 15:34:35.839868', 79, NULL);
INSERT INTO jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per, j_qcode) VALUES (11, '2007-01-01', 300.0000, 51001, 6, NULL, 1, true, NULL, true, NULL, 'phpcompta', '2007-10-20 15:35:49.136592', 79, 'BQ1');
INSERT INTO jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per, j_qcode) VALUES (12, '2007-01-01', 300.0000, 58, 6, NULL, 1, false, NULL, true, NULL, 'phpcompta', '2007-10-20 15:35:49.136592', 79, NULL);



INSERT INTO op_predef (od_id, jrn_def_id, od_name, od_item, od_jrn_type, od_direct) VALUES (2, 2, 'VEN1', 2, 'VEN', false);
INSERT INTO op_predef (od_id, jrn_def_id, od_name, od_item, od_jrn_type, od_direct) VALUES (4, 3, 'ACH3', 1, 'ACH', false);
INSERT INTO op_predef (od_id, jrn_def_id, od_name, od_item, od_jrn_type, od_direct) VALUES (6, 1, '', 5, 'FIN', false);



INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (1, 2, 'c1', NULL, NULL, NULL, true, NULL, NULL, NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (2, 2, 'vs2', 250.0000, 101, 1.0000, false, NULL, NULL, NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (3, 2, '', 0.0000, -1, 1.0000, false, NULL, NULL, NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (4, 4, 'f1', NULL, NULL, NULL, false, NULL, NULL, NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (5, 4, 'sdb1', 200.0000, 101, 1.0000, true, 0.0000, NULL, NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (6, 6, 'bq1', NULL, NULL, NULL, true, NULL, NULL, NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (7, 6, 'c1', 299.0000, NULL, NULL, false, NULL, '', NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (8, 6, '', 0.0000, NULL, NULL, false, NULL, '', NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (9, 6, '', 0.0000, NULL, NULL, false, NULL, '', NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (10, 6, '', 0.0000, NULL, NULL, false, NULL, '', NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (11, 6, '', 0.0000, NULL, NULL, false, NULL, '', NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (12, 7, '101', 300.0000, NULL, NULL, true, NULL, NULL, NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (13, 7, '455', 300.0000, NULL, NULL, false, NULL, NULL, NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (14, 7, '', 0.0000, NULL, NULL, true, NULL, NULL, NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (15, 7, '', 0.0000, NULL, NULL, true, NULL, NULL, NULL);
INSERT INTO op_predef_detail (opd_id, od_id, opd_poste, opd_amount, opd_tva_id, opd_quantity, opd_debit, opd_tva_amount, opd_comment, opd_qc) VALUES (16, 7, '', 0.0000, NULL, NULL, true, NULL, NULL, NULL);






INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_NAME', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_CP', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_COMMUNE', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_TVA', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_STREET', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_NUMBER', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_TEL', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_PAYS', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_FAX', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_ANALYTIC', 'nu');
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_COUNTRY', 'fr');



INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('DNA', '6740', 'Dépense non déductible');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('CUSTOMER', '400', 'Poste comptable de base pour les clients');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('COMPTE_TVA', '451', 'TVA à payer');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('BANQUE', '550', 'Poste comptable de base pour les banques');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('VIREMENT_INTERNE', '58', 'Poste Comptable pour les virements internes');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('COMPTE_COURANT', '56', 'Poste comptable pour le compte courant');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('CAISSE', '57', 'Poste comptable pour la caisse');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('TVA_DNA', '6740', 'Tva non déductible s');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('TVA_DED_IMPOT', '619000', 'Tva déductible par l''impôt');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('VENTE', '70', 'Poste comptable de base pour les ventes');



INSERT INTO parm_money (pm_id, pm_code, pm_rate) VALUES (1, 'EUR', 1.0000);



INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (80, '2007-02-01', '2007-02-28', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (81, '2007-03-01', '2007-03-31', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (82, '2007-04-01', '2007-04-30', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (83, '2007-05-01', '2007-05-31', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (84, '2007-06-01', '2007-06-30', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (85, '2007-07-01', '2007-07-31', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (86, '2007-08-01', '2007-08-31', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (87, '2007-09-01', '2007-09-30', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (88, '2007-10-01', '2007-10-31', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (89, '2007-11-01', '2007-11-30', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (90, '2007-12-01', '2007-12-30', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (91, '2007-12-31', '2007-12-31', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (79, '2007-01-01', '2007-01-31', '2007', false, true);



INSERT INTO plan_analytique (pa_id, pa_name, pa_description) VALUES (1, 'TEST1', '');



INSERT INTO poste_analytique (po_id, po_name, pa_id, po_amount, po_description) VALUES (1, 'POSTE1', 1, 0.0000, '');












INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (1, 'comptes de capitaux', 0, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (101, 'Capital', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (105, 'Ecarts de réévaluation', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (1061, 'Réserve légale', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (1063, 'Réserves statutaires ou contractuelles', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (1064, 'Réserves réglementées', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (1068, 'Autres réserves', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (108, 'Compte de l''exploitant', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (12, 'résultat de l''exercice (bénéfice ou perte)', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (145, 'Amortissements dérogatoires', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (146, 'Provision spéciale de réévaluation', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (147, 'Plus-values réinvesties', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (148, 'Autres provisions réglementées', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (15, 'Provisions pour risques et charges', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (16, 'emprunts et dettes assimilees', 1, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (2, 'comptes d''immobilisations', 0, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (20, 'immobilisations incorporelles', 2, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (201, 'Frais d''établissement', 20, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (206, 'Droit au bail', 20, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (207, 'Fonds commercial', 20, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (208, 'Autres immobilisations incorporelles', 20, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (21, 'immobilisations corporelles', 2, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (23, 'immobilisations en cours', 2, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (27, 'autres immobilisations financieres', 2, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (280, 'Amortissements des immobilisations incorporelles', 2, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (281, 'Amortissements des immobilisations corporelles', 2, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (290, 'Provisions pour dépréciation des immobilisations incorporelles', 2, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (291, 'Provisions pour dépréciation des immobilisations corporelles (même ventilation que celle du compte 21)', 2, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (297, 'Provisions pour dépréciation des autres immobilisations financières', 2, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (3, 'comptes de stocks et en cours', 0, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (31, 'matieres premières (et fournitures)', 3, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (32, 'autres approvisionnements', 3, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (33, 'en-cours de production de biens', 3, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (34, 'en-cours de production de services', 3, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (35, 'stocks de produits', 3, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (37, 'stocks de marchandises', 3, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (391, 'Provisions pour dépréciation des matières premières (et fournitures)', 3, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (392, 'Provisions pour dépréciation des autres approvisionnements', 3, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (393, 'Provisions pour dépréciation des en-cours de production de biens', 3, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (394, 'Provisions pour dépréciation des en-cours de production de services', 3, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (395, 'Provisions pour dépréciation des stocks de produits', 3, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (397, 'Provisions pour dépréciation des stocks de marchandises', 3, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (4, 'comptes de tiers', 0, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (400, 'Fournisseurs et Comptes rattachés', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (409, 'Fournisseurs débiteurs', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (410, 'Clients et Comptes rattachés', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (419, 'Clients créditeurs', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (421, 'Personnel - Rémunérations dues', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (428, 'Personnel - Charges à payer et produits à recevoir', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (43, 'Sécurité sociale et autres organismes sociaux', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (444, 'Etat - Impôts sur les bénéfices', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (445, 'Etat - Taxes sur le chiffre d''affaires', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (447, 'Autres impôts, taxes et versements assimilés', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (45, 'Groupe et associes', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (455, 'Associés - Comptes courants', 45, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (46, 'Débiteurs divers et créditeurs divers', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (47, 'comptes transitoires ou d''attente', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (481, 'Charges à répartir sur plusieurs exercices', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (486, 'Charges constatées d''avance', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (487, 'Produits constatés d''avance', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (491, 'Provisions pour dépréciation des comptes de clients', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (496, 'Provisions pour dépréciation des comptes de débiteurs divers', 4, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (5, 'comptes financiers', 0, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (50, 'valeurs mobilières de placement', 5, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (51, 'banques, établissements financiers et assimilés', 5, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (53, 'Caisse', 5, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (54, 'régies d''avance et accréditifs', 5, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (58, 'virements internes', 5, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (590, 'Provisions pour dépréciation des valeurs mobilières de placement', 5, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (6, 'comptes de charges', 0, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (60, 'Achats (sauf 603)', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (603, 'variations des stocks (approvisionnements et marchandises)', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (61, 'autres charges externes - Services extérieurs', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (62, 'autres charges externes - Autres services extérieurs', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (63, 'Impôts, taxes et versements assimiles', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (641, 'Rémunérations du personnel', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (644, 'Rémunération du travail de l''exploitant', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (645, 'Charges de sécurité sociale et de prévoyance', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (646, 'Cotisations sociales personnelles de l''exploitant', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (65, 'Autres charges de gestion courante', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (66, 'Charges financières', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (67, 'Charges exceptionnelles', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (681, 'Dotations aux amortissements et aux provisions - Charges d''exploitation', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (686, 'Dotations aux amortissements et aux provisions - Charges financières', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (687, 'Dotations aux amortissements et aux provisions - Charges exceptionnelles', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (691, 'Participation des salariés aux résultats', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (695, 'Impôts sur les bénéfices', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (697, 'Imposition forfaitaire annuelle des sociétés', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (699, 'Produits - Reports en arrière des déficits', 6, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (7, 'comptes de produits', 0, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (701, 'Ventes de produits finis', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (706, 'Prestations de services', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (707, 'Ventes de marchandises', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (708, 'Produits des activités annexes', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (709, 'Rabais, remises et ristournes accordés par l''entreprise', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (713, 'Variation des stocks (en-cours de production, produits)', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (72, 'Production immobilisée', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (73, 'Produits nets partiels sur opérations à long terme', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (74, 'Subventions d''exploitation', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (75, 'Autres produits de gestion courante', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (753, 'Jetons de présence et rémunérations d''administrateurs, gérants,...', 75, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (754, 'Ristournes perçues des coopératives (provenant des excédents)', 75, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (755, 'Quotes-parts de résultat sur opérations faites en commun', 75, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (76, 'Produits financiers', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (77, 'Produits exceptionnels', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (781, 'Reprises sur amortissements et provisions (à inscrire dans les produits d''exploitation)', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (786, 'Reprises sur provisions pour risques (à inscrire dans les produits financiers)', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (787, 'Reprises sur provisions (à inscrire dans les produits exceptionnels)', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (79, 'Transferts de charges', 7, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (8, 'Comptes spéciaux', 0, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (9, 'Comptes analytiques', 0, NULL, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (410001, 'client1', 410, NULL, NULL);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (410002, 'client2', 410, NULL, NULL);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (701001, 'Vente Service', 701, NULL, NULL);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (701002, 'Vente Service 2', 701, NULL, NULL);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (400001, 'fournisseur1', 400, NULL, NULL);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (60001, 'sdb1', 60, NULL, NULL);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, fr_country, pcm_country) VALUES (51001, 'Banque', 51, NULL, NULL);



INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (101, 'TVAFranceNormal', 0.1960, 'TVA 19,6% - France métropolitaine - Taux normal', '445661,44571');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (102, 'TVAFranceRéduit', 0.0550, 'TVA 5,5% - France métropolitaine - Taux réduit', '445662,44572');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (103, 'TVAFranceSuperRéduit', 0.0210, 'TVA 2,1% - France métropolitaine - Taux super réduit', '445663,44573');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (104, 'TVAFranceImmos', 0.1960, 'TVA 19,6% - France métropolitaine - Taux immobilisations', '44562,0');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (105, 'TVAFranceAnciens', 0.0000, 'TVA x% - France métropolitaine - Taux anciens', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (201, 'TVADomNormal', 0.0850, 'TVA 8,5%  - DOM - Taux normal', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (202, 'TVADomNPR', 0.0850, 'TVA 8,5% - DOM - Taux normal NPR', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (203, 'TVADomRéduit', 0.0210, 'TVA 2,1% - DOM - Taux réduit', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (204, 'TVADom-I', 0.0175, 'TVA 1,75% - DOM - Taux I', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (205, 'TVADomPresse', 0.0105, 'TVA 1,05% - DOM - Taux publications de presse', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (206, 'TVADomOctroi', 0.0000, 'TVA x% - DOM - Taux octroi de mer', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (207, 'TVADomImmos', 0.0000, 'TVA x% - DOM - Taux immobilisations', '445,0');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (301, 'TVACorse-I', 0.1300, 'TVA 13% - Corse - Taux I', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (302, 'TVACorse-II', 0.0800, 'TVA 8% - Corse - Taux II', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (303, 'TVACorse-III', 0.0210, 'TVA 2,1% - Corse - Taux III', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (304, 'TVACorse-IV', 0.0090, 'TVA 0,9% - Corse - Taux IV', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (305, 'TVACorseImmos', 0.0000, 'TVA x% - Corse - Taux immobilisations', '445,0');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (401, 'TVAacquisIntracom', 0.0000, 'TVA x% - Acquisitions intracommunautaires/Pays', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (402, 'TVAacquisIntracomImmos', 0.0000, 'TVA x% - Acquisitions intracommunautaires immobilisations/Pays', '445,0');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (501, 'TVAfranchise', 0.0000, 'TVA x% - Non imposable : Achats en franchise', '');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (502, 'TVAexport', 0.0000, 'TVA x% - Non imposable : Exports hors CE/Pays', '');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (503, 'TVAautres', 0.0000, 'TVA x% - Non imposable : Autres opérations', '');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (504, 'TVAlivrIntracom', 0.0000, 'TVA x% - Non imposable : Livraisons intracommunautaires/Pays', '');



INSERT INTO user_local_pref (user_id, parameter_type, parameter_value) VALUES ('phpcompta', 'PERIODE', '79');






INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (1, 'phpcompta', 4, '');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (2, 'phpcompta', 1, '');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (3, 'phpcompta', 3, '');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (4, 'phpcompta', 2, '');



INSERT INTO version (val) VALUES (34);



