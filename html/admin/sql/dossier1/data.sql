--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;

SET search_path = public, pg_catalog;

--
-- Data for TOC entry 28 (OID 36222)
-- Name: tmp_pcmn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 29 (OID 36229)
-- Name: version; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 30 (OID 36235)
-- Name: parm_money; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 31 (OID 36238)
-- Name: parm_periode; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 32 (OID 36254)
-- Name: jrn_type; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 33 (OID 36259)
-- Name: jrn_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 34 (OID 36270)
-- Name: jrnx; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 35 (OID 36281)
-- Name: user_pref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 36 (OID 36290)
-- Name: formdef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 37 (OID 36296)
-- Name: form; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 38 (OID 36306)
-- Name: centralized; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 39 (OID 36317)
-- Name: user_sec_jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 40 (OID 36323)
-- Name: action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 41 (OID 36328)
-- Name: user_sec_act; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 42 (OID 36336)
-- Name: jrn_action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 43 (OID 36343)
-- Name: tva_rate; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 44 (OID 36359)
-- Name: fiche_def_ref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 45 (OID 36365)
-- Name: fiche_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 46 (OID 36372)
-- Name: attr_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 47 (OID 36378)
-- Name: fiche; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 48 (OID 36381)
-- Name: jnt_fic_att_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 49 (OID 36384)
-- Name: attr_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 50 (OID 36389)
-- Name: jnt_fic_attr; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 51 (OID 36393)
-- Name: jrn_rapt; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 52 (OID 36402)
-- Name: jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 53 (OID 36410)
-- Name: stock_goods; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 54 (OID 36420)
-- Name: attr_min; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- TOC entry 2 (OID 36231)
-- Name: s_periode; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_periode', 1, false);


--
-- TOC entry 3 (OID 36233)
-- Name: s_currency; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_currency', 1, false);


--
-- TOC entry 4 (OID 36246)
-- Name: s_jrn_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_def', 5, false);


--
-- TOC entry 5 (OID 36248)
-- Name: s_grpt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_grpt', 1, false);


--
-- TOC entry 6 (OID 36250)
-- Name: s_jrn_op; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_op', 1, false);


--
-- TOC entry 7 (OID 36252)
-- Name: s_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn', 1, false);


--
-- TOC entry 8 (OID 36268)
-- Name: s_jrnx; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrnx', 1, false);


--
-- TOC entry 9 (OID 36286)
-- Name: s_formdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_formdef', 1, false);


--
-- TOC entry 10 (OID 36288)
-- Name: s_form; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_form', 1, false);


--
-- TOC entry 11 (OID 36302)
-- Name: s_idef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_idef', 1, false);


--
-- TOC entry 12 (OID 36304)
-- Name: s_centralized; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_centralized', 1, false);


--
-- TOC entry 13 (OID 36313)
-- Name: s_user_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_user_jrn', 1, false);


--
-- TOC entry 14 (OID 36315)
-- Name: s_user_act; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_user_act', 1, false);


--
-- TOC entry 15 (OID 36334)
-- Name: s_jrnaction; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrnaction', 1, false);


--
-- TOC entry 16 (OID 36349)
-- Name: s_fiche; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_fiche', 1, false);


--
-- TOC entry 17 (OID 36351)
-- Name: s_fiche_def_ref; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_fiche_def_ref', 1, false);


--
-- TOC entry 18 (OID 36353)
-- Name: s_fdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_fdef', 1, false);


--
-- TOC entry 19 (OID 36355)
-- Name: s_attr_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_attr_def', 1, false);


--
-- TOC entry 20 (OID 36357)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jnt_fic_att_value', 1, false);


--
-- TOC entry 21 (OID 36391)
-- Name: s_jrn_rapt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_rapt', 1, false);


--
-- TOC entry 22 (OID 36400)
-- Name: s_stock_goods; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_stock_goods', 1, false);


--
-- TOC entry 23 (OID 36422)
-- Name: s_internal; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_internal', 1, false);


--
-- TOC entry 24 (OID 36424)
-- Name: s_jrn_4; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_4', 1, false);


--
-- TOC entry 25 (OID 36426)
-- Name: s_jrn_3; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_3', 1, false);


--
-- TOC entry 26 (OID 36428)
-- Name: s_jrn_2; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_2', 1, false);


--
-- TOC entry 27 (OID 36430)
-- Name: s_jrn_1; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_1', 1, false);


