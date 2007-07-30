--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Name: action_gestion_ag_id_seq; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('action_gestion', 'ag_id'), 1, false);


--
-- Name: document_d_id_seq; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('document', 'd_id'), 1, false);


--
-- Name: document_modele_md_id_seq; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('document_modele', 'md_id'), 1, false);


--
-- Name: document_seq; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('document_seq', 1, false);


--
-- Name: document_state_s_id_seq; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('document_state', 's_id'), 3, true);


--
-- Name: document_type_dt_id_seq; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('document_type', 'dt_id'), 10, false);


--
-- Name: s_jnt_id; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jnt_id', 53, true);


--
-- Name: s_attr_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_attr_def', 27, true);


--
-- Name: s_cbc; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_cbc', 1, false);


--
-- Name: s_central; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_central', 1, false);


--
-- Name: s_central_order; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_central_order', 1, false);


--
-- Name: s_centralized; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_centralized', 1, false);


--
-- Name: s_currency; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_currency', 1, true);


--
-- Name: s_fdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_fdef', 6, true);


--
-- Name: s_fiche; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_fiche', 30, true);


--
-- Name: s_fiche_def_ref; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_fiche_def_ref', 16, true);


--
-- Name: s_form; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_form', 1, false);


--
-- Name: s_formdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_formdef', 1, false);


--
-- Name: s_grpt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_grpt', 1, true);


--
-- Name: s_idef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_idef', 1, false);


--
-- Name: s_internal; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_internal', 1, false);


--
-- Name: s_invoice; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_invoice', 1, false);


--
-- Name: s_isup; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_isup', 1, false);


--
-- Name: s_jnt_fic_att_value; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jnt_fic_att_value', 1, false);


--
-- Name: s_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn', 1, false);


--
-- Name: s_jrn_1; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_1', 1, false);


--
-- Name: s_jrn_2; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_2', 1, false);


--
-- Name: s_jrn_3; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_3', 1, false);


--
-- Name: s_jrn_4; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_4', 1, false);


--
-- Name: s_jrn_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_def', 5, false);


--
-- Name: s_jrn_op; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_op', 1, false);


--
-- Name: s_jrn_rapt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_rapt', 1, false);


--
-- Name: s_jrnaction; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrnaction', 5, true);


--
-- Name: s_jrnx; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrnx', 1, false);


--
-- Name: s_periode; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_periode', 78, true);


--
-- Name: s_quantity; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_quantity', 1, false);


--
-- Name: s_stock_goods; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_stock_goods', 1, false);


--
-- Name: s_user_act; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_user_act', 1, false);


--
-- Name: s_user_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_user_jrn', 4, true);


--
-- Name: seq_doc_type_1; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('seq_doc_type_1', 1, false);


--
-- Name: seq_doc_type_2; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('seq_doc_type_2', 1, false);


--
-- Name: seq_doc_type_3; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('seq_doc_type_3', 1, false);


--
-- Name: seq_doc_type_4; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('seq_doc_type_4', 1, false);


--
-- Name: seq_doc_type_5; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('seq_doc_type_5', 1, false);


--
-- Name: seq_doc_type_6; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('seq_doc_type_6', 1, false);


--
-- Name: seq_doc_type_7; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('seq_doc_type_7', 1, false);


--
-- Name: seq_doc_type_8; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('seq_doc_type_8', 1, false);


--
-- Name: seq_doc_type_9; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('seq_doc_type_9', 1, false);


--
-- Data for Name: action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO "action" (ac_id, ac_description) VALUES (1, 'Journaux');
INSERT INTO "action" (ac_id, ac_description) VALUES (2, 'Facturation');
INSERT INTO "action" (ac_id, ac_description) VALUES (4, 'Impression');
INSERT INTO "action" (ac_id, ac_description) VALUES (5, 'Formulaire');
INSERT INTO "action" (ac_id, ac_description) VALUES (6, 'Mise à jour Plan Comptable');
INSERT INTO "action" (ac_id, ac_description) VALUES (7, 'Gestion Journaux');
INSERT INTO "action" (ac_id, ac_description) VALUES (8, 'Paramètres');
INSERT INTO "action" (ac_id, ac_description) VALUES (9, 'Sécurité');
INSERT INTO "action" (ac_id, ac_description) VALUES (10, 'Centralise');
INSERT INTO "action" (ac_id, ac_description) VALUES (3, 'Fiche Read');
INSERT INTO "action" (ac_id, ac_description) VALUES (16, 'Voir le stock');
INSERT INTO "action" (ac_id, ac_description) VALUES (17, 'Modifie le stock');
INSERT INTO "action" (ac_id, ac_description) VALUES (15, 'Fiche écriture');
INSERT INTO "action" (ac_id, ac_description) VALUES (18, 'Devise');
INSERT INTO "action" (ac_id, ac_description) VALUES (19, 'Période');
INSERT INTO "action" (ac_id, ac_description) VALUES (20, 'Voir la balance des comptes');
INSERT INTO "action" (ac_id, ac_description) VALUES (21, 'Import et export des écritures d''ouverture');
INSERT INTO "action" (ac_id, ac_description) VALUES (28, 'Module Suivi Document');
INSERT INTO "action" (ac_id, ac_description) VALUES (22, 'Module Client');
INSERT INTO "action" (ac_id, ac_description) VALUES (24, 'Module Fournisseur');
INSERT INTO "action" (ac_id, ac_description) VALUES (26, 'Module Administration');
INSERT INTO "action" (ac_id, ac_description) VALUES (30, 'Module Gestion');


--
-- Data for Name: action_gestion; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: attr_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

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


--
-- Data for Name: attr_min; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

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


--
-- Data for Name: attr_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: centralized; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: document; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: document_modele; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: document_state; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO document_state (s_id, s_value) VALUES (1, 'Envoyé');
INSERT INTO document_state (s_id, s_value) VALUES (2, 'Brouillon');
INSERT INTO document_state (s_id, s_value) VALUES (3, 'A envoyer');
INSERT INTO document_state (s_id, s_value) VALUES (4, 'Reçu');


--
-- Data for Name: document_type; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO document_type (dt_id, dt_value) VALUES (1, 'Document Interne');
INSERT INTO document_type (dt_id, dt_value) VALUES (2, 'Bons de commande client');
INSERT INTO document_type (dt_id, dt_value) VALUES (3, 'Bon de commande Fournisseur');
INSERT INTO document_type (dt_id, dt_value) VALUES (4, 'Facture');
INSERT INTO document_type (dt_id, dt_value) VALUES (5, 'Lettre de rappel');
INSERT INTO document_type (dt_id, dt_value) VALUES (6, 'Courrier');
INSERT INTO document_type (dt_id, dt_value) VALUES (7, 'Proposition');
INSERT INTO document_type (dt_id, dt_value) VALUES (8, 'Email');
INSERT INTO document_type (dt_id, dt_value) VALUES (9, 'Divers');


--
-- Data for Name: fiche; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: fiche_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (5, 61, 'S & B D', true, 3);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (6, 700, 'Vente', true, 1);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (2, 411, 'Client', true, 9);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (4, 401, 'Fournisseur', true, 8);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (3, 51, 'Banque', true, 4);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (1, 607, 'Marchandises', true, 2);


--
-- Data for Name: fiche_def_ref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (7, 'Matériel à amortir', 2400);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (13, 'Dépenses non admises', 674);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (14, 'Administration des Finances', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (15, 'Autres fiches', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (1, 'Vente Service', 70);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (2, 'Achat Marchandises', 607);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (3, 'Achat Service et biens divers', 61);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (4, 'Banque', 51);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (5, 'Prêt > a un an', 27);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (8, 'Fournisseurs', 400);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (9, 'Clients', 411);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (10, 'Salaire Administrateur', 6411);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (11, 'Salaire Ouvrier', 6411);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (12, 'Salaire Employé', 6411);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (6, 'Prêt < a un an', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (16, 'Contact', NULL);


--
-- Data for Name: form; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: format_csv_banque; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO format_csv_banque (name, include_file) VALUES ('Dexia', 'dexia_be.inc.php');


--
-- Data for Name: formdef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: import_tmp; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: invoice; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: jnt_fic_att_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: jnt_fic_attr; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

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


--
-- Data for Name: jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: jrn_action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

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


--
-- Data for Name: jrn_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (4, 'Opération Diverses', NULL, NULL, NULL, NULL, 5, 5, false, NULL, 'ODS', 'ODS-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (1, 'Financier', '5* ', '5*', '3,2,4', '3,2,4', 5, 5, false, NULL, 'FIN', 'FIN-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (3, 'Achat', '6*', '4*', '5', '4', 1, 3, true, 'échéance', 'ACH', 'ACH-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (2, 'Vente', '4*', '7*', '2', '6', 2, 1, true, 'échéance', 'VEN', 'VEN-01');


--
-- Data for Name: jrn_rapt; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: jrn_type; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('FIN', 'Financier');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('VEN', 'Vente');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('ACH', 'Achat');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('ODS', 'Opérations Diverses');


--
-- Data for Name: jrnx; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: parameter; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_NAME', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_CP', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_COMMUNE', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_TVA', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_STREET', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_NUMBER', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_TEL', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_PAYS', NULL);
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_FAX', NULL);


--
-- Data for Name: parm_code; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

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


--
-- Data for Name: parm_money; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO parm_money (pm_id, pm_code, pm_rate) VALUES (1, 'EUR', 1.0000);


--
-- Data for Name: parm_periode; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (53, '2005-01-01', '2005-01-31', '2005', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (54, '2005-02-01', '2005-02-28', '2005', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (55, '2005-03-01', '2005-03-31', '2005', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (56, '2005-04-01', '2005-04-30', '2005', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (57, '2005-05-01', '2005-05-31', '2005', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (58, '2005-06-01', '2005-06-30', '2005', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (59, '2005-07-01', '2005-07-31', '2005', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (60, '2005-08-01', '2005-08-31', '2005', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (61, '2005-09-01', '2005-09-30', '2005', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (62, '2005-10-01', '2005-10-31', '2005', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (63, '2005-11-01', '2005-11-30', '2005', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (64, '2005-12-01', '2005-12-31', '2005', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (65, '2005-12-31', '2005-12-31', '2005', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (66, '2006-01-01', '2006-01-31', '2006', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (67, '2006-02-01', '2006-02-28', '2006', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (68, '2006-03-01', '2006-03-31', '2006', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (69, '2006-04-01', '2006-04-30', '2006', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (70, '2006-05-01', '2006-05-31', '2006', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (71, '2006-06-01', '2006-06-30', '2006', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (72, '2006-07-01', '2006-07-31', '2006', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (73, '2006-08-01', '2006-08-31', '2006', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (74, '2006-09-01', '2006-09-30', '2006', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (75, '2006-10-01', '2006-10-31', '2006', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (76, '2006-11-01', '2006-11-30', '2006', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (77, '2006-12-01', '2006-12-31', '2006', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (78, '2006-12-31', '2006-12-31', '2006', false, false);


--
-- Data for Name: quant_sold; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: stock_goods; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: tmp_pcmn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1, 'comptes de capitaux', 0, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10, 'capital et réserves', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (101, 'Capital', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1011, 'Capital souscrit - non appelé', 101, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1012, 'Capital souscrit - appelé, non versé', 101, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1013, 'Capital souscrit - appelé, versé', 101, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10131, 'Capital non amorti', 1013, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10132, 'Capital amorti', 1013, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1018, 'Capital souscrit soumis à des réglementations particulières', 101, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (104, 'Primes liées au capital social', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1041, 'Primes d''émission', 104, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1042, 'Primes de fusion', 104, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1043, 'Primes d''apport', 104, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1044, 'Primes de conversion d''obligations en actions', 104, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1045, 'Bons de souscription d''actions', 104, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (105, 'Ecarts de réévaluation', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1051, 'Réserve spéciale de réévaluation', 105, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1052, 'Ecart de réévaluation libre', 105, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1053, 'Réserve de réévaluation', 105, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1055, 'Ecarts de rééavaluation (autres opérations légales)', 105, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1057, 'Autres écarts de réévaluation en France', 105, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1058, 'Autres écarts de réévaluation à l''Etranger', 105, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (106, 'Réserves', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1061, 'Réserve légale', 106, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10611, 'Réserve légale proprement dite', 1061, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10612, 'Plus-values nettes à long terme', 1061, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1062, 'Réserves indisponibles', 106, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1063, 'Réserves statutaires ou contractuelles', 106, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1064, 'Réserves réglementées', 106, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10641, 'Plus-values nettes à long terme', 1064, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10643, 'Réserves consécutives à l''octroi de subventions d''investissement', 1064, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10648, 'Autres réserves réglementées', 1064, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1068, 'Autres réserves', 106, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10681, 'Réserve de propre assureur', 1068, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10688, 'Réserves diverses', 1068, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (107, 'Ecart d''équivalence', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (108, 'Compte de l''exploitant', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (109, 'Actionnaires : Capital souscritnon appelé', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (11, 'report a nouveau (solde créditeur ou débiteur)', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (110, 'Report à nouveau (solde créditeur)', 11, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (119, 'Report à nouveau (solde débiteur)', 11, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (12, 'résultat de l''exercice (bénéfice ou perte)', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (120, 'Résultat de l''exercice (bénéfice)', 12, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (129, 'Résultat de l''exercice (perte)', 12, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13, 'subventions d''investissement', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (131, 'Subventions d''équipement', 13, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1311, 'Etat', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1312, 'Régions', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1313, 'Départements', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1314, 'Communes', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1315, 'Collectivités publiques', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1316, 'Entreprises publiques', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1317, 'Entreprises et organismes privés', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1318, 'Autres', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (138, 'Autres subventions d''investissement (même ventilation que celle du compte 131)', 13, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (139, 'Subventions d''investissement inscrites au compte de résultat', 13, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1391, 'Subventions d''équipement', 139, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13911, 'Etat', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13912, 'Régions', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13913, 'Départements', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13914, 'Communes', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13915, 'Collectivités publiques', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13916, 'Entreprises publiques', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13917, 'Entreprises et organismes privés', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13918, 'Autres', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1398, 'Autres subventions d''investissement (même ventilation que celle du compte 1391)', 139, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (14, 'provisions reglementees', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (142, 'Provisions réglementées relatives aux immobilisations', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1423, 'Provisions pour reconstitution des gisements miniers et pétroliers', 142, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1424, 'Provisions pour investissement (participation des salariés)', 142, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (143, 'Provisions réglementées relatives aux stocks', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1431, 'Hausse des prix', 143, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1432, 'Fluctuation des cours', 143, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (144, 'Provisions réglementées relatives aux autres éléments de l''actif', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (145, 'Amortissements dérogatoires', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (146, 'Provision spéciale de réévaluation', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (147, 'Plus-values réinvesties', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (148, 'Autres provisions réglementées', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (15, 'Provisions pour risques et charges', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (151, 'Provisions pour risques', 15, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1511, 'Provisions pour litiges', 151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1512, 'Provisions pour garanties données aux clients', 151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1513, 'Provisions pour pertes sur marchés à terme', 151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1514, 'Provisions pour amendes et pénalités', 151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1515, 'Provisions pour pertes de change', 151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1518, 'Autres provisions pour risques', 151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (153, 'Provisions pour pensions et obligations similaires', 15, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (155, 'Provisions pour impôts', 15, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (156, 'Provisions pour renouvellement des immobilisations (entreprises concessionnaires)', 15, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (157, 'Provisions pour charges à répartir sur plusieurs exercices', 15, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1572, 'Provisions pour grosses réparations', 157, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (158, 'Autres provisions pour charges', 15, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1582, 'Provisions pour charges sociales et fiscales sur congés à payer', 158, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16, 'Emprunts et dettes assimilees', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (161, 'Emprunts obligataires convertibles', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (163, 'Autres emprunts obligataires', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (164, 'Emprunts auprès des établissements de crédit', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (165, 'Dépôts et cautionnements reçus', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1651, 'Dépôts', 165, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1655, 'Cautionnements', 165, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (166, 'Participation des salariés aux résultats', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1661, 'Comptes bloqués', 166, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1662, 'Fonds de participation', 166, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (167, 'Emprunts et dettes assortis de conditions particulières', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1671, 'Emissions de titres participatifs', 167, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1674, 'Avances conditionnées de l''Etat', 167, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1675, 'Emprunts participatifs', 167, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (168, 'Autres emprunts et dettes assimilées', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1681, 'Autres emprunts', 168, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1685, 'Rentes viagères capitalisées', 168, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1687, 'Autres dettes', 168, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1688, 'Intérêts courus', 168, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16881, 'Sur emprunts obligataires convertibles', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16883, 'Sur autres emprunts obligataires', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16884, 'Sur emprunts auprès des établissements de crédit', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16885, 'Sur dépôts et cautionnements reçus', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16886, 'Sur participation des salariés aux résultats', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16887, 'Sur emprunts et dettes assortis de conditions particulières', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16888, 'Sur autres emprunts et dettes assimilées', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (169, 'Primes de remboursement des obligations', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (17, 'dettes rattachées a des participations', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (171, 'Dettes rattachées à des participations (groupe)', 17, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (174, 'Dettes rattachées à des participations (hors groupe)', 17, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (178, 'Dettes rattachées à des sociétés en participation', 17, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1781, 'Principal', 178, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1788, 'Intérêts courus', 178, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (18, 'comptes de liaison des établissements et societes en participation', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (181, 'Comptes de liaison des établissements', 18, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (186, 'Biens et prestations de services échangés entre établissements (charges)', 18, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (187, 'Biens et prestations de services échangés entre établissements (produits)', 18, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (188, 'Comptes de liaison des sociétés en participation', 18, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2, 'comptes d''immobilisations', 0, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (20, 'immobilisations incorporelles', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (201, 'Frais d''établissement', 20, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2011, 'Frais de constitution', 201, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2012, 'Frais de premier établissement', 201, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (20121, 'Frais de prospection', 2012, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (20122, 'Frais de publicité', 2012, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2013, 'Frais d''augmentation de capital et d''opérations diverses (fusions, scissions, transformations)', 201, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (203, 'Frais de recherche et de développement', 20, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (205, 'Concessions et droits similaires, brevets, licences, marques, procédés, logiciels, droits et valeurs similaires', 20, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (206, 'Droit au bail', 20, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (207, 'Fonds commercial', 20, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (208, 'Autres immobilisations incorporelles', 20, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21, 'Immobilisations corporelles', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (211, 'Terrains', 21, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2111, 'Terrains nus', 211, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2112, 'Terrains aménagés', 211, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2113, 'Sous-sols et sur-sols', 211, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2114, 'Terrains de gisement', 211, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21141, 'Carrières', 2114, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2115, 'Terrains bâtis', 211, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21151, 'Ensembles immobiliers industriels (A, B...)', 2115, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21155, 'Ensembles immobiliers administratifs et commerciaux (A, B...)', 2115, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21158, 'Autres ensembles immobiliers', 2115, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (211581, 'Affectés aux opérations professionnelles (A, B...)', 21158, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (211588, 'Affectés aux opérations non professionnelles (A, B...)', 21158, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2116, 'Compte d''ordre sur immobilisations (art. 6 du décret n° 78-737 du 11 juillet 1978)', 211, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (212, 'Agencements et aménagements de terrains (même ventilation que celle du compte 211)', 21, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (213, 'Constructions', 21, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2131, 'Bâtiments', 213, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21311, 'Ensembles immobiliers industriels (A, B...)', 2131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21315, 'Ensembles immobiliers administratifs et commerciaux (A, B...)', 2131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21318, 'Autres ensembles immobiliers', 2131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (213181, 'affectés aux opérations professionnelles (A, B...)', 21318, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (213188, 'affectés aux opérations non professionnelles (A, B...)', 21318, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2135, 'Installations généralesagencementsaménagements des constructions (même ventilation que celle du compte 2131)', 213, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2138, 'Ouvrages d''infrastructure', 213, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21381, 'Voies de terre', 2138, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21382, 'Voies de fer', 2138, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21383, 'Voies d''eau', 2138, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21384, 'Barrages', 2138, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21385, 'Pistes d''aérodromes', 2138, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (214, 'Constructions sur sol d''autrui (même ventilation que celle du compte 213)', 21, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (215, 'Installations techniques, matériels et outillage industriels', 21, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2151, 'Installations complexes spécialisées', 215, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21511, 'sur sol propre', 2151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21514, 'sur sol d''autrui', 2151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2153, 'Installations à caractère spécifique', 215, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21531, 'sur sol propre', 2153, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21534, 'sur sol d''autrui', 2153, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2154, 'Matériel industriel', 215, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2155, 'Outillage industriel', 215, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2157, 'Agencements et aménagements du matériel et outillage industriels', 215, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (218, 'Autres immobilisations corporelles', 21, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2181, 'Installations générales, agencements, aménagements divers', 218, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2182, 'Matériel de transport', 218, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2183, 'Matériel de bureau et matériel informatique', 218, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2184, 'Mobilier', 218, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2185, 'Cheptel', 218, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2186, 'Emballages récupérables', 218, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (22, 'immobilisations mises en concession', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (23, 'immobilisations en cours', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (231, 'Immobilisations corporelles en cours', 23, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2312, 'Terrains', 231, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2313, 'Constructions', 231, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2315, 'Installations techniques, matériel et outillage industriels', 231, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2318, 'Autres immobilisations corporelles', 231, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (232, 'Immobilisations incorporelles en cours', 23, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (237, 'Avances et acomptes versés sur immobilisations incorporelles', 23, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (238, 'Avances et acomptes versés sur commandes d''immobilisations corporelles', 23, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2382, 'Terrains', 238, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2383, 'Constructions', 238, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2385, 'Installations techniques, matériel et outillage industriels', 238, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2388, 'Autres immobilisations corporelles', 238, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (25, 'Parts dans des entreprises liées et créances sur des entreprises liées', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (26, 'Participations et créances rattachées à des participations', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (261, 'Titres de participation', 26, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2611, 'Actions', 261, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2618, 'Autres titres', 261, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (266, 'Autres formes de participation', 26, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (267, 'Créances rattachées à des participations', 26, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2671, 'Créances rattachées à des participations (groupe)', 267, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2674, 'Créances rattachées à des participations (hors groupe)', 267, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2675, 'Versements représentatifs d''apports non capitalisés (appel de fonds)', 267, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2676, 'Avances consolidables', 267, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2677, 'Autres créances rattachées à des participations', 267, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2678, 'Intérêts courus', 267, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (268, 'Créances rattachées à des sociétés en participation', 26, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2681, 'Principal', 268, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2688, 'Intérêts courus', 268, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (269, 'Versements restant à effectuer sur titres de participation non libérés', 26, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (27, 'autres immobilisations financieres', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (271, 'Titres immobilisés autres que les titres immobilisés de l''activité de portefeuille (droit de propriété)', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2711, 'Actions', 271, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2718, 'Autres titres', 271, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (272, 'Titres immobilisés (droit de créance)', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2721, 'Obligations', 272, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2722, 'Bons', 272, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (273, 'Titres immobilisés de l''activité de portefeuille', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (274, 'Prêts', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2741, 'Prêts participatifs', 274, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2742, 'Prêts aux associés', 274, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2743, 'Prêts au personnel', 274, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2748, 'Autres prêts', 274, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (275, 'Dépôts et cautionnements versés', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2751, 'Dépôts', 275, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2755, 'Cautionnements', 275, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (276, 'Autres créances immobilisées', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2761, 'Créances diverses', 276, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2768, 'Intérêts courus', 276, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (27682, 'Sur titres immobilisés (droit de créance)', 2768, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (27684, 'Sur prêts', 2768, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (27685, 'Sur dépôts et cautionnements', 2768, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (27688, 'Sur créances diverses', 2768, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (277, '(Actions propres ou parts propres)', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2771, 'Actions propres ou parts propres', 277, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2772, 'Actions propres ou parts propres en voie d''annulation', 277, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (279, 'Versements restant à effectuer sur titres immobilisés non libérés', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (28, 'amortissements des immobilisations', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (280, 'Amortissements des immobilisations incorporelles', 28, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2801, 'Frais d''établissement (même ventilation que celle du compte 201)', 280, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2803, 'Frais de recherche et de développement', 280, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2805, 'Concessions et droits similaires, brevets, licences, logiciels, droits et valeurs similaires', 280, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2807, 'Fonds commercial', 280, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2808, 'Autres immobilisations incorporelles', 280, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (281, 'Amortissements des immobilisations corporelles', 28, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2811, 'Terrains de gisement', 281, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2812, 'Agencements, aménagements de terrains (même ventilation que celle du compte 212)', 281, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2813, 'Constructions (même ventilation que celle du compte 213)', 281, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2814, 'Constructions sur sol d''autrui (même ventilation que celle du compte 214)', 281, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2815, 'Installations, matériel et outillage industriels (même ventilation que celle du compte 215)', 281, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2818, 'Autres immobilisations corporelles (même ventilation que celle du compte 218)', 281, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (282, 'Amortissements des immobilisations mises en concession', 28, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (29, 'provisions pour dépréciation des immobilisations', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (290, 'Provisions pour dépréciation des immobilisations incorporelles', 29, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2905, 'Marques, procédés, droits et valeurs similaires', 290, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2906, 'Droit au bail', 290, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2907, 'Fonds commercial', 290, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2908, 'Autres immobilisations incorporelles', 290, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (291, 'Provisions pour dépréciation des immobilisations corporelles (même ventilation que celle du compte 21)', 29, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2911, 'Terrains (autres que terrains de gisement)', 291, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (292, 'Provisions pour dépréciation des immobilisations mises en concession', 292, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (293, 'Provisions pour dépréciation des immobilisations en cours', 29, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2931, 'Immobilisations corporelles en cours', 293, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2932, 'Immobilisations incorporelles en cours', 293, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (296, 'Provisions pour dépréciation des participations et créances rattachées à des participations', 29, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2961, 'Titres de participation', 296, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2966, 'Autres formes de participation', 296, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2967, 'Créances rattachées à des participations (même ventilation que celle du compte 267)', 296, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2968, 'Créances rattachées à des sociétés en participation (même ventilation que celle du compte 268)', 296, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (297, 'Provisions pour dépréciation des autres immobilisations financières', 29, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2971, 'Titres immobilisés autres que les titres immobilisés de l''activité de portefeuille -droit de propriété (même ventilation que celle du compte 271)', 297, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2972, 'Titres immobilisésdroit de créance (même ventilation que celle du compte 272)', 297, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2973, 'Titres immobilisés de l''activité de portefeuille', 297, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2974, 'Prêts (même ventilation que celle du compte 274)', 297, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2975, 'Dépôts et cautionnements versés (même ventilation que celle du compte 275)', 297, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2976, 'Autres créances immobilisées (même ventilation que celle du compte 276)', 297, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3, 'Comptes de stocks et en cours', 0, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (31, 'matieres premières (et fournitures)', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (311, 'Matières (ou groupe) A', 31, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (312, 'Matières (ou groupe) B', 31, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (317, 'Fournitures A, B, C, ...', 31, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (32, 'autres approvisionnements', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (321, 'Matières consommables', 32, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3211, 'Matières (ou groupe) C', 321, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3212, 'Matières (ou groupe) D', 321, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (322, 'Fournitures consommables', 32, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3221, 'Combustibles', 322, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3222, 'Produits d''entretien', 322, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3223, 'Fournitures d''atelier et d''usine', 322, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3224, 'Fournitures de magasin', 322, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3225, 'Fournitures de bureau', 322, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (326, 'Emballages', 32, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3261, 'Emballages perdus', 326, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3265, 'Emballages récupérables non identifiables', 326, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3267, 'Emballages à usage mixte', 326, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (33, 'en-cours de production de biens', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (331, 'Produits en cours', 33, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3311, 'Produits en cours P 1', 331, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3312, 'Produits en cours P 2', 331, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (335, 'Travaux en cours', 33, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3351, 'Travaux en cours T 1', 335, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3352, 'Travaux en cours T 2', 335, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (34, 'en-cours de production de services', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (341, 'Etudes en cours', 34, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3411, 'Etudes en cours E 1', 341, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3412, 'Etudes en cours E 2', 341, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (345, 'Prestations de services en cours', 34, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3451, 'Prestations de services S 1', 345, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3452, 'Prestations de services S 2', 345, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (35, 'stocks de produits', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (351, 'Produits intermédiaires', 35, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3511, 'Produits intermédiaires (ou groupe) A', 351, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3512, 'Produits intermédiaires (ou groupe) B', 351, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (355, 'Produits finis', 35, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3551, 'Produits finis (ou groupe) A', 355, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3552, 'Produits finis (ou groupe) B', 355, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (358, 'Produits résiduels (ou matières de récupération)', 35, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3581, 'Déchets', 358, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3585, 'Rebuts', 358, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3586, 'Matières de récupération', 358, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (36, '(compte à ouvrir, le cas échéant, sous l''intitulé " stocks provenant d''immobilisations ")', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (37, 'stocks de marchandises', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (371, 'Marchandises (ou groupe) A', 37, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (372, 'Marchandises (ou groupe) B', 37, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (38, '(lorsque l''entité tient un inventaire permanent en comptabilité générale, le compte 38 peut être utilisé pour comptabiliser les stocks en voie d''acheminement, mis en dépôt ou donnés en consignation)', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (39, 'provisions pour dépréciation des stocks et en-cours', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (391, 'Provisions pour dépréciation des matières premières (et fournitures)', 39, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3911, 'Matières (ou groupe) A', 391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3912, 'Matières (ou groupe) B', 391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3917, 'Fournitures A, B, C, ...', 391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (392, 'Provisions pour dépréciation des autres approvisionnements', 39, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3921, 'Matières consommables (même ventilation que celle du compte 321)', 392, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3922, 'Fournitures consommables (même ventilation que celle ducompte 322)', 392, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3926, 'Emballages (même ventilation que celle du compte 326)', 392, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (393, 'Provisions pour dépréciation des en-cours de production de biens', 39, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3931, 'Produits en cours (même ventilation que celle du compte 331)', 393, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3935, 'Travaux en cours (même ventilation que celle du compte 335)', 393, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (394, 'Provisions pour dépréciation des en-cours de production de services', 39, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3941, 'Etudes en cours (même ventilation que celle du compte 341)', 394, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3945, 'Prestations de services en cours (même ventilation que celle du compte 345)', 394, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (395, 'Provisions pour dépréciation des stocks de produits', 39, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3951, 'Produits intermédiaires (même ventilation que celle du compte 351)', 395, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3955, 'Produits finis (même ventilation que celle du compte 355)', 395, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (397, 'Provisions pour dépréciation des stocks de marchandises', 39, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3971, 'Marchandise (ou groupe) A', 397, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3972, 'Marchandise (ou groupe) B', 397, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4, 'Classe 4 : comptes de tiers', 0, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (40, 'fournisseurs et comptes rattaches', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (400, 'Fournisseurs et Comptes rattachés', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (401, 'Fournisseurs', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4011, 'FournisseursAchats de biens et prestations de services', 401, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4017, 'FournisseursRetenues de garantie', 401, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (403, 'FournisseursEffets à payer', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (404, 'Fournisseurs d''immobilisations', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4041, 'FournisseursAchats d''immobilisations', 404, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4047, 'Fournisseurs d''immobilisationsRetenues de garantie', 404, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (405, 'Fournisseurs d''immobilisationsEffets à payer', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (408, 'FournisseursFactures non parvenues', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4081, 'Fournisseurs', 408, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4084, 'Fournisseurs d''immobilisations', 408, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4088, 'FournisseursIntérêts courus', 408, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (409, 'Fournisseurs débiteurs', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4091, 'FournisseursAvances et acomptes versés sur commandes', 409, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4096, 'FournisseursCréances pour emballages et matériel à rendre', 409, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4097, 'FournisseursAutres avoirs', 409, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (40971, 'Fournisseurs d''exploitation', 4097, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (40974, 'Fournisseurs d''immobilisations', 4097, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4098, 'Rabais, remises, ristournes à obtenir et autres avoirs non encore reçus', 409, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (41, 'clients et comptes rattaches', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (410, 'Clients et Comptes rattachés', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (411, 'Clients', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4110001, 'Clients 1', 411, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4110002, 'Clients 2', 411, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4110003, 'Clients 3', 411, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4111, 'ClientsVentes de biens ou de prestations de services', 411, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4117, 'ClientsRetenues de garantie', 411, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (413, 'ClientsEffets à recevoir', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (416, 'Clients douteux ou litigieux', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (417, '" Créances " sur travaux non encore facturables', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (418, 'ClientsProduits non encore facturés', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4181, 'ClientsFactures à établir', 418, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4188, 'ClientsIntérêts courus', 418, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (419, 'Clients créditeurs', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4191, 'ClientsAvances et acomptes reçus sur commandes', 419, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4196, 'ClientsDettes sur emballages et matériels consignés', 419, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4197, 'ClientsAutres avoirs', 419, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4198, 'Rabais, remises, ristournes à accorder et autres avoirs à établir', 419, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (42, 'Personnel et comptes rattaches', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (421, 'PersonnelRémunérations dues', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (422, 'Comités d''entreprises, d''établissement,...', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (424, 'Participation des salariés aux résultats', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4246, 'Réserve spéciale (art. L. 442-2 du Code du travail)', 424, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4248, 'Comptes courants', 424, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (425, 'PersonnelAvances et acomptes', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (426, 'PersonnelDépôts', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (427, 'PersonnelOppositions', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (428, 'PersonnelCharges à payer et produits à recevoir', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4282, 'Dettes provisionnées pour congés à payer', 428, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4284, 'Dettes provisionnées pour participation des salariés aux résultats', 428, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4286, 'Autres charges à payer', 428, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4287, 'Produits à recevoir', 428, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (43, 'Sécurité sociale et autres organismes sociaux', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (431, 'Sécurité sociale', 43, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (437, 'Autres organismes sociaux', 43, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (438, 'Organismes sociauxCharges à payer et produits à recevoir', 43, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4382, 'Charges sociales sur congés à payer', 438, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4386, 'Autres charges à payer', 438, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4387, 'Produits à recevoir', 438, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44, 'État et autres collectivités publiques', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (441, 'ÉtatSubventions à recevoir', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4411, 'Subventions d''investissement', 441, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4417, 'Subventions d''exploitation', 441, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4418, 'Subventions d''équilibre', 441, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4419, 'Avances sur subventions', 441, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (442, 'EtatImpôts et taxes recouvrables sur des tiers', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4424, 'Obligataires', 442, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4425, 'Associés', 442, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (443, 'Opérations particulières avec l''Etat les collectivités publiques, les organismes internationaux', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4431, 'Créances sur l''Etat résultant de la suppression de la règle du décalage d''un mois en matière de T.V.A.', 443, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4438, 'Intérêts courus sur créances figurant au 4431', 443, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (444, 'EtatImpôts sur les bénéfices', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (445, 'EtatTaxes sur le chiffre d''affaires', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4452, 'T.V.A. due intracommunautaire', 445, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4455, 'Taxes sur le chiffre d''affaires à décaisser', 445, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44551, 'T.V.A. à décaisser', 4455, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44558, 'Taxes assimilées à la T.V.A.', 4455, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4456, 'Taxes sur le chiffre d''affaires déductibles', 445, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44562, 'T.V.A. sur immobilisations', 446, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44563, 'T.V.A. transférée par d''autres entreprises', 4456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44566, 'T.V.A. sur autres biens et services', 4456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44567, 'Crédit de T.V.A. à reporter', 4456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44568, 'Taxes assimilées à la T.V.A.', 4456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4457, 'Taxes sur le chiffre d''affaires collectées par l''entreprise', 445, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44571, 'T.V.A. collectée', 4457, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44578, 'Taxes assimilées à la T.V.A.', 4457, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4458, 'Taxes sur le chiffre d''affaires à régulariser ou en attente', 445, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44581, 'AcomptesRégime simplifié d''imposition', 4458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44582, 'AcomptesRégime de forfait', 4458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44583, 'Remboursement de taxes sur le chiffre d''affaires demandé', 4458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44584, 'T.V.A. récupérée d''avance', 4458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44586, 'Taxes sur le chiffre d''affaires sur factures non parvenues', 4458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44587, 'Taxes sur le chiffres d''affaires sur factures à établir', 4458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (446, 'Obligations cautionnées', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (447, 'Autres impôts, taxes et versements assimilés', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (448, 'EtatCharges à payer et produits à recevoir', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4482, 'Charges fiscales sur congés à payer', 448, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4486, 'Charges à payer', 448, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4487, 'Produits à recevoir', 448, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (45, 'Groupe et associes', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (451, 'Groupe', 45, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (455, 'AssociésComptes courants', 45, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4551, 'Principal', 455, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4558, 'Intérêts courus', 455, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (456, 'AssociésOpérations sur le capital', 45, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4561, 'AssociésComptes d''apport en société', 456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (45611, 'Apports en nature', 4561, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (45615, 'Apports en numéraire', 4561, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4562, 'ApporteursCapital appelé, non versé', 456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (45621, 'ActionnairesCapital souscrit et appelé, non versé', 4562, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (45625, 'AssociésCapital appelé, non versé', 4562, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4563, 'AssociésVersements reçus sur augmentation de capital', 456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4564, 'AssociésVersements anticipés', 456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4566, 'Actionnaires défaillants', 456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4567, 'AssociésCapital à rembourser', 456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (457, 'AssociésDividendes à payer', 45, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (458, 'AssociésOpérations faites en commun et en G.I.E.', 45, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4581, 'Opérations courantes', 458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4588, 'Intérêts courus', 458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (46, 'Débiteurs divers et créditeurs divers', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (462, 'Créances sur cessions d''immobilisations', 46, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (464, 'Dettes sur acquisitions de valeurs mobilières de placement', 46, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (465, 'Créances sur cessions de valeurs mobilières de placement', 46, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (467, 'Autres comptes débiteurs ou créditeurs', 46, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (468, 'DiversCharges à payer et produits à recevoir', 46, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4686, 'Charges à payer', 468, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4687, 'Produits à recevoir', 468, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (47, 'comptes transitoires ou d''attente', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (471, 'Comptes d''attente', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (472, 'Comptes d''attente', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (473, 'Comptes d''attente', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (474, 'Comptes d''attente', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (475, 'Comptes d''attente', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (476, 'Différence de conversion Actif', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4761, 'Diminution des créances', 476, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4762, 'Augmentation des dettes', 476, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4768, 'Différences compensées par couverture de change', 476, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (477, 'Différences de conversion Passif', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4771, 'Augmentation des créances', 477, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4772, 'Diminution des dettes', 477, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4778, 'Différences compensées par couverture de change', 477, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (478, 'Autres comptes transitoires', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (48, 'comptes de régularisation', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (481, 'Charges à répartir sur plusieurs exercices', 48, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4811, 'Charges différées', 481, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4812, 'Frais d''acquisition des immobilisations', 481, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4816, 'Frais d''émission des emprunts', 481, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4818, 'Charges à étaler', 481, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (486, 'Charges constatées d''avance', 48, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (487, 'Produits constatés d''avance', 48, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (488, 'Comptes de répartition périodique des charges et des produits', 48, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4886, 'Charges', 488, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4887, 'Produits', 488, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (49, 'provisions pour dépréciation des comptes de tiers', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (491, 'Provisions pour dépréciation des comptes de clients', 49, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (495, 'Provisions pour dépréciation des comptes du groupe et des associés', 49, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4951, 'Comptes du groupe', 495, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4955, 'Comptes courants des associés', 495, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4958, 'Opérations faites en commun et en G.I.E.', 495, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (496, 'Provisions pour dépréciation des comptes de débiteurs divers', 49, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4962, 'Créances sur cessions d''immobilisations', 496, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4965, 'Créances sur cessions de valeurs mobilières de placement', 496, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4967, 'Autres comptes débiteurs', 496, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5, 'Comptes financiers', 0, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (50, 'valeurs mobilières de placement', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (501, 'Parts dans des entreprises liées', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (502, 'Actions propres', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (503, 'Actions', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5031, 'Titres cotés', 503, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5035, 'Titres non cotés', 503, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (504, 'Autres titres conférant un droit de propriété', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (505, 'Obligations et bons émis par la société et rachetés par elle', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (506, 'Obligations', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5061, 'Titres cotés', 506, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5065, 'Titres non cotés', 506, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (507, 'Bons du Trésor et bons de caisse à court terme', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (508, 'Autres valeurs mobilières de placement et autres créances assimilées', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5081, 'Autres valeurs mobilières', 508, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5082, 'Bons de souscription', 508, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5088, 'Intérêts courus sur obligations, bons et valeurs assimilés', 508, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (509, 'Versements restant à effectuer sur valeurs mobilières de placement non libérées', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (51, 'banques, établissements financiers et assimilés', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (510002, 'banques 2', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (510001, 'Banque1', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (511, 'Valeurs à l''encaissement', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5111, 'Coupons échus à l''encaissement', 511, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5112, 'Chèques à encaisser', 511, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5113, 'Effets à l''encaissement', 511, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5114, 'Effets à l''escompte', 511, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (512, 'Banques', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5121, 'Comptes en monnaie nationale', 512, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5124, 'Comptes en devises', 512, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (514, 'Chèques postaux', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (515, '" Caisses " du Trésor et des établissements publics', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (516, 'Sociétés de bourse', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (517, 'Autres organismes financiers', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (518, 'Intérêts courus', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5181, 'Intérêts courus à payer', 519, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5188, 'Intérêts courus à recevoir', 519, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (519, 'Concours bancaires courants', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5191, 'Crédit de mobilisation de créances commerciales (CMCC)', 519, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5193, 'Mobilisation de créances nées à l''étranger', 519, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5198, 'Intérêts courus sur concours bancaires courants', 519, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (52, 'Instruments de trésorerie', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (53, 'Caisse', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (531, 'Caisse siège social', 53, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5311, 'Caisse en monnaie nationale', 531, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5314, 'Caisse en devises', 531, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (532, 'Caisse succursale (ou usine) A', 53, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (533, 'Caisse succursale (ou usine) B', 53, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (54, 'régies d''avance et accréditifs', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (58, 'virements internes', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (59, 'provisions pour dépréciation des comptes financiers', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (590, 'Provisions pour dépréciation des valeurs mobilières de placement', 59, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5903, 'Actions', 590, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5904, 'Autres titres conférant un droit de propriété', 590, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5906, 'Obligations', 590, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5908, 'Autres valeurs mobilières de placement et créances assimilées', 590, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6, 'comptes de charges', 0, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60, 'Achats (sauf 603)', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (601, 'Achats stockésMatières premières (et fournitures)', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6011, 'Matières (ou groupe) A', 601, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6012, 'Matières (ou groupe) B', 601, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6017, 'Fournitures A, B, C, ...', 601, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (602, 'Achats stockésAutres approvisionnements', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6021, 'Matières consommables', 602, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60211, 'Matières (ou groupe) C', 6021, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60212, 'Matières (ou groupe) D', 6021, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6022, 'Fournitures consommables', 602, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60221, 'Combustibles', 6022, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60222, 'Produits d''entretien', 6022, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60223, 'Fournitures d''atelier et d''usine', 6022, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60224, 'Fournitures de magasin', 6022, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60225, 'Fourniture de bureau', 6022, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6026, 'Emballages', 602, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60261, 'Emballages perdus', 6026, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60265, ' Emballages récupérables non identifiables', 6026, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60267, 'Emballages à usage mixte', 6026, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (604, 'Achats d''études et prestations de services', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (605, 'Achats de matériel, équipements et travaux', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (606, 'Achats non stockés de matière et fournitures', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6061, 'Fournitures non stockables (eau, énergie, ...)', 606, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6063, 'Fournitures d''entretien et de petit équipement', 606, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6064, 'Fournitures administratives', 606, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6068, 'Autres matières et fournitures', 606, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (607, 'Achats de marchandises', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6071, 'Marchandise (ou groupe) A', 607, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6072, 'Marchandise (ou groupe) B', 607, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (608, '(Compte réservé, le cas échéant, à la récapitulation des frais accessoires incorporés aux achats)', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (609, 'Rabais, remises et ristournes obtenus sur achats', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6091, 'de matières premières (et fournitures)', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6092, 'd''autres approvisionnements stockés', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6094, 'd''études et prestations de services', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6095, 'de matériel, équipements et travaux', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6096, 'd''approvisionnements non stockés', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6097, 'de marchandises', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6098, 'Rabais, remises et ristournes non affectés', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (603, 'variations des stocks (approvisionnements et marchandises)', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6031, 'Variation des stocks de matières premières (et fournitures)', 603, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6032, 'Variation des stocks des autres approvisionnements', 603, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6037, 'Variation des stocks de marchandises', 603, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (61, 'autres charges externes - Services extérieurs', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (611, 'Sous-traitance générale', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (612, 'Redevances de crédit-bail', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6122, 'Crédit-bail mobilier', 612, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6125, 'Crédit-bail immobilier', 612, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (613, 'Locations', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6132, 'Locations immobilières', 613, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6135, 'Locations mobilières', 613, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6136, 'Malis sur emballages', 613, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (614, 'Charges locatives et de copropriété', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (615, 'Entretien et réparations', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6152, 'sur biens immobiliers', 615, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6155, 'sur biens mobiliers', 615, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6156, 'Maintenance', 615, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (616, 'Primes d''assurances', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6161, 'Multirisques', 616, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6162, 'Assurance obligatoire dommage construction', 616, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6163, 'Assurance-transport', 616, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (61636, 'sur achats', 6163, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (61637, 'sur ventes', 6163, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (61638, 'sur autres biens', 6163, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6164, 'Risques d''exploitation', 616, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6165, 'Insolvabilité clients', 616, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (617, 'Etudes et recherches', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (618, 'Divers', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6181, 'Documentation générale', 618, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6183, 'Documentation technique', 618, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6185, 'Frais de colloques, séminaires, conférences', 618, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (619, 'Rabais, remises et ristournes obtenus sur services extérieurs', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (62, 'autres charges externes - Autres services extérieurs', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (621, 'Personnel extérieur à l''entreprise', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6211, 'Personnel intérimaire', 621, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6214, 'Personnel détaché ou prêté à l''entreprise', 621, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (622, 'Rémunérations d''intermédiaires et honoraires', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6221, 'Commissions et courtages sur achats', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6222, 'Commissions et courtages sur ventes', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6224, 'Rémunérations des transitaires', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6225, 'Rémunérations d''affacturage', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6226, 'Honoraires', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6227, 'Frais d''actes et de contentieux', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6228, 'Divers', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (623, 'Publicité, publications, relations publiques', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6231, 'Annonces et insertions', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6232, 'Echantillons', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6233, 'Foires et expositions', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6234, 'Cadeaux à la clientèle', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6235, 'Primes', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6236, 'Catalogues et imprimés', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6237, 'Publications', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6238, 'Divers (pourboires, dont courant, ...)', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (624, 'Transports de biens et transports collectifs du personnel', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6241, 'Transports sur achats', 624, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6242, 'Transports sur ventes', 624, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6243, 'Transports entre établissements ou chantiers', 624, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6244, 'Transports administratifs', 624, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6247, 'Transports collectifs du personnel', 624, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6248, 'Divers', 624, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (625, 'Déplacements, missions et réceptions', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6251, 'Voyages et déplacements', 625, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6255, 'Frais de déménagement', 625, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6256, 'Missions', 625, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6257, 'Réceptions', 625, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (626, 'Frais postaux et de télécommunications', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (627, 'Services bancaires et assimilés', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6271, 'Frais sur titres (achat, vente, garde)', 627, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6272, 'Commissions et frais sur émission d''emprunts', 627, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6275, 'Frais sur effets', 627, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6276, 'Location de coffres', 627, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6278, 'Autres frais et commissions sur prestations de services', 627, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (628, 'Divers', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6281, 'Concours divers (cotisations, ...)', 628, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6284, 'Frais de recrutement de personnel', 628, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (629, 'Rabais, remises et ristournes obtenus sur autres services extérieurs', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63, 'Impôts, taxes et versements assimiles', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (631, 'Impôts, taxes et versements assimilés sur rémunérations (administrations des impôts)', 63, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6311, 'Taxe sur les salaires', 631, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6312, 'Taxe d''apprentissage', 631, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6313, 'Participation des employeurs à la formation professionnelle continue', 631, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6314, 'Cotisation pour défaut d''investissement obligatoire dans la construction', 631, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6318, 'Autres', 631, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (633, 'Impôts, taxes et versements assimilés sur rémunérations (autres organismes)', 63, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6331, 'Versement de transport', 633, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6332, 'Allocations logement', 633, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6333, 'Participation des employeurs à la formation professionnelle continue', 633, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6334, 'Participation des employeurs à l''effort de construction', 633, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6335, 'Versements libératoires ouvrant droit à l''exonération de la taxe d''apprentissage', 633, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6338, 'Autres', 633, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (635, 'Autres impôts, taxes et versements assimilés (administrations des impôts)', 63, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6351, 'Impôts directs (sauf impôts sur les bénéfices)', 635, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63511, 'Taxe professionnelle', 6351, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63512, 'Taxes foncières', 6351, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63513, 'Autres impôts locaux', 6351, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63514, 'Taxe sur les véhicules des sociétés', 6351, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6352, 'Taxe sur le chiffre d''affaires non récupérables', 635, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6353, 'Impôts indirects', 635, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6354, 'Droits d''enregistrement et de timbre', 635, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63541, 'Droits de mutation', 6354, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6358, 'Autres droits', 635, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (637, 'Autres impôts, taxes et versements assimilés (autres organismes)', 63, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6371, 'Contribution sociale de solidarité à la charge des sociétés', 637, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6372, 'Taxes perçues par les organismes publics internationaux', 637, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6374, 'Impôts et taxes exigibles à l''Etranger', 637, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6378, 'Taxes diverses', 637, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (64, 'Charges de personnel', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (641, 'Rémunérations du personnel', 64, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6411, 'Salaires, appointements', 641, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6412, 'Congés payés', 641, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6413, 'Primes et gratifications', 641, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6414, 'Indemnités et avantages divers', 641, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6415, 'Supplément familial', 641, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (644, 'Rémunération du travail de l''exploitant', 64, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (645, 'Charges de sécurité sociale et de prévoyance', 64, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6451, 'Cotisations à l''URSSAF', 645, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6452, 'Cotisations aux mutuelles', 645, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6453, 'Cotisations aux caisses de retraites', 645, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6454, 'Cotisations aux ASSEDIC', 645, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6458, 'Cotisations aux autres organismes sociaux', 645, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (646, 'Cotisations sociales personnelles de l''exploitant', 64, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (647, 'Autres charges sociales', 64, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6471, 'Prestations directes', 647, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6472, 'Versements aux comités d''entreprise et d''établissement', 647, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6473, 'Versements aux comités d''hygiène et de sécurité', 647, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6474, 'Versements aux autres ½uvres sociales', 647, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6475, 'Médecine du travail, pharmacie', 647, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (648, 'Autres charges de personnel', 64, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (65, 'Autres charges de gestion courante', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (651, 'Redevances pour concessions, brevets, licences, marques, procédés, logiciels, droits et valeurs similaires', 65, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6511, 'Redevances pour concessions, brevets, licences, marques, procédés, logiciels', 651, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6516, 'Droits d''auteur et de reproduction', 651, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6518, 'Autres droits et valeurs similaires', 651, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (653, 'Jetons de présence', 65, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (654, 'Pertes sur créances irrécouvrables', 65, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6541, 'Créances de l''exercice', 654, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6544, 'Créances des exercices antérieurs', 654, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (655, 'Quotes-parts de résultat sur opérations faites en commun', 65, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6551, 'Quote-part de bénéfice transférée (comptabilité du gérant)', 655, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6555, 'Quote-part de perte supportée (comptabilité des associés non gérants)', 655, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (658, 'Charges diverses de gestion courante', 65, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (66, 'Charges financières', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (661, 'Charges d''intérêts', 66, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6611, 'Intérêts des emprunts et dettes', 661, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (66116, 'des emprunts et dettes assimilées', 6611, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (66117, 'des dettes rattachées à des participations', 6611, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6615, 'Intérêts des comptes courants et des dépôts créditeurs', 661, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6616, 'Intérêts bancaires et sur opérations de financement (escompte,...)', 661, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6617, 'Intérêts des obligations cautionnées', 661, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6618, 'Intérêts des autres dettes', 661, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (66181, 'des dettes commerciales', 6618, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (66188, 'des dettes diverses', 6618, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (664, 'Pertes sur créances liées à des participations', 66, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (665, 'Escomptes accordés', 66, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (666, 'Pertes de change', 66, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (667, 'Charges nettes sur cessions de valeurs mobilières de placement', 66, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (668, 'Autres charges financières', 66, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (67, 'Charges exceptionnelles', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (671, 'Charges exceptionnelles sur opérations de gestion', 67, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6711, 'Pénalités sur marchés (et dédits payés sur achats et ventes)', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6712, 'Pénalités, amendes fiscales et pénales', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6713, 'Dons, libéralités', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6714, 'Créances devenues irrécouvrables dans l''exercice', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6715, 'Subventions accordées', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6717, 'Rappel d''impôts (autres qu''impôts sur les bénéfices)', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6718, 'Autres charges exceptionnelles sur opérations de gestion', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (672, '(Compte à la disposition des entités pour enregistrer, en cours d''exercice, les charges sur xercices antérieurs)', 67, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (675, 'Valeurs comptables des éléments d''actif cédés', 67, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6751, 'Immobilisations incorporelles', 675, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6752, 'Immobilisations corporelles', 675, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6756, 'Immobilisations financières', 675, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6758, 'Autres éléments d''actif', 675, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (678, 'Autres charges exceptionnelles', 67, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6781, 'Malis provenant de clauses d''indexation', 678, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6782, 'Lots', 678, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6783, 'Malis provenant du rachat par l''entreprise d''actions et obligations émises par elle-même', 678, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6788, 'Charges exceptionnelles diverses', 678, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68, 'Dotations aux amortissements et aux provisions', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (681, 'Dotations aux amortissements et aux provisionsCharges d''exploitation', 68, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6811, 'Dotations aux amortissements sur immobilisations incorporelles et corporelles', 681, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68111, 'Immobilisations incorporelles', 6811, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68112, 'Immobilisations corporelles', 6811, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6812, 'Dotations aux amortissements des charges d''exploitation à répartir', 681, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6815, 'Dotations aux provisions pour risques et charges d''exploitation', 681, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6816, 'Dotations aux provisions pour dépréciation des immobilisations incorporelles et corporelles', 681, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68161, 'Immobilisations incorporelles', 6816, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68162, 'Immobilisations corporelles', 6816, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6817, 'Dotations aux provisions pour dépréciation des actifs circulants', 681, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68173, 'Stocks et en-cours', 6817, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68174, 'Créances', 6817, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (686, 'Dotations aux amortissements et aux provisionsCharges financières', 68, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6861, 'Dotations aux amortissements des primes de remboursement des obligations', 686, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6865, 'Dotations aux provisions pour risques et charges financiers', 686, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6866, 'Dotations aux provisions pour dépréciation des éléments financiers', 686, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68662, 'Immobilisations financières', 6866, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68665, 'Valeurs mobilières de placement', 6866, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6868, 'Autres dotations', 686, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (687, 'Dotations aux amortissements et aux provisionsCharges exceptionnelles', 68, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6871, 'Dotations aux amortissements exceptionnels des immobilisations', 687, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6872, 'Dotations aux provisions réglementées (immobilisations)', 687, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68725, 'Amortissements dérogatoires', 6872, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6873, 'Dotations aux provisions réglementées (stocks)', 687, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6874, 'Dotations aux autres provisions réglementées', 687, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6875, 'Dotations aux provisions pour risques et charges exceptionnels', 687, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6876, 'Dotations aux provisions pour dépréciations exceptionnelles', 687, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (69, 'participation des salariesimpôts sur les benefices et assimiles', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (691, 'Participation des salariés aux résultats', 69, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (695, 'Impôts sur les bénéfices', 69, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6951, 'Impôts dus en France', 695, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6952, 'Contribution additionnelle à l''impôt sur les bénéfices', 695, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6954, 'Impôts dus à l''étranger', 695, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (696, 'Suppléments d''impôt sur les sociétés liés aux distributions', 69, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (697, 'Imposition forfaitaire annuelle des sociétés', 69, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (698, 'Intégration fiscale', 69, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6981, 'Intégration fiscaleCharges', 698, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6989, 'Intégration fiscaleProduits', 698, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (699, 'ProduitsReports en arrière des déficits', 69, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7, 'Comptes de produits', 0, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (70, 'ventes de produits fabriques, prestations de services, marchandises', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (701, 'Ventes de produits finis', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7011, 'Produits finis (ou groupe) A', 701, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7012, 'Produits finis (ou groupe) B', 701, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (702, 'Ventes de produits intermédiaires', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (703, 'Ventes de produits résiduels', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (704, 'Travaux', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7041, 'Travaux de catégorie (ou activité) A', 704, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7042, 'Travaux de catégorie (ou activité) B', 704, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (705, 'Etudes', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (706, 'Prestations de services', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (707, 'Ventes de marchandises', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7071, 'Marchandises (ou groupe) A', 707, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7072, 'Marchandises (ou groupe) B', 707, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (708, 'Produits des activités annexes', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7081, 'Produits des services exploités dans l''intérêt du personnel', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7082, 'Commissions et courtages', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7083, 'Locations diverses', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7084, 'Mise à disposition de personnel facturée', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7085, 'Ports et frais accessoires facturés', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7086, 'Bonis sur reprises d''emballages consignés', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7087, 'Bonifications obtenues des clients et primes sur ventes', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7088, 'Autres produits d''activités annexes (cessions d''approvisionnements,...)', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (709, 'Rabais, remises et ristournes accordés par l''entreprise', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7091, 'sur ventes de produits finis', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7092, 'sur ventes de produits intermédiaires', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7094, 'sur travaux', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7095, 'sur études', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7096, 'sur prestations de services', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7097, 'sur ventes de marchandises', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7098, 'sur produits des activités annexes', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71, 'production stockée (ou déstockage)', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (713, 'Variation des stocks (en-cours de production, produits)', 71, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7133, 'Variation des en-cours de production de biens', 713, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71331, 'Produits en cours', 7133, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71335, 'Travaux en cours', 7133, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7134, 'Variation des en-cours de production de services', 713, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71341, 'Etudes en cours', 7134, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71345, 'Prestations de services en cours', 7134, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7135, 'Variation des stocks de produits', 713, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71351, 'Produits intermédiaires', 7135, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71355, 'Produits finis', 7135, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71358, 'Produits résiduels', 7135, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (72, 'Production immobilisée', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (721, 'Immobilisations incorporelles', 72, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (722, 'Immobilisations corporelles', 72, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (73, 'Produits nets partiels sur opérations à long terme', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (731, 'Produits nets partiels sur opérations en cours (à subdiviser par opération)', 73, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (739, 'Produits nets partiels sur opérations terminées', 73, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (74, 'Subventions d''exploitation', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (75, 'Autres produits de gestion courante', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (751, 'Redevances pour concessions, brevets, licences, marques, procédés, logiciels, droits et valeurs similaires', 75, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7511, 'Redevances pour concessions, brevets, licences, marques, procédés, logiciels', 751, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7516, 'Droits d''auteur et de reproduction', 751, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7518, 'Autres droits et valeurs similaires', 751, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (752, 'Revenus des immeubles non affectés à des activités professionnelles', 75, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (753, 'Jetons de présence et rémunérations d''administrateurs, gérants,...', 75, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (754, 'Ristournes perçues des coopératives (provenant des excédents)', 75, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (755, 'Quotes-parts de résultat sur opérations faites en commun', 75, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7551, 'Quote-part de perte transférée (comptabilité du gérant)', 755, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7555, 'Quote-part de bénéfice attribuée (comptabilité des associés non-gérants)', 755, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (758, 'Produits divers de gestion courante', 75, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (76, 'Produits financiers', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (761, 'Produits de participations', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7611, 'Revenus des titres de participation', 761, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7616, 'Revenus sur autres formes de participation', 761, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7617, 'Revenus des créances rattachées à des participations', 761, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (762, 'Produits des autres immobilisations financières', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7621, 'Revenus des titres immobilisés', 762, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7626, 'Revenus des prêts', 762, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7627, 'Revenus des créances immobilisées', 762, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (763, 'Revenus des autres créances', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7631, 'Revenus des créances commerciales', 763, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7638, 'Revenus des créances diverses', 763, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (764, 'Revenus des valeurs mobilières de placement', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (765, 'Escomptes obtenus', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (766, 'Gains de change', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (767, 'Produits nets sur cessions de valeurs mobilières de placement', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (768, 'Autres produits financiers', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (77, 'Produits exceptionnels', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (771, 'Produits exceptionnels sur opérations de gestion', 77, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7711, 'Dédits et pénalités perçus sur achats et sur ventes', 771, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7713, 'Libéralités reçues', 771, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7714, 'Rentrées sur créances amorties', 771, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7715, 'Subventions d''équilibre', 771, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7717, 'Dégrèvements d''impôts autres qu''impôts sur les bénéfices', 771, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7718, 'Autres produits exceptionnels sur opérations de gestion', 771, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (772, '(Compte à la disposition des entités pour enregistrer, en cours d''exercice, les produits sur xercices antérieurs)', 77, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (775, 'Produits des cessions d''éléments d''actif', 77, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7751, 'Immobilisations incorporelles', 775, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7752, 'Immobilisations corporelles', 775, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7756, 'Immobilisations financières', 775, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7758, 'Autres éléments d''actif', 775, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (777, 'Quote-part des subventions d''investissement virée au résultat de l''exercice', 77, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (778, 'Autres produits exceptionnels', 77, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7781, 'Bonis provenant de clauses d''indexation', 778, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7782, 'Lots', 778, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7783, 'Bonis provenant du rachat par l''entreprise d''actions et d''obligations émises par elle-même', 778, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7788, 'Produits exceptionnels divers', 778, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78, 'Reprises sur amortissements et provisions', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (781, 'Reprises sur amortissements et provisions (à inscrire dans les produits d''exploitation)', 78, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7811, 'Reprises sur amortissements des immobilisations incorporelles et corporelles', 781, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78111, 'Immobilisations incorporelles', 7811, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78112, 'Immobilisations corporelles', 7811, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7815, 'Reprises sur provisions pour risques et charges d''exploitation', 781, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7816, 'Reprises sur provisions pour dépréciation des immobilisations incorporelles et corporelles', 781, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78161, 'Immobilisations incorporelles', 7816, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78162, 'Immobilisations corporelles', 7816, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7817, 'Reprises sur provisions pour dépréciation des actifs circulants', 781, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78173, 'Stocks et en-cours', 7817, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78174, 'Créances', 7817, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (786, 'Reprises sur provisions pour risques (à inscrire dans les produits financiers)', 78, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7865, 'Reprises sur provisions pour risques et charges financiers', 786, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7866, 'Reprises sur provisions pour dépréciation des éléments financiers', 786, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78662, 'Immobilisations financières', 7866, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78665, 'Valeurs mobilières de placements', 7866, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (787, 'Reprises sur provisions (à inscrire dans les produits exceptionnels)', 78, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7872, 'Reprises sur provisions réglementées (immobilisations)', 787, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78725, 'Amortissements dérogatoires', 7872, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78726, 'Provision spéciale de réévaluation', 7872, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78727, 'Plus-values réinvesties', 7872, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7873, 'Reprises sur provisions réglementées (stocks)', 787, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7874, 'Reprises sur autres provisions réglementées', 787, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7875, 'Reprises sur provisions pour risques et charges exceptionnels', 787, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7876, 'Reprises sur provisions pour dépréciations exceptionnelles', 787, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (79, 'Transferts de charges', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (791, 'Transferts de charges d''exploitation', 79, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (796, 'Transferts de charges financières', 79, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (797, 'Transferts de charges exceptionnelles ', 79, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (8, 'Comptes Spéciaux', 0, 'FR');


--
-- Data for Name: tva_rate; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis à la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis à la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis à la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis à la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis à la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis à la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis à la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis à la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis à la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis à la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis à la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (1, '19.6%', 0.1960, 'TVA ', '44566,44571');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (2, '5.5%', 0.0550, 'TVA réduite', '44566,44571');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (3, '2.1%', 0.0210, 'TVA réduite', '44566,44571');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (4, '0%', 0.0000, 'Tva applicable lors de vente/achat intracommunautaire ', '44566,44571');


--
-- Data for Name: user_local_pref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO user_local_pref (user_id, parameter_type, parameter_value) VALUES ('dany', 'PERIODE', '66');
INSERT INTO user_local_pref (user_id, parameter_type, parameter_value) VALUES ('phpcompta', 'PERIODE', '66');


--
-- Data for Name: user_sec_act; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: user_sec_jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (1, 'phpcompta', 4, '');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (2, 'phpcompta', 1, '');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (3, 'phpcompta', 3, '');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (4, 'phpcompta', 2, '');


--
-- Data for Name: version; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO version (val) VALUES (28);


--
-- PostgreSQL database dump complete
--

