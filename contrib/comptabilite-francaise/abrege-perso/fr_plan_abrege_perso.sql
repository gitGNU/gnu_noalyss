--
-- PostgreSQL database dump
-- Version 2007/09/16 10:54
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Data for Name: tmp_pcmn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) FROM stdin;
1	comptes de capitaux	0	FR
101	Capital	1	FR
105	Ecarts de r��valuation	1	FR
1061	R�serve l�gale	1	FR
1063	R�serves statutaires ou contractuelles	1	FR
1064	R�serves r�glement�es	1	FR
1068	Autres r�serves	1	FR
108	Compte de l'exploitant	1	FR
12	r�sultat de l'exercice (b�n�fice ou perte)	1	FR
145	Amortissements d�rogatoires	1	FR
146	Provision sp�ciale de r��valuation	1	FR
147	Plus-values r�investies	1	FR
148	Autres provisions r�glement�es	1	FR
15	Provisions pour risques et charges	1	FR
16	emprunts et dettes assimilees	1	FR
2	comptes d'immobilisations	0	FR
20	immobilisations incorporelles	2	FR
201	Frais d'�tablissement	20	FR
206	Droit au bail	20	FR
207	Fonds commercial	20	FR
208	Autres immobilisations incorporelles	20	FR
21	immobilisations corporelles	2	FR
23	immobilisations en cours	2	FR
27	autres immobilisations financieres	2	FR
280	Amortissements des immobilisations incorporelles	2	FR
281	Amortissements des immobilisations corporelles	2	FR
290	Provisions pour d�pr�ciation des immobilisations incorporelles	2	FR
291	Provisions pour d�pr�ciation des immobilisations corporelles (m�me ventilation que celle du compte 21)	2	FR
297	Provisions pour d�pr�ciation des autres immobilisations financi�res	2	FR
3	comptes de stocks et en cours	0	FR
31	matieres premi�res (et fournitures)	3	FR
32	autres approvisionnements	3	FR
33	en-cours de production de biens	3	FR
34	en-cours de production de services	3	FR
35	stocks de produits	3	FR
37	stocks de marchandises	3	FR
391	Provisions pour d�pr�ciation des mati�res premi�res (et fournitures)	3	FR
392	Provisions pour d�pr�ciation des autres approvisionnements	3	FR
393	Provisions pour d�pr�ciation des en-cours de production de biens	3	FR
394	Provisions pour d�pr�ciation des en-cours de production de services	3	FR
395	Provisions pour d�pr�ciation des stocks de produits	3	FR
397	Provisions pour d�pr�ciation des stocks de marchandises	3	FR
4	comptes de tiers	0	FR
400	Fournisseurs et Comptes rattach�s	4	FR
409	Fournisseurs d�biteurs	4	FR
410	Clients et Comptes rattach�s	4	FR
419	Clients cr�diteurs	4	FR
421	Personnel - R�mun�rations dues	4	FR
428	Personnel - Charges � payer et produits � recevoir	4	FR
43	S�curit� sociale et autres organismes sociaux	4	FR
431	S�curit� sociale	43	FR
43731	Cotis.Sal.+Pat. Retraite salari�s d�es	43	FR
43732	Cotis.Sal.+Pat. Retraite cadres d�es	43	FR
4374	Cotis.Sal.+Pat. ASSEDIC d�es	43	FR
444	Etat - Imp�ts sur les b�n�fices	4	FR
445	Etat - Taxes sur le chiffre d'affaires	4	FR
44562	T.V.A. sur immobilisations	445	FR
445661	T.V.A. d�ductible 19,6% sur autres biens et services	445	FR
445662	T.V.A. d�ductible 5,5% sur autres biens et services	445	FR
445663	T.V.A. d�ductible 2,1% sur autres biens et services	445	FR
44571	T.V.A. collect�e 19,6%	445	FR
44572	T.V.A. collect�e 5,5%	445	FR
44573	T.V.A. collect�e 2,1%	445	FR
447	Autres imp�ts, taxes et versements assimil�s	4	FR
45	Groupe et associes	4	FR
455	Associ�s - Comptes courants	45	FR
46	D�biteurs divers et cr�diteurs divers	4	FR
47	comptes transitoires ou d'attente	4	FR
481	Charges � r�partir sur plusieurs exercices	4	FR
486	Charges constat�es d'avance	4	FR
487	Produits constat�s d'avance	4	FR
491	Provisions pour d�pr�ciation des comptes de clients	4	FR
496	Provisions pour d�pr�ciation des comptes de d�biteurs divers	4	FR
5	comptes financiers	0	FR
50	valeurs mobili�res de placement	5	FR
51	banques, �tablissements financiers et assimil�s	5	FR
53	Caisse	5	FR
54	r�gies d'avance et accr�ditifs	5	FR
58	virements internes	5	FR
590	Provisions pour d�pr�ciation des valeurs mobili�res de placement	5	FR
6	comptes de charges	0	FR
60	Achats (sauf 603)	6	FR
607	Achats de marchandises	6	FR
603	variations des stocks (approvisionnements et marchandises)	6	FR
6031	Variation des stocks de mati�res premi�res (et fournitures)	603	FR
6032	Variation des stocks des autres approvisionnements	603	FR
6037	Variation des stocks de marchandises	603	FR
61	autres charges externes - Services ext�rieurs	6	FR
62	autres charges externes - Autres services ext�rieurs	6	FR
63	Imp�ts, taxes et versements assimiles	6	FR
641	R�mun�rations du personnel	6	FR
644	R�mun�ration du travail de l'exploitant	6	FR
645	Charges de s�curit� sociale et de pr�voyance	6	FR
6451	Cotisations � l'URSSAF	645	FR
6453	Cotisations aux caisses de retraites	645	FR
6454	Cotisations aux ASSEDIC	645	FR
646	Cotisations sociales personnelles de l'exploitant	6	FR
65	Autres charges de gestion courante	6	FR
66	Charges financi�res	6	FR
67	Charges exceptionnelles	6	FR
681	Dotations aux amortissements et aux provisions - Charges d'exploitation	6	FR
6811	Dotations aux amortissements sur immobilisations incorporelles et corporelles	681	FR
6815	Dotations aux provisions pour risques et charges d'exploitation	681	FR
6816	Dotations aux provisions pour d�pr�ciation des immobilisations incorporelles et corporelles	681	FR
6817	Dotations aux provisions pour d�pr�ciation des actifs circulants	681	FR
686	Dotations aux amortissements et aux provisions - Charges financi�res	6	FR
687	Dotations aux amortissements et aux provisions - Charges exceptionnelles	6	FR
691	Participation des salari�s aux r�sultats	6	FR
695	Imp�ts sur les b�n�fices	6	FR
697	Imposition forfaitaire annuelle des soci�t�s	6	FR
699	Produits - Reports en arri�re des d�ficits	6	FR
7	comptes de produits	0	FR
701	Ventes de produits finis	7	FR
706	Prestations de services	7	FR
707	Ventes de marchandises	7	FR
708	Produits des activit�s annexes	7	FR
709	Rabais, remises et ristournes accord�s par l'entreprise	7	FR
713	Variation des stocks (en-cours de production, produits)	7	FR
72	Production immobilis�e	7	FR
74	Subventions d'exploitation	7	FR
75	Autres produits de gestion courante	7	FR
753	Jetons de pr�sence et r�mun�rations d'administrateurs, g�rants,...	75	FR
754	Ristournes per�ues des coop�ratives (provenant des exc�dents)	75	FR
755	Quotes-parts de r�sultat sur op�rations faites en commun	75	FR
76	Produits financiers	7	FR
77	Produits exceptionnels	7	FR
781	Reprises sur amortissements et provisions (� inscrire dans les produits d'exploitation)	7	FR
786	Reprises sur provisions pour risques (� inscrire dans les produits financiers)	7	FR
787	Reprises sur provisions (� inscrire dans les produits exceptionnels)	7	FR
79	Transferts de charges	7	FR
8	Comptes sp�ciaux	0	FR
80	Engagements hors bilan	8	FR
801	Engagements donn�s par l'entit�	80	FR
8011	Avals, cautions, garanties	801	FR
8014	Effets circulant sous l'endos de l'entit�	801	FR
8016	Redevances cr�dit-bail restant � courir	801	FR
80161	Cr�dit-bail mobilier	8016	FR
80165	Cr�dit-bail immobilier	8016	FR
8018	Autres engagements donn�s	801	FR
802	Engagements re�us par l'entit�	80	FR
8021	Avals, cautions, garanties	802	FR
8024	Cr�ances escompt�es non �chues	802	FR
8026	Engagements re�us pour utilisation en cr�dit-bail	802	FR
80261	Cr�dit-bail mobilier	8026	FR
80265	Cr�dit-bail immobilier	8026	FR
8028	Autres engagements re�us	802	FR
809	Contrepartie des engagements	80	FR
8091	Contrepartie 801	809	FR
8092	Contrepartie 802	809	FR
88	R�sultat en instance d'affectation	8	FR
89	Bilan	8	FR
890	Bilan d'ouverture	89	FR
891	Bilan de cl�ture	89	FR
9	Comptes analytiques	0	FR
\.

--
-- PostgreSQL database dump complete
--
