--
-- PostgreSQL database dump
--

SET search_path = public, pg_catalog;

--
-- Data for TOC entry 6 (OID 44866)
-- Name: ac_users; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO ac_users (use_id, use_first_name, use_name, use_login, use_active, use_pass, use_admin, use_theme, use_usertype) VALUES (4, 'demo', 'demo', 'demo', 1, 'fe01ce2a7fbac8fafaed7c982a04e229', 0, 'Light', 'user');
INSERT INTO ac_users (use_id, use_first_name, use_name, use_login, use_active, use_pass, use_admin, use_theme, use_usertype) VALUES (5, 'dany', 'dany', 'dany', 1, '1b9fc02e98389d29c1506fe944b07d16', 0, 'Light', 'user');
INSERT INTO ac_users (use_id, use_first_name, use_name, use_login, use_active, use_pass, use_admin, use_theme, use_usertype) VALUES (1, NULL, NULL, 'phpcompta', 1, 'b1cc88e1907cde80cb2595fa793b3da9', 1, 'Light', 'user');


--
-- Data for TOC entry 7 (OID 44876)
-- Name: ac_dossier; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO ac_dossier (dos_id, dos_name, dos_description, dos_jnt_user) VALUES (1, 'Demo', 'Base de données pour développement & démo', 0);
INSERT INTO ac_dossier (dos_id, dos_name, dos_description, dos_jnt_user) VALUES (4, 'Test Sécurité', 'Test pour la sécurité', 0);


--
-- Data for TOC entry 8 (OID 44885)
-- Name: jnt_use_dos; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (1, 1, 1);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (2, 1, 2);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (3, 1, 5);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (4, 1, 6);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (13, 4, 1);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (14, 1, 7);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (15, 1, 8);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (16, 1, 9);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (17, 1, 10);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (18, 1, 11);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (19, 1, 3);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (20, 1, 4);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (22, 5, 4);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (23, 5, 1);


--
-- Data for TOC entry 9 (OID 44888)
-- Name: version; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO "version" (val) VALUES (3);


--
-- Data for TOC entry 10 (OID 44892)
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
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (10, 21, 'NO');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (12, 23, 'NO');
INSERT INTO priv_user (priv_id, priv_jnt, priv_priv) VALUES (11, 22, 'R');


--
-- Data for TOC entry 11 (OID 44898)
-- Name: theme; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO theme (the_name, the_filestyle, the_filebutton) VALUES ('classic', 'style.css', NULL);
INSERT INTO theme (the_name, the_filestyle, the_filebutton) VALUES ('Aqua', 'style-aqua.css', NULL);
INSERT INTO theme (the_name, the_filestyle, the_filebutton) VALUES ('Elegant', 'style-elegant.css', NULL);
INSERT INTO theme (the_name, the_filestyle, the_filebutton) VALUES ('Light', 'style-light.css', NULL);


--
-- Data for TOC entry 12 (OID 44905)
-- Name: modeledef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO modeledef (mod_id, mod_name, mod_desc) VALUES (1, '(BE) Basique', 'Comptabilité Belge, tout doit être adaptée');
INSERT INTO modeledef (mod_id, mod_name, mod_desc) VALUES (2, 'test2', 'test');
INSERT INTO modeledef (mod_id, mod_name, mod_desc) VALUES (13, 'Autre', 'Test');


--
-- Data for TOC entry 13 (OID 679377)
-- Name: log; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- TOC entry 1 (OID 44864)
-- Name: users_id; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('users_id', 5, true);


--
-- TOC entry 2 (OID 44883)
-- Name: seq_jnt_use_dos; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('seq_jnt_use_dos', 23, true);


--
-- TOC entry 3 (OID 44890)
-- Name: seq_priv_user; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('seq_priv_user', 12, true);


--
-- TOC entry 4 (OID 44903)
-- Name: s_modid; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_modid', 13, true);


--
-- TOC entry 5 (OID 44911)
-- Name: dossier_id; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('dossier_id', 4, true);


