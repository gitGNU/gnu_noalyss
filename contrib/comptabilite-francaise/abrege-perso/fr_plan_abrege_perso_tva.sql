--
-- PostgreSQL database dump
-- Version 2007/10/23 22:23
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Data for Name: tva_rate; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) FROM stdin;
101	TVAFranceNormal	0.196	TVA 19,6% - France m�tropolitaine - Taux normal	445661,44571
102	TVAFranceR�duit	0.055	TVA 5,5% - France m�tropolitaine - Taux r�duit	445662,44572
103	TVAFranceSuperR�duit	0.021	TVA 2,1% - France m�tropolitaine - Taux super r�duit	445663,44573
104	TVAFranceImmos	0.196	TVA 19,6% - France m�tropolitaine - Taux immobilisations	44562,0
105	TVAFranceAnciens	0	TVA x% - France m�tropolitaine - Taux anciens	445,445
201	TVADomNormal	0.085	TVA 8,5%  - DOM - Taux normal	445,445
202	TVADomNPR	0.085	TVA 8,5% - DOM - Taux normal NPR	445,445
203	TVADomR�duit	0.021	TVA 2,1% - DOM - Taux r�duit	445,445
204	TVADom-I	0.0175	TVA 1,75% - DOM - Taux I	445,445
205	TVADomPresse	0.0105	TVA 1,05% - DOM - Taux publications de presse	445,445
206	TVADomOctroi	0	TVA x% - DOM - Taux octroi de mer	445,445
207	TVADomImmos	0	TVA x% - DOM - Taux immobilisations	445,0
301	TVACorse-I	0.13	TVA 13% - Corse - Taux I	445,445
302	TVACorse-II	0.08	TVA 8% - Corse - Taux II	445,445
303	TVACorse-III	0.021	TVA 2,1% - Corse - Taux III	445,445
304	TVACorse-IV	0.009	TVA 0,9% - Corse - Taux IV	445,445
305	TVACorseImmos	0	TVA x% - Corse - Taux immobilisations	445,0
401	TVAacquisIntracom	0	TVA x% - Acquisitions intracommunautaires/Pays	445,445
402	TVAacquisIntracomImmos	0	TVA x% - Acquisitions intracommunautaires immobilisations/Pays	445,0
501	TVAfranchise	0	TVA x% - Non imposable : Achats en franchise	
502	TVAexport	0	TVA x% - Non imposable : Exports hors CE/Pays	
503	TVAautres	0	TVA x% - Non imposable : Autres op�rations	
504	TVAlivrIntracom	0	TVA x% - Non imposable : Livraisons intracommunautaires/Pays	
\.

--
-- PostgreSQL database dump complete
--
