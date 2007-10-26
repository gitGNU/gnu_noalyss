--
-- PostgreSQL database dump
-- Version 2007-09-08 02:49
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Data for Name: parm_code; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY parm_code (p_code, p_value, p_comment) FROM stdin;
BANQUE	512001	Poste comptable par d�faut pour les banques
COMPTE_COURANT	512002	Poste comptable par d�faut pour le compte courant
CAISSE	53	Poste comptable par d�faut pour les caisses
VENTE	706	Poste comptable par d�faut pour les ventes
VIREMENT_INTERNE	58	Poste comptable par d�faut pour les virements internes
\.

--
-- PostgreSQL database dump complete
--
