--
-- PostgreSQL database dump
--

SET search_path = public, pg_catalog;

--
-- Data for TOC entry 6 (OID 940848)
-- Name: ac_users; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO ac_users (use_id, use_first_name, use_name, use_login, use_active, use_pass, use_admin, use_theme, use_usertype) VALUES (4, 'demo', 'demo', 'demo', 1, 'fe01ce2a7fbac8fafaed7c982a04e229', 0, 'Light', 'user');
INSERT INTO ac_users (use_id, use_first_name, use_name, use_login, use_active, use_pass, use_admin, use_theme, use_usertype) VALUES (5, 'Dany', 'De Bontridder', 'dany', 1, '3adc2ecf3ffced14bcde9be1c078df5f', 0, 'Light', 'user');
INSERT INTO ac_users (use_id, use_first_name, use_name, use_login, use_active, use_pass, use_admin, use_theme, use_usertype) VALUES (6, 'France ', 'Bertrand', 'france', 1, 'c7d71eed70ad733630046409d8adfb2f', 0, 'Light', 'user');
INSERT INTO ac_users (use_id, use_first_name, use_name, use_login, use_active, use_pass, use_admin, use_theme, use_usertype) VALUES (1, NULL, NULL, 'phpcompta', 1, '3adc2ecf3ffced14bcde9be1c078df5f', 1, 'Light', 'user');


--
-- Data for TOC entry 7 (OID 940858)
-- Name: ac_dossier; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO ac_dossier (dos_id, dos_name, dos_description, dos_jnt_user) VALUES (1, 'Demo', 'Base de données pour développement & démo', 0);
INSERT INTO ac_dossier (dos_id, dos_name, dos_description, dos_jnt_user) VALUES (3, 'Alchimerys', 'Comptabilité production', 0);
INSERT INTO ac_dossier (dos_id, dos_name, dos_description, dos_jnt_user) VALUES (4, 'Alchimerys 2004', 'Alchimerys 2004', 0);
INSERT INTO ac_dossier (dos_id, dos_name, dos_description, dos_jnt_user) VALUES (7, 'Test', '', 0);
INSERT INTO ac_dossier (dos_id, dos_name, dos_description, dos_jnt_user) VALUES (9, 'Creation', 'Test', 0);
INSERT INTO ac_dossier (dos_id, dos_name, dos_description, dos_jnt_user) VALUES (10, 'Nouveau test', '', 0);
INSERT INTO ac_dossier (dos_id, dos_name, dos_description, dos_jnt_user) VALUES (12, 'test 3', '', 0);


--
-- Data for TOC entry 8 (OID 940867)
-- Name: jnt_use_dos; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (1, 1, 1);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (13, 4, 1);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (19, 1, 3);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (20, 5, 3);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (21, 5, 1);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (22, 6, 3);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (23, 6, 1);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (24, 1, 4);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (26, 1, 6);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (27, 1, 8);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (28, 1, 9);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (29, 1, 10);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (30, 1, 12);


--
-- Data for TOC entry 9 (OID 940870)
-- Name: version; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO "version" (val) VALUES (4);


--
-- Data for TOC entry 10 (OID 940874)
-- Name: priv_user; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (1, 5, 'NO');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (2, 6, 'NO');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (3, 7, 'NO');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (4, 8, 'NO');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (5, 9, 'NO');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (6, 10, 'NO');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (7, 11, 'W');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (8, 12, 'NO');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (9, 13, 'W');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (12, 22, 'NO');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (13, 23, 'R');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (10, 20, 'W');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (11, 21, 'W');


--
-- Data for TOC entry 11 (OID 940880)
-- Name: theme; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO theme (the_name, the_filestyle, the_filebutton) VALUES ('classic', 'style.css', NULL);
INSERT INTO theme (the_name, the_filestyle, the_filebutton) VALUES ('Aqua', 'style-aqua.css', NULL);
INSERT INTO theme (the_name, the_filestyle, the_filebutton) VALUES ('Elegant', 'style-elegant.css', NULL);
INSERT INTO theme (the_name, the_filestyle, the_filebutton) VALUES ('Light', 'style-light.css', NULL);


--
-- Data for TOC entry 12 (OID 940887)
-- Name: modeledef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO modeledef (mod_id, mod_name, mod_desc) VALUES (1, '(BE) Basique', 'Comptabilité Belge, tout doit être adaptée');
INSERT INTO modeledef (mod_id, mod_name, mod_desc) VALUES (3, 'Alchimerys', 'Comptabilité Production');
INSERT INTO modeledef (mod_id, mod_name, mod_desc) VALUES (7, 'Nouveay', '');
INSERT INTO modeledef (mod_id, mod_name, mod_desc) VALUES (10, 'Test', '');


--
-- TOC entry 1 (OID 940846)
-- Name: users_id; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('users_id', 6, true);


--
-- TOC entry 2 (OID 940865)
-- Name: seq_jnt_use_dos; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('seq_jnt_use_dos', 30, true);


--
-- TOC entry 3 (OID 940872)
-- Name: seq_priv_user; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('seq_priv_user', 13, true);


--
-- TOC entry 4 (OID 940885)
-- Name: s_modid; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_modid', 10, true);


--
-- TOC entry 5 (OID 940893)
-- Name: dossier_id; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('dossier_id', 12, true);


