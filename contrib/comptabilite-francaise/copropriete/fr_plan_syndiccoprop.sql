--
-- PostgreSQL database dump
-- Version 2008/02/10 09:19
--

--
-- Name: TABLE tmp_pcmn; Type: COMMENT; Schema:  public; Owner: phpcompta
--

COMMENT ON TABLE tmp_pcmn IS 'Plan comptable - Syndicat des copropri�taires : strict';

--
-- Data for Name: tmp_pcmn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) FROM stdin;
1	Classe 1 - Provisions, avances, subventions et emprunts	0
10	Provisions et avances :	1
102	Provisions pour travaux d�cid�s	10
103	Avances	10
1031	Avances de tr�sorerie	103
1032	Avances travaux au titre de l'article 18, 6e alin�a de la loi susvis�e	103
1033	Autres avances	103
12	Solde en attente sur travaux et op�rations exceptionnelles	1
13	Subventions :	1
131	Subventions accord�es en instance de versement	13
4	Classe 4 - Copropri�taires et tiers	0
40	Fournisseurs :	4
401	Factures parvenues	40
408	Factures non parvenues	40
409	Fournisseurs d�biteurs	40
42	Personnel :	4
421	R�mun�rations dues	42
43	S�curit� sociale et autres organismes sociaux :	4
431	S�curit� sociale	43
432	Autres organismes sociaux	43
44	Etat et collectivit�s territoriales :	4
441	Etat et autres organismes - subventions � recevoir	44
442	Etat - imp�ts et versements assimil�s	44
443	Collectivit�s territoriales - aides	44
45	Collectivit� des copropri�taires :	4
450	Copropri�taire individualis�	45
4501	Sur d�cision AG : Copropri�taire - budget pr�visionnel	450
4502	Sur d�cision AG : Copropri�taire - travaux de l'article 14-2 de la loi susvis�e et op�rations exceptionnelles	450
4503	Sur d�cision AG : Copropri�taire - avances	450
4504	Sur d�cision AG : Copropri�taire - emprunts	450
459	Copropri�taire - cr�ances douteuses	45
46	D�biteurs et cr�diteurs divers :	4
461	D�biteurs divers	46
462	Cr�diteurs divers	46
47	Compte d'attente :	4
471	Compte en attente d'imputation d�biteur	47
472	Compte en attente d'imputation cr�diteur	47
48	Compte de r�gularisation :	4
486	Charges pay�es d'avance	48
487	Produits encaiss�s d'avance	48
49	D�pr�ciation des comptes de tiers :	4
491	Copropri�taires	49
492	Personnes autres que les copropri�taires	49
5	Classe 5 - Comptes financiers	0
50	Fonds plac�s :	5
501	Compte � terme	50
502	Autre compte	50
51	Banques, ou fonds disponibles en banque pour le syndicat :	5
512	Banques	51
514	Ch�ques postaux	51
53	Caisse.	5
6	Classe 6 - Comptes de charges	0
60	Achats de mati�res et fournitures :	6
601	Eau	60
602	Electrici�	60
603	Chauffage, �nergie et combustibles	60
604	Achats produits d'entretien et petits �quipements	60
605	Mat�riel	60
606	Fournitures	60
61	Services ext�rieurs :	6
611	Nettoyage des locaux	5
612	Locations immobili�res	61
613	Locations mobili�res	61
614	Contrats de maintenance	61
615	Entretien et petites r�parations	61
616	Primes d'assurances	61
62	Frais d'administration et honoraires :	6
621	R�mun�rations du syndic sur gestion copropri�t�	62
6211	R�mun�ration du syndic	621
6212	D�bours	621
6213	Frais postaux	621
622	Autres honoraires du syndic	62
6221	Honoraires travaux	622
6222	Prestations particuli�res	622
6223	Autres honoraires	622
623	R�mun�rations de tiers intervenants	62
624	Frais du conseil syndical	62
63	Imp�ts - taxes et versements assimil�s :	6
632	Taxe de balayage	63
633	Taxe fonci�re	63
634	Autres imp�ts et taxes	63
64	Frais de personnel :	6
641	Salaires	64
642	Charges sociales et organismes sociaux	64
643	Taxe sur les salaires	64
644	Autres (m�decine du travail, mutuelles, etc.)	64
66	Charges financi�res des emprunts, agios ou autres :	6
661	Remboursement d'annuit�s d'emprunt	66
662	Autres charges financi�res et agios	66
67	Charges pour travaux et op�rations exceptionnelles :	6
671	Travaux d�cid�s par l'assembl�e g�n�rale	67
672	Travaux urgents	67
673	Etudes techniques, diagnostic, consultation	67
677	Pertes sur cr�ances irr�couvrables	67
678	Charges exceptionnelles	67
68	Dotations aux d�pr�ciations sur cr�ances douteuses.	6
7	Classe 7 - Comptes de produits	0
70	Appels de fonds :	7
701	Provisions sur op�rations courantes	70
702	Provisions sur travaux de l'article 14-2 et op�rations exceptionnelles	70
703	Avances	70
704	Remboursements d'annuit�s d'emprunts	70
71	Autres produits :	7
711	Subventions	71
712	Emprunts	71
713	Indemnit�s d'assurances	71
714	Produits divers (dont int�r�ts l�gaux dus par les copropri�taires)	71
716	Produits financiers	71
718	Produits exceptionnels	71
78	Reprises de d�pr�ciations sur cr�ances douteuses.	7
8	Comptes sp�ciaux	0
9	Comptes analytique	0
\.

--
-- PostgreSQL database dump complete
--
