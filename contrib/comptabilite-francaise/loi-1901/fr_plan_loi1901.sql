--
-- PostgreSQL database dump
-- Version 2007-09-08 01:18
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Data for Name: tmp_pcmn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) FROM stdin;
1	CLASSE 1. Comptes de capitaux 	0	FR
10	FONDS ASSOCIATIF ET RESERVES (pour les fondations : "fonds propres et r�serves")	1	FR
102	Fonds associatif sans droit de reprise	10	FR
1021	Valeur du patrimoine int�gr�	102	FR
1022	Fonds statutaire (� �clater en fonction des statuts)	102	FR
1023	Subventions d'investissement non renouvelables	102	FR
1024	Apports sans droit de reprise	102	FR
1025	Legs et donations avec contrepartie d'actifs immobilis�s	102	FR
1026	Subventions d'investissement affect�es � des biens renouvelables	102	FR
103	Fonds associatif avec droit de reprise	10	FR
1031	Valeur des biens affect�s repris � la fin du contrat d'apport	103	FR
1032	Valeur des biens affect�s repris � la dissolution de l'association	103	FR
1033	Valeur des biens non affect�s repris � la fin du contrat d'apport	103	FR
1034	Apports avec droit de reprise	103	FR
1035	Legs et donations avec contrepartie d'actifs immobilis�s assortis d'une obligation ou d'une condition	103	FR
1036	Subventions d'investissement affect�es � des biens renouvelables	103	FR
1039	Fonds associatif avec doit de reprise inscrit au compte de r�sultat	103	FR
105	Ecarts de r��valuation	10	FR
1051	Ecarts de r��valuation sur des biens sans droit de reprise	105	FR
1052	Ecarts de r��valuation (immobilisations non grev�es d'un droit de reprise)	105	FR
1053	Ecarts de r��valuation (immobilisations grev�es d'un droit de reprise)	105	FR
10531	Ecarts de r��valuation (immobilisations grev�es d'un droit de reprise avant dissolution de l'association)	1053	FR
10532	Ecarts de r��valuation (immobilisations grev�es d'un droit de reprise � la dissolution de l'association)	1053	FR
106	R�serves	10	FR
1062	R�serves indisponibles	106	FR
1063	R�serves statutaires ou contractuelles	106	FR
1064	R�serves r�glement�es	106	FR
1068	Autres r�serves (dont r�serves pour projet associatif)	106	FR
10682	R�serves pour investissements	1068	FR
10683	R�serves de tr�sorerie (provenant du r�sultat)	1068	FR
10688	R�serves diverses	1068	FR
11	ELEMENTS EN INSTANCE D'AFFECTATION	1	FR
110	Report � nouveau (solde cr�diteur)	11	FR
115	R�sultats sous contr�le de tiers financeurs	11	FR
119	Report � nouveau (solde d�biteur) 	11	FR
12	RESULTAT NET DE L'EXERCICE	1	FR
120	R�sultat de l'exercice (exc�dent)	12	FR
129	R�sultat de l'exercice (d�ficit)	12	FR
13	SUBVENTIONS D'INVESTISSEMENT (affect�s � des biens renouvelables)	1	FR
131	Subventions d'investissement (renouvelables)	13	FR
139	Subventions d'investissement inscrites au compte de r�sultat	13	FR
15	PROVISIONS POUR RISQUES ET CHARGES	1	FR
151	Provisions pour risques	15	FR
1516	Provisions pour risques d'emploi	151	FR
1518	Autres provisions pour risques	151	FR
157	Provisions pour charges � r�partir sur plusieurs exercices	15	FR
1572	Provisions pour grosses r�parations	157	FR
16	EMPRUNTS ET DETTES ASSIMILEES	1	FR
164	Emprunts aupr�s des �tablissements de cr�dit	16	FR
1641	Emprunts (� d�tailler)	164	FR
167	Emprunts et dettes assorties de conditions particuli�res	16	FR
1672	Titres associatifs	167	FR
168	Autres emprunts et dettes assimil�es	16	FR
1681	Autres emprunts (� d�tailler)	168	FR
1685	Rentes viag�res capitalis�es	168	FR
1687	Autres dettes (� d�tailler)	168	FR
1688	Int�r�ts courus (� d�tailler)	168	FR
18	COMPTES DE LIAISON DES ETABLISSEMENTS	1	FR
181	Apports permanents entre si�ge social et �tablissements	18	FR
185	Biens et prestations de services �chang�s entre �tablissements et si�ge social	18	FR
186	Biens et prestations de services �chang�s entre �tablissements (charges)	18	FR
187	Biens et prestations de services �chang�s entre �tablissements (produits)	18	FR
19	FONDS DEDIES	1	FR
194	Fonds d�di�s sur subventions de fonctionnement	19	FR
195	Fonds d�di�s sur dons manuels affect�s	19	FR
197	Fonds d�di�s sur legs et donations affect�s	19	FR
198	Exc�dent disponible apr�s affectation au projet associatif	19	FR
199	Reprise des fonds affect�s au projet associatif	19	FR
2	CLASSE 2. Comptes d'immobilisations 	0	FR
20	IMMOBILISATIONS INCORPORELLES	2	FR
201	Frais d'�tablissement	20	FR
2012	Frais de premier �tablissement	201	FR
206	Droit au bail	20	FR
208	Autres immobilisations incorporelles	20	FR
21	IMMOBILISATIONS CORPORELLES	2	FR
211	Terrains	21	FR
212	Agencements et am�nagements des constructions	21	FR
213	Constructions	21	FR
2131	B�timents	213	FR
2135	Installations g�n�rales, agencements, am�nagements des constructions	213	FR
214	Constructions sur sol d'autrui	21	FR
215	Installations techniques, mat�riel et outillage industriels	21	FR
2151	Installations complexes sp�cialis�es	215	FR
2154	Mat�riel industriel	215	FR
2155	Outillage industriel	215	FR
218	Autres immobilisations corporelles	21	FR
2181	Installations g�n�rales, agencements, am�nagements divers	218	FR
2182	Mat�riel de transport	218	FR
2183	Mat�riel de bureau et mat�riel informatique	218	FR
2184	Mobilier	218	FR
2185	Cheptel	218	FR
228	Immobilisations grev�es de droits	2	FR
229	Droits des propri�taires	2	FR
23	IMMOBILISATIONS EN COURS	2	FR
231	Immobilisations corporelles en cours	23	FR
2313	Constructions	231	FR
2315	Installations techniques, mat�riel et outillage industriels	231	FR
2318	Autres immobilisations corporelles	231	FR
238	Avances et acomptes vers�s sur commande d'immobilisations corporelles	23	FR
26	PARTICIPATIONS ET CREANCES RATTACHEES A DES PARTICIPATIONS	2	FR
261	Titres de participation	26	FR
266	Autres formes de participation	26	FR
267	Cr�ances rattach�es � des participations	26	FR
269	Versements restant � effectuer sur titres de participation non lib�r�s	26	FR
27	AUTRES IMMOBILISATIONS FINANCIERES	2	FR
271	Titres immobilis�s (droit de propri�t�)	27	FR
2711	Actions	271	FR
272	Titres immobilis�s (droit de cr�ance)	27	FR
2721	Obligations	272	FR
2722	Bons	272	FR
2728	Autres	272	FR
274	Pr�ts	27	FR
2743	Pr�ts au personnel	274	FR
2748	Autres pr�ts	274	FR
275	D�p�ts et cautionnements vers�s	27	FR
2751	D�p�ts	275	FR
2755	Cautionnements	275	FR
276	Autres cr�ances immobilis�es	27	FR
2761	Cr�ances diverses	276	FR
2768	Int�r�ts courus (� d�tailler)	276	FR
279	Versement restant � effectuer sur titres immobilis�s non lib�r�s	27	FR
28	AMORTISSEMENTS DES IMMOBILISATIONS	2	FR
280	Amortissements des immobilisations incorporelles	28	FR
2801	Frais d'�tablissement (m�me ventilation que celle du compte 201)	280	FR
2808	Autres immobilisations incorporelles	280	FR
281	Amortissements des immobilisations corporelles	28	FR
2812	Agencements, am�nagements de terrains (m�me ventilation que celle du compte 212)	281	FR
2813	Constructions (m�me ventilation que celle du compte 213)	281	FR
2814	Constructions sur sol d'autrui (m�me ventilation que celle du compte 214)	281	FR
2815	Installations techniques, mat�riel et outillage industriels (m�me ventilation que celle du compte 215)	281	FR
2818	Autres immobilisations corporelles (m�me ventilation que celle du compte 218)	281	FR
29	PROVISIONS POUR DEPRECIATION DES IMMOBILISATIONS	2	FR
290	Provisions pour d�pr�ciation des immobilisations incorporelles	29	FR
2906	Droit au bail	290	FR
2908	Autres immobilisations incorporelles	290	FR
291	Provisions pour d�pr�ciation des autres immobilisations corporelles	29	FR
2911	Terrains	291	FR
296	Provisions pour d�pr�ciation des participations et cr�ances rattach�es � des participations	29	FR
2961	Titres de participation	296	FR
2966	Autres formes de participation	296	FR
2967	Cr�ances rattach�es � des participations (m�me ventilation que celle du compte 267)	296	FR
297	Provisions pour d�pr�ciation des autres immobilisations financi�res	29	FR
2971	Titres immobilis�s (droit de propri�t�) (m�me ventilation que celle du compte 271)	297	FR
2972	Titres immobilis�s (droit de cr�ance) (m�me ventilation que celle du compte 272)	297	FR
2974	Pr�ts (m�me ventilation que celle du compte 274)	297	FR
2975	D�p�ts et cautionnements vers�s (m�me ventilation que celle du compte 275)	297	FR
2976	Autres cr�ances immobilis�es (m�me ventilation que celle du compte 276)	297	FR
3	CLASSE 3. Comptes de stocks et en-cours 	0	FR
31	MATIERES PREMIERES ET FOURNITURES	3	FR
32	AUTRES APPROVISIONNEMENTS	3	FR
33	EN-COURS DE PRODUCTION DE BIENS	3	FR
34	EN-COURS DE PRODUCTION DE SERVICES	3	FR
35	STOCKS DE PRODUITS	3	FR
37	STOCKS DE MARCHANDISES	3	FR
39	PROVISIONS POUR DEPRECIATION DES STOCKS ET EN-COURS	3	FR
391	Provisions pour d�pr�ciation des mati�res premi�res et fournitures	39	FR
392	Provisions pour d�pr�ciation des autres approvisionnements	39	FR
393	Provisions pour d�pr�ciation des en-cours de production de biens	39	FR
394	Provisions pour d�pr�ciation des en-cours de production de services	39	FR
395	Provisions pour d�pr�ciation des stocks de produits	39	FR
397	Provisions pour d�pr�ciation des stocks de marchandises	39	FR
4	CLASSE 4. Comptes de tiers 	0	FR
40	FOURNISSEURS ET COMPTES RATTACHES	4	FR
401	Fournisseurs	40	FR
4011	Fournisseurs - Achats de biens ou de prestations de services	401	FR
404	Fournisseurs d'immobilisations	40	FR
4041	Fournisseurs - achats d'immobilisations	404	FR
4047	Fournisseurs d'immobilisations - Retenues de garantie	404	FR
408	Fournisseurs - Factures non parvenues	40	FR
4081	Fournisseurs - Achats de biens ou de prestations de services	408	FR
4084	Fournisseurs - achats d'immobilisations	408	FR
409	Fournisseurs d�biteurs	40	FR
4091	Fournisseurs - Avances et acomptes vers�s sur commandes	409	FR
4096	Fournisseurs - Cr�ances pour emballage et mat�riel � rendre	409	FR
41	USAGERS ET COMPTES RATTACHES	4	FR
411	Usagers (et organismes de prise en charge)	41	FR
416	Cr�ances douteuses ou litigieuses	41	FR
418	Usagers - Produits non encore factur�s	41	FR
419	Usagers cr�diteurs	41	FR
42	PERSONNEL ET COMPTES RATTACHES	4	FR
421	Personnel - R�mun�rations dues	42	FR
422	Comit�s d'entreprises, d'�tablissement...	42	FR
425	Personnel - Avances et acomptes	42	FR
427	Personnel - Oppositions	42	FR
428	Personnel - Charges � payer et produits � recevoir	42	FR
4282	Dettes provisionn�es pour cong�s � payer	428	FR
4286	Autres charges � payer	428	FR
4287	Produits � recevoir	428	FR
43	SECURITE SOCIALE ET AUTRES ORGANISMES SOCIAUX	4	FR
431	S�curit� sociale	43	FR
437	Autres organismes sociaux	43	FR
4372	Mutuelles	437	FR
4373	Caisses de retraites et de pr�voyance	437	FR
4374	Caisses d'allocations de ch�mage - ASSEDIC	437	FR
4378	Autres organismes sociaux - divers	437	FR
438	Organismes sociaux - Charges � payer et produits � recevoir	43	FR
4382	Charges sociales sur cong�s � payer	438	FR
4386	Autres charges � payer	438	FR
4387	Produits � recevoir	438	FR
44	ETAT ET AUTRES COLLECTIVITES PUBLIQUES	4	FR
441	Etat - Subventions � recevoir	44	FR
4411	Subventions d'investissement	441	FR
4417	Subventions d'exploitation	441	FR
4419	Avances sur subventions	441	FR
444	Etat - Imp�ts sur les b�n�fices	44	FR
4445	Etat - Imp�t sur les soci�t� (organismes sans but lucratif)	444	FR
445	Etat - Taxes sur le chiffre d'affaires	44	FR
447	Autres imp�ts, taxes et versements assimil�s	44	FR
4471	Imp�ts, taxes et versements assimil�s sur r�mun�rations (administration des imp�ts)	447	FR
44711	Taxe sur les salaires	4471	FR
44713	Participation des employeurs � la formation professionnelle continue	4471	FR
44714	Cotisation pour d�faut d'investissement obligatoire dans la construction	4471	FR
44718	Autres imp�ts, taxes et versements assimil�s	4471	FR
4473	Imp�ts, taxes et versements assimil�s sur r�mun�rations (autres organismes)	447	FR
44733	Participation des employeurs � la formation professionnelle continue	4473	FR
44734	Participation des employeurs � l'effort de construction (versements � fonds perdus)	4473	FR
4475	Autres imp�ts, taxes et versements assimil�s (administration des imp�ts)	447	FR
4477	Autres imp�ts, taxes et versements assimil�s (autres organismes)	447	FR
448	Etat - Charges � payer et produits � recevoir	44	FR
4482	Charges fiscales sur cong�s � payer	448	FR
4486	Autres charges � payer	448	FR
4487	Produits � recevoir	448	FR
45	CONFEDERATION, FEDERATION, UNION, ASSOCIATIONS AFFILIEES ET SOCIETAIRES 	4	FR
451	Conf�d�ration, f�d�ration, union et associations affili�es - Compte courant	45	FR
455	Soci�taires - Comptes courants	45	FR
46	DEBITEURS DIVERS ET CREDITEURS DIVERS	4	FR
467	Autres comptes d�biteurs ou cr�diteurs	46	FR
468	Divers - Charges � payer et produits � recevoir	46	FR
4686	Charges � payer	468	FR
4687	Produits � recevoir	468	FR
47	COMPTES D'ATTENTE	4	FR
471	Recettes � classer	47	FR
472	D�penses � classer et � r�gulariser	47	FR
475	Legs et donations en cours de r�alisation	47	FR
48	COMPTE DE REGULARISATION	4	FR
481	Charges � r�partir sur plusieurs exercices	48	FR
4812	Frais d'acquisition des immobilisations	481	FR
4818	Charges � �taler	481	FR
486	Charges constat�es d'avance	48	FR
487	Produits constat�s d'avance	48	FR
49	PROVISIONS POUR DEPRECIATION DES COMPTES DE TIERS	4	FR
491	Provisions pour d�pr�ciation des comptes d'usagers (et organismes de prise en charge)	49	FR
496	Provisions pour d�pr�ciation des comptes de d�biteurs divers	49	FR
5	CLASSE 5. Comptes financiers 	0	FR
50	VALEURS MOBILIERES DE PLACEMENT	5	FR
503	Actions	50	FR
5031	Titres cot�s	503	FR
5035	Titres non cot�s	503	FR
506	Obligations	50	FR
5061	Titres cot�s	506	FR
5065	Titres non cot�s	506	FR
507	Bons du Tr�sor et bons de caisse � court terme	50	FR
508	Autres valeurs mobili�res et cr�ances assimil�es	50	FR
5081	Autres valeurs mobili�res	508	FR
5088	Int�r�ts courus sur obligations, bons et valeurs assimil�es	508	FR
51	BANQUES, ETABLISSEMENTS FINANCIERS ET ASSIMILES	5	FR
512	Banques	51	FR
514	Ch�ques postaux	51	FR
515	Caisses	51	FR
517	Autres organismes financiers	51	FR
5171	Caisse d'�pargne	517	FR
518	Int�r�ts courus	51	FR
5186	Int�r�ts courus � payer	518	FR
5187	Int�r�ts courus � recevoir	518	FR
53	CAISSE	5	FR
531	Caisse du si�ge	53	FR
532	Caisses des lieux d'activit�s	53	FR
54	REGIES D'AVANCES ET ACCREDITIFS	5	FR
541	R�gies d'avances	54	FR
542	Accr�ditifs	54	FR
58	VIREMENTS INTERNES	5	FR
581	Virements de fonds	58	FR
59	PROVISIONS POUR DEPRECIATION DES COMPTES FINANCIERS	5	FR
590	Provisions pour d�pr�ciation des valeurs mobili�res de placement	59	FR
6	CLASSE 6. Comptes de charges 	0	FR
60	ACHATS (sauf 603)	6	FR
601	Achats stock�s - Mati�res premi�res et fournitures (*1 Structure laiss�e libre en vue de r�pondre � la diversit� des actions entreprises par le secteur associatif)	60	FR
602	Achats stock�s - Autres approvisionnements (*1 Structure laiss�e libre en vue de r�pondre � la diversit� des actions entreprises par le secteur associatif)	60	FR
604	Achats d'�tudes et prestations de services (*2 Incorpor�s directement aux produits et prestations de services)	60	FR
606	Achats non stock�s de mati�res et fournitures (*1 Structure laiss�e libre en vue de r�pondre � la diversit� des actions entreprises par le secteur associatif)	60	FR
6061	Fournitures non stockables (eau, �nergie...)	606	FR
6063	Fournitures d'entretien et de petit �quipement	606	FR
6064	Fournitures administratives	606	FR
6068	Autres mati�res et fournitures	606	FR
607	Achats de marchandises	60	FR
6071	Marchandise A	607	FR
6072	Marchandise B	607	FR
609	Rabais, remises et ristournes obtenues sur achats	60	FR
603	Variation des stocks (approvisionnements et marchandises)	6	FR
6031	Variation des stocks de mati�res premi�res et fournitures	6	FR
6032	Variation des stocks des autres approvisionnements	6	FR
6037	Variation des stocks de marchandises	6	FR
61	AUTRES CHARGES EXTERNES - Services ext�rieurs	6	FR
611	Sous-traitance g�n�rale	61	FR
612	Redevances de cr�dit-bail	61	FR
6122	Cr�dit-bail mobilier	612	FR
613	Locations	61	FR
6132	Locations immobili�res	613	FR
6135	Locations mobili�res	613	FR
614	Charges locatives et de co-propri�t�	61	FR
615	Entretien et r�parations	61	FR
6152	... sur biens immobiliers	615	FR
6155	... sur biens mobiliers	615	FR
6156	Maintenance	615	FR
616	Primes d'assurances	61	FR
6161	Multirisques	616	FR
6162	Assurance obligatoire dommage-construction	616	FR
6168	Autres assurances	616	FR
617	Etudes et recherches	61	FR
618	Divers	61	FR
6181	Documentation g�n�rale	618	FR
6183	Documentation technique	618	FR
6185	Frais de colloques, s�minaires, conf�rences	618	FR
619	Rabais, remises et ristournes obtenues sur services ext�rieurs	61	FR
62	AUTRES CHARGES EXTERNES - AUTRES SERVICES EXTERIEURS	6	FR
621	Personnel ext�rieur � l'association	62	FR
622	R�mun�rations d'interm�diaires et honoraires	62	FR
6226	Honoraires	622	FR
6227	Frais d'actes et de contentieux	622	FR
623	Publicit�, publications, relations publiques	62	FR
6231	Annonces et insertions	623	FR
6233	Foires et expositions	623	FR
6236	Catalogues et imprim�s	623	FR
6237	Publications	623	FR
6238	Divers (pourboires, dons courants...)	623	FR
624	Transports de biens et transports collectifs du personnel	62	FR
6241	Transports sur achats	624	FR
6243	Transports entre �tablissements	624	FR
6247	Transports collectifs du personnel	624	FR
6248	Divers	624	FR
625	D�placements, missions et r�ceptions	62	FR
6251	Voyages et d�placements	625	FR
6256	Missions	625	FR
6257	R�ceptions	625	FR
626	Frais postaux et frais de t�l�communications	62	FR
627	Services bancaires et assimil�s	62	FR
628	Divers	62	FR
6281	Cotisations (li�es � l'activit� �conomique)	628	FR
6284	Frais de recrutement du personnel	628	FR
629	Rabais, remises et ristournes obtenus sur autres services ext�rieurs	62	FR
63	IMPOTS, TAXES ET VERSEMENTS ASSIMILES	6	FR
631	Imp�ts, taxes et versements assimil�s sur r�mun�rations (administration des imp�ts)	63	FR
6311	Taxe sur les salaires	631	FR
6313	Participation des employeurs � la formation professionnelle continue	631	FR
6314	Cotisation pour d�faut d'investissement obligatoire dans la construction	631	FR
633	Imp�ts, taxes et versements assimil�s sur r�mun�rations (autres organismes)	63	FR
6331	Versement de transport	633	FR
6333	Participation des employeurs � la formation professionnelle continue	633	FR
6334	Participation des employeurs � l'effort de construction (versements � fonds perdus)	633	FR
635	Autres imp�ts, taxes et versements assimil�s (administration des imp�ts)	63	FR
6351	Imp�ts directs	635	FR
63512	Taxes fonci�res	6351	FR
63513	Autres imp�ts locaux	6351	FR
63518	Autres imp�ts directs	6351	FR
6353	Imp�ts indirects	635	FR
6354	Droits d'enregistrement et de timbre	635	FR
6358	Autres droits	635	FR
637	Autres imp�ts, taxes et versements assimil�s (autres organismes)	63	FR
64	CHARGES DE PERSONNEL	6	FR
641	R�mun�rations du personnel	64	FR
6411	Salaires, appointements	641	FR
6412	Cong�s pay�s	641	FR
6413	Primes et gratifications	641	FR
6414	Indemnit�s et avantages divers	641	FR
6415	Suppl�ment familial	641	FR
645	Charges de s�curit� sociale et de pr�voyance	64	FR
6451	Cotisations � l'URSSAF	645	FR
6452	Cotisations aux mutuelles	645	FR
6453	Cotisations aux caisses de retraites et de pr�voyance	645	FR
6454	Cotisations aux ASSEDIC	645	FR
6458	Cotisations aux autres organismes sociaux	645	FR
647	Autres charges sociales	64	FR
6472	Versements aux comit�s d'entreprise et d'�tablissement	647	FR
6475	M�decine du travail, pharmacie	647	FR
648	Autres charges de personnel	64	FR
65	AUTRES CHARGES DE GESTION COURANTE	6	FR
651	Redevances pour concessions, brevets, licences, marques, proc�d�s, droits et valeurs similaires	65	FR
6511	Redevances pour concessions, brevets, licences, marques, proc�d�s	651	FR
6516	Droits d'auteur et de reproduction (SACEM)	651	FR
6518	Autres droits et valeurs similaires	651	FR
654	Pertes sur cr�ances irr�couvrables	65	FR
6541	Cr�ances de l'exercice	654	FR
6544	Cr�ances des exercices ant�rieurs	654	FR
657	Subventions vers�es par l'association	65	FR
6571	Bourses accord�es aux usagers	657	FR
658	Charges diverses de gestion courante	65	FR
6586	Cotisations (li�es � la vie statutaire)	658	FR
66	CHARGES FINANCIERES	6	FR
661	Charges d'int�r�ts	66	FR
6611	Int�r�ts des emprunts et dettes	661	FR
6616	Int�r�ts bancaires	661	FR
6618	Int�r�ts des autres dettes	661	FR
666	Pertes de change	66	FR
667	Charges nettes sur cessions de valeurs mobili�res de placement	66	FR
67	CHARGES EXCEPTIONNELLES	6	FR
671	Charges exceptionnelles sur op�rations de gestion	67	FR
6712	P�nalit�s et amendes fiscales ou p�nales	671	FR
6713	Dons, lib�ralit�s	671	FR
6714	Cr�ances devenues irr�couvrables dans l'exercice	671	FR
6717	Rappels d'imp�ts (autres qu'imp�ts sur les b�n�fices)	671	FR
6718	Autres charges exceptionnelles sur op�rations de gestion	671	FR
672	Charges sur exercices ant�rieurs (� reclasser)	67	FR
675	Valeurs comptables des �l�ments d'actif c�d�s	67	FR
6751	Immobilisations incorporelles	675	FR
6752	Immobilisations corporelles	675	FR
6756	Immobilisations financi�res	675	FR
678	Autres charges exceptionnelles 	67	FR
68	DOTATIONS AUX AMORTISSEMENTS, PROVISIONS ET ENGAGEMENTS	6	FR
681	Dotations aux amortissements, provisions et engagements	68	FR
6811	Dotations aux amortissements des immobilisations incorporelles et corporelles	681	FR
68111	Dotations aux amortissements des immobilisations incorporelles	6811	FR
68112	Dotations aux amortissements des immobilisations corporelles	6811	FR
6812	Dotations aux amortissements des charges d'exploitation � r�partir	681	FR
6815	Dotations aux provisions pour risques et charges d'exploitation	681	FR
6816	Dotations aux provisions pour d�pr�ciation des immobilisations incorporelles et corporelles	681	FR
6817	Dotations aux provisions pour d�pr�ciation des actifs circulants (autres que les valeurs mobili�res de placement)	681	FR
686	Dotations aux amortissements et aux provisions - Charges financi�res	68	FR
6866	Dotations aux provisions pour d�pr�ciation des �l�ments financiers	686	FR
68662	Dotations aux provisions financi�res	6866	FR
68665	Valeurs mobili�res de placement	6866	FR
687	Dotations aux amortissements et aux provisions - Charges exceptionnelles	68	FR
6871	Dotations aux amortissements exceptionnels des immobilisations	687	FR
6876	Dotations aux provisions pour d�pr�ciations exceptionnelles	687	FR
689	Engagements � r�aliser sur ressources affect�es	68	FR
6894	Engagements � r�aliser sur subventions attribu�es	689	FR
6895	Engagements � r�aliser sur dons manuels affect�s	689	FR
6897	Engagements � r�aliser sur legs et donations affect�s	689	FR
69	IMPOTS SUR LES BENEFICES	6	FR
695	Imp�ts sur les soci�t�s	69	FR
7	CLASSE 7. Comptes de produits 	0	FR
70	VENTES DE PRODUITS FINIS, PRESTATIONS DE SERVICES, MARCHANDISES	7	FR
701	Ventes de produits finis	70	FR
706	Prestations de services	70	FR
707	Ventes de marchandises	70	FR
708	Produits des activit�s annexes	70	FR
7081	Produits des prestations fournies au personnel	708	FR
7083	Locations diverses	708	FR
7084	Mise � disposition de personnel factur�e	708	FR
7088	Autres produits d'activit�s annexes	708	FR
709	Rabais, remises et ristournes accord�s par l'association	70	FR
71	PRODUCTION STOCKEE (OU DESTOCKAGE)	7	FR
713	Variation des stocks (en-cours de production, produits)	71	FR
7133	Variation des en-cours de production de biens	713	FR
7134	Variation des en-cours de production de services	713	FR
7135	Variation des stocks de produits	713	FR
72	PRODUCTION IMMOBILISEE	7	FR
74	SUBVENTIONS D'EXPLOITATION	7	FR
75	AUTRES PRODUITS DE GESTION COURANTE	7	FR
751	Redevances pour concessions, brevets, licences, marques, proc�d�s, droits et valeurs similaires	75	FR
754	Collectes	75	FR
756	Cotisations	75	FR
757	Quote-part d'�l�ments du fonds associatif vir�e au compte de r�sultat	75	FR
7571	Quote-part de subventions d'investissement (renouvelables) vir�e au compte de r�sultat	757	FR
7573	Quote-part des apports vir�e au compte de r�sultat	757	FR
758	Produits divers de gestion courante	75	FR
7585	Contributions volontaires	758	FR
7586	Contributions volontaires	758	FR
7587	Contributions volontaires	758	FR
7588	Contributions volontaires	758	FR
76	PRODUITS FINANCIERS	7	FR
761	Produits des participations	76	FR
762	Produits des autres immobilisations financi�res	76	FR
7621	Revenus des titres immobilis�s	762	FR
7624	Revenus des pr�ts	762	FR
764	Revenus des valeurs mobili�res de placement	76	FR
765	Escomptes obtenus	76	FR
766	Gains de change	76	FR
767	Produits nets sur cessions de valeurs mobili�res de placement	76	FR
768	Autres produits financiers	76	FR
7681	Int�r�ts des comptes financiers d�biteurs	768	FR
77	PRODUITS EXCEPTIONNELS	7	FR
771	Produits exceptionnels sur op�rations de gestion	77	FR
7713	Lib�ralit�s per�ues	771	FR
7714	Rentr�es sur cr�ances amorties	771	FR
7715	Subvention d'�quilibre	771	FR
7717	D�gr�vements d'imp�ts (autres qu'imp�ts sur les b�n�fices)	771	FR
7718	Autres produits exceptionnels sur op�rations de gestion	771	FR
772	Produits sur exercices ant�rieurs (� reclasser)	77	FR
775	Produits des cessions d'�l�ments d'actif	77	FR
7751	Immobilisations incorporelles	775	FR
7752	Immobilisations corporelles	775	FR
7756	Immobilisations financi�res	775	FR
777	Quote-part des subventions d'investissement vir�e au r�sultat de l'exercice	77	FR
778	Autres produits exceptionnels	77	FR
78	REPRISES SUR AMORTISSEMENTS ET PROVISIONS	7	FR
781	Reprises sur amortissements et provisions (� inscrire dans les produits d'exploitation)	78	FR
7811	Reprises sur amortissements des immobilisations incorporelles et corporelles	781	FR
7815	Reprises sur provisions pour risques et charges d'exploitation	781	FR
7816	Reprises sur provisions pour d�pr�ciation des immobilisations incorporelles et corporelles	781	FR
7817	Reprises sur provisions pour d�pr�ciation des actifs circulants (autres que les valeurs mobili�res de placement)	781	FR
786	Reprises sur provisions (� inscrire dans les produits financiers)	78	FR
7866	Reprises sur provisions pour d�pr�ciation des �l�ments financiers	786	FR
78662	Immobilisations financi�res	7866	FR
78665	Valeurs mobili�res de placement	7866	FR
787	Reprises sur provisions (� inscrire dans les produits exceptionnels)	78	FR
7876	Reprise sur provisions pour d�pr�ciations exceptionnelles	787	FR
789	Report des ressources non utilis�es des exercices ant�rieurs	78	FR
79	TRANSFERTS DE CHARGES	7	FR
791	Transferts de charges d'exploitation	79	FR
796	Transferts de charges financi�res	79	FR
797	Transferts de charges exceptionnelles	79	FR
8	CLASSE 8. CONTRIBUTIONS VOLONTAIRES 	0	FR
86	EMPLOIS DES CONTRIBUTIONS VOLONTAIRES EN NATURE - R�partition par nature de charges	8	FR
860	Secours en nature, alimentaires, vestimentaires, ...	86	FR
861	Mise � disposition gratuite de biens	86	FR
8611	Mise � disposition gratuite de locaux	861	FR
8612	Mise � disposition gratuite de mat�riels	861	FR
862	Prestations	86	FR
864	Personnel b�n�vole	86	FR
87	CONTRIBUTIONS VOLONTAIRES EN NATURE - R�partition par nature de ressources	8	FR
870	B�n�volat	87	FR
871	Prestations en nature	87	FR
875	Dons en nature	87	FR
9	Comptes analytiques	0	FR
\.

--
-- PostgreSQL database dump complete
--
