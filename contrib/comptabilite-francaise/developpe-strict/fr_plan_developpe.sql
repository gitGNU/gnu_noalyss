--
-- PostgreSQL database dump
-- Version 2007-09-08 01:10
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
10	capital et r�serves	1	FR
1011	Capital souscrit - non appel�	10	FR
1012	Capital souscrit - appel�, non vers�	10	FR
1013	Capital souscrit - appel�, vers�	10	FR
10131	Capital non amorti	1013	FR
10132	Capital amorti	1013	FR
1018	Capital souscrit soumis � des r�glementations particuli�res	10	FR
104	Primes li�es au capital social	10	FR
1041	Primes d'�mission	104	FR
1042	Primes de fusion	104	FR
1043	Primes d'apport	104	FR
1044	Primes de conversion d'obligations en actions	104	FR
1045	Bons de souscription d'actions	104	FR
1051	R�serve sp�ciale de r��valuation	10	FR
1052	Ecart de r��valuation libre	10	FR
1053	R�serve de r��valuation	10	FR
1055	Ecarts de r��avaluation (autres op�rations l�gales)	10	FR
1057	Autres �carts de r��valuation en France	10	FR
1058	Autres �carts de r��valuation � l'Etranger	10	FR
106	R�serves	10	FR
10611	R�serve l�gale proprement dite	106	FR
10612	Plus-values nettes � long terme	106	FR
1062	R�serves indisponibles	106	FR
10641	Plus-values nettes � long terme	106	FR
10643	R�serves cons�cutives � l'octroi de subventions d'investissement	106	FR
10648	Autres r�serves r�glement�es	106	FR
10681	R�serve de propre assureur	106	FR
10688	R�serves diverses	106	FR
107	Ecart d'�quivalence	10	FR
109	Actionnaires�: Capital souscrit - non appel�	10	FR
11	report a nouveau (solde cr�diteur ou d�biteur)	1	FR
110	Report � nouveau (solde cr�diteur)	11	FR
119	Report � nouveau (solde d�biteur)	11	FR
120	R�sultat de l'exercice (b�n�fice)	1	FR
129	R�sultat de l'exercice (perte)	1	FR
13	subventions d'investissement	1	FR
131	Subventions d'�quipement	13	FR
1311	Etat	131	FR
1312	R�gions	131	FR
1313	D�partements	131	FR
1314	Communes	131	FR
1315	Collectivit�s publiques	131	FR
1316	Entreprises publiques	131	FR
1317	Entreprises et organismes priv�s	131	FR
1318	Autres	131	FR
138	Autres subventions d'investissement (m�me ventilation que celle du compte 131)	13	FR
139	Subventions d'investissement inscrites au compte de r�sultat	13	FR
1391	Subventions d'�quipement	139	FR
13911	Etat	1391	FR
13912	R�gions	1391	FR
13913	D�partements	1391	FR
13914	Communes	1391	FR
13915	Collectivit�s publiques	1391	FR
13916	Entreprises publiques	1391	FR
13917	Entreprises et organismes priv�s	1391	FR
13918	Autres	1391	FR
1398	Autres subventions d'investissement (m�me ventilation que celle du compte 1391)	139	FR
14	provisions reglementees	1	FR
142	Provisions r�glement�es relatives aux immobilisations	14	FR
1423	Provisions pour reconstitution des gisements miniers et p�troliers	142	FR
1424	Provisions pour investissement (participation des salari�s)	142	FR
143	Provisions r�glement�es relatives aux stocks	14	FR
1431	Hausse des prix	143	FR
1432	Fluctuation des cours	143	FR
144	Provisions r�glement�es relatives aux autres �l�ments de l'actif	14	FR
151	Provisions pour risques	1	FR
1511	Provisions pour litiges	151	FR
1512	Provisions pour garanties donn�es aux clients	151	FR
1513	Provisions pour pertes sur march�s � terme	151	FR
1514	Provisions pour amendes et p�nalit�s	151	FR
1515	Provisions pour pertes de change	151	FR
1518	Autres provisions pour risques	151	FR
153	Provisions pour pensions et obligations similaires	1	FR
155	Provisions pour imp�ts	1	FR
156	Provisions pour renouvellement des immobilisations (entreprises concessionnaires)	1	FR
157	Provisions pour charges � r�partir sur plusieurs exercices	1	FR
1572	Provisions pour grosses r�parations	157	FR
158	Autres provisions pour charges	1	FR
1582	Provisions pour charges sociales et fiscales sur cong�s � payer	158	FR
161	Emprunts obligataires convertibles	1	FR
163	Autres emprunts obligataires	1	FR
164	Emprunts aupr�s des �tablissements de cr�dit	1	FR
165	D�p�ts et cautionnements re�us	1	FR
1651	D�p�ts	165	FR
1655	Cautionnements	165	FR
166	Participation des salari�s aux r�sultats	1	FR
1661	Comptes bloqu�s	166	FR
1662	Fonds de participation	166	FR
167	Emprunts et dettes assortis de conditions particuli�res	1	FR
1671	Emissions de titres participatifs	167	FR
1674	Avances conditionn�es de l'Etat	167	FR
1675	Emprunts participatifs	167	FR
168	Autres emprunts et dettes assimil�es	1	FR
1681	Autres emprunts	168	FR
1685	Rentes viag�res capitalis�es	168	FR
1687	Autres dettes	168	FR
1688	Int�r�ts courus	168	FR
16881	Sur emprunts obligataires convertibles	168	FR
16883	Sur autres emprunts obligataires	168	FR
16884	Sur emprunts aupr�s des �tablissements de cr�dit	168	FR
16885	Sur d�p�ts et cautionnements re�us	168	FR
16886	Sur participation des salari�s aux r�sultats	168	FR
16887	Sur emprunts et dettes assortis de conditions particuli�res	168	FR
16888	Sur autres emprunts et dettes assimil�es	168	FR
169	Primes de remboursement des obligations	1	FR
17	dettes rattach�es a des participations	1	FR
171	Dettes rattach�es � des participations (groupe)	17	FR
174	Dettes rattach�es � des participations (hors groupe)	17	FR
178	Dettes rattach�es � des soci�t�s en participation	17	FR
1781	Principal	178	FR
1788	Int�r�ts courus	178	FR
18	comptes de liaison des �tablissements et societes en participation	1	FR
181	Comptes de liaison des �tablissements	18	FR
186	Biens et prestations de services �chang�s entre �tablissements (charges)	18	FR
187	Biens et prestations de services �chang�s entre �tablissements (produits)	18	FR
188	Comptes de liaison des soci�t�s en participation	18	FR
2	comptes d'immobilisations	0	FR
2011	Frais de constitution	2	FR
2012	Frais de premier �tablissement	2	FR
20121	Frais de prospection	2	FR
20122	Frais de publicit�	2	FR
2013	Frais d'augmentation de capital et d'op�rations diverses (fusions, scissions, transformations)	2	FR
203	Frais de recherche et de d�veloppement	2	FR
205	Concessions et droits similaires, brevets, licences, marques, proc�d�s, logiciels, droits et valeurs similaires	2	FR
211	Terrains	2	FR
2111	Terrains nus	211	FR
2112	Terrains am�nag�s	211	FR
2113	Sous-sols et sur-sols	211	FR
2114	Terrains de gisement	211	FR
21141	Carri�res	2114	FR
2115	Terrains b�tis	211	FR
21151	Ensembles immobiliers industriels (A, B...)	2115	FR
21155	Ensembles immobiliers administratifs et commerciaux (A, B...)	2115	FR
21158	Autres ensembles immobiliers	2115	FR
211581	affect�s aux op�rations professionnelles (A, B...)	21158	FR
211588	affect�s aux op�rations non professionnelles (A, B...)	21158	FR
2116	Compte d'ordre sur immobilisations (art. 6 du d�cret n� 78-737 du 11 juillet 1978)	211	FR
212	Agencements et am�nagements de terrains (m�me ventilation que celle du compte 211)	2	FR
213	Constructions	2	FR
2131	B�timents	213	FR
21311	Ensembles immobiliers industriels (A, B...)	2131	FR
21315	Ensembles immobiliers administratifs et commerciaux (A, B...)	2131	FR
21318	Autres ensembles immobiliers	2131	FR
213181	affect�s aux op�rations professionnelles (A, B...)	21318	FR
213188	affect�s aux op�rations non professionnelles (A, B...)	21318	FR
2135	Installations g�n�rales - agencements - am�nagements des constructions (m�me ventilation que celle du compte 2131)	213	FR
2138	Ouvrages d'infrastructure	213	FR
21381	Voies de terre	2138	FR
21382	Voies de fer	2138	FR
21383	Voies d'eau	2138	FR
21384	Barrages	2138	FR
21385	Pistes d'a�rodromes	2138	FR
214	Constructions sur sol d'autrui (m�me ventilation que celle du compte 213)	2	FR
215	Installations techniques, mat�riels et outillage industriels	2	FR
2151	Installations complexes sp�cialis�es	215	FR
21511	sur sol propre	2151	FR
21514	sur sol d'autrui	2151	FR
2153	Installations � caract�re sp�cifique	215	FR
21531	sur sol propre	2153	FR
21534	sur sol d'autrui	2153	FR
2154	Mat�riel industriel	215	FR
2155	Outillage industriel	215	FR
2157	Agencements et am�nagements du mat�riel et outillage industriels	215	FR
218	Autres immobilisations corporelles	2	FR
2181	Installations g�n�rales, agencements, am�nagements divers	218	FR
2182	Mat�riel de transport	218	FR
2183	Mat�riel de bureau et mat�riel informatique	218	FR
2184	Mobilier	218	FR
2185	Cheptel	218	FR
2186	Emballages r�cup�rables	218	FR
22	immobilisations mises en concession	2	FR
231	Immobilisations corporelles en cours	2	FR
2312	Terrains	231	FR
2313	Constructions	231	FR
2315	Installations techniques, mat�riel et outillage industriels	231	FR
2318	Autres immobilisations corporelles	231	FR
232	Immobilisations incorporelles en cours	2	FR
237	Avances et acomptes vers�s sur immobilisations incorporelles	2	FR
238	Avances et acomptes vers�s sur commandes d'immobilisations corporelles	2	FR
2382	Terrains	238	FR
2383	Constructions	238	FR
2385	Installations techniques, mat�riel et outillage industriels	238	FR
2388	Autres immobilisations corporelles	238	FR
25	Parts dans des entreprises li�es et cr�ances sur des entreprises li�es	2	FR
26	Participations et cr�ances rattach�es � des participations	2	FR
261	Titres de participation	26	FR
2611	Actions	261	FR
2618	Autres titres	261	FR
266	Autres formes de participation	26	FR
267	Cr�ances rattach�es � des participations	26	FR
2671	Cr�ances rattach�es � des participations (groupe)	267	FR
2674	Cr�ances rattach�es � des participations (hors groupe)	267	FR
2675	Versements repr�sentatifs d'apports non capitalis�s (appel de fonds)	267	FR
2676	Avances consolidables	267	FR
2677	Autres cr�ances rattach�es � des participations	267	FR
2678	Int�r�ts courus	267	FR
268	Cr�ances rattach�es � des soci�t�s en participation	26	FR
2681	Principal	268	FR
2688	Int�r�ts courus	268	FR
269	Versements restant � effectuer sur titres de participation non lib�r�s	26	FR
271	Titres immobilis�s autres que les titres immobilis�s de l'activit� de portefeuille (droit de propri�t�)	2	FR
2711	Actions	271	FR
2718	Autres titres	271	FR
272	Titres immobilis�s (droit de cr�ance)	2	FR
2721	Obligations	272	FR
2722	Bons	272	FR
273	Titres immobilis�s de l'activit� de portefeuille	2	FR
274	Pr�ts	2	FR
2741	Pr�ts participatifs	274	FR
2742	Pr�ts aux associ�s	274	FR
2743	Pr�ts au personnel	274	FR
2748	Autres pr�ts	274	FR
275	D�p�ts et cautionnements vers�s	2	FR
2751	D�p�ts	275	FR
2755	Cautionnements	275	FR
276	Autres cr�ances immobilis�es	2	FR
2761	Cr�ances diverses	276	FR
2768	Int�r�ts courus	276	FR
27682	Sur titres immobilis�s (droit de cr�ance)	2768	FR
27684	Sur pr�ts	2768	FR
27685	Sur d�p�ts et cautionnements	2768	FR
27688	Sur cr�ances diverses	2768	FR
277	(Actions propres ou parts propres)	2	FR
2771	Actions propres ou parts propres	277	FR
2772	Actions propres ou parts propres en voie d�annulation	277	FR
279	Versements restant � effectuer sur titres immobilis�s non lib�r�s	2	FR
28	amortissements des immobilisations	2	FR
2801	Frais d'�tablissement (m�me ventilation que celle du compte 201)	28	FR
2803	Frais de recherche et de d�veloppement	28	FR
2805	Concessions et droits similaires, brevets, licences, logiciels, droits et valeurs similaires	28	FR
2807	Fonds commercial	28	FR
2808	Autres immobilisations incorporelles	28	FR
2811	Terrains de gisement	28	FR
2812	Agencements, am�nagements de terrains (m�me ventilation que celle du compte 212)	28	FR
2813	Constructions (m�me ventilation que celle du compte 213)	28	FR
2814	Constructions sur sol d'autrui (m�me ventilation que celle du compte 214)	28	FR
2815	Installations, mat�riel et outillage industriels (m�me ventilation que celle du compte 215)	28	FR
2818	Autres immobilisations corporelles (m�me ventilation que celle du compte 218)	28	FR
282	Amortissements des immobilisations mises en concession	28	FR
29	provisions pour d�pr�ciation des immobilisations	2	FR
2905	Marques, proc�d�s, droits et valeurs similaires	29	FR
2906	Droit au bail	29	FR
2907	Fonds commercial	29	FR
2908	Autres immobilisations incorporelles	29	FR
2911	Terrains (autres que terrains de gisement)	29	FR
292	Provisions pour d�pr�ciation des immobilisations mises en concession	29	FR
293	Provisions pour d�pr�ciation des immobilisations en cours	29	FR
2931	Immobilisations corporelles en cours	293	FR
2932	Immobilisations incorporelles en cours	293	FR
296	Provisions pour d�pr�ciation des participations et cr�ances rattach�es � des participations	29	FR
2961	Titres de participation	296	FR
2966	Autres formes de participation	296	FR
2967	Cr�ances rattach�es � des participations (m�me ventilation que celle du compte 267)	296	FR
2968	Cr�ances rattach�es � des soci�t�s en participation (m�me ventilation que celle du compte 268)	296	FR
2971	Titres immobilis�s autres que les titres immobilis�s de l'activit� de portefeuille -droit de propri�t� (m�me ventilation que celle du compte 271)	29	FR
2972	Titres immobilis�s - droit de cr�ance (m�me ventilation que celle du compte 272)	29	FR
2973	Titres immobilis�s de l'activit� de portefeuille	29	FR
2974	Pr�ts (m�me ventilation que celle du compte 274)	29	FR
2975	D�p�ts et cautionnements vers�s (m�me ventilation que celle du compte 275)	29	FR
2976	Autres cr�ances immobilis�es (m�me ventilation que celle du compte 276)	29	FR
3	comptes de stocks et en cours	0	FR
311	Mati�res (ou groupe) A	3	FR
312	Mati�res (ou groupe) B	3	FR
317	Fournitures A, B, C, ...	3	FR
321	Mati�res consommables	3	FR
3211	Mati�res (ou groupe) C	321	FR
3212	Mati�res (ou groupe) D	321	FR
322	Fournitures consommables	3	FR
3221	Combustibles	322	FR
3222	Produits d'entretien	322	FR
3223	Fournitures d'atelier et d'usine	322	FR
3224	Fournitures de magasin	322	FR
3225	Fournitures de bureau	322	FR
326	Emballages	3	FR
3261	Emballages perdus	326	FR
3265	Emballages r�cup�rables non identifiables	326	FR
3267	Emballages � usage mixte	326	FR
331	Produits en cours	3	FR
3311	Produits en cours P 1	331	FR
3312	Produits en cours P 2	331	FR
335	Travaux en cours	3	FR
3351	avaux en cours T 1	335	FR
3352	Travaux en cours T 2	335	FR
341	Etudes en cours	3	FR
3411	Etudes en cours E 1	341	FR
3412	Etudes en cours E 2	341	FR
345	Prestations de services en cours	3	FR
3451	Prestations de services S 1	345	FR
3452	Prestations de services S 2	345	FR
351	Produits interm�diaires	3	FR
3511	Produits interm�diaires (ou groupe) A	351	FR
3512	Produits interm�diaires (ou groupe) B	351	FR
355	Produits finis	3	FR
3551	Produits finis (ou groupe) A	355	FR
3552	Produits finis (ou groupe) B	355	FR
358	Produits r�siduels (ou mati�res de r�cup�ration)	3	FR
3581	D�chets	358	FR
3585	Rebuts	358	FR
3586	Mati�res de r�cup�ration	358	FR
36	(compte � ouvrir, le cas �ch�ant, sous l'intitul� "�stocks provenant d'immobilisations�")	3	FR
371	Marchandises (ou groupe) A	3	FR
372	Marchandises (ou groupe) B	3	FR
38	(lorsque l'entit� tient un inventaire permanent en comptabilit� g�n�rale, le compte 38 peut �tre utilis� pour comptabiliser les stocks en voie d'acheminement, mis en d�p�t ou donn�s en consignation)	3	FR
39	provisions pour d�pr�ciation des stocks et en-cours	3	FR
3911	Mati�res (ou groupe) A	39	FR
3912	Mati�res (ou groupe) B	39	FR
3917	Fournitures A, B, C, ...	39	FR
3921	Mati�res consommables (m�me ventilation que celle du compte 321)	39	FR
3922	Fournitures consommables (m�me ventilation que celle ducompte 322)	39	FR
3926	Emballages (m�me ventilation que celle du compte 326)	39	FR
3931	Produits en cours (m�me ventilation que celle du compte 331)	39	FR
3935	Travaux en cours (m�me ventilation que celle du compte 335)	39	FR
3941	Etudes en cours (m�me ventilation que celle du compte 341)	39	FR
3945	Prestations de services en cours (m�me ventilation que celle du compte 345)	39	FR
3951	Produits interm�diaires (m�me ventilation que celle du compte 351)	39	FR
3955	Produits finis (m�me ventilation que celle du compte 355)	39	FR
3971	Marchandise (ou groupe) A	39	FR
3972	Marchandise (ou groupe) B	39	FR
4	comptes de tiers	0	FR
40	fournisseurs et comptes rattaches	4	FR
401	Fournisseurs	40	FR
4011	Fournisseurs - Achats de biens et prestations de services	401	FR
4017	Fournisseurs - Retenues de garantie	401	FR
403	Fournisseurs - Effets � payer	40	FR
404	Fournisseurs d'immobilisations	40	FR
4041	Fournisseurs - Achats d'immobilisations	404	FR
4047	Fournisseurs d'immobilisations - Retenues de garantie	404	FR
405	Fournisseurs d'immobilisations - Effets � payer	40	FR
408	Fournisseurs - Factures non parvenues	40	FR
4081	Fournisseurs	408	FR
4084	Fournisseurs d'immobilisations	408	FR
4088	Fournisseurs - Int�r�ts courus	408	FR
4091	Fournisseurs - Avances et acomptes vers�s sur commandes	40	FR
4096	Fournisseurs - Cr�ances pour emballages et mat�riel � rendre	40	FR
4097	Fournisseurs - Autres avoirs	40	FR
40971	Fournisseurs d'exploitation	4097	FR
40974	Fournisseurs d'immobilisations	4097	FR
4098	Rabais, remises, ristournes � obtenir et autres avoirs non encore re�us	40	FR
41	clients et comptes rattaches	4	FR
411	Clients	41	FR
4111	Clients - Ventes de biens ou de prestations de services	411	FR
4117	Clients - Retenues de garantie	411	FR
413	Clients - Effets � recevoir	41	FR
416	Clients douteux ou litigieux	41	FR
417	"�Cr�ances�" sur travaux non encore facturables	41	FR
418	Clients - Produits non encore factur�s	41	FR
4181	Clients - Factures � �tablir	418	FR
4188	Clients - Int�r�ts courus	418	FR
4191	Clients - Avances et acomptes re�us sur commandes	41	FR
4196	Clients - Dettes sur emballages et mat�riels consign�s	41	FR
4197	Clients - Autres avoirs	41	FR
4198	Rabais, remises, ristournes � accorder et autres avoirs � �tablir	41	FR
42	Personnel et comptes rattaches	4	FR
422	Comit�s d'entreprises, d'�tablissement,...	42	FR
424	Participation des salari�s aux r�sultats	42	FR
4246	R�serve sp�ciale (art. L. 442-2 du Code du travail)	424	FR
4248	Comptes courants	424	FR
425	Personnel - Avances et acomptes	42	FR
426	Personnel - D�p�ts	42	FR
427	Personnel - Oppositions	42	FR
4282	Dettes provisionn�es pour cong�s � payer	42	FR
4284	Dettes provisionn�es pour participation des salari�s aux r�sultats	42	FR
4286	Autres charges � payer	42	FR
4287	Produits � recevoir	42	FR
431	S�curit� sociale	4	FR
437	Autres organismes sociaux	4	FR
438	Organismes sociaux - Charges � payer et produits � recevoir	4	FR
4382	Charges sociales sur cong�s � payer	438	FR
4386	Autres charges � payer	438	FR
4387	Produits � recevoir	438	FR
44	�tat et autres collectivit�s publiques	4	FR
441	�tat - Subventions � recevoir	44	FR
4411	Subventions d'investissement	441	FR
4417	Subventions d'exploitation	441	FR
4418	Subventions d'�quilibre	441	FR
4419	Avances sur subventions	441	FR
442	Etat - Imp�ts et taxes recouvrables sur des tiers	44	FR
4424	Obligataires	442	FR
4425	Associ�s	442	FR
443	Op�rations particuli�res avec l'Etat les collectivit�s publiques, les organismes internationaux	44	FR
4431	Cr�ances sur l'Etat r�sultant de la suppression de la r�gle du d�calage d'un mois en mati�re de T.V.A.	443	FR
4438	Int�r�ts courus sur cr�ances figurant au 4431	443	FR
4452	T.V.A. due intracommunautaire	44	FR
4455	Taxes sur le chiffre d'affaires � d�caisser	44	FR
44551	T.V.A. � d�caisser	4455	FR
44558	Taxes assimil�es � la T.V.A.	4455	FR
4456	Taxes sur le chiffre d'affaires d�ductibles	44	FR
44562	T.V.A. sur immobilisations	4456	FR
44563	T.V.A. transf�r�e par d'autres entreprises	4456	FR
44566	T.V.A. sur autres biens et services	4456	FR
44567	Cr�dit de T.V.A. � reporter	4456	FR
44568	Taxes assimil�es � la T.V.A.	4456	FR
4457	Taxes sur le chiffre d'affaires collect�es par l'entreprise	44	FR
44571	T.V.A. collect�e	4457	FR
44578	Taxes assimil�es � la T.V.A.	4457	FR
4458	Taxes sur le chiffre d'affaires � r�gulariser ou en attente	44	FR
44581	Acomptes - R�gime simplifi� d'imposition	4458	FR
44582	Acomptes - R�gime de forfait	4458	FR
44583	Remboursement de taxes sur le chiffre d'affaires demand�	4458	FR
44584	T.V.A. r�cup�r�e d'avance	4458	FR
44586	Taxes sur le chiffre d'affaires sur factures non parvenues	4458	FR
44587	Taxes sur le chiffres d'affaires sur factures � �tablir	4458	FR
446	Obligations cautionn�es	44	FR
448	Etat - Charges � payer et produits � recevoir	44	FR
4482	Charges fiscales sur cong�s � payer	448	FR
4486	Charges � payer	448	FR
4487	Produits � recevoir	448	FR
451	Groupe	4	FR
4551	Principal	4	FR
4558	Int�r�ts courus	4	FR
456	Associ�s - Op�rations sur le capital	4	FR
4561	Associ�s - Comptes d'apport en soci�t�	456	FR
45611	Apports en nature	4561	FR
45615	Apports en num�raire	4561	FR
4562	Apporteurs - Capital appel�, non vers�	456	FR
45621	Actionnaires - Capital souscrit et appel�, non vers�	4562	FR
45625	Associ�s - Capital appel�, non vers�	4562	FR
4563	Associ�s - Versements re�us sur augmentation de capital	456	FR
4564	Associ�s - Versements anticip�s	456	FR
4566	Actionnaires d�faillants	456	FR
4567	Associ�s - Capital � rembourser	456	FR
457	Associ�s - Dividendes � payer	4	FR
458	Associ�s - Op�rations faites en commun et en G.I.E.	4	FR
4581	Op�rations courantes	458	FR
4588	Int�r�ts courus	458	FR
462	Cr�ances sur cessions d'immobilisations	4	FR
464	Dettes sur acquisitions de valeurs mobili�res de placement	4	FR
465	Cr�ances sur cessions de valeurs mobili�res de placement	4	FR
467	Autres comptes d�biteurs ou cr�diteurs	4	FR
468	Divers - Charges � payer et produits � recevoir	4	FR
4686	Charges � payer	468	FR
4687	Produits � recevoir	468	FR
471	Comptes d'attente	4	FR
472	Comptes d'attente	4	FR
473	Comptes d'attente	4	FR
474	Comptes d'attente	4	FR
475	Comptes d'attente	4	FR
476	Diff�rence de conversion - Actif	4	FR
4761	Diminution des cr�ances	476	FR
4762	Augmentation des dettes	476	FR
4768	Diff�rences compens�es par couverture de change	476	FR
477	Diff�rences de conversion - Passif	4	FR
4771	Augmentation des cr�ances	477	FR
4772	Diminution des dettes	477	FR
4778	Diff�rences compens�es par couverture de change	477	FR
478	Autres comptes transitoires	4	FR
48	comptes de r�gularisation	4	FR
4811	Charges diff�r�es	48	FR
4812	Frais d'acquisition des immobilisations	481	FR
4816	Frais d'�mission des emprunts	481	FR
4818	Charges � �taler	481	FR
488	Comptes de r�partition p�riodique des charges et des produits	48	FR
4886	Charges	48	FR
4887	Produits	48	FR
49	provisions pour d�pr�ciation des comptes de tiers	4	FR
495	Provisions pour d�pr�ciation des comptes du groupe et des associ�s	49	FR
4951	Comptes du groupe	495	FR
4955	Comptes courants des associ�s	495	FR
4958	Op�rations faites en commun et en G.I.E.	495	FR
4962	Cr�ances sur cessions d'immobilisations	49	FR
4965	Cr�ances sur cessions de valeurs mobili�res de placement	49	FR
4967	Autres comptes d�biteurs	49	FR
5	comptes financiers	0	FR
501	Parts dans des entreprises li�es	5	FR
502	Actions propres	5	FR
503	Actions	5	FR
5031	Titres cot�s	503	FR
5035	Titres non cot�s	503	FR
504	Autres titres conf�rant un droit de propri�t�	5	FR
505	Obligations et bons �mis par la soci�t� et rachet�s par elle	5	FR
506	Obligations	5	FR
5061	Titres cot�s	506	FR
5065	Titres non cot�s	506	FR
507	Bons du Tr�sor et bons de caisse � court terme	5	FR
508	Autres valeurs mobili�res de placement et autres cr�ances assimil�es	5	FR
5081	Autres valeurs mobili�res	508	FR
5082	Bons de souscription	508	FR
5088	Int�r�ts courus sur obligations, bons et valeurs assimil�s	508	FR
509	Versements restant � effectuer sur valeurs mobili�res de placement non lib�r�es	5	FR
511	Valeurs � l'encaissement	5	FR
5111	Coupons �chus � l'encaissement	511	FR
5112	Ch�ques � encaisser	511	FR
5113	Effets � l'encaissement	511	FR
5114	Effets � l'escompte	511	FR
512	Banques	5	FR
5121	Comptes en monnaie nationale	512	FR
5124	Comptes en devises	512	FR
514	Ch�ques postaux	5	FR
515	"�Caisses�" du Tr�sor et des �tablissements publics	5	FR
516	Soci�t�s de bourse	5	FR
517	Autres organismes financiers	5	FR
518	Int�r�ts courus	5	FR
5181	Int�r�ts courus � payer	518	FR
5188	Int�r�ts courus � recevoir	518	FR
519	Concours bancaires courants	5	FR
5191	Cr�dit de mobilisation de cr�ances commerciales (CMCC)	519	FR
5193	Mobilisation de cr�ances n�es � l'�tranger	519	FR
5198	Int�r�ts courus sur concours bancaires courants	519	FR
52	Instruments de tr�sorerie	5	FR
531	Caisse si�ge social	5	FR
5311	Caisse en monnaie nationale	531	FR
5314	Caisse en devises	531	FR
532	Caisse succursale (ou usine) A	5	FR
533	Caisse succursale (ou usine) B	5	FR
59	provisions pour d�pr�ciation des comptes financiers	5	FR
5903	Actions	59	FR
5904	Autres titres conf�rant un droit de propri�t�	59	FR
5906	Obligations	59	FR
5908	Autres valeurs mobili�res de placement et cr�ances assimil�es	59	FR
6	comptes de charges	0	FR
601	Achats stock�s - Mati�res premi�res (et fournitures)	6	FR
6011	Mati�res (ou groupe) A	601	FR
6012	Mati�res (ou groupe) B	601	FR
6017	Fournitures A, B, C, ...	601	FR
602	Achats stock�s - Autres approvisionnements	6	FR
6021	Mati�res consommables	602	FR
60211	Mati�res (ou groupe) C	6021	FR
60212	Mati�res (ou groupe) D	6021	FR
6022	Fournitures consommables	602	FR
60221	Combustibles	6022	FR
60222	Produits d'entretien	6022	FR
60223	Fournitures d'atelier et d'usine	6022	FR
60224	Fournitures de magasin	6022	FR
60225	Fourniture de bureau	6022	FR
6026	Emballages	602	FR
60261	Emballages perdus	6026	FR
60265	ballages r�cup�rables non identifiables	6026	FR
60267	Emballages � usage mixte	6026	FR
604	Achats d'�tudes et prestations de services	6	FR
605	Achats de mat�riel, �quipements et travaux	6	FR
606	Achats non stock�s de mati�re et fournitures	6	FR
6061	Fournitures non stockables (eau, �nergie, ...)	606	FR
6063	Fournitures d'entretien et de petit �quipement	606	FR
6064	Fournitures administratives	606	FR
6068	Autres mati�res et fournitures	606	FR
607	Achats de marchandises	6	FR
6071	Marchandise (ou groupe) A	607	FR
6072	Marchandise (ou groupe) B	607	FR
608	(Compte r�serv�, le cas �ch�ant, � la r�capitulation des frais accessoires incorpor�s aux achats)	6	FR
609	Rabais, remises et ristournes obtenus sur achats	6	FR
6091	de mati�res premi�res (et fournitures)	609	FR
6092	d'autres approvisionnements stock�s	609	FR
6094	d'�tudes et prestations de services	609	FR
6095	de mat�riel, �quipements et travaux	609	FR
6096	d'approvisionnements non stock�s	609	FR
6097	de marchandises	609	FR
6098	Rabais, remises et ristournes non affect�s	609	FR
6031	Variation des stocks de mati�res premi�res (et fournitures)	6	FR
6032	Variation des stocks des autres approvisionnements	6	FR
6037	Variation des stocks de marchandises	6	FR
61	autres charges externes - Services ext�rieurs	6	FR
611	Sous-traitance g�n�rale	61	FR
612	Redevances de cr�dit-bail	61	FR
6122	Cr�dit-bail mobilier	612	FR
6125	Cr�dit-bail immobilier	612	FR
613	Locations	61	FR
6132	Locations immobili�res	613	FR
6135	Locations mobili�res	613	FR
6136	Malis sur emballages	613	FR
614	Charges locatives et de copropri�t�	61	FR
615	Entretien et r�parations	61	FR
6152	sur biens immobiliers	615	FR
6155	sur biens mobiliers	615	FR
6156	Maintenance	615	FR
616	Primes d'assurances	61	FR
6161	Multirisques	616	FR
6162	Assurance obligatoire dommage construction	616	FR
6163	Assurance-transport	616	FR
61636	sur achats	6163	FR
61637	sur ventes	6163	FR
61638	sur autres biens	6163	FR
6164	Risques d'exploitation	616	FR
6165	Insolvabilit� clients	616	FR
617	Etudes et recherches	61	FR
618	Divers	61	FR
6181	Documentation g�n�rale	618	FR
6183	Documentation technique	618	FR
6185	Frais de colloques, s�minaires, conf�rences	618	FR
619	Rabais, remises et ristournes obtenus sur services ext�rieurs	61	FR
62	autres charges externes - Autres services ext�rieurs	6	FR
621	Personnel ext�rieur � l'entreprise	62	FR
6211	Personnel int�rimaire	621	FR
6214	Personnel d�tach� ou pr�t� � l'entreprise	621	FR
622	R�mun�rations d'interm�diaires et honoraires	62	FR
6221	Commissions et courtages sur achats	622	FR
6222	Commissions et courtages sur ventes	622	FR
6224	R�mun�rations des transitaires	622	FR
6225	R�mun�rations d'affacturage	622	FR
6226	Honoraires	622	FR
6227	Frais d'actes et de contentieux	622	FR
6228	Divers	622	FR
623	Publicit�, publications, relations publiques	62	FR
6231	Annonces et insertions	623	FR
6232	Echantillons	623	FR
6233	Foires et expositions	623	FR
6234	Cadeaux � la client�le	623	FR
6235	Primes	623	FR
6236	Catalogues et imprim�s	623	FR
6237	Publications	623	FR
6238	Divers (pourboires, dont courant, ...)	623	FR
624	Transports de biens et transports collectifs du personnel	62	FR
6241	Transports sur achats	624	FR
6242	Transports sur ventes	624	FR
6243	Transports entre �tablissements ou chantiers	624	FR
6244	Transports administratifs	624	FR
6247	Transports collectifs du personnel	624	FR
6248	Divers	624	FR
625	D�placements, missions et r�ceptions	62	FR
6251	Voyages et d�placements	625	FR
6255	Frais de d�m�nagement	625	FR
6256	Missions	625	FR
6257	R�ceptions	625	FR
626	Frais postaux et de t�l�communications	62	FR
627	Services bancaires et assimil�s	62	FR
6271	Frais sur titres (achat, vente, garde)	627	FR
6272	Commissions et frais sur �mission d'emprunts	627	FR
6275	Frais sur effets	627	FR
6276	Location de coffres	627	FR
6278	Autres frais et commissions sur prestations de services	627	FR
628	Divers	62	FR
6281	Concours divers (cotisations, ...)	628	FR
6284	Frais de recrutement de personnel	628	FR
629	Rabais, remises et ristournes obtenus sur autres services ext�rieurs	62	FR
631	Imp�ts, taxes et versements assimil�s sur r�mun�rations (administrations des imp�ts)	6	FR
6311	Taxe sur les salaires	631	FR
6312	Taxe d'apprentissage	631	FR
6313	Participation des employeurs � la formation professionnelle continue	631	FR
6314	Cotisation pour d�faut d'investissement obligatoire dans la construction	631	FR
6318	Autres	631	FR
633	Imp�ts, taxes et versements assimil�s sur r�mun�rations (autres organismes)	6	FR
6331	Versement de transport	633	FR
6332	Allocations logement	633	FR
6333	Participation des employeurs � la formation professionnelle continue	633	FR
6334	Participation des employeurs � l'effort de construction	633	FR
6335	Versements lib�ratoires ouvrant droit � l'exon�ration de la taxe d'apprentissage	633	FR
6338	Autres	633	FR
635	Autres imp�ts, taxes et versements assimil�s (administrations des imp�ts)	6	FR
6351	Imp�ts directs (sauf imp�ts sur les b�n�fices)	635	FR
63511	Taxe professionnelle	6351	FR
63512	Taxes fonci�res	6351	FR
63513	Autres imp�ts locaux	6351	FR
63514	Taxe sur les v�hicules des soci�t�s	6351	FR
6352	Taxe sur le chiffre d'affaires non r�cup�rables	635	FR
6353	Imp�ts indirects	635	FR
6354	Droits d'enregistrement et de timbre	635	FR
63541	Droits de mutation	6354	FR
6358	Autres droits	635	FR
637	Autres imp�ts, taxes et versements assimil�s (autres organismes)	6	FR
6371	Contribution sociale de solidarit� � la charge des soci�t�s	637	FR
6372	Taxes per�ues par les organismes publics internationaux	637	FR
6374	Imp�ts et taxes exigibles � l'Etranger	637	FR
6378	Taxes diverses	637	FR
64	Charges de personnel	6	FR
6411	Salaires, appointements	64	FR
6412	Cong�s pay�s	641	FR
6413	Primes et gratifications	641	FR
6414	Indemnit�s et avantages divers	641	FR
6415	Suppl�ment familial	641	FR
6451	Cotisations � l'URSSAF	64	FR
6452	Cotisations aux mutuelles	64	FR
6453	Cotisations aux caisses de retraites	64	FR
6454	Cotisations aux ASSEDIC	64	FR
6458	Cotisations aux autres organismes sociaux	64	FR
647	Autres charges sociales	64	FR
6471	Prestations directes	647	FR
6472	Versements aux comit�s d'entreprise et d'�tablissement	647	FR
6473	Versements aux comit�s d'hygi�ne et de s�curit�	647	FR
6474	Versements aux autres �uvres sociales	647	FR
6475	M�decine du travail, pharmacie	647	FR
648	Autres charges de personnel	64	FR
651	Redevances pour concessions, brevets, licences, marques, proc�d�s, logiciels, droits et valeurs similaires	6	FR
6511	Redevances pour concessions, brevets, licences, marques, proc�d�s, logiciels	651	FR
6516	Droits d'auteur et de reproduction	651	FR
6518	Autres droits et valeurs similaires	651	FR
653	Jetons de pr�sence	6	FR
654	Pertes sur cr�ances irr�couvrables	6	FR
6541	Cr�ances de l'exercice	654	FR
6544	Cr�ances des exercices ant�rieurs	654	FR
655	Quotes-parts de r�sultat sur op�rations faites en commun	6	FR
6551	Quote-part de b�n�fice transf�r�e (comptabilit� du g�rant)	655	FR
6555	Quote-part de perte support�e (comptabilit� des associ�s non g�rants)	655	FR
658	Charges diverses de gestion courante	6	FR
661	Charges d'int�r�ts	6	FR
6611	Int�r�ts des emprunts et dettes	661	FR
66116	des emprunts et dettes assimil�es	6611	FR
66117	des dettes rattach�es � des participations	6611	FR
6615	Int�r�ts des comptes courants et des d�p�ts cr�diteurs	661	FR
6616	Int�r�ts bancaires et sur op�rations de financement (escompte,...)	661	FR
6617	Int�r�ts des obligations cautionn�es	661	FR
6618	Int�r�ts des autres dettes	661	FR
66181	des dettes commerciales	6618	FR
66188	des dettes diverses	66188	FR
664	Pertes sur cr�ances li�es � des participations	6	FR
665	Escomptes accord�s	6	FR
666	Pertes de change	6	FR
667	Charges nettes sur cessions de valeurs mobili�res de placement	6	FR
668	Autres charges financi�res	6	FR
671	Charges exceptionnelles sur op�rations de gestion	6	FR
6711	P�nalit�s sur march�s (et d�dits pay�s sur achats et ventes)	671	FR
6712	P�nalit�s, amendes fiscales et p�nales	671	FR
6713	Dons, lib�ralit�s	671	FR
6714	Cr�ances devenues irr�couvrables dans l'exercice	671	FR
6715	Subventions accord�es	671	FR
6717	Rappel d'imp�ts (autres qu'imp�ts sur les b�n�fices)	671	FR
6718	Autres charges exceptionnelles sur op�rations de gestion	671	FR
672	(Compte � la disposition des entit�s pour enregistrer, en cours d'exercice, les charges sur exercices ant�rieurs)	6	FR
675	Valeurs comptables des �l�ments d'actif c�d�s	6	FR
6751	Immobilisations incorporelles	675	FR
6752	Immobilisations corporelles	675	FR
6756	Immobilisations financi�res	675	FR
6758	Autres �l�ments d'actif	675	FR
678	Autres charges exceptionnelles	6	FR
6781	Malis provenant de clauses d'indexation	678	FR
6782	Lots	678	FR
6783	Malis provenant du rachat par l'entreprise d'actions et obligations �mises par elle-m�me	678	FR
6788	Charges exceptionnelles diverses	678	FR
68	Dotations aux amortissements et aux provisions	6	FR
6811	Dotations aux amortissements sur immobilisations incorporelles et corporelles	68	FR
68111	Immobilisations incorporelles	6811	FR
68112	Immobilisations corporelles	6811	FR
6812	Dotations aux amortissements des charges d'exploitation � r�partir	68	FR
6815	Dotations aux provisions pour risques et charges d'exploitation	68	FR
6816	Dotations aux provisions pour d�pr�ciation des immobilisations incorporelles et corporelles	68	FR
68161	Immobilisations incorporelles	6816	FR
68162	Immobilisations corporelles	6816	FR
6817	Dotations aux provisions pour d�pr�ciation des actifs circulants	68	FR
68173	Stocks et en-cours	6817	FR
68174	Cr�ances	6817	FR
6861	Dotations aux amortissements des primes de remboursement des obligations	68	FR
6865	Dotations aux provisions pour risques et charges financiers	68	FR
6866	Dotations aux provisions pour d�pr�ciation des �l�ments financiers	68	FR
68662	Immobilisations financi�res	6866	FR
68665	Valeurs mobili�res de placement	6866	FR
6868	Autres dotations	68	FR
6871	Dotations aux amortissements exceptionnels des immobilisations	68	FR
6872	Dotations aux provisions r�glement�es (immobilisations)	68	FR
68725	Amortissements d�rogatoires	6872	FR
6873	Dotations aux provisions r�glement�es (stocks)	68	FR
6874	Dotations aux autres provisions r�glement�es	68	FR
6875	Dotations aux provisions pour risques et charges exceptionnels	68	FR
6876	Dotations aux provisions pour d�pr�ciations exceptionnelles	68	FR
69	participation des salaries - imp�ts sur les benefices et assimiles	6	FR
6951	Imp�ts dus en France	69	FR
6952	Contribution additionnelle � l'imp�t sur les b�n�fices	69	FR
6954	Imp�ts dus � l'�tranger	69	FR
696	Suppl�ments d'imp�t sur les soci�t�s li�s aux distributions	69	FR
698	Int�gration fiscale	69	FR
6981	Int�gration fiscale - Charges	698	FR
6989	Int�gration fiscale - Produits	698	FR
7	comptes de produits	0	FR
70	ventes de produits fabriques, prestations de services, marchandises	7	FR
7011	Produits finis (ou groupe) A	70	FR
7012	Produits finis (ou groupe) B	70	FR
702	Ventes de produits interm�diaires	70	FR
703	Ventes de produits r�siduels	70	FR
704	Travaux	70	FR
7041	Travaux de cat�gorie (ou activit�) A	704	FR
7042	Travaux de cat�gorie (ou activit�) B	704	FR
705	Etudes	7	FR
7071	Marchandises (ou groupe) A	70	FR
7072	Marchandises (ou groupe) B	70	FR
7081	Produits des services exploit�s dans l'int�r�t du personnel	70	FR
7082	Commissions et courtages	70	FR
7083	Locations diverses	70	FR
7084	Mise � disposition de personnel factur�e	70	FR
7085	Ports et frais accessoires factur�s	70	FR
7086	Bonis sur reprises d'emballages consign�s	70	FR
7087	Bonifications obtenues des clients et primes sur ventes	70	FR
7088	Autres produits d'activit�s annexes (cessions d'approvisionnements,...)	70	FR
7091	sur ventes de produits finis	70	FR
7092	sur ventes de produits interm�diaires	70	FR
7094	sur travaux	70	FR
7095	sur �tudes	70	FR
7096	sur prestations de services	70	FR
7097	sur ventes de marchandises	70	FR
7098	sur produits des activit�s annexes	70	FR
71	production stock�e (ou d�stockage)	7	FR
7133	Variation des en-cours de production de biens	71	FR
71331	Produits en cours	7133	FR
71335	Travaux en cours	7133	FR
7134	Variation des en-cours de production de services	71	FR
71341	Etudes en cours	7134	FR
71345	Prestations de services en cours	7134	FR
7135	Variation des stocks de produits	71	FR
71351	Produits interm�diaires	7135	FR
71355	Produits finis	7135	FR
71358	Produits r�siduels	7135	FR
721	Immobilisations incorporelles	7	FR
722	Immobilisations corporelles	7	FR
731	Produits nets partiels sur op�rations en cours (� subdiviser par op�ration)	7	FR
739	Produits nets partiels sur op�rations termin�es	7	FR
751	Redevances pour concessions, brevets, licences, marques, proc�d�s, logiciels, droits et valeurs similaires	7	FR
7511	Redevances pour concessions, brevets, licences, marques, proc�d�s, logiciels	751	FR
7516	Droits d'auteur et de reproduction	751	FR
7518	Autres droits et valeurs similaires	751	FR
752	Revenus des immeubles non affect�s � des activit�s professionnelles	7	FR
7551	Quote-part de perte transf�r�e (comptabilit� du g�rant)	7	FR
7555	Quote-part de b�n�fice attribu�e (comptabilit� des associ�s non-g�rants)	7	FR
758	Produits divers de gestion courante	7	FR
761	Produits de participations	7	FR
7611	Revenus des titres de participation	761	FR
7616	Revenus sur autres formes de participation	761	FR
7617	Revenus des cr�ances rattach�es � des participations	761	FR
762	Produits des autres immobilisations financi�res	7	FR
7621	Revenus des titres immobilis�s	762	FR
7626	Revenus des pr�ts	762	FR
7627	Revenus des cr�ances immobilis�es	762	FR
763	Revenus des autres cr�ances	7	FR
7631	Revenus des cr�ances commerciales	763	FR
7638	Revenus des cr�ances diverses	763	FR
764	Revenus des valeurs mobili�res de placement	7	FR
765	Escomptes obtenus	7	FR
766	Gains de change	7	FR
767	Produits nets sur cessions de valeurs mobili�res de placement	7	FR
768	Autres produits financiers	7	FR
771	Produits exceptionnels sur op�rations de gestion	7	FR
7711	D�dits et p�nalit�s per�us sur achats et sur ventes	771	FR
7713	Lib�ralit�s re�ues	771	FR
7714	Rentr�es sur cr�ances amorties	771	FR
7715	Subventions d'�quilibre	771	FR
7717	D�gr�vements d'imp�ts autres qu'imp�ts sur les b�n�fices	771	FR
7718	Autres produits exceptionnels sur op�rations de gestion	771	FR
772	(Compte � la disposition des entit�s pour enregistrer, en cours d'exercice, les produits sur exercices ant�rieurs)	7	FR
775	Produits des cessions d'�l�ments d'actif	7	FR
7751	Immobilisations incorporelles	775	FR
7752	Immobilisations corporelles	775	FR
7756	Immobilisations financi�res	775	FR
7758	Autres �l�ments d'actif	775	FR
777	Quote-part des subventions d'investissement vir�e au r�sultat de l'exercice	7	FR
778	Autres produits exceptionnels	7	FR
7781	Bonis provenant de clauses d'indexation	778	FR
7782	Lots	778	FR
7783	Bonis provenant du rachat par l'entreprise d'actions et d'obligations �mises par elle-m�me	778	FR
7788	Produits exceptionnels divers	778	FR
78	Reprises sur amortissements et provisions	7	FR
7811	Reprises sur amortissements des immobilisations incorporelles et corporelles	78	FR
78111	Immobilisations incorporelles	7811	FR
78112	Immobilisations corporelles	7811	FR
7815	Reprises sur provisions pour risques et charges d'exploitation	78	FR
7816	Reprises sur provisions pour d�pr�ciation des immobilisations incorporelles et corporelles	78	FR
78161	Immobilisations incorporelles	7816	FR
78162	Immobilisations corporelles	7816	FR
7817	Reprises sur provisions pour d�pr�ciation des actifs circulants	78	FR
78173	Stocks et en-cours	7817	FR
78174	Cr�ances	7817	FR
7865	Reprises sur provisions pour risques et charges financiers	78	FR
7866	Reprises sur provisions pour d�pr�ciation des �l�ments financiers	78	FR
78662	Immobilisations financi�res	7866	FR
78665	Valeurs mobili�res de placements	7866	FR
7872	Reprises sur provisions r�glement�es (immobilisations)	78	FR
78725	Amortissements d�rogatoires	7872	FR
78726	Provision sp�ciale de r��valuation	7872	FR
78727	Plus-values r�investies	7872	FR
7873	Reprises sur provisions r�glement�es (stocks)	78	FR
7874	Reprises sur autres provisions r�glement�es	78	FR
7875	Reprises sur provisions pour risques et charges exceptionnels	78	FR
7876	Reprises sur provisions pour d�pr�ciations exceptionnelles	78	FR
791	Transferts de charges d'exploitation	7	FR
796	Transferts de charges financi�res	7	FR
797	Transferts de charges exceptionnelles	7	FR
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
