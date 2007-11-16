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
104	Primes li�es au capital social	10	FR
106	R�serves	10	FR
1062	R�serves indisponibles	106	FR
107	Ecart d'�quivalence	10	FR
109	Actionnaires�: Capital souscrit - non appel�	10	FR
11	report a nouveau (solde cr�diteur ou d�biteur)	1	FR
13	subventions d'investissement	1	FR
131	Subventions d'�quipement	13	FR
138	Autres subventions d'investissement (m�me ventilation que celle du compte 131)	13	FR
139	Subventions d'investissement inscrites au compte de r�sultat	13	FR
1391	Subventions d'�quipement	139	FR
1398	Autres subventions d'investissement (m�me ventilation que celle du compte 1391)	139	FR
14	provisions reglementees	1	FR
142	Provisions r�glement�es relatives aux immobilisations	14	FR
143	Provisions r�glement�es relatives aux stocks	14	FR
144	Provisions r�glement�es relatives aux autres �l�ments de l'actif	14	FR
151	Provisions pour risques	1	FR
153	Provisions pour pensions et obligations similaires	1	FR
155	Provisions pour imp�ts	1	FR
156	Provisions pour renouvellement des immobilisations (entreprises concessionnaires)	1	FR
157	Provisions pour charges � r�partir sur plusieurs exercices	1	FR
158	Autres provisions pour charges	1	FR
161	Emprunts obligataires convertibles	1	FR
163	Autres emprunts obligataires	1	FR
164	Emprunts aupr�s des �tablissements de cr�dit	1	FR
165	D�p�ts et cautionnements re�us	1	FR
166	Participation des salari�s aux r�sultats	1	FR
167	Emprunts et dettes assortis de conditions particuli�res	1	FR
1671	Emissions de titres participatifs	167	FR
1674	Avances conditionn�es de l'Etat	167	FR
1675	Emprunts participatifs	167	FR
168	Autres emprunts et dettes assimil�es	1	FR
169	Primes de remboursement des obligations	1	FR
17	dettes rattach�es a des participations	1	FR
171	Dettes rattach�es � des participations (groupe)	17	FR
174	Dettes rattach�es � des participations (hors groupe)	17	FR
178	Dettes rattach�es � des soci�t�s en participation	17	FR
18	comptes de liaison des �tablissements et societes en participation	1	FR
2	comptes d'immobilisations	0	FR
203	Frais de recherche et de d�veloppement	2	FR
205	Concessions et droits similaires, brevets, licences, marques, proc�d�s, logiciels, droits et valeurs similaires	2	FR
211	Terrains	2	FR
2111	Terrains nus	211	FR
2112	Terrains am�nag�s	211	FR
2113	Sous-sols et sur-sols	211	FR
2114	Terrains de gisement	211	FR
2115	Terrains b�tis	211	FR
2116	Compte d'ordre sur immobilisations (art. 6 du d�cret n� 78-737 du 11 juillet 1978)	211	FR
212	Agencements et am�nagements de terrains (m�me ventilation que celle du compte 211)	2	FR
213	Constructions	2	FR
2131	B�timents	213	FR
2135	Installations g�n�rales - agencements - am�nagements des constructions (m�me ventilation que celle du compte 2131)	213	FR
2138	Ouvrages d'infrastructure	213	FR
214	Constructions sur sol d'autrui (m�me ventilation que celle du compte 213)	2	FR
215	Installations techniques, mat�riels et outillage industriels	2	FR
2151	Installations complexes sp�cialis�es	215	FR
2153	Installations � caract�re sp�cifique	215	FR
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
232	Immobilisations incorporelles en cours	2	FR
237	Avances et acomptes vers�s sur immobilisations incorporelles	2	FR
238	Avances et acomptes vers�s sur commandes d'immobilisations corporelles	2	FR
25	Parts dans des entreprises li�es et cr�ances sur des entreprises li�es	2	FR
26	Participations et cr�ances rattach�es � des participations	2	FR
261	Titres de participation	26	FR
266	Autres formes de participation	26	FR
267	Cr�ances rattach�es � des participations	26	FR
268	Cr�ances rattach�es � des soci�t�s en participation	26	FR
269	Versements restant � effectuer sur titres de participation non lib�r�s	26	FR
271	Titres immobilis�s autres que les titres immobilis�s de l'activit� de portefeuille (droit de propri�t�)	2	FR
272	Titres immobilis�s (droit de cr�ance)	2	FR
273	Titres immobilis�s de l'activit� de portefeuille	2	FR
274	Pr�ts	2	FR
275	D�p�ts et cautionnements vers�s	2	FR
276	Autres cr�ances immobilis�es	2	FR
277	(Actions propres ou parts propres)	2	FR
279	Versements restant � effectuer sur titres immobilis�s non lib�r�s	2	FR
28	amortissements des immobilisations	2	FR
2801	Frais d'�tablissement (m�me ventilation que celle du compte 201)	28	FR
2803	Frais de recherche et de d�veloppement	28	FR
2805	Concessions et droits similaires, brevets, licences, logiciels, droits et valeurs similaires	28	FR
2807	Fonds commercial	28	FR
2808	Autres immobilisations incorporelles	28	FR
2811	Terrains de gisement	28	FR
2812	Agencements, am�nagements de terrains (m�me ventilation que celle du compte 212)	2	FR
2813	Constructions (m�me ventilation que celle du compte 213)	2	FR
2814	Constructions sur sol d'autrui (m�me ventilation que celle du compte 214)	2	FR
2815	Installations, mat�riel et outillage industriels (m�me ventilation que celle du compte 215)	2	FR
2818	Autres immobilisations corporelles (m�me ventilation que celle du compte 218)	2	FR
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
2967	Cr�ances rattach�es � des participations (m�me ventilation que celle du compte 267)	26	FR
2968	Cr�ances rattach�es � des soci�t�s en participation (m�me ventilation que celle du compte 268)	26	FR
2971	Titres immobilis�s autres que les titres immobilis�s de l'activit� de portefeuille -droit de propri�t� (m�me ventilation que celle du compte 271)	2	FR
2972	Titres immobilis�s - droit de cr�ance (m�me ventilation que celle du compte 272)	2	FR
2973	Titres immobilis�s de l'activit� de portefeuille	29	FR
2974	Pr�ts (m�me ventilation que celle du compte 274)	2	FR
2975	D�p�ts et cautionnements vers�s (m�me ventilation que celle du compte 275)	2	FR
2976	Autres cr�ances immobilis�es (m�me ventilation que celle du compte 276)	2	FR
3	comptes de stocks et en cours	0	FR
321	Mati�res consommables	3	FR
322	Fournitures consommables	3	FR
326	Emballages	3	FR
331	Produits en cours	3	FR
335	Travaux en cours	3	FR
341	Etudes en cours	3	FR
345	Prestations de services en cours	3	FR
351	Produits interm�diaires	3	FR
355	Produits finis	3	FR
358	Produits r�siduels (ou mati�res de r�cup�ration)	3	FR
36	(compte � ouvrir, le cas �ch�ant, sous l'intitul� "�stocks provenant d'immobilisations�")	3	FR
38	(lorsque l'entit� tient un inventaire permanent en comptabilit� g�n�rale, le compte 38 peut �tre utilis� pour comptabiliser les stocks en voie d'acheminement, mis en d�p�t ou donn�s en consignation)	3	FR
39	provisions pour d�pr�ciation des stocks et en-cours	3	FR
4	comptes de tiers	0	FR
40	fournisseurs et comptes rattaches	4	FR
401	Fournisseurs	40	FR
403	Fournisseurs - Effets � payer	40	FR
404	Fournisseurs d'immobilisations	40	FR
405	Fournisseurs d'immobilisations - Effets � payer	40	FR
408	Fournisseurs - Factures non parvenues	40	FR
4091	Fournisseurs - Avances et acomptes vers�s sur commandes	40	FR
4096	Fournisseurs - Cr�ances pour emballages et mat�riel � rendre	40	FR
4097	Fournisseurs - Autres avoirs	40	FR
4098	Rabais, remises, ristournes � obtenir et autres avoirs non encore re�us	40	FR
41	clients et comptes rattaches	4	FR
411	Clients	41	FR
413	Clients - Effets � recevoir	41	FR
416	Clients douteux ou litigieux	41	FR
417	"�Cr�ances�" sur travaux non encore facturables	41	FR
418	Clients - Produits non encore factur�s	41	FR
4191	Clients - Avances et acomptes re�us sur commandes	41	FR
4196	Clients - Dettes sur emballages et mat�riels consign�s	41	FR
4197	Clients - Autres avoirs	41	FR
4198	Rabais, remises, ristournes � accorder et autres avoirs � �tablir	41	FR
42	Personnel et comptes rattaches	4	FR
422	Comit�s d'entreprises, d'�tablissement,...	42	FR
424	Participation des salari�s aux r�sultats	42	FR
425	Personnel - Avances et acomptes	42	FR
426	Personnel - D�p�ts	42	FR
427	Personnel - Oppositions	42	FR
431	S�curit� sociale	4	FR
437	Autres organismes sociaux	4	FR
438	Organismes sociaux - Charges � payer et produits � recevoir	4	FR
44	�tat et autres collectivit�s publiques	4	FR
441	�tat - Subventions � recevoir	44	FR
442	Etat - Imp�ts et taxes recouvrables sur des tiers	44	FR
443	Op�rations particuli�res avec l'Etat les collectivit�s publiques, les organismes internationaux	44	FR
4431	Cr�ances sur l'Etat r�sultant de la suppression de la r�gle du d�calage d'un mois en mati�re de T.V.A.	443	FR
4438	Int�r�ts courus sur cr�ances figurant au 4431	443	FR
4452	T.V.A. due intracommunautaire	44	FR
4455	Taxes sur le chiffre d'affaires � d�caisser	44	FR
4456	Taxes sur le chiffre d'affaires d�ductibles	44	FR
4457	Taxes sur le chiffre d'affaires collect�es par l'entreprise	44	FR
4458	Taxes sur le chiffre d'affaires � r�gulariser ou en attente	44	FR
446	Obligations cautionn�es	44	FR
448	Etat - Charges � payer et produits � recevoir	44	FR
451	Groupe	4	FR
456	Associ�s - Op�rations sur le capital	4	FR
457	Associ�s - Dividendes � payer	4	FR
458	Associ�s - Op�rations faites en commun et en G.I.E.	4	FR
471	Comptes d'attente	4	FR
472	Comptes d'attente	4	FR
473	Comptes d'attente	4	FR
474	Comptes d'attente	4	FR
475	Comptes d'attente	4	FR
476	Diff�rence de conversion - Actif	4	FR
477	Diff�rences de conversion - Passif	4	FR
478	Autres comptes transitoires	4	FR
48	comptes de r�gularisation	4	FR
4811	Charges diff�r�es	48	FR
4812	Frais d'acquisition des immobilisations	48	FR
4816	Frais d'�mission des emprunts	48	FR
4818	Charges � �taler	48	FR
488	Comptes de r�partition p�riodique des charges et des produits	48	FR
49	provisions pour d�pr�ciation des comptes de tiers	4	FR
495	Provisions pour d�pr�ciation des comptes du groupe et des associ�s	49	FR
4951	Comptes du groupe	495	FR
4955	Comptes courants des associ�s	495	FR
4958	Op�rations faites en commun et en G.I.E.	495	FR
5	comptes financiers	0	FR
501	Parts dans des entreprises li�es	5	FR
502	Actions propres	5	FR
503	Actions	5	FR
504	Autres titres conf�rant un droit de propri�t�	5	FR
505	Obligations et bons �mis par la soci�t� et rachet�s par elle	5	FR
506	Obligations	5	FR
507	Bons du Tr�sor et bons de caisse � court terme	5	FR
508	Autres valeurs mobili�res de placement et autres cr�ances assimil�es	5	FR
509	Versements restant � effectuer sur valeurs mobili�res de placement non lib�r�es	5	FR
511	Valeurs � l'encaissement	5	FR
512	Banques	5	FR
514	Ch�ques postaux	5	FR
515	"�Caisses�" du Tr�sor et des �tablissements publics	5	FR
516	Soci�t�s de bourse	5	FR
517	Autres organismes financiers	5	FR
518	Int�r�ts courus	5	FR
519	Concours bancaires courants	5	FR
52	Instruments de tr�sorerie	5	FR
59	provisions pour d�pr�ciation des comptes financiers	5	FR
5903	Actions	59	FR
5904	Autres titres conf�rant un droit de propri�t�	59	FR
5906	Obligations	59	FR
5908	Autres valeurs mobili�res de placement et cr�ances assimil�es	59	FR
6	comptes de charges	0	FR
601	Achats stock�s - Mati�res premi�res (et fournitures)	6	FR
602	Achats stock�s - Autres approvisionnements	6	FR
6021	Mati�res consommables	602	FR
6022	Fournitures consommables	602	FR
6026	Emballages	602	FR
604	Achats d'�tudes et prestations de services	6	FR
605	Achats de mat�riel, �quipements et travaux	6	FR
606	Achats non stock�s de mati�re et fournitures	6	FR
607	Achats de marchandises	6	FR
608	(Compte r�serv�, le cas �ch�ant, � la r�capitulation des frais accessoires incorpor�s aux achats)	6	FR
609	Rabais, remises et ristournes obtenus sur achats	6	FR
6031	Variation des stocks de mati�res premi�res (et fournitures)	6	FR
6032	Variation des stocks des autres approvisionnements	6	FR
6037	Variation des stocks de marchandises	6	FR
61	autres charges externes - Services ext�rieurs	6	FR
611	Sous-traitance g�n�rale	61	FR
612	Redevances de cr�dit-bail	61	FR
6122	Cr�dit-bail mobilier	612	FR
6125	Cr�dit-bail immobilier	612	FR
613	Locations	61	FR
614	Charges locatives et de copropri�t�	61	FR
615	Entretien et r�parations	61	FR
616	Primes d'assurances	61	FR
617	Etudes et recherches	61	FR
618	Divers	61	FR
619	Rabais, remises et ristournes obtenus sur services ext�rieurs	61	FR
62	autres charges externes - Autres services ext�rieurs	6	FR
621	Personnel ext�rieur � l'entreprise	62	FR
622	R�mun�rations d'interm�diaires et honoraires	62	FR
623	Publicit�, publications, relations publiques	62	FR
624	Transports de biens et transports collectifs du personnel	62	FR
625	D�placements, missions et r�ceptions	62	FR
626	Frais postaux et de t�l�communications	62	FR
627	Services bancaires et assimil�s	62	FR
628	Divers	62	FR
629	Rabais, remises et ristournes obtenus sur autres services ext�rieurs	62	FR
631	Imp�ts, taxes et versements assimil�s sur r�mun�rations (administrations des imp�ts)	6	FR
633	Imp�ts, taxes et versements assimil�s sur r�mun�rations (autres organismes)	6	FR
635	Autres imp�ts, taxes et versements assimil�s (administrations des imp�ts)	6	FR
637	Autres imp�ts, taxes et versements assimil�s (autres organismes)	6	FR
64	Charges de personnel	6	FR
647	Autres charges sociales	64	FR
648	Autres charges de personnel	64	FR
651	Redevances pour concessions, brevets, licences, marques, proc�d�s, logiciels, droits et valeurs similaires	6	FR
653	Jetons de pr�sence	6	FR
654	Pertes sur cr�ances irr�couvrables	6	FR
655	Quotes-parts de r�sultat sur op�rations faites en commun	6	FR
658	Charges diverses de gestion courante	6	FR
661	Charges d'int�r�ts	6	FR
664	Pertes sur cr�ances li�es � des participations	6	FR
665	Escomptes accord�s	6	FR
666	Pertes de change	6	FR
667	Charges nettes sur cessions de valeurs mobili�res de placement	6	FR
668	Autres charges financi�res	6	FR
671	Charges exceptionnelles sur op�rations de gestion	6	FR
672	(Compte � la disposition des entit�s pour enregistrer, en cours d'exercice, les charges sur exercices ant�rieurs)	6	FR
675	Valeurs comptables des �l�ments d'actif c�d�s	6	FR
678	Autres charges exceptionnelles	6	FR
68	Dotations aux amortissements et aux provisions	6	FR
6811	Dotations aux amortissements sur immobilisations incorporelles et corporelles	68	FR
6812	Dotations aux amortissements des charges d'exploitation � r�partir	68	FR
6815	Dotations aux provisions pour risques et charges d'exploitation	68	FR
6816	Dotations aux provisions pour d�pr�ciation des immobilisations incorporelles et corporelles	68	FR
6817	Dotations aux provisions pour d�pr�ciation des actifs circulants	68	FR
6861	Dotations aux amortissements des primes de remboursement des obligations	68	FR
6865	Dotations aux provisions pour risques et charges financiers	68	FR
6866	Dotations aux provisions pour d�pr�ciation des �l�ments financiers	68	FR
6868	Autres dotations	68	FR
6871	Dotations aux amortissements exceptionnels des immobilisations	68	FR
6872	Dotations aux provisions r�glement�es (immobilisations)	68	FR
6873	Dotations aux provisions r�glement�es (stocks)	68	FR
6874	Dotations aux autres provisions r�glement�es	68	FR
6875	Dotations aux provisions pour risques et charges exceptionnels	68	FR
6876	Dotations aux provisions pour d�pr�ciations exceptionnelles	68	FR
69	participation des salaries - imp�ts sur les benefices et assimiles	6	FR
696	Suppl�ments d'imp�t sur les soci�t�s li�s aux distributions	69	FR
698	Int�gration fiscale	69	FR
6981	Int�gration fiscale - Charges	698	FR
6989	Int�gration fiscale - Produits	698	FR
7	comptes de produits	0	FR
70	ventes de produits fabriques, prestations de services, marchandises	7	FR
702	Ventes de produits interm�diaires	70	FR
703	Ventes de produits r�siduels	70	FR
704	Travaux	70	FR
705	Etudes	70	FR
71	production stock�e (ou d�stockage)	7	FR
7133	Variation des en-cours de production de biens	71	FR
7134	Variation des en-cours de production de services	71	FR
7135	Variation des stocks de produits	71	FR
721	Immobilisations incorporelles	7	FR
722	Immobilisations corporelles	7	FR
731	Produits nets partiels sur op�rations en cours (� subdiviser par op�ration)	7	FR
739	Produits nets partiels sur op�rations termin�es	7	FR
751	Redevances pour concessions, brevets, licences, marques, proc�d�s, logiciels, droits et valeurs similaires	7	FR
752	Revenus des immeubles non affect�s � des activit�s professionnelles	7	FR
758	Produits divers de gestion courante	7	FR
761	Produits de participations	7	FR
762	Produits des autres immobilisations financi�res	7	FR
763	Revenus des autres cr�ances	7	FR
764	Revenus des valeurs mobili�res de placement	7	FR
765	Escomptes obtenus	7	FR
766	Gains de change	7	FR
767	Produits nets sur cessions de valeurs mobili�res de placement	7	FR
768	Autres produits financiers	7	FR
771	Produits exceptionnels sur op�rations de gestion	7	FR
772	(Compte � la disposition des entit�s pour enregistrer, en cours d'exercice, les produits sur exercices ant�rieurs)	7	FR
775	Produits des cessions d'�l�ments d'actif	7	FR
777	Quote-part des subventions d'investissement vir�e au r�sultat de l'exercice	7	FR
778	Autres produits exceptionnels	7	FR
78	Reprises sur amortissements et provisions	7	FR
7811	Reprises sur amortissements des immobilisations incorporelles et corporelles	78	FR
7815	Reprises sur provisions pour risques et charges d'exploitation	78	FR
7816	Reprises sur provisions pour d�pr�ciation des immobilisations incorporelles et corporelles	78	FR
7817	Reprises sur provisions pour d�pr�ciation des actifs circulants	78	FR
7865	Reprises sur provisions pour risques et charges financiers	78	FR
7866	Reprises sur provisions pour d�pr�ciation des �l�ments financiers	78	FR
7872	Reprises sur provisions r�glement�es (immobilisations)	78	FR
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
