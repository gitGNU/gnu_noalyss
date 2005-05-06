--
-- PostgreSQL database dump
--

SET search_path = public, pg_catalog;

--
-- Data for TOC entry 6 (OID 58856)
-- Name: ac_users; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO ac_users (use_id, use_first_name, use_name, use_login, use_active, use_pass, use_admin, use_theme, use_usertype) VALUES (4, 'demo', 'demo', 'demo', 1, 'fe01ce2a7fbac8fafaed7c982a04e229', 0, 'Light', 'user');
INSERT INTO ac_users (use_id, use_first_name, use_name, use_login, use_active, use_pass, use_admin, use_theme, use_usertype) VALUES (1, NULL, NULL, 'phpcompta', 1, 'b1cc88e1907cde80cb2595fa793b3da9', 1, 'Light', 'user');


--
-- Data for TOC entry 7 (OID 58866)
-- Name: ac_dossier; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO ac_dossier (dos_id, dos_name, dos_description, dos_jnt_user) VALUES (1, 'Demo', 'Base de données pour développement & démo', 0);


--
-- Data for TOC entry 8 (OID 58875)
-- Name: jnt_use_dos; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (1, 1, 1);
INSERT INTO jnt_use_dos (jnt_id, use_id, dos_id) VALUES (13, 4, 1);


--
-- Data for TOC entry 9 (OID 58878)
-- Name: version; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO "version" (val) VALUES (4);


--
-- Data for TOC entry 10 (OID 58882)
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
-- Data for TOC entry 11 (OID 58888)
-- Name: theme; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO theme (the_name, the_filestyle, the_filebutton) VALUES ('classic', 'style.css', NULL);
INSERT INTO theme (the_name, the_filestyle, the_filebutton) VALUES ('Aqua', 'style-aqua.css', NULL);
INSERT INTO theme (the_name, the_filestyle, the_filebutton) VALUES ('Elegant', 'style-elegant.css', NULL);
INSERT INTO theme (the_name, the_filestyle, the_filebutton) VALUES ('Light', 'style-light.css', NULL);


--
-- Data for TOC entry 12 (OID 58895)
-- Name: modeledef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO modeledef (mod_id, mod_name, mod_desc) VALUES (1, '(BE) Basique', 'Comptabilité Belge, tout doit être adaptée');


--
-- TOC entry 1 (OID 58854)
-- Name: users_id; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('users_id', 5, true);


--
-- TOC entry 2 (OID 58873)
-- Name: seq_jnt_use_dos; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('seq_jnt_use_dos', 23, true);


--
-- TOC entry 3 (OID 58880)
-- Name: seq_priv_user; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('seq_priv_user', 12, true);


--
-- TOC entry 4 (OID 58893)
-- Name: s_modid; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_modid', 6, false);


--
-- TOC entry 5 (OID 58901)
-- Name: dossier_id; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('dossier_id', 12, true);


