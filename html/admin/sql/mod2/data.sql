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
INSERT INTO "action" (ac_id, ac_description) VALUES (6, 'Mise � jour Plan Comptable');
INSERT INTO "action" (ac_id, ac_description) VALUES (7, 'Gestion Journaux');
INSERT INTO "action" (ac_id, ac_description) VALUES (8, 'Param�tres');
INSERT INTO "action" (ac_id, ac_description) VALUES (9, 'S�curit�');
INSERT INTO "action" (ac_id, ac_description) VALUES (10, 'Centralise');
INSERT INTO "action" (ac_id, ac_description) VALUES (3, 'Fiche Read');
INSERT INTO "action" (ac_id, ac_description) VALUES (16, 'Voir le stock');
INSERT INTO "action" (ac_id, ac_description) VALUES (17, 'Modifie le stock');
INSERT INTO "action" (ac_id, ac_description) VALUES (15, 'Fiche �criture');
INSERT INTO "action" (ac_id, ac_description) VALUES (18, 'Devise');
INSERT INTO "action" (ac_id, ac_description) VALUES (19, 'P�riode');
INSERT INTO "action" (ac_id, ac_description) VALUES (20, 'Voir la balance des comptes');
INSERT INTO "action" (ac_id, ac_description) VALUES (21, 'Import et export des �critures d''ouverture');
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
INSERT INTO attr_def (ad_id, ad_text) VALUES (3, 'Num�ro de compte');
INSERT INTO attr_def (ad_id, ad_text) VALUES (4, 'Nom de la banque');
INSERT INTO attr_def (ad_id, ad_text) VALUES (5, 'Poste Comptable');
INSERT INTO attr_def (ad_id, ad_text) VALUES (6, 'Prix vente');
INSERT INTO attr_def (ad_id, ad_text) VALUES (7, 'Prix achat');
INSERT INTO attr_def (ad_id, ad_text) VALUES (8, 'Dur�e Amortissement');
INSERT INTO attr_def (ad_id, ad_text) VALUES (9, 'Description');
INSERT INTO attr_def (ad_id, ad_text) VALUES (10, 'Date d�but');
INSERT INTO attr_def (ad_id, ad_text) VALUES (11, 'Montant initial');
INSERT INTO attr_def (ad_id, ad_text) VALUES (12, 'Personne de contact ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (13, 'num�ro de tva ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (14, 'Adresse ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (16, 'pays ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (17, 't�l�phone ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (18, 'email ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (19, 'Gestion stock');
INSERT INTO attr_def (ad_id, ad_text) VALUES (20, 'Partie fiscalement non d�ductible');
INSERT INTO attr_def (ad_id, ad_text) VALUES (21, 'TVA non d�ductible');
INSERT INTO attr_def (ad_id, ad_text) VALUES (22, 'TVA non d�ductible r�cup�rable par l''imp�t');
INSERT INTO attr_def (ad_id, ad_text) VALUES (23, 'Quick Code');
INSERT INTO attr_def (ad_id, ad_text) VALUES (24, 'Ville');
INSERT INTO attr_def (ad_id, ad_text) VALUES (25, 'Soci�t�');
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

INSERT INTO document_state (s_id, s_value) VALUES (1, 'Envoy�');
INSERT INTO document_state (s_id, s_value) VALUES (2, 'Brouillon');
INSERT INTO document_state (s_id, s_value) VALUES (3, 'A envoyer');
INSERT INTO document_state (s_id, s_value) VALUES (4, 'Re�u');


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

INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (7, 'Mat�riel � amortir', 2400);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (13, 'D�penses non admises', 674);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (14, 'Administration des Finances', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (15, 'Autres fiches', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (1, 'Vente Service', 70);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (2, 'Achat Marchandises', 607);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (3, 'Achat Service et biens divers', 61);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (4, 'Banque', 51);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (5, 'Pr�t > a un an', 27);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (8, 'Fournisseurs', 400);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (9, 'Clients', 411);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (10, 'Salaire Administrateur', 6411);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (11, 'Salaire Ouvrier', 6411);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (12, 'Salaire Employ�', 6411);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (6, 'Pr�t < a un an', NULL);
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
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (4, 'Voir Impay�s', 'Voir toutes les factures non pay�es', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (1, 'Nouvelle', 'Cr�ation d''une facture', 'user_jrn.php', 'action=insert_vente&blank', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (10, 'Nouveau', 'Encode un nouvel achat (mat�riel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (12, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (14, 'Voir Impay�s', 'Voir toutes les factures non pay�es', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (20, 'Nouveau', 'Encode un nouvel achat (mat�riel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'FIN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (22, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'FIN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (30, 'Nouveau', NULL, 'user_jrn.php', 'action=new&blank', 'FR', 'ODS');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (32, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'ODS');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (40, 'Soldes', 'Voir les soldes des comptes en banques', 'user_jrn.php', 'action=solde', 'FR', 'FIN');


--
-- Data for Name: jrn_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (4, 'Op�ration Diverses', NULL, NULL, NULL, NULL, 5, 5, false, NULL, 'ODS', 'ODS-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (1, 'Financier', '5* ', '5*', '3,2,4', '3,2,4', 5, 5, false, NULL, 'FIN', 'FIN-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (3, 'Achat', '6*', '4*', '5', '4', 1, 3, true, '�ch�ance', 'ACH', 'ACH-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (2, 'Vente', '4*', '7*', '2', '6', 2, 1, true, '�ch�ance', 'VEN', 'VEN-01');


--
-- Data for Name: jrn_rapt; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for Name: jrn_type; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('FIN', 'Financier');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('VEN', 'Vente');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('ACH', 'Achat');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('ODS', 'Op�rations Diverses');


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

INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('DNA', '6740', 'D�pense non d�ductible');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('CUSTOMER', '400', 'Poste comptable de base pour les clients');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('COMPTE_TVA', '451', 'TVA � payer');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('BANQUE', '550', 'Poste comptable de base pour les banques');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('VIREMENT_INTERNE', '58', 'Poste Comptable pour les virements internes');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('COMPTE_COURANT', '56', 'Poste comptable pour le compte courant');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('CAISSE', '57', 'Poste comptable pour la caisse');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('TVA_DNA', '6740', 'Tva non d�ductible s');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('TVA_DED_IMPOT', '619000', 'Tva d�ductible par l''imp�t');
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
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10, 'capital et r�serves', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (101, 'Capital', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1011, 'Capital souscrit - non appel�', 101, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1012, 'Capital souscrit - appel�, non vers�', 101, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1013, 'Capital souscrit - appel�, vers�', 101, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10131, 'Capital non amorti', 1013, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10132, 'Capital amorti', 1013, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1018, 'Capital souscrit soumis � des r�glementations particuli�res', 101, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (104, 'Primes li�es au capital social', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1041, 'Primes d''�mission', 104, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1042, 'Primes de fusion', 104, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1043, 'Primes d''apport', 104, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1044, 'Primes de conversion d''obligations en actions', 104, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1045, 'Bons de souscription d''actions', 104, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (105, 'Ecarts de r��valuation', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1051, 'R�serve sp�ciale de r��valuation', 105, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1052, 'Ecart de r��valuation libre', 105, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1053, 'R�serve de r��valuation', 105, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1055, 'Ecarts de r��avaluation (autres op�rations l�gales)', 105, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1057, 'Autres �carts de r��valuation en France', 105, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1058, 'Autres �carts de r��valuation � l''Etranger', 105, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (106, 'R�serves', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1061, 'R�serve l�gale', 106, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10611, 'R�serve l�gale proprement dite', 1061, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10612, 'Plus-values nettes � long terme', 1061, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1062, 'R�serves indisponibles', 106, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1063, 'R�serves statutaires ou contractuelles', 106, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1064, 'R�serves r�glement�es', 106, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10641, 'Plus-values nettes � long terme', 1064, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10643, 'R�serves cons�cutives � l''octroi de subventions d''investissement', 1064, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10648, 'Autres r�serves r�glement�es', 1064, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1068, 'Autres r�serves', 106, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10681, 'R�serve de propre assureur', 1068, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10688, 'R�serves diverses', 1068, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (107, 'Ecart d''�quivalence', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (108, 'Compte de l''exploitant', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (109, 'Actionnaires : Capital souscritnon appel�', 10, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (11, 'report a nouveau (solde cr�diteur ou d�biteur)', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (110, 'Report � nouveau (solde cr�diteur)', 11, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (119, 'Report � nouveau (solde d�biteur)', 11, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (12, 'r�sultat de l''exercice (b�n�fice ou perte)', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (120, 'R�sultat de l''exercice (b�n�fice)', 12, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (129, 'R�sultat de l''exercice (perte)', 12, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13, 'subventions d''investissement', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (131, 'Subventions d''�quipement', 13, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1311, 'Etat', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1312, 'R�gions', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1313, 'D�partements', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1314, 'Communes', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1315, 'Collectivit�s publiques', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1316, 'Entreprises publiques', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1317, 'Entreprises et organismes priv�s', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1318, 'Autres', 131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (138, 'Autres subventions d''investissement (m�me ventilation que celle du compte 131)', 13, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (139, 'Subventions d''investissement inscrites au compte de r�sultat', 13, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1391, 'Subventions d''�quipement', 139, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13911, 'Etat', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13912, 'R�gions', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13913, 'D�partements', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13914, 'Communes', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13915, 'Collectivit�s publiques', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13916, 'Entreprises publiques', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13917, 'Entreprises et organismes priv�s', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13918, 'Autres', 1391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1398, 'Autres subventions d''investissement (m�me ventilation que celle du compte 1391)', 139, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (14, 'provisions reglementees', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (142, 'Provisions r�glement�es relatives aux immobilisations', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1423, 'Provisions pour reconstitution des gisements miniers et p�troliers', 142, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1424, 'Provisions pour investissement (participation des salari�s)', 142, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (143, 'Provisions r�glement�es relatives aux stocks', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1431, 'Hausse des prix', 143, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1432, 'Fluctuation des cours', 143, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (144, 'Provisions r�glement�es relatives aux autres �l�ments de l''actif', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (145, 'Amortissements d�rogatoires', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (146, 'Provision sp�ciale de r��valuation', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (147, 'Plus-values r�investies', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (148, 'Autres provisions r�glement�es', 14, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (15, 'Provisions pour risques et charges', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (151, 'Provisions pour risques', 15, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1511, 'Provisions pour litiges', 151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1512, 'Provisions pour garanties donn�es aux clients', 151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1513, 'Provisions pour pertes sur march�s � terme', 151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1514, 'Provisions pour amendes et p�nalit�s', 151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1515, 'Provisions pour pertes de change', 151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1518, 'Autres provisions pour risques', 151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (153, 'Provisions pour pensions et obligations similaires', 15, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (155, 'Provisions pour imp�ts', 15, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (156, 'Provisions pour renouvellement des immobilisations (entreprises concessionnaires)', 15, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (157, 'Provisions pour charges � r�partir sur plusieurs exercices', 15, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1572, 'Provisions pour grosses r�parations', 157, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (158, 'Autres provisions pour charges', 15, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1582, 'Provisions pour charges sociales et fiscales sur cong�s � payer', 158, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16, 'Emprunts et dettes assimilees', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (161, 'Emprunts obligataires convertibles', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (163, 'Autres emprunts obligataires', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (164, 'Emprunts aupr�s des �tablissements de cr�dit', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (165, 'D�p�ts et cautionnements re�us', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1651, 'D�p�ts', 165, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1655, 'Cautionnements', 165, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (166, 'Participation des salari�s aux r�sultats', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1661, 'Comptes bloqu�s', 166, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1662, 'Fonds de participation', 166, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (167, 'Emprunts et dettes assortis de conditions particuli�res', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1671, 'Emissions de titres participatifs', 167, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1674, 'Avances conditionn�es de l''Etat', 167, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1675, 'Emprunts participatifs', 167, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (168, 'Autres emprunts et dettes assimil�es', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1681, 'Autres emprunts', 168, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1685, 'Rentes viag�res capitalis�es', 168, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1687, 'Autres dettes', 168, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1688, 'Int�r�ts courus', 168, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16881, 'Sur emprunts obligataires convertibles', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16883, 'Sur autres emprunts obligataires', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16884, 'Sur emprunts aupr�s des �tablissements de cr�dit', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16885, 'Sur d�p�ts et cautionnements re�us', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16886, 'Sur participation des salari�s aux r�sultats', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16887, 'Sur emprunts et dettes assortis de conditions particuli�res', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16888, 'Sur autres emprunts et dettes assimil�es', 1688, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (169, 'Primes de remboursement des obligations', 16, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (17, 'dettes rattach�es a des participations', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (171, 'Dettes rattach�es � des participations (groupe)', 17, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (174, 'Dettes rattach�es � des participations (hors groupe)', 17, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (178, 'Dettes rattach�es � des soci�t�s en participation', 17, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1781, 'Principal', 178, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1788, 'Int�r�ts courus', 178, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (18, 'comptes de liaison des �tablissements et societes en participation', 1, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (181, 'Comptes de liaison des �tablissements', 18, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (186, 'Biens et prestations de services �chang�s entre �tablissements (charges)', 18, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (187, 'Biens et prestations de services �chang�s entre �tablissements (produits)', 18, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (188, 'Comptes de liaison des soci�t�s en participation', 18, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2, 'comptes d''immobilisations', 0, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (20, 'immobilisations incorporelles', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (201, 'Frais d''�tablissement', 20, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2011, 'Frais de constitution', 201, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2012, 'Frais de premier �tablissement', 201, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (20121, 'Frais de prospection', 2012, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (20122, 'Frais de publicit�', 2012, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2013, 'Frais d''augmentation de capital et d''op�rations diverses (fusions, scissions, transformations)', 201, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (203, 'Frais de recherche et de d�veloppement', 20, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (205, 'Concessions et droits similaires, brevets, licences, marques, proc�d�s, logiciels, droits et valeurs similaires', 20, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (206, 'Droit au bail', 20, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (207, 'Fonds commercial', 20, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (208, 'Autres immobilisations incorporelles', 20, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21, 'Immobilisations corporelles', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (211, 'Terrains', 21, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2111, 'Terrains nus', 211, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2112, 'Terrains am�nag�s', 211, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2113, 'Sous-sols et sur-sols', 211, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2114, 'Terrains de gisement', 211, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21141, 'Carri�res', 2114, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2115, 'Terrains b�tis', 211, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21151, 'Ensembles immobiliers industriels (A, B...)', 2115, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21155, 'Ensembles immobiliers administratifs et commerciaux (A, B...)', 2115, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21158, 'Autres ensembles immobiliers', 2115, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (211581, 'Affect�s aux op�rations professionnelles (A, B...)', 21158, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (211588, 'Affect�s aux op�rations non professionnelles (A, B...)', 21158, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2116, 'Compte d''ordre sur immobilisations (art. 6 du d�cret n� 78-737 du 11 juillet 1978)', 211, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (212, 'Agencements et am�nagements de terrains (m�me ventilation que celle du compte 211)', 21, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (213, 'Constructions', 21, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2131, 'B�timents', 213, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21311, 'Ensembles immobiliers industriels (A, B...)', 2131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21315, 'Ensembles immobiliers administratifs et commerciaux (A, B...)', 2131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21318, 'Autres ensembles immobiliers', 2131, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (213181, 'affect�s aux op�rations professionnelles (A, B...)', 21318, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (213188, 'affect�s aux op�rations non professionnelles (A, B...)', 21318, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2135, 'Installations g�n�ralesagencementsam�nagements des constructions (m�me ventilation que celle du compte 2131)', 213, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2138, 'Ouvrages d''infrastructure', 213, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21381, 'Voies de terre', 2138, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21382, 'Voies de fer', 2138, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21383, 'Voies d''eau', 2138, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21384, 'Barrages', 2138, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21385, 'Pistes d''a�rodromes', 2138, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (214, 'Constructions sur sol d''autrui (m�me ventilation que celle du compte 213)', 21, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (215, 'Installations techniques, mat�riels et outillage industriels', 21, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2151, 'Installations complexes sp�cialis�es', 215, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21511, 'sur sol propre', 2151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21514, 'sur sol d''autrui', 2151, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2153, 'Installations � caract�re sp�cifique', 215, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21531, 'sur sol propre', 2153, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21534, 'sur sol d''autrui', 2153, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2154, 'Mat�riel industriel', 215, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2155, 'Outillage industriel', 215, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2157, 'Agencements et am�nagements du mat�riel et outillage industriels', 215, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (218, 'Autres immobilisations corporelles', 21, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2181, 'Installations g�n�rales, agencements, am�nagements divers', 218, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2182, 'Mat�riel de transport', 218, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2183, 'Mat�riel de bureau et mat�riel informatique', 218, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2184, 'Mobilier', 218, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2185, 'Cheptel', 218, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2186, 'Emballages r�cup�rables', 218, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (22, 'immobilisations mises en concession', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (23, 'immobilisations en cours', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (231, 'Immobilisations corporelles en cours', 23, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2312, 'Terrains', 231, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2313, 'Constructions', 231, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2315, 'Installations techniques, mat�riel et outillage industriels', 231, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2318, 'Autres immobilisations corporelles', 231, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (232, 'Immobilisations incorporelles en cours', 23, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (237, 'Avances et acomptes vers�s sur immobilisations incorporelles', 23, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (238, 'Avances et acomptes vers�s sur commandes d''immobilisations corporelles', 23, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2382, 'Terrains', 238, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2383, 'Constructions', 238, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2385, 'Installations techniques, mat�riel et outillage industriels', 238, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2388, 'Autres immobilisations corporelles', 238, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (25, 'Parts dans des entreprises li�es et cr�ances sur des entreprises li�es', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (26, 'Participations et cr�ances rattach�es � des participations', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (261, 'Titres de participation', 26, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2611, 'Actions', 261, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2618, 'Autres titres', 261, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (266, 'Autres formes de participation', 26, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (267, 'Cr�ances rattach�es � des participations', 26, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2671, 'Cr�ances rattach�es � des participations (groupe)', 267, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2674, 'Cr�ances rattach�es � des participations (hors groupe)', 267, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2675, 'Versements repr�sentatifs d''apports non capitalis�s (appel de fonds)', 267, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2676, 'Avances consolidables', 267, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2677, 'Autres cr�ances rattach�es � des participations', 267, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2678, 'Int�r�ts courus', 267, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (268, 'Cr�ances rattach�es � des soci�t�s en participation', 26, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2681, 'Principal', 268, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2688, 'Int�r�ts courus', 268, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (269, 'Versements restant � effectuer sur titres de participation non lib�r�s', 26, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (27, 'autres immobilisations financieres', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (271, 'Titres immobilis�s autres que les titres immobilis�s de l''activit� de portefeuille (droit de propri�t�)', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2711, 'Actions', 271, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2718, 'Autres titres', 271, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (272, 'Titres immobilis�s (droit de cr�ance)', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2721, 'Obligations', 272, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2722, 'Bons', 272, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (273, 'Titres immobilis�s de l''activit� de portefeuille', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (274, 'Pr�ts', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2741, 'Pr�ts participatifs', 274, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2742, 'Pr�ts aux associ�s', 274, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2743, 'Pr�ts au personnel', 274, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2748, 'Autres pr�ts', 274, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (275, 'D�p�ts et cautionnements vers�s', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2751, 'D�p�ts', 275, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2755, 'Cautionnements', 275, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (276, 'Autres cr�ances immobilis�es', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2761, 'Cr�ances diverses', 276, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2768, 'Int�r�ts courus', 276, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (27682, 'Sur titres immobilis�s (droit de cr�ance)', 2768, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (27684, 'Sur pr�ts', 2768, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (27685, 'Sur d�p�ts et cautionnements', 2768, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (27688, 'Sur cr�ances diverses', 2768, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (277, '(Actions propres ou parts propres)', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2771, 'Actions propres ou parts propres', 277, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2772, 'Actions propres ou parts propres en voie d''annulation', 277, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (279, 'Versements restant � effectuer sur titres immobilis�s non lib�r�s', 27, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (28, 'amortissements des immobilisations', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (280, 'Amortissements des immobilisations incorporelles', 28, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2801, 'Frais d''�tablissement (m�me ventilation que celle du compte 201)', 280, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2803, 'Frais de recherche et de d�veloppement', 280, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2805, 'Concessions et droits similaires, brevets, licences, logiciels, droits et valeurs similaires', 280, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2807, 'Fonds commercial', 280, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2808, 'Autres immobilisations incorporelles', 280, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (281, 'Amortissements des immobilisations corporelles', 28, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2811, 'Terrains de gisement', 281, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2812, 'Agencements, am�nagements de terrains (m�me ventilation que celle du compte 212)', 281, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2813, 'Constructions (m�me ventilation que celle du compte 213)', 281, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2814, 'Constructions sur sol d''autrui (m�me ventilation que celle du compte 214)', 281, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2815, 'Installations, mat�riel et outillage industriels (m�me ventilation que celle du compte 215)', 281, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2818, 'Autres immobilisations corporelles (m�me ventilation que celle du compte 218)', 281, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (282, 'Amortissements des immobilisations mises en concession', 28, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (29, 'provisions pour d�pr�ciation des immobilisations', 2, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (290, 'Provisions pour d�pr�ciation des immobilisations incorporelles', 29, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2905, 'Marques, proc�d�s, droits et valeurs similaires', 290, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2906, 'Droit au bail', 290, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2907, 'Fonds commercial', 290, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2908, 'Autres immobilisations incorporelles', 290, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (291, 'Provisions pour d�pr�ciation des immobilisations corporelles (m�me ventilation que celle du compte 21)', 29, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2911, 'Terrains (autres que terrains de gisement)', 291, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (292, 'Provisions pour d�pr�ciation des immobilisations mises en concession', 292, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (293, 'Provisions pour d�pr�ciation des immobilisations en cours', 29, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2931, 'Immobilisations corporelles en cours', 293, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2932, 'Immobilisations incorporelles en cours', 293, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (296, 'Provisions pour d�pr�ciation des participations et cr�ances rattach�es � des participations', 29, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2961, 'Titres de participation', 296, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2966, 'Autres formes de participation', 296, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2967, 'Cr�ances rattach�es � des participations (m�me ventilation que celle du compte 267)', 296, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2968, 'Cr�ances rattach�es � des soci�t�s en participation (m�me ventilation que celle du compte 268)', 296, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (297, 'Provisions pour d�pr�ciation des autres immobilisations financi�res', 29, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2971, 'Titres immobilis�s autres que les titres immobilis�s de l''activit� de portefeuille -droit de propri�t� (m�me ventilation que celle du compte 271)', 297, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2972, 'Titres immobilis�sdroit de cr�ance (m�me ventilation que celle du compte 272)', 297, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2973, 'Titres immobilis�s de l''activit� de portefeuille', 297, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2974, 'Pr�ts (m�me ventilation que celle du compte 274)', 297, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2975, 'D�p�ts et cautionnements vers�s (m�me ventilation que celle du compte 275)', 297, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2976, 'Autres cr�ances immobilis�es (m�me ventilation que celle du compte 276)', 297, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3, 'Comptes de stocks et en cours', 0, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (31, 'matieres premi�res (et fournitures)', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (311, 'Mati�res (ou groupe) A', 31, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (312, 'Mati�res (ou groupe) B', 31, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (317, 'Fournitures A, B, C, ...', 31, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (32, 'autres approvisionnements', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (321, 'Mati�res consommables', 32, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3211, 'Mati�res (ou groupe) C', 321, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3212, 'Mati�res (ou groupe) D', 321, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (322, 'Fournitures consommables', 32, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3221, 'Combustibles', 322, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3222, 'Produits d''entretien', 322, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3223, 'Fournitures d''atelier et d''usine', 322, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3224, 'Fournitures de magasin', 322, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3225, 'Fournitures de bureau', 322, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (326, 'Emballages', 32, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3261, 'Emballages perdus', 326, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3265, 'Emballages r�cup�rables non identifiables', 326, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3267, 'Emballages � usage mixte', 326, 'FR');
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
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (351, 'Produits interm�diaires', 35, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3511, 'Produits interm�diaires (ou groupe) A', 351, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3512, 'Produits interm�diaires (ou groupe) B', 351, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (355, 'Produits finis', 35, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3551, 'Produits finis (ou groupe) A', 355, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3552, 'Produits finis (ou groupe) B', 355, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (358, 'Produits r�siduels (ou mati�res de r�cup�ration)', 35, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3581, 'D�chets', 358, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3585, 'Rebuts', 358, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3586, 'Mati�res de r�cup�ration', 358, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (36, '(compte � ouvrir, le cas �ch�ant, sous l''intitul� " stocks provenant d''immobilisations ")', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (37, 'stocks de marchandises', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (371, 'Marchandises (ou groupe) A', 37, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (372, 'Marchandises (ou groupe) B', 37, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (38, '(lorsque l''entit� tient un inventaire permanent en comptabilit� g�n�rale, le compte 38 peut �tre utilis� pour comptabiliser les stocks en voie d''acheminement, mis en d�p�t ou donn�s en consignation)', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (39, 'provisions pour d�pr�ciation des stocks et en-cours', 3, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (391, 'Provisions pour d�pr�ciation des mati�res premi�res (et fournitures)', 39, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3911, 'Mati�res (ou groupe) A', 391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3912, 'Mati�res (ou groupe) B', 391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3917, 'Fournitures A, B, C, ...', 391, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (392, 'Provisions pour d�pr�ciation des autres approvisionnements', 39, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3921, 'Mati�res consommables (m�me ventilation que celle du compte 321)', 392, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3922, 'Fournitures consommables (m�me ventilation que celle ducompte 322)', 392, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3926, 'Emballages (m�me ventilation que celle du compte 326)', 392, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (393, 'Provisions pour d�pr�ciation des en-cours de production de biens', 39, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3931, 'Produits en cours (m�me ventilation que celle du compte 331)', 393, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3935, 'Travaux en cours (m�me ventilation que celle du compte 335)', 393, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (394, 'Provisions pour d�pr�ciation des en-cours de production de services', 39, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3941, 'Etudes en cours (m�me ventilation que celle du compte 341)', 394, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3945, 'Prestations de services en cours (m�me ventilation que celle du compte 345)', 394, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (395, 'Provisions pour d�pr�ciation des stocks de produits', 39, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3951, 'Produits interm�diaires (m�me ventilation que celle du compte 351)', 395, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3955, 'Produits finis (m�me ventilation que celle du compte 355)', 395, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (397, 'Provisions pour d�pr�ciation des stocks de marchandises', 39, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3971, 'Marchandise (ou groupe) A', 397, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3972, 'Marchandise (ou groupe) B', 397, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4, 'Classe 4 : comptes de tiers', 0, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (40, 'fournisseurs et comptes rattaches', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (400, 'Fournisseurs et Comptes rattach�s', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (401, 'Fournisseurs', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4011, 'FournisseursAchats de biens et prestations de services', 401, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4017, 'FournisseursRetenues de garantie', 401, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (403, 'FournisseursEffets � payer', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (404, 'Fournisseurs d''immobilisations', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4041, 'FournisseursAchats d''immobilisations', 404, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4047, 'Fournisseurs d''immobilisationsRetenues de garantie', 404, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (405, 'Fournisseurs d''immobilisationsEffets � payer', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (408, 'FournisseursFactures non parvenues', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4081, 'Fournisseurs', 408, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4084, 'Fournisseurs d''immobilisations', 408, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4088, 'FournisseursInt�r�ts courus', 408, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (409, 'Fournisseurs d�biteurs', 40, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4091, 'FournisseursAvances et acomptes vers�s sur commandes', 409, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4096, 'FournisseursCr�ances pour emballages et mat�riel � rendre', 409, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4097, 'FournisseursAutres avoirs', 409, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (40971, 'Fournisseurs d''exploitation', 4097, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (40974, 'Fournisseurs d''immobilisations', 4097, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4098, 'Rabais, remises, ristournes � obtenir et autres avoirs non encore re�us', 409, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (41, 'clients et comptes rattaches', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (410, 'Clients et Comptes rattach�s', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (411, 'Clients', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4110001, 'Clients 1', 411, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4110002, 'Clients 2', 411, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4110003, 'Clients 3', 411, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4111, 'ClientsVentes de biens ou de prestations de services', 411, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4117, 'ClientsRetenues de garantie', 411, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (413, 'ClientsEffets � recevoir', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (416, 'Clients douteux ou litigieux', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (417, '" Cr�ances " sur travaux non encore facturables', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (418, 'ClientsProduits non encore factur�s', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4181, 'ClientsFactures � �tablir', 418, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4188, 'ClientsInt�r�ts courus', 418, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (419, 'Clients cr�diteurs', 41, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4191, 'ClientsAvances et acomptes re�us sur commandes', 419, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4196, 'ClientsDettes sur emballages et mat�riels consign�s', 419, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4197, 'ClientsAutres avoirs', 419, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4198, 'Rabais, remises, ristournes � accorder et autres avoirs � �tablir', 419, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (42, 'Personnel et comptes rattaches', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (421, 'PersonnelR�mun�rations dues', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (422, 'Comit�s d''entreprises, d''�tablissement,...', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (424, 'Participation des salari�s aux r�sultats', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4246, 'R�serve sp�ciale (art. L. 442-2 du Code du travail)', 424, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4248, 'Comptes courants', 424, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (425, 'PersonnelAvances et acomptes', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (426, 'PersonnelD�p�ts', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (427, 'PersonnelOppositions', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (428, 'PersonnelCharges � payer et produits � recevoir', 42, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4282, 'Dettes provisionn�es pour cong�s � payer', 428, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4284, 'Dettes provisionn�es pour participation des salari�s aux r�sultats', 428, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4286, 'Autres charges � payer', 428, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4287, 'Produits � recevoir', 428, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (43, 'S�curit� sociale et autres organismes sociaux', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (431, 'S�curit� sociale', 43, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (437, 'Autres organismes sociaux', 43, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (438, 'Organismes sociauxCharges � payer et produits � recevoir', 43, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4382, 'Charges sociales sur cong�s � payer', 438, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4386, 'Autres charges � payer', 438, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4387, 'Produits � recevoir', 438, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44, '�tat et autres collectivit�s publiques', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (441, '�tatSubventions � recevoir', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4411, 'Subventions d''investissement', 441, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4417, 'Subventions d''exploitation', 441, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4418, 'Subventions d''�quilibre', 441, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4419, 'Avances sur subventions', 441, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (442, 'EtatImp�ts et taxes recouvrables sur des tiers', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4424, 'Obligataires', 442, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4425, 'Associ�s', 442, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (443, 'Op�rations particuli�res avec l''Etat les collectivit�s publiques, les organismes internationaux', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4431, 'Cr�ances sur l''Etat r�sultant de la suppression de la r�gle du d�calage d''un mois en mati�re de T.V.A.', 443, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4438, 'Int�r�ts courus sur cr�ances figurant au 4431', 443, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (444, 'EtatImp�ts sur les b�n�fices', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (445, 'EtatTaxes sur le chiffre d''affaires', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4452, 'T.V.A. due intracommunautaire', 445, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4455, 'Taxes sur le chiffre d''affaires � d�caisser', 445, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44551, 'T.V.A. � d�caisser', 4455, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44558, 'Taxes assimil�es � la T.V.A.', 4455, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4456, 'Taxes sur le chiffre d''affaires d�ductibles', 445, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44562, 'T.V.A. sur immobilisations', 446, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44563, 'T.V.A. transf�r�e par d''autres entreprises', 4456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44566, 'T.V.A. sur autres biens et services', 4456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44567, 'Cr�dit de T.V.A. � reporter', 4456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44568, 'Taxes assimil�es � la T.V.A.', 4456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4457, 'Taxes sur le chiffre d''affaires collect�es par l''entreprise', 445, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44571, 'T.V.A. collect�e', 4457, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44578, 'Taxes assimil�es � la T.V.A.', 4457, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4458, 'Taxes sur le chiffre d''affaires � r�gulariser ou en attente', 445, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44581, 'AcomptesR�gime simplifi� d''imposition', 4458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44582, 'AcomptesR�gime de forfait', 4458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44583, 'Remboursement de taxes sur le chiffre d''affaires demand�', 4458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44584, 'T.V.A. r�cup�r�e d''avance', 4458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44586, 'Taxes sur le chiffre d''affaires sur factures non parvenues', 4458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44587, 'Taxes sur le chiffres d''affaires sur factures � �tablir', 4458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (446, 'Obligations cautionn�es', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (447, 'Autres imp�ts, taxes et versements assimil�s', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (448, 'EtatCharges � payer et produits � recevoir', 44, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4482, 'Charges fiscales sur cong�s � payer', 448, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4486, 'Charges � payer', 448, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4487, 'Produits � recevoir', 448, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (45, 'Groupe et associes', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (451, 'Groupe', 45, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (455, 'Associ�sComptes courants', 45, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4551, 'Principal', 455, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4558, 'Int�r�ts courus', 455, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (456, 'Associ�sOp�rations sur le capital', 45, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4561, 'Associ�sComptes d''apport en soci�t�', 456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (45611, 'Apports en nature', 4561, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (45615, 'Apports en num�raire', 4561, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4562, 'ApporteursCapital appel�, non vers�', 456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (45621, 'ActionnairesCapital souscrit et appel�, non vers�', 4562, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (45625, 'Associ�sCapital appel�, non vers�', 4562, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4563, 'Associ�sVersements re�us sur augmentation de capital', 456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4564, 'Associ�sVersements anticip�s', 456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4566, 'Actionnaires d�faillants', 456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4567, 'Associ�sCapital � rembourser', 456, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (457, 'Associ�sDividendes � payer', 45, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (458, 'Associ�sOp�rations faites en commun et en G.I.E.', 45, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4581, 'Op�rations courantes', 458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4588, 'Int�r�ts courus', 458, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (46, 'D�biteurs divers et cr�diteurs divers', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (462, 'Cr�ances sur cessions d''immobilisations', 46, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (464, 'Dettes sur acquisitions de valeurs mobili�res de placement', 46, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (465, 'Cr�ances sur cessions de valeurs mobili�res de placement', 46, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (467, 'Autres comptes d�biteurs ou cr�diteurs', 46, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (468, 'DiversCharges � payer et produits � recevoir', 46, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4686, 'Charges � payer', 468, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4687, 'Produits � recevoir', 468, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (47, 'comptes transitoires ou d''attente', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (471, 'Comptes d''attente', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (472, 'Comptes d''attente', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (473, 'Comptes d''attente', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (474, 'Comptes d''attente', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (475, 'Comptes d''attente', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (476, 'Diff�rence de conversion Actif', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4761, 'Diminution des cr�ances', 476, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4762, 'Augmentation des dettes', 476, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4768, 'Diff�rences compens�es par couverture de change', 476, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (477, 'Diff�rences de conversion Passif', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4771, 'Augmentation des cr�ances', 477, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4772, 'Diminution des dettes', 477, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4778, 'Diff�rences compens�es par couverture de change', 477, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (478, 'Autres comptes transitoires', 47, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (48, 'comptes de r�gularisation', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (481, 'Charges � r�partir sur plusieurs exercices', 48, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4811, 'Charges diff�r�es', 481, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4812, 'Frais d''acquisition des immobilisations', 481, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4816, 'Frais d''�mission des emprunts', 481, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4818, 'Charges � �taler', 481, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (486, 'Charges constat�es d''avance', 48, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (487, 'Produits constat�s d''avance', 48, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (488, 'Comptes de r�partition p�riodique des charges et des produits', 48, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4886, 'Charges', 488, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4887, 'Produits', 488, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (49, 'provisions pour d�pr�ciation des comptes de tiers', 4, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (491, 'Provisions pour d�pr�ciation des comptes de clients', 49, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (495, 'Provisions pour d�pr�ciation des comptes du groupe et des associ�s', 49, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4951, 'Comptes du groupe', 495, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4955, 'Comptes courants des associ�s', 495, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4958, 'Op�rations faites en commun et en G.I.E.', 495, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (496, 'Provisions pour d�pr�ciation des comptes de d�biteurs divers', 49, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4962, 'Cr�ances sur cessions d''immobilisations', 496, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4965, 'Cr�ances sur cessions de valeurs mobili�res de placement', 496, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4967, 'Autres comptes d�biteurs', 496, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5, 'Comptes financiers', 0, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (50, 'valeurs mobili�res de placement', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (501, 'Parts dans des entreprises li�es', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (502, 'Actions propres', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (503, 'Actions', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5031, 'Titres cot�s', 503, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5035, 'Titres non cot�s', 503, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (504, 'Autres titres conf�rant un droit de propri�t�', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (505, 'Obligations et bons �mis par la soci�t� et rachet�s par elle', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (506, 'Obligations', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5061, 'Titres cot�s', 506, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5065, 'Titres non cot�s', 506, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (507, 'Bons du Tr�sor et bons de caisse � court terme', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (508, 'Autres valeurs mobili�res de placement et autres cr�ances assimil�es', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5081, 'Autres valeurs mobili�res', 508, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5082, 'Bons de souscription', 508, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5088, 'Int�r�ts courus sur obligations, bons et valeurs assimil�s', 508, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (509, 'Versements restant � effectuer sur valeurs mobili�res de placement non lib�r�es', 50, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (51, 'banques, �tablissements financiers et assimil�s', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (510002, 'banques 2', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (510001, 'Banque1', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (511, 'Valeurs � l''encaissement', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5111, 'Coupons �chus � l''encaissement', 511, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5112, 'Ch�ques � encaisser', 511, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5113, 'Effets � l''encaissement', 511, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5114, 'Effets � l''escompte', 511, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (512, 'Banques', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5121, 'Comptes en monnaie nationale', 512, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5124, 'Comptes en devises', 512, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (514, 'Ch�ques postaux', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (515, '" Caisses " du Tr�sor et des �tablissements publics', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (516, 'Soci�t�s de bourse', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (517, 'Autres organismes financiers', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (518, 'Int�r�ts courus', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5181, 'Int�r�ts courus � payer', 519, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5188, 'Int�r�ts courus � recevoir', 519, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (519, 'Concours bancaires courants', 51, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5191, 'Cr�dit de mobilisation de cr�ances commerciales (CMCC)', 519, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5193, 'Mobilisation de cr�ances n�es � l''�tranger', 519, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5198, 'Int�r�ts courus sur concours bancaires courants', 519, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (52, 'Instruments de tr�sorerie', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (53, 'Caisse', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (531, 'Caisse si�ge social', 53, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5311, 'Caisse en monnaie nationale', 531, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5314, 'Caisse en devises', 531, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (532, 'Caisse succursale (ou usine) A', 53, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (533, 'Caisse succursale (ou usine) B', 53, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (54, 'r�gies d''avance et accr�ditifs', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (58, 'virements internes', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (59, 'provisions pour d�pr�ciation des comptes financiers', 5, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (590, 'Provisions pour d�pr�ciation des valeurs mobili�res de placement', 59, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5903, 'Actions', 590, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5904, 'Autres titres conf�rant un droit de propri�t�', 590, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5906, 'Obligations', 590, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5908, 'Autres valeurs mobili�res de placement et cr�ances assimil�es', 590, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6, 'comptes de charges', 0, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60, 'Achats (sauf 603)', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (601, 'Achats stock�sMati�res premi�res (et fournitures)', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6011, 'Mati�res (ou groupe) A', 601, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6012, 'Mati�res (ou groupe) B', 601, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6017, 'Fournitures A, B, C, ...', 601, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (602, 'Achats stock�sAutres approvisionnements', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6021, 'Mati�res consommables', 602, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60211, 'Mati�res (ou groupe) C', 6021, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60212, 'Mati�res (ou groupe) D', 6021, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6022, 'Fournitures consommables', 602, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60221, 'Combustibles', 6022, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60222, 'Produits d''entretien', 6022, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60223, 'Fournitures d''atelier et d''usine', 6022, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60224, 'Fournitures de magasin', 6022, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60225, 'Fourniture de bureau', 6022, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6026, 'Emballages', 602, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60261, 'Emballages perdus', 6026, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60265, ' Emballages r�cup�rables non identifiables', 6026, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60267, 'Emballages � usage mixte', 6026, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (604, 'Achats d''�tudes et prestations de services', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (605, 'Achats de mat�riel, �quipements et travaux', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (606, 'Achats non stock�s de mati�re et fournitures', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6061, 'Fournitures non stockables (eau, �nergie, ...)', 606, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6063, 'Fournitures d''entretien et de petit �quipement', 606, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6064, 'Fournitures administratives', 606, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6068, 'Autres mati�res et fournitures', 606, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (607, 'Achats de marchandises', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6071, 'Marchandise (ou groupe) A', 607, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6072, 'Marchandise (ou groupe) B', 607, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (608, '(Compte r�serv�, le cas �ch�ant, � la r�capitulation des frais accessoires incorpor�s aux achats)', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (609, 'Rabais, remises et ristournes obtenus sur achats', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6091, 'de mati�res premi�res (et fournitures)', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6092, 'd''autres approvisionnements stock�s', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6094, 'd''�tudes et prestations de services', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6095, 'de mat�riel, �quipements et travaux', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6096, 'd''approvisionnements non stock�s', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6097, 'de marchandises', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6098, 'Rabais, remises et ristournes non affect�s', 609, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (603, 'variations des stocks (approvisionnements et marchandises)', 60, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6031, 'Variation des stocks de mati�res premi�res (et fournitures)', 603, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6032, 'Variation des stocks des autres approvisionnements', 603, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6037, 'Variation des stocks de marchandises', 603, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (61, 'autres charges externes - Services ext�rieurs', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (611, 'Sous-traitance g�n�rale', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (612, 'Redevances de cr�dit-bail', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6122, 'Cr�dit-bail mobilier', 612, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6125, 'Cr�dit-bail immobilier', 612, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (613, 'Locations', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6132, 'Locations immobili�res', 613, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6135, 'Locations mobili�res', 613, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6136, 'Malis sur emballages', 613, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (614, 'Charges locatives et de copropri�t�', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (615, 'Entretien et r�parations', 61, 'FR');
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
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6165, 'Insolvabilit� clients', 616, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (617, 'Etudes et recherches', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (618, 'Divers', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6181, 'Documentation g�n�rale', 618, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6183, 'Documentation technique', 618, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6185, 'Frais de colloques, s�minaires, conf�rences', 618, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (619, 'Rabais, remises et ristournes obtenus sur services ext�rieurs', 61, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (62, 'autres charges externes - Autres services ext�rieurs', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (621, 'Personnel ext�rieur � l''entreprise', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6211, 'Personnel int�rimaire', 621, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6214, 'Personnel d�tach� ou pr�t� � l''entreprise', 621, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (622, 'R�mun�rations d''interm�diaires et honoraires', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6221, 'Commissions et courtages sur achats', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6222, 'Commissions et courtages sur ventes', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6224, 'R�mun�rations des transitaires', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6225, 'R�mun�rations d''affacturage', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6226, 'Honoraires', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6227, 'Frais d''actes et de contentieux', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6228, 'Divers', 622, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (623, 'Publicit�, publications, relations publiques', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6231, 'Annonces et insertions', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6232, 'Echantillons', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6233, 'Foires et expositions', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6234, 'Cadeaux � la client�le', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6235, 'Primes', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6236, 'Catalogues et imprim�s', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6237, 'Publications', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6238, 'Divers (pourboires, dont courant, ...)', 623, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (624, 'Transports de biens et transports collectifs du personnel', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6241, 'Transports sur achats', 624, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6242, 'Transports sur ventes', 624, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6243, 'Transports entre �tablissements ou chantiers', 624, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6244, 'Transports administratifs', 624, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6247, 'Transports collectifs du personnel', 624, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6248, 'Divers', 624, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (625, 'D�placements, missions et r�ceptions', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6251, 'Voyages et d�placements', 625, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6255, 'Frais de d�m�nagement', 625, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6256, 'Missions', 625, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6257, 'R�ceptions', 625, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (626, 'Frais postaux et de t�l�communications', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (627, 'Services bancaires et assimil�s', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6271, 'Frais sur titres (achat, vente, garde)', 627, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6272, 'Commissions et frais sur �mission d''emprunts', 627, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6275, 'Frais sur effets', 627, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6276, 'Location de coffres', 627, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6278, 'Autres frais et commissions sur prestations de services', 627, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (628, 'Divers', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6281, 'Concours divers (cotisations, ...)', 628, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6284, 'Frais de recrutement de personnel', 628, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (629, 'Rabais, remises et ristournes obtenus sur autres services ext�rieurs', 62, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63, 'Imp�ts, taxes et versements assimiles', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (631, 'Imp�ts, taxes et versements assimil�s sur r�mun�rations (administrations des imp�ts)', 63, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6311, 'Taxe sur les salaires', 631, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6312, 'Taxe d''apprentissage', 631, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6313, 'Participation des employeurs � la formation professionnelle continue', 631, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6314, 'Cotisation pour d�faut d''investissement obligatoire dans la construction', 631, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6318, 'Autres', 631, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (633, 'Imp�ts, taxes et versements assimil�s sur r�mun�rations (autres organismes)', 63, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6331, 'Versement de transport', 633, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6332, 'Allocations logement', 633, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6333, 'Participation des employeurs � la formation professionnelle continue', 633, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6334, 'Participation des employeurs � l''effort de construction', 633, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6335, 'Versements lib�ratoires ouvrant droit � l''exon�ration de la taxe d''apprentissage', 633, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6338, 'Autres', 633, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (635, 'Autres imp�ts, taxes et versements assimil�s (administrations des imp�ts)', 63, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6351, 'Imp�ts directs (sauf imp�ts sur les b�n�fices)', 635, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63511, 'Taxe professionnelle', 6351, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63512, 'Taxes fonci�res', 6351, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63513, 'Autres imp�ts locaux', 6351, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63514, 'Taxe sur les v�hicules des soci�t�s', 6351, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6352, 'Taxe sur le chiffre d''affaires non r�cup�rables', 635, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6353, 'Imp�ts indirects', 635, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6354, 'Droits d''enregistrement et de timbre', 635, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63541, 'Droits de mutation', 6354, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6358, 'Autres droits', 635, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (637, 'Autres imp�ts, taxes et versements assimil�s (autres organismes)', 63, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6371, 'Contribution sociale de solidarit� � la charge des soci�t�s', 637, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6372, 'Taxes per�ues par les organismes publics internationaux', 637, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6374, 'Imp�ts et taxes exigibles � l''Etranger', 637, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6378, 'Taxes diverses', 637, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (64, 'Charges de personnel', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (641, 'R�mun�rations du personnel', 64, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6411, 'Salaires, appointements', 641, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6412, 'Cong�s pay�s', 641, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6413, 'Primes et gratifications', 641, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6414, 'Indemnit�s et avantages divers', 641, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6415, 'Suppl�ment familial', 641, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (644, 'R�mun�ration du travail de l''exploitant', 64, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (645, 'Charges de s�curit� sociale et de pr�voyance', 64, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6451, 'Cotisations � l''URSSAF', 645, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6452, 'Cotisations aux mutuelles', 645, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6453, 'Cotisations aux caisses de retraites', 645, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6454, 'Cotisations aux ASSEDIC', 645, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6458, 'Cotisations aux autres organismes sociaux', 645, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (646, 'Cotisations sociales personnelles de l''exploitant', 64, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (647, 'Autres charges sociales', 64, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6471, 'Prestations directes', 647, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6472, 'Versements aux comit�s d''entreprise et d''�tablissement', 647, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6473, 'Versements aux comit�s d''hygi�ne et de s�curit�', 647, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6474, 'Versements aux autres �uvres sociales', 647, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6475, 'M�decine du travail, pharmacie', 647, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (648, 'Autres charges de personnel', 64, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (65, 'Autres charges de gestion courante', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (651, 'Redevances pour concessions, brevets, licences, marques, proc�d�s, logiciels, droits et valeurs similaires', 65, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6511, 'Redevances pour concessions, brevets, licences, marques, proc�d�s, logiciels', 651, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6516, 'Droits d''auteur et de reproduction', 651, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6518, 'Autres droits et valeurs similaires', 651, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (653, 'Jetons de pr�sence', 65, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (654, 'Pertes sur cr�ances irr�couvrables', 65, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6541, 'Cr�ances de l''exercice', 654, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6544, 'Cr�ances des exercices ant�rieurs', 654, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (655, 'Quotes-parts de r�sultat sur op�rations faites en commun', 65, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6551, 'Quote-part de b�n�fice transf�r�e (comptabilit� du g�rant)', 655, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6555, 'Quote-part de perte support�e (comptabilit� des associ�s non g�rants)', 655, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (658, 'Charges diverses de gestion courante', 65, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (66, 'Charges financi�res', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (661, 'Charges d''int�r�ts', 66, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6611, 'Int�r�ts des emprunts et dettes', 661, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (66116, 'des emprunts et dettes assimil�es', 6611, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (66117, 'des dettes rattach�es � des participations', 6611, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6615, 'Int�r�ts des comptes courants et des d�p�ts cr�diteurs', 661, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6616, 'Int�r�ts bancaires et sur op�rations de financement (escompte,...)', 661, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6617, 'Int�r�ts des obligations cautionn�es', 661, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6618, 'Int�r�ts des autres dettes', 661, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (66181, 'des dettes commerciales', 6618, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (66188, 'des dettes diverses', 6618, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (664, 'Pertes sur cr�ances li�es � des participations', 66, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (665, 'Escomptes accord�s', 66, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (666, 'Pertes de change', 66, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (667, 'Charges nettes sur cessions de valeurs mobili�res de placement', 66, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (668, 'Autres charges financi�res', 66, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (67, 'Charges exceptionnelles', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (671, 'Charges exceptionnelles sur op�rations de gestion', 67, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6711, 'P�nalit�s sur march�s (et d�dits pay�s sur achats et ventes)', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6712, 'P�nalit�s, amendes fiscales et p�nales', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6713, 'Dons, lib�ralit�s', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6714, 'Cr�ances devenues irr�couvrables dans l''exercice', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6715, 'Subventions accord�es', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6717, 'Rappel d''imp�ts (autres qu''imp�ts sur les b�n�fices)', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6718, 'Autres charges exceptionnelles sur op�rations de gestion', 671, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (672, '(Compte � la disposition des entit�s pour enregistrer, en cours d''exercice, les charges sur xercices ant�rieurs)', 67, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (675, 'Valeurs comptables des �l�ments d''actif c�d�s', 67, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6751, 'Immobilisations incorporelles', 675, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6752, 'Immobilisations corporelles', 675, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6756, 'Immobilisations financi�res', 675, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6758, 'Autres �l�ments d''actif', 675, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (678, 'Autres charges exceptionnelles', 67, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6781, 'Malis provenant de clauses d''indexation', 678, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6782, 'Lots', 678, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6783, 'Malis provenant du rachat par l''entreprise d''actions et obligations �mises par elle-m�me', 678, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6788, 'Charges exceptionnelles diverses', 678, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68, 'Dotations aux amortissements et aux provisions', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (681, 'Dotations aux amortissements et aux provisionsCharges d''exploitation', 68, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6811, 'Dotations aux amortissements sur immobilisations incorporelles et corporelles', 681, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68111, 'Immobilisations incorporelles', 6811, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68112, 'Immobilisations corporelles', 6811, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6812, 'Dotations aux amortissements des charges d''exploitation � r�partir', 681, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6815, 'Dotations aux provisions pour risques et charges d''exploitation', 681, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6816, 'Dotations aux provisions pour d�pr�ciation des immobilisations incorporelles et corporelles', 681, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68161, 'Immobilisations incorporelles', 6816, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68162, 'Immobilisations corporelles', 6816, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6817, 'Dotations aux provisions pour d�pr�ciation des actifs circulants', 681, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68173, 'Stocks et en-cours', 6817, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68174, 'Cr�ances', 6817, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (686, 'Dotations aux amortissements et aux provisionsCharges financi�res', 68, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6861, 'Dotations aux amortissements des primes de remboursement des obligations', 686, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6865, 'Dotations aux provisions pour risques et charges financiers', 686, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6866, 'Dotations aux provisions pour d�pr�ciation des �l�ments financiers', 686, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68662, 'Immobilisations financi�res', 6866, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68665, 'Valeurs mobili�res de placement', 6866, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6868, 'Autres dotations', 686, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (687, 'Dotations aux amortissements et aux provisionsCharges exceptionnelles', 68, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6871, 'Dotations aux amortissements exceptionnels des immobilisations', 687, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6872, 'Dotations aux provisions r�glement�es (immobilisations)', 687, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68725, 'Amortissements d�rogatoires', 6872, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6873, 'Dotations aux provisions r�glement�es (stocks)', 687, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6874, 'Dotations aux autres provisions r�glement�es', 687, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6875, 'Dotations aux provisions pour risques et charges exceptionnels', 687, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6876, 'Dotations aux provisions pour d�pr�ciations exceptionnelles', 687, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (69, 'participation des salariesimp�ts sur les benefices et assimiles', 6, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (691, 'Participation des salari�s aux r�sultats', 69, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (695, 'Imp�ts sur les b�n�fices', 69, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6951, 'Imp�ts dus en France', 695, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6952, 'Contribution additionnelle � l''imp�t sur les b�n�fices', 695, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6954, 'Imp�ts dus � l''�tranger', 695, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (696, 'Suppl�ments d''imp�t sur les soci�t�s li�s aux distributions', 69, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (697, 'Imposition forfaitaire annuelle des soci�t�s', 69, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (698, 'Int�gration fiscale', 69, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6981, 'Int�gration fiscaleCharges', 698, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6989, 'Int�gration fiscaleProduits', 698, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (699, 'ProduitsReports en arri�re des d�ficits', 69, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7, 'Comptes de produits', 0, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (70, 'ventes de produits fabriques, prestations de services, marchandises', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (701, 'Ventes de produits finis', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7011, 'Produits finis (ou groupe) A', 701, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7012, 'Produits finis (ou groupe) B', 701, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (702, 'Ventes de produits interm�diaires', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (703, 'Ventes de produits r�siduels', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (704, 'Travaux', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7041, 'Travaux de cat�gorie (ou activit�) A', 704, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7042, 'Travaux de cat�gorie (ou activit�) B', 704, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (705, 'Etudes', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (706, 'Prestations de services', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (707, 'Ventes de marchandises', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7071, 'Marchandises (ou groupe) A', 707, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7072, 'Marchandises (ou groupe) B', 707, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (708, 'Produits des activit�s annexes', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7081, 'Produits des services exploit�s dans l''int�r�t du personnel', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7082, 'Commissions et courtages', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7083, 'Locations diverses', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7084, 'Mise � disposition de personnel factur�e', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7085, 'Ports et frais accessoires factur�s', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7086, 'Bonis sur reprises d''emballages consign�s', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7087, 'Bonifications obtenues des clients et primes sur ventes', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7088, 'Autres produits d''activit�s annexes (cessions d''approvisionnements,...)', 708, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (709, 'Rabais, remises et ristournes accord�s par l''entreprise', 70, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7091, 'sur ventes de produits finis', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7092, 'sur ventes de produits interm�diaires', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7094, 'sur travaux', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7095, 'sur �tudes', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7096, 'sur prestations de services', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7097, 'sur ventes de marchandises', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7098, 'sur produits des activit�s annexes', 709, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71, 'production stock�e (ou d�stockage)', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (713, 'Variation des stocks (en-cours de production, produits)', 71, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7133, 'Variation des en-cours de production de biens', 713, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71331, 'Produits en cours', 7133, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71335, 'Travaux en cours', 7133, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7134, 'Variation des en-cours de production de services', 713, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71341, 'Etudes en cours', 7134, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71345, 'Prestations de services en cours', 7134, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7135, 'Variation des stocks de produits', 713, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71351, 'Produits interm�diaires', 7135, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71355, 'Produits finis', 7135, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71358, 'Produits r�siduels', 7135, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (72, 'Production immobilis�e', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (721, 'Immobilisations incorporelles', 72, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (722, 'Immobilisations corporelles', 72, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (73, 'Produits nets partiels sur op�rations � long terme', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (731, 'Produits nets partiels sur op�rations en cours (� subdiviser par op�ration)', 73, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (739, 'Produits nets partiels sur op�rations termin�es', 73, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (74, 'Subventions d''exploitation', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (75, 'Autres produits de gestion courante', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (751, 'Redevances pour concessions, brevets, licences, marques, proc�d�s, logiciels, droits et valeurs similaires', 75, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7511, 'Redevances pour concessions, brevets, licences, marques, proc�d�s, logiciels', 751, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7516, 'Droits d''auteur et de reproduction', 751, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7518, 'Autres droits et valeurs similaires', 751, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (752, 'Revenus des immeubles non affect�s � des activit�s professionnelles', 75, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (753, 'Jetons de pr�sence et r�mun�rations d''administrateurs, g�rants,...', 75, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (754, 'Ristournes per�ues des coop�ratives (provenant des exc�dents)', 75, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (755, 'Quotes-parts de r�sultat sur op�rations faites en commun', 75, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7551, 'Quote-part de perte transf�r�e (comptabilit� du g�rant)', 755, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7555, 'Quote-part de b�n�fice attribu�e (comptabilit� des associ�s non-g�rants)', 755, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (758, 'Produits divers de gestion courante', 75, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (76, 'Produits financiers', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (761, 'Produits de participations', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7611, 'Revenus des titres de participation', 761, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7616, 'Revenus sur autres formes de participation', 761, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7617, 'Revenus des cr�ances rattach�es � des participations', 761, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (762, 'Produits des autres immobilisations financi�res', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7621, 'Revenus des titres immobilis�s', 762, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7626, 'Revenus des pr�ts', 762, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7627, 'Revenus des cr�ances immobilis�es', 762, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (763, 'Revenus des autres cr�ances', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7631, 'Revenus des cr�ances commerciales', 763, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7638, 'Revenus des cr�ances diverses', 763, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (764, 'Revenus des valeurs mobili�res de placement', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (765, 'Escomptes obtenus', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (766, 'Gains de change', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (767, 'Produits nets sur cessions de valeurs mobili�res de placement', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (768, 'Autres produits financiers', 76, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (77, 'Produits exceptionnels', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (771, 'Produits exceptionnels sur op�rations de gestion', 77, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7711, 'D�dits et p�nalit�s per�us sur achats et sur ventes', 771, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7713, 'Lib�ralit�s re�ues', 771, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7714, 'Rentr�es sur cr�ances amorties', 771, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7715, 'Subventions d''�quilibre', 771, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7717, 'D�gr�vements d''imp�ts autres qu''imp�ts sur les b�n�fices', 771, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7718, 'Autres produits exceptionnels sur op�rations de gestion', 771, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (772, '(Compte � la disposition des entit�s pour enregistrer, en cours d''exercice, les produits sur xercices ant�rieurs)', 77, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (775, 'Produits des cessions d''�l�ments d''actif', 77, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7751, 'Immobilisations incorporelles', 775, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7752, 'Immobilisations corporelles', 775, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7756, 'Immobilisations financi�res', 775, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7758, 'Autres �l�ments d''actif', 775, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (777, 'Quote-part des subventions d''investissement vir�e au r�sultat de l''exercice', 77, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (778, 'Autres produits exceptionnels', 77, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7781, 'Bonis provenant de clauses d''indexation', 778, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7782, 'Lots', 778, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7783, 'Bonis provenant du rachat par l''entreprise d''actions et d''obligations �mises par elle-m�me', 778, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7788, 'Produits exceptionnels divers', 778, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78, 'Reprises sur amortissements et provisions', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (781, 'Reprises sur amortissements et provisions (� inscrire dans les produits d''exploitation)', 78, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7811, 'Reprises sur amortissements des immobilisations incorporelles et corporelles', 781, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78111, 'Immobilisations incorporelles', 7811, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78112, 'Immobilisations corporelles', 7811, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7815, 'Reprises sur provisions pour risques et charges d''exploitation', 781, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7816, 'Reprises sur provisions pour d�pr�ciation des immobilisations incorporelles et corporelles', 781, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78161, 'Immobilisations incorporelles', 7816, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78162, 'Immobilisations corporelles', 7816, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7817, 'Reprises sur provisions pour d�pr�ciation des actifs circulants', 781, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78173, 'Stocks et en-cours', 7817, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78174, 'Cr�ances', 7817, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (786, 'Reprises sur provisions pour risques (� inscrire dans les produits financiers)', 78, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7865, 'Reprises sur provisions pour risques et charges financiers', 786, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7866, 'Reprises sur provisions pour d�pr�ciation des �l�ments financiers', 786, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78662, 'Immobilisations financi�res', 7866, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78665, 'Valeurs mobili�res de placements', 7866, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (787, 'Reprises sur provisions (� inscrire dans les produits exceptionnels)', 78, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7872, 'Reprises sur provisions r�glement�es (immobilisations)', 787, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78725, 'Amortissements d�rogatoires', 7872, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78726, 'Provision sp�ciale de r��valuation', 7872, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (78727, 'Plus-values r�investies', 7872, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7873, 'Reprises sur provisions r�glement�es (stocks)', 787, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7874, 'Reprises sur autres provisions r�glement�es', 787, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7875, 'Reprises sur provisions pour risques et charges exceptionnels', 787, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7876, 'Reprises sur provisions pour d�pr�ciations exceptionnelles', 787, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (79, 'Transferts de charges', 7, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (791, 'Transferts de charges d''exploitation', 79, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (796, 'Transferts de charges financi�res', 79, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (797, 'Transferts de charges exceptionnelles ', 79, 'FR');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (8, 'Comptes Sp�ciaux', 0, 'FR');


--
-- Data for Name: tva_rate; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis � la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis � la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis � la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis � la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis � la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis � la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis � la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis � la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis � la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis � la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (5, '0%', 0.0000, 'Pas soumis � la TVA', NULL);
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (1, '19.6%', 0.1960, 'TVA ', '44566,44571');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (2, '5.5%', 0.0550, 'TVA r�duite', '44566,44571');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (3, '2.1%', 0.0210, 'TVA r�duite', '44566,44571');
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

