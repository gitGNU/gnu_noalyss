--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;

SET search_path = public, pg_catalog;

--
-- Data for TOC entry 30 (OID 109648)
-- Name: tmp_pcmn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10, 'Capital ', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (100, ' Capital souscrit', 10, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (11, 'Prime d''�mission ', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (12, 'Plus Value de r��valuation ', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13, 'R�serve ', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (130, 'R�serve l�gale', 13, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (131, 'R�serve indisponible', 13, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1310, 'R�serve pour actions propres', 131, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1311, ' Autres r�serves indisponibles', 131, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (132, ' R�serves immunis�es', 13, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (133, 'R�serves disponibles', 13, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (14, 'B�n�fice ou perte report�e', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (140, 'B�n�fice report�', 14, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (141, 'Perte report�e', 14, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (15, 'Subside en capital', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16, 'Provisions pour risques et charges', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (160, 'Provisions pour pensions et obligations similaires', 16, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (161, 'Provisions pour charges fiscales', 16, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (162, 'Provisions pour grosses r�paration et gros entretien', 16, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (17, ' Dettes � plus d''un an', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (170, 'Emprunts subordonn�s', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1700, 'convertibles', 170, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1701, 'non convertibles', 170, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (171, 'Emprunts subordonn�s', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1710, 'convertibles', 170, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1711, 'non convertibles', 170, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (172, ' Dettes de locations financement', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (173, ' Etablissement de cr�dit', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1730, 'Dettes en comptes', 173, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1731, 'Promesses', 173, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1732, 'Cr�dits d''acceptation', 173, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (174, 'Autres emprunts', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (175, 'Dettes commerciales', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1750, 'Fournisseurs', 175, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1751, 'Effets � payer', 175, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (176, 'Acomptes re�us sur commandes', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (178, 'Cautionnement re�us en num�raires', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (179, 'Dettes diverses', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (20, 'Frais d''�tablissement', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (200, 'Frais de constitution et d''augmentation de capital', 20, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (201, ' Frais d''�mission d''emprunts et primes de remboursement', 20, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (202, 'Autres frais d''�tablissement', 20, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (204, 'Frais de restructuration', 20, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21, 'Immobilisations incorporelles', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (210, 'Frais de recherche et de d�veloppement', 21, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (211, 'Concessions, brevet, licence savoir faire, marque et droit similaires', 21, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (212, 'Goodwill', 21, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (213, 'Acomptes vers�s', 21, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (22, 'Terrains et construction', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (220, 'Terrains', 22, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (221, 'Construction', 22, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (222, 'Terrains b�tis', 22, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (223, 'Autres droits r�els sur des immeubles', 22, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (23, ' Installations, machines et outillages', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (24, 'Mobilier et Mat�riel roulant', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (25, 'Immobilisations d�tenus en location-financement et droits similaires', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (250, 'Terrains', 25, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (251, 'Construction', 25, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (252, 'Terrains b�tis', 25, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (253, 'Mobilier et mat�riels roulants', 25, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (26, 'Autres immobilisations corporelles', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (27, 'Immobilisations corporelles en cours et acomptes vers�s', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (28, 'Immobilisations financi�res', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (280, 'Participation dans des entreprises li�es', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2800, 'Valeur d''acquisition', 280, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2801, 'Montants non-appel�s(-)', 280, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2808, 'Plus-values act�es', 280, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2809, 'R�ductions de valeurs act�es', 280, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (281, 'Cr�ance sur  des entreprises li�es', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2810, 'Cr�ance en compte', 281, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2811, 'Effets � recevoir', 281, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2812, 'Titre � reveny fixe', 281, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2817, 'Cr�ances douteuses', 281, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2819, 'R�duction de valeurs act�es', 281, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (282, 'Participations dans des entreprises avec lesquelles il existe un lien de participation', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2820, 'Valeur d''acquisition', 282, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2821, 'Montants non-appel�s(-)', 282, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2828, 'Plus-values act�es', 282, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2829, 'R�ductions de valeurs act�es', 282, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (283, 'Cr�ances sur des entreprises avec lesquelles existe un lien de participation', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2830, 'Cr�ance en compte', 283, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2831, 'Effets � recevoir', 283, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2832, 'Titre � revenu fixe', 283, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2837, 'Cr�ances douteuses', 283, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2839, 'R�duction de valeurs act�es', 283, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (284, 'Autres actions et parts', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2840, 'Valeur d''acquisition', 284, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2841, 'Montants non-appel�s(-)', 284, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2848, 'Plus-values act�es', 284, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2849, 'R�ductions de valeurs act�es', 284, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (285, 'Autres cr�ances', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2850, 'Cr�ance en compte', 285, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2851, 'Effets � recevoir', 285, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2852, 'Titre � revenu fixe', 285, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2857, 'Cr�ances douteuses', 285, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2859, 'R�ductions de valeurs act�es', 285, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (288, 'Cautionnements vers�s en num�raires', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (29, 'Cr�ances � plus d''un an', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (290, 'Cr�ances commerciales', 29, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2900, 'Clients', 290, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2901, 'Effets � recevoir', 290, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2906, 'Acomptes vers�s', 290, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2907, 'Cr�ances douteuses', 290, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2909, 'R�ductions de valeurs act�es', 290, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (291, 'Autres cr�ances', 29, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2910, 'Cr�ances en comptes', 291, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2911, 'Effets � recevoir', 291, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2917, 'Cr�ances douteuses', 291, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2919, 'R�ductions de valeurs act�es(-)', 291, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (30, 'Approvisionements - Mati�res premi�res', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (300, 'Valeur d''acquisition', 30, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (309, 'R�ductions de valeur act�es', 30, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (31, 'Approvisionnements - fournitures', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (310, 'Valeur d''acquisition', 31, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (319, 'R�ductions de valeurs act�es(-)', 31, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (32, 'En-cours de fabrication', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (320, 'Valeurs d''acquisition', 32, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (329, 'R�ductions de valeur act�es', 32, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (33, 'Produits finis', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (330, 'Valeur d''acquisition', 33, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (339, 'R�ductions de valeur act�es', 33, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (34, 'Marchandises', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (340, 'Valeur d''acquisition', 34, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (349, 'R�ductions de valeur act�es', 34, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (35, 'Immeubles destin�s � la vente', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (350, 'Valeur d''acquisition', 35, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (359, 'R�ductions de valeur act�es', 35, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (36, 'Acomptes vers�s sur achats pour stocks', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (360, 'Valeur d''acquisition', 36, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (369, 'R�ductions de valeur act�es', 36, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (37, 'Commandes en cours �x�cution', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (370, 'Valeur d''acquisition', 37, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (371, 'B�n�fice pris en compte ', 37, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (379, 'R�ductions de valeur act�es', 37, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (40, 'Cr�ances commerciales', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (400, 'Clients', 40, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (401, 'Effets � recevoir', 40, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (404, 'Produits � recevoir', 40, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (406, 'Acomptes vers�s', 40, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (407, 'Cr�ances douteuses', 40, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (409, 'R�ductions de valeur act�es', 40, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (41, 'Autres cr�ances', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (410, 'Capital appel� non vers�', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (411, 'TVA � r�cup�rer', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (412, 'Imp�ts et pr�comptes � r�cup�rer', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4120, 'Imp�t belge sur le r�sultat', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4121, 'Imp�t belge sur le r�sultat', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4122, 'Imp�t belge sur le r�sultat', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4123, 'Imp�t belge sur le r�sultat', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4124, 'Imp�t belge sur le r�sultat', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4125, 'Autres imp�ts et taxes belges', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4126, 'Autres imp�ts et taxes belges', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4127, 'Autres imp�ts et taxes belges', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4128, 'Imp�ts et taxes �trangers', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (414, 'Produits � recevoir', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (416, 'Cr�ances diverses', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4160, 'Comptes de l''exploitant', 416, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (417, 'Cr�ances douteuses', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (418, 'Cautionnements vers�s en num�raires', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (419, 'R�ductions de valeur act�es', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (42, 'Dettes � plus dun an �ch�ant dans l''ann�e', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (420, 'Emprunts subordonn�s', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4200, 'convertibles', 420, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4201, 'non convertibles', 420, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (421, 'Emprunts subordonn�s', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4210, 'convertibles', 420, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4211, 'non convertibles', 420, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (422, ' Dettes de locations financement', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (423, ' Etablissement de cr�dit', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4230, 'Dettes en comptes', 423, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4231, 'Promesses', 423, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4232, 'Cr�dits d''acceptation', 423, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (424, 'Autres emprunts', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (425, 'Dettes commerciales', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4250, 'Fournisseurs', 425, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4251, 'Effets � payer', 425, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (426, 'Acomptes re�us sur commandes', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (428, 'Cautionnement re�us en num�raires', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (429, 'Dettes diverses', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (43, 'Dettes financi�res', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (430, 'Etablissements de cr�dit - Emprunts � compte � terme fixe', 43, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (431, 'Etablissements de cr�dit - Promesses', 43, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (432, ' Etablissements de cr�dit - Cr�dits d''acceptation', 43, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (433, 'Etablissements de cr�dit -Dettes en comptes courant', 43, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (439, 'Autres emprunts', 43, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44, 'Dettes commerciales', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (440, 'Fournisseurs', 44, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (441, 'Effets � payer', 44, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (444, 'Factures � recevoir', 44, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (45, 'Dettes fiscales, salariales et sociales', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (450, 'Dettes fiscales estim�es', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4500, 'Imp�ts belges sur le r�sultat', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4501, 'Imp�ts belges sur le r�sultat', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4502, 'Imp�ts belges sur le r�sultat', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4503, 'Imp�ts belges sur le r�sultat', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4504, 'Imp�ts belges sur le r�sultat', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4505, 'Autres imp�ts et taxes belges', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4506, 'Autres imp�ts et taxes belges', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4507, 'Autres imp�ts et taxes belges', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4508, 'Imp�ts et taxes �trangers', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (451, 'TVA � payer', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (452, 'Imp�ts et taxes � payer', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4520, 'Imp�ts belges sur le r�sultat', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4521, 'Imp�ts belges sur le r�sultat', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4522, 'Imp�ts belges sur le r�sultat', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4523, 'Imp�ts belges sur le r�sultat', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4524, 'Imp�ts belges sur le r�sultat', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4525, 'Autres imp�ts et taxes belges', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4526, 'Autres imp�ts et taxes belges', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4527, 'Autres imp�ts et taxes belges', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4528, 'Imp�ts et taxes �trangers', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (453, 'Pr�comptes retenus', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (454, 'Office National de la S�curit� Sociales', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (455, 'R�mun�rations', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (456, 'P�cules de vacances', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (459, 'Autres dettes sociales', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (46, 'Acomptes re�us sur commandes', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (47, 'Dettes d�coulant de l''affectation du r�sultat', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (470, 'Dividendes et tanti�mes d''exercices ant�rieurs', 47, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (471, 'Dividendes de l''exercice', 47, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (472, 'Tanti�mes de l''exercice', 47, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (473, 'Autres allocataires', 47, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (48, 'Dettes diverses', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (480, 'Obligations et coupons �chus', 48, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (488, 'Cautionnements re�us en num�raires', 48, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (489, 'Autres dettes diverses', 48, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4890, 'Compte de l''exploitant', 489, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (49, 'Comptes de r�gularisation', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (490, 'Charges � reporter', 49, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (491, 'Produits acquis', 49, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (492, 'Charges � imputer', 49, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (493, 'Produits � reporter', 49, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (499, 'Comptes d''attentes', 49, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (50, 'Actions propres', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (51, 'Actions et parts', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (510, 'Valeur d''acquisition', 51, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (511, 'Montant non appel�s', 51, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (519, 'R�ductions de valeur act�es', 51, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (52, 'Titres � revenu fixe', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (520, 'Valeur d''acquisition', 52, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (529, 'R�ductions de valeur act�es', 52, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (53, 'D�p�ts � terme', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (530, 'de plus d''un an', 53, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (531, 'de plus d''un mois et d''un an au plus', 53, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (532, 'd''un mois au plus', 53, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (539, 'R�ductions de valeur act�es', 53, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (54, 'Valeurs �chues � l''encaissement', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (55, 'Etablissement de cr�dit', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5500, 'Comptes courants', 550, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5501, 'Ch�ques �mis (-)', 550, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5509, 'R�duction de valeur act�e', 550, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5510, 'Comptes courants', 551, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5511, 'Ch�ques �mis (-)', 551, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5519, 'R�duction de valeur act�e', 551, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5520, 'Comptes courants', 552, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5521, 'Ch�ques �mis (-)', 552, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5529, 'R�duction de valeur act�e', 552, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5530, 'Comptes courants', 553, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5531, 'Ch�ques �mis (-)', 553, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5539, 'R�duction de valeur act�e', 553, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5540, 'Comptes courants', 554, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5541, 'Ch�ques �mis (-)', 554, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5549, 'R�duction de valeur act�e', 554, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5550, 'Comptes courants', 555, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5551, 'Ch�ques �mis (-)', 555, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5559, 'R�duction de valeur act�e', 555, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5560, 'Comptes courants', 556, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5561, 'Ch�ques �mis (-)', 556, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5569, 'R�duction de valeur act�e', 556, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5570, 'Comptes courants', 557, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5571, 'Ch�ques �mis (-)', 557, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5579, 'R�duction de valeur act�e', 557, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5580, 'Comptes courants', 558, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5581, 'Ch�ques �mis (-)', 558, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5589, 'R�duction de valeur act�e', 558, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5590, 'Comptes courants', 559, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5591, 'Ch�ques �mis (-)', 559, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5599, 'R�duction de valeur act�e', 559, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (56, 'Office des ch�ques postaux', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (560, 'Compte courant', 56, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (561, 'Ch�ques �mis', 56, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (57, 'Caisses', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (578, 'Caisse timbre', 57, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (58, 'Virement interne', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60, 'Approvisionnement et marchandises', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (600, 'Achats de mati�res premi�res', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (601, 'Achats de fournitures', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (602, 'Achats de services, travaux et �tudes', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (603, 'Sous-traitances g�n�rales', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (604, 'Achats de marchandises', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (605, 'Achats d''immeubles destin�s � la vente', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (608, 'Remises, ristournes et rabais obtenus(-)', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (609, 'Variation de stock', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6090, 'de mati�res premi�res', 609, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6091, 'de fournitures', 609, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6094, 'de marchandises', 609, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6095, 'immeubles achet�s destin�s � la vente', 609, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (61, 'Services et biens divers', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (62, 'R�mun�rations, charges sociales et pensions', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (620, 'R�mun�rations et avantages sociaux directs', 62, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6200, 'Administrateurs ou g�rants', 620, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6201, 'Personnel de directions', 620, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6202, 'Employ�s,620', 6202, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6203, 'Ouvriers', 620, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6204, 'Autres membres du personnel', 620, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (621, 'Cotisations patronales d''assurances sociales', 62, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (622, 'Primes partonales pour assurances extra-l�gales', 62, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (623, 'Autres frais de personnel', 62, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (624, 'Pensions de retraite et de survie', 62, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6240, 'Administrateurs ou g�rants', 624, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6241, 'Personnel', 624, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63, 'Amortissements, r�ductions de valeurs et provisions pour risques et charges', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (630, 'Dotations aux amortissements et r�duction de valeurs sur immobilisations', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6300, ' Dotations aux amortissements sur frais d''�tablissement', 630, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (705, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6301, 'Dotations aux amortissements sur immobilisations incorporelles', 630, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6302, 'Dotations aux amortissements sur immobilisations corporelles', 630, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6308, 'Dotations aux r�ductions de valeur sur immobilisations incorporelles', 630, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6309, 'Dotations aux r�ductions de valeur sur immobilisations corporelles', 630, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (631, 'R�ductions de valeur sur stocks', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6310, 'Dotations', 631, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6311, 'Reprises(-)', 631, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (632, 'R�ductions de valeur sur commande en cours d''�x�cution', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6320, 'Dotations', 632, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6321, 'Reprises(-)', 632, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (633, 'R�ductions de valeurs sur cr�ances commerciales � plus d''un an', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6330, 'Dotations', 633, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6331, 'Reprises(-)', 633, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (634, 'R�ductions de valeur sur cr�ances commerciales � un an au plus', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6340, 'Dotations', 634, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6341, 'Reprise', 634, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (635, 'Provisions pour pensions et obligations similaires', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6350, 'Dotations', 635, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6351, 'Utilisation et reprises', 635, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (636, 'Provisions pour grosses r�parations et gros entretien', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6360, 'Dotations', 636, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6361, 'Reprises(-)', 636, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (637, 'Provisions pour autres risques et charges', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6370, 'Dotations', 637, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6371, 'Reprises(-)', 637, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (64, 'Autres charges d''exploitation', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (640, 'Charges fiscales d''exploitation', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (641, 'Moins-values sur r�alisations courantes d''immobilisations corporelles', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (642, 'Moins-value sur r�alisation de cr�ances commerciales', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (643, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (644, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (645, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (646, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (647, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (648, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (649, 'Charges d''exploitation port�es � l''actif au titre de frais de restructuration(-)', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (65, 'Charges financi�res', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (650, 'Charges des dettes', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6500, 'Int�r�ts, commmissions et frais aff�rents aux dettes', 650, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6501, 'Amortissements des frais d''�missions d''emrunts et des primes de remboursement', 650, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6502, 'Autres charges des dettes', 650, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6503, 'Int�r�ts intercalaires port�s � l''actif(-)', 650, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (651, 'R�ductions de valeur sur actifs circulants', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6510, 'Dotations', 651, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6511, 'Reprises(-)', 651, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (652, 'Moins-value sur r�alisation d''actifs circulants', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (653, 'Charges d''escompte de cr�ances', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (654, 'Diff�rences de changes', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (655, 'Ecarts de conversion des devises', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (656, 'Charges financi�res diverses', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (657, 'Charges financi�res diverses', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (658, 'Charges financi�res diverses', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (659, 'Charges financi�res diverses', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (66, 'Charges exceptionnelles', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (660, 'Amortissements et r�ductions de valeur exceptionnels (dotations)', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6600, 'sur frais d''�tablissement', 660, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6601, 'sur immobilisations incorporelles', 660, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6602, 'sur immobilisations corporelles', 660, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (661, 'R�ductions de valeur sur immobilisations financi�res (dotations)', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (662, 'Provisions pour risques et charges exceptionnels', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (663, 'Moins-values sur r�alisations d''actifs immobilis�s', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (664, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (665, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (666, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (667, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (668, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (669, ' Charges exceptionnelles port�es � l''actif au titre de frais de restructuration', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (67, 'imp�ts sur le r�sultat', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (670, 'Imp�ts belge sur le r�sultat de l''exercice', 67, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6700, 'Imp�ts et pr�comptes dus ou vers�s', 670, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6701, 'Exc�dents de versement d''imp�ts et de pr�comptes port�s � l''actifs (-)', 670, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6702, 'Charges fiscales estim�es', 670, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (671, 'Imp�ts belges sur le r�sultats d''exercices ant�rieures', 67, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6710, 'Suppl�ments d''imp�t dus ou vers�s', 671, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6711, 'Suppl�ments d''imp�ts estim�s', 671, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6712, 'Provisions fiscales constitu�es', 671, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (672, 'Imp�ts �trangers sur le r�sultat de l''exercice', 67, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (673, 'Imp�ts �trangers sur le r�sultat d''exercice ant�rieures', 67, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68, 'Transferts aux r�serves immunis�es', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (69, 'Affectations et pr�l�vements', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (690, 'Perte report�e de l''exercice pr�c�dent', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (691, 'Dotation � la r�serve l�gale', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (692, 'Dotation aux autres r�serves', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (693, 'B�n�fice � reporter', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (694, 'R�mun�ration du capital', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (695, 'Administrateurs ou g�rants', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (696, 'Autres allocataires', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (70, 'Chiffre d''affaire', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (700, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (701, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (702, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (703, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (704, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (706, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (707, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (709, 'Remises, ristournes et rabais accord�s(-)', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71, 'Variations des stocks et commandes en cours d''�x�cution', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (712, 'des en-cours de fabrication', 71, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (713, 'des produits finis', 71, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (715, 'des immeubles construits destin�s � la vente', 71, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (717, ' des commandes  en cours d''�x�cution', 71, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7170, 'Valeur d''acquisition', 717, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7171, 'B�n�fice pris en compte', 717, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (72, 'Production immobilis�e', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (74, 'Autres produits d''exploitation', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (740, 'Subsides d'' exploitation  et montants compensatoires', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (741, 'Plus-values sur r�alisation courantes d'' immobilisations corporelles', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (742, 'Plus-values sur r�alisations de cr�ances commerciales', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (743, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (744, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (745, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (746, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (747, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (748, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (749, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (75, 'Produits financiers', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (750, 'Produits sur immobilisations financi�res', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (751, 'Produits des actifs circulants', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (752, 'Plus-value sur r�alisations d''actis circulants', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (753, 'Subsides en capital et int�r�ts', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (754, 'Diff�rences de change', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (755, 'Ecarts de conversion des devises', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (756, 'Produits financiers divers', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (757, 'Produits financiers divers', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (758, 'Produits financiers divers', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (759, 'Produits financiers divers', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (76, 'Produits exceptionnels', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (760, 'Reprise d''amortissements et de r�ductions de valeur', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7601, 'sur immobilisations corporelles', 760, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7602, 'sur immobilisations incorporelles', 760, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (761, 'Reprises de r�ductions de valeur sur immobilisations financi�res', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (762, 'Reprises de provisions pour risques et charges exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (763, 'Plus-value sur r�alisation d''actifs immobilis�', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (764, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (765, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (766, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (767, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (768, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (769, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (77, 'R�gularisations d''imp�ts et reprises de provisions fiscales', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (771, 'imp�ts belges sur le r�sultat', 77, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7710, 'R�gularisations d''imp�ts dus ou vers�', 771, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7711, 'R�gularisations d''imp�ts estim�s', 771, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7712, 'Reprises de provisions fiscales', 771, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (773, 'Imp�ts �trangers sur le r�sultats', 77, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (79, 'Affectations et pr�l�vements', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (790, 'B�n�fice report� de l''exercice pr�c�dent', 79, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (791, 'Pr�l�vement sur le capital et les primes d''�mission', 79, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (792, 'Pr�l�vement sur les r�serves', 79, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (793, 'Perte � reporter', 79, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (794, 'Intervention d''associ�s (ou du propri�taire) dans la perte', 79, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1, 'Fonds propres, provisions pour risques et charges � plus d''un an', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2, 'Frais d''�tablissement, actifs immobilis�s et cr�ances � plus d''un an', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3, 'Stocks et commandes en cours d''�x�cution', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4, 'Cr�ances et dettes � un an au plus', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5, 'Placements de tr�sorerie et valeurs disponibles', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6, 'Charges', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7, 'Produits', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4000001, 'Client 1', 400, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4000002, 'Client 2', 400, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (70002, 'Marchandise B', 700, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4400001, 'Fournisseur A', 440, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4400002, 'Fournisseur B', 440, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (610001, 'fourniture A', 61, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (55000002, 'Argenta', 5500, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (55000001, 'Banque 1', 5500, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4000003, 'Client fiche', 400, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4000004, 'Toto', 400, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4000005, 'NOUVEAU CLIENT', 400, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4511, 'TVA � payer 21%', 451, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4512, 'TVA � payer 12%', 451, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4513, 'TVA � payer 6%', 451, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4514, 'TVA � payer 0%', 451, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4111, 'TVA � r�cup�rer 21%', 411, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4112, 'TVA � r�cup�rer 12%', 411, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4113, 'TVA � r�cup�rer 6% ', 411, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4114, 'TVA � r�cup�rer 0%', 411, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (70004, 'Marchandise D', 700, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (70003, 'Marchandise C', 700, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (70001, 'Marchandise A', 700, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4400003, 'Fournisseur E', 440, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4400004, 'Propri�taire bureau', 440, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (101, 'Capital non appel�', 10, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (610002, 'Loyer', 61, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4400005, 'Fournisseur Eau Gaz', 440, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (610003, 'eau, gaz electricit�', 61, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (550, 'Banque 1', 55, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6040001, 'Marchandise A', 604, 'BE');


--
-- Data for TOC entry 31 (OID 109655)
-- Name: version; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO "version" (val) VALUES (5);


--
-- Data for TOC entry 32 (OID 109661)
-- Name: parm_money; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO parm_money (pm_id, pm_code, pm_rate) VALUES (1, 'EUR', 1);


--
-- Data for TOC entry 33 (OID 109664)
-- Name: parm_periode; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (1, '2003-01-01', '2003-01-31', '2003', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (2, '2003-02-01', '2003-02-28', '2003', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (3, '2003-03-01', '2003-03-31', '2003', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (4, '2003-04-01', '2003-04-30', '2003', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (5, '2003-05-01', '2003-05-31', '2003', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (6, '2003-06-01', '2003-06-30', '2003', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (7, '2003-07-01', '2003-07-31', '2003', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (8, '2003-08-01', '2003-08-31', '2003', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (9, '2003-09-01', '2003-09-30', '2003', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (10, '2003-10-01', '2003-10-30', '2003', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (11, '2003-11-01', '2003-11-30', '2003', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (12, '2003-12-01', '2003-12-31', '2003', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (13, '2003-12-31', NULL, '2003', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (27, '2004-01-01', '2004-01-31', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (28, '2004-02-01', '2004-02-28', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (29, '2004-03-01', '2004-03-31', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (30, '2004-04-01', '2004-04-30', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (31, '2004-05-01', '2004-05-31', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (32, '2004-06-01', '2004-06-30', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (33, '2004-07-01', '2004-07-31', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (34, '2004-08-01', '2004-08-31', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (35, '2004-09-01', '2004-09-30', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (36, '2004-10-01', '2004-10-30', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (37, '2004-11-01', '2004-11-30', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (38, '2004-12-01', '2004-12-31', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (39, '2004-12-31', NULL, '2004', false);


--
-- Data for TOC entry 34 (OID 109680)
-- Name: jrn_type; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('FIN', 'Financier');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('VEN', 'Vente');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('ACH', 'Achat');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('OD ', 'Op�rations Diverses');


--
-- Data for TOC entry 35 (OID 109685)
-- Name: jrn_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (4, 'Op�ration Diverses', NULL, NULL, NULL, NULL, 5, 5, false, NULL, 'OD ', 'OD-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (3, 'Achat', '6*', '4*', '5', '4', 1, 3, true, '�ch�ance', 'ACH', 'ACH-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (2, 'Vente', '7*', '4*', '2', '3', 1, 3, true, '�ch�ance', 'VEN', 'VEN-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (1, 'Financier', '5* ', '5*', '1,2,4,9', '1,2,4,9', 5, 5, false, NULL, 'FIN', 'FIN-01');


--
-- Data for TOC entry 36 (OID 109696)
-- Name: jrnx; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 37 (OID 109707)
-- Name: user_pref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO user_pref (pref_user, pref_periode) VALUES ('france', 1);
INSERT INTO user_pref (pref_user, pref_periode) VALUES ('dany', 30);
INSERT INTO user_pref (pref_user, pref_periode) VALUES ('phpcompta', 1);


--
-- Data for TOC entry 38 (OID 109716)
-- Name: formdef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 39 (OID 109722)
-- Name: form; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 40 (OID 109732)
-- Name: centralized; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 41 (OID 109743)
-- Name: user_sec_jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (1, 'phpcompta', 1, '');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (2, 'phpcompta', 2, '');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (3, 'phpcompta', 3, '');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (4, 'phpcompta', 4, '');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (6, 'dany', 2, 'NO');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (7, 'dany', 1, 'NO');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (9, 'france', 4, 'W');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (10, 'france', 2, 'W');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (11, 'france', 1, 'R');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (12, 'france', 3, 'R');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (5, 'dany', 4, 'R');
INSERT INTO user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) VALUES (8, 'dany', 3, 'R');


--
-- Data for TOC entry 42 (OID 109749)
-- Name: action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO "action" (ac_id, ac_description) VALUES (1, 'Journaux');
INSERT INTO "action" (ac_id, ac_description) VALUES (2, 'Facturation');
INSERT INTO "action" (ac_id, ac_description) VALUES (4, 'Impression');
INSERT INTO "action" (ac_id, ac_description) VALUES (5, 'Formulaire');
INSERT INTO "action" (ac_id, ac_description) VALUES (6, 'Mise � jour Plan Comptable');
INSERT INTO "action" (ac_id, ac_description) VALUES (7, 'Gestion Journaux');
INSERT INTO "action" (ac_id, ac_description) VALUES (8, 'Param�tres');
INSERT INTO "action" (ac_id, ac_description) VALUES (9, 'S�curit�');
INSERT INTO "action" (ac_id, ac_description) VALUES (10, 'Centralise');
INSERT INTO "action" (ac_id, ac_description) VALUES (3, 'Fiche Read');
INSERT INTO "action" (ac_id, ac_description) VALUES (16, 'Voir le stock');
INSERT INTO "action" (ac_id, ac_description) VALUES (17, 'Modifie le stock');
INSERT INTO "action" (ac_id, ac_description) VALUES (15, 'Fiche ajout');
INSERT INTO "action" (ac_id, ac_description) VALUES (14, 'Achat');


--
-- Data for TOC entry 43 (OID 109754)
-- Name: user_sec_act; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO user_sec_act (ua_id, ua_login, ua_act_id) VALUES (1, 'france', 2);
INSERT INTO user_sec_act (ua_id, ua_login, ua_act_id) VALUES (2, 'france', 1);
INSERT INTO user_sec_act (ua_id, ua_login, ua_act_id) VALUES (3, 'france', 3);
INSERT INTO user_sec_act (ua_id, ua_login, ua_act_id) VALUES (4, 'demo', 10);
INSERT INTO user_sec_act (ua_id, ua_login, ua_act_id) VALUES (5, 'demo', 2);
INSERT INTO user_sec_act (ua_id, ua_login, ua_act_id) VALUES (6, 'demo', 14);
INSERT INTO user_sec_act (ua_id, ua_login, ua_act_id) VALUES (7, 'demo', 3);
INSERT INTO user_sec_act (ua_id, ua_login, ua_act_id) VALUES (8, 'demo', 5);
INSERT INTO user_sec_act (ua_id, ua_login, ua_act_id) VALUES (9, 'demo', 7);
INSERT INTO user_sec_act (ua_id, ua_login, ua_act_id) VALUES (10, 'demo', 4);
INSERT INTO user_sec_act (ua_id, ua_login, ua_act_id) VALUES (11, 'demo', 1);
INSERT INTO user_sec_act (ua_id, ua_login, ua_act_id) VALUES (12, 'demo', 6);
INSERT INTO user_sec_act (ua_id, ua_login, ua_act_id) VALUES (13, 'demo', 8);


--
-- Data for TOC entry 44 (OID 109762)
-- Name: jrn_action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (2, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (4, 'Voir Impay�s', 'Voir toutes les factures non pay�es', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (1, 'Nouvelle', 'Cr�ation d''une facture', 'user_jrn.php', 'action=insert_vente&blank', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (10, 'Nouveau', 'Encode un nouvel achat (mat�riel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (12, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (14, 'Voir Impay�s', 'Voir toutes les factures non pay�es', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (20, 'Nouveau', 'Encode un nouvel achat (mat�riel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'FIN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (22, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'FIN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (30, 'Nouveau', NULL, 'user_jrn.php', 'action=new&blank', 'FR', 'OD ');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (32, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'OD ');


--
-- Data for TOC entry 45 (OID 109769)
-- Name: tva_rate; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (1, '21%', 0.20999999999999999, 'Tva applicable � tout ce qui bien et service divers', '4111,4511');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (2, '12%', 0.12, 'Tva ', '4112,4512');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (3, '6%', 0.059999999999999998, 'Tva applicable aux journaux et livres', '4113,4513');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (4, '0%', 0, 'Tva applicable lors de vente/achat intracommunautaire', '4114,4514');


--
-- Data for TOC entry 46 (OID 109785)
-- Name: fiche_def_ref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (1, 'Vente Service', 700);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (2, 'Achat Marchandises', 604);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (3, 'Achat Service et biens divers', 61);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (4, 'Banque', 5500);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (5, 'Pr�t > a un an', 17);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (6, 'Pr�t < a un an', 430);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (8, 'Fournisseurs', 440);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (9, 'Clients', 400);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (10, 'Salaire Administrateur', 6200);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (11, 'Salaire Ouvrier', 6203);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (12, 'Salaire Employ�', 6202);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (13, 'D�penses non admises', 674);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (7, 'Mat�riel � amortir', 24);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (14, 'Administration des Finances', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (15, 'Autres fiches', NULL);


--
-- Data for TOC entry 47 (OID 109791)
-- Name: fiche_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (1, 5500, 'Banque', true, 4);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (2, 400, 'Client', true, 9);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (3, 700, 'Vente', false, 1);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (4, 440, 'Fournisseur', true, 8);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (5, 61, 'Charges', true, 3);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (6, 604, 'Marchandise', true, 2);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (9, NULL, 'Taxes & impot', false, 14);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (7, 604, 'March Cat A', true, 2);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (8, 700, 'March Cat b', false, 1);


--
-- Data for TOC entry 48 (OID 109798)
-- Name: attr_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO attr_def (ad_id, ad_text) VALUES (1, 'Nom');
INSERT INTO attr_def (ad_id, ad_text) VALUES (2, 'Taux TVA');
INSERT INTO attr_def (ad_id, ad_text) VALUES (3, 'Num�ro de compte');
INSERT INTO attr_def (ad_id, ad_text) VALUES (4, 'Nom de la banque');
INSERT INTO attr_def (ad_id, ad_text) VALUES (5, 'Poste Comptable');
INSERT INTO attr_def (ad_id, ad_text) VALUES (6, 'Prix vente');
INSERT INTO attr_def (ad_id, ad_text) VALUES (7, 'Prix achat');
INSERT INTO attr_def (ad_id, ad_text) VALUES (8, 'Dur�e Amortissement');
INSERT INTO attr_def (ad_id, ad_text) VALUES (9, 'Description');
INSERT INTO attr_def (ad_id, ad_text) VALUES (10, 'Date d�but');
INSERT INTO attr_def (ad_id, ad_text) VALUES (11, 'Montant initial');
INSERT INTO attr_def (ad_id, ad_text) VALUES (12, 'Personne de contact ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (13, 'num�ro de tva ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (14, 'Adresse ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (15, 'code postale ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (16, 'pays ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (17, 't�l�phone ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (18, 'email ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (19, 'Gestion stock');


--
-- Data for TOC entry 49 (OID 109804)
-- Name: fiche; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche (f_id, fd_id) VALUES (1, 1);
INSERT INTO fiche (f_id, fd_id) VALUES (2, 2);
INSERT INTO fiche (f_id, fd_id) VALUES (3, 2);
INSERT INTO fiche (f_id, fd_id) VALUES (4, 3);
INSERT INTO fiche (f_id, fd_id) VALUES (5, 3);
INSERT INTO fiche (f_id, fd_id) VALUES (6, 3);
INSERT INTO fiche (f_id, fd_id) VALUES (7, 3);
INSERT INTO fiche (f_id, fd_id) VALUES (8, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (9, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (10, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (11, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (12, 1);
INSERT INTO fiche (f_id, fd_id) VALUES (13, 2);
INSERT INTO fiche (f_id, fd_id) VALUES (14, 2);
INSERT INTO fiche (f_id, fd_id) VALUES (15, 2);
INSERT INTO fiche (f_id, fd_id) VALUES (16, 3);
INSERT INTO fiche (f_id, fd_id) VALUES (17, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (18, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (19, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (20, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (21, 6);


--
-- Data for TOC entry 50 (OID 109807)
-- Name: jnt_fic_att_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (1, 1, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (2, 1, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (3, 1, 3);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (4, 1, 4);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (5, 1, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (6, 1, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (7, 1, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (8, 1, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (9, 1, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (10, 1, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (11, 1, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (12, 2, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (13, 2, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (14, 2, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (15, 2, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (16, 2, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (17, 2, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (18, 2, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (19, 2, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (20, 2, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (21, 3, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (22, 3, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (23, 3, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (24, 3, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (25, 3, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (26, 3, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (27, 3, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (28, 3, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (29, 3, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (30, 4, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (31, 4, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (32, 4, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (33, 4, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (34, 5, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (35, 5, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (36, 5, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (37, 5, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (38, 6, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (39, 6, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (40, 6, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (41, 6, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (42, 7, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (43, 7, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (44, 7, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (45, 7, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (46, 4, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (47, 5, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (48, 6, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (49, 7, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (50, 8, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (51, 8, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (52, 8, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (53, 8, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (54, 8, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (55, 8, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (56, 8, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (57, 8, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (58, 8, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (59, 9, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (60, 9, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (61, 9, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (62, 9, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (63, 9, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (64, 9, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (65, 9, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (66, 9, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (67, 9, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (68, 10, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (69, 10, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (70, 10, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (71, 10, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (72, 11, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (73, 11, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (74, 11, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (75, 11, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (76, 12, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (77, 12, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (78, 12, 3);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (79, 12, 4);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (80, 12, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (81, 12, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (82, 12, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (83, 12, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (84, 12, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (85, 12, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (86, 12, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (87, 13, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (88, 14, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (89, 14, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (90, 14, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (91, 14, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (92, 14, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (93, 14, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (94, 14, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (95, 14, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (96, 14, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (97, 15, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (98, 15, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (99, 15, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (100, 15, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (101, 15, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (102, 15, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (103, 15, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (104, 15, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (105, 15, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (106, 1, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (107, 16, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (108, 16, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (109, 16, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (110, 16, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (111, 16, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (112, 17, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (113, 17, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (114, 17, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (115, 17, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (116, 17, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (117, 17, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (118, 17, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (119, 17, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (120, 17, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (121, 18, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (122, 18, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (123, 18, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (124, 18, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (125, 18, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (126, 18, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (127, 18, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (128, 18, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (129, 18, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (130, 19, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (131, 19, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (132, 19, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (133, 19, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (134, 19, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (135, 19, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (136, 19, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (137, 19, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (138, 19, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (139, 20, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (140, 20, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (141, 20, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (142, 20, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (143, 21, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (144, 21, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (145, 21, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (146, 21, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (147, 21, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (148, 21, 19);


--
-- Data for TOC entry 51 (OID 109810)
-- Name: attr_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO attr_value (jft_id, av_text) VALUES (12, '4000001');
INSERT INTO attr_value (jft_id, av_text) VALUES (13, 'Client 1');
INSERT INTO attr_value (jft_id, av_text) VALUES (14, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (15, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (16, 'Rue du Client 1');
INSERT INTO attr_value (jft_id, av_text) VALUES (17, '99999');
INSERT INTO attr_value (jft_id, av_text) VALUES (18, 'Belgique');
INSERT INTO attr_value (jft_id, av_text) VALUES (19, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (20, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (21, '4000002');
INSERT INTO attr_value (jft_id, av_text) VALUES (22, 'Client 2');
INSERT INTO attr_value (jft_id, av_text) VALUES (23, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (24, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (25, 'Rue du client 2');
INSERT INTO attr_value (jft_id, av_text) VALUES (26, '108000');
INSERT INTO attr_value (jft_id, av_text) VALUES (27, 'Belgique');
INSERT INTO attr_value (jft_id, av_text) VALUES (28, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (29, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (34, 'Marchandise B');
INSERT INTO attr_value (jft_id, av_text) VALUES (35, '1');
INSERT INTO attr_value (jft_id, av_text) VALUES (47, '70002');
INSERT INTO attr_value (jft_id, av_text) VALUES (36, '150');
INSERT INTO attr_value (jft_id, av_text) VALUES (37, '140');
INSERT INTO attr_value (jft_id, av_text) VALUES (50, '4400001');
INSERT INTO attr_value (jft_id, av_text) VALUES (51, 'Fournisseur A');
INSERT INTO attr_value (jft_id, av_text) VALUES (52, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (53, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (54, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (55, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (56, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (57, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (58, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (59, '4400002');
INSERT INTO attr_value (jft_id, av_text) VALUES (60, 'Fournisseur B');
INSERT INTO attr_value (jft_id, av_text) VALUES (61, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (62, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (63, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (64, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (65, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (66, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (67, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (68, '610001');
INSERT INTO attr_value (jft_id, av_text) VALUES (69, 'fourniture A');
INSERT INTO attr_value (jft_id, av_text) VALUES (70, '1');
INSERT INTO attr_value (jft_id, av_text) VALUES (71, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (76, '55000002');
INSERT INTO attr_value (jft_id, av_text) VALUES (77, 'Argenta');
INSERT INTO attr_value (jft_id, av_text) VALUES (78, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (79, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (80, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (81, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (82, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (83, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (84, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (85, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (86, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (87, '4000003');
INSERT INTO attr_value (jft_id, av_text) VALUES (88, '4000004');
INSERT INTO attr_value (jft_id, av_text) VALUES (89, 'Toto');
INSERT INTO attr_value (jft_id, av_text) VALUES (90, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (91, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (92, 'Maison de toto');
INSERT INTO attr_value (jft_id, av_text) VALUES (93, 'cp');
INSERT INTO attr_value (jft_id, av_text) VALUES (94, 'pays');
INSERT INTO attr_value (jft_id, av_text) VALUES (95, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (96, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (97, '4000005');
INSERT INTO attr_value (jft_id, av_text) VALUES (98, 'NOUVEAU CLIENT');
INSERT INTO attr_value (jft_id, av_text) VALUES (99, 'Toto');
INSERT INTO attr_value (jft_id, av_text) VALUES (100, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (101, 'Adresse');
INSERT INTO attr_value (jft_id, av_text) VALUES (102, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (103, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (104, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (105, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (42, 'Marchandise D');
INSERT INTO attr_value (jft_id, av_text) VALUES (43, '15');
INSERT INTO attr_value (jft_id, av_text) VALUES (49, '70004');
INSERT INTO attr_value (jft_id, av_text) VALUES (44, '150');
INSERT INTO attr_value (jft_id, av_text) VALUES (45, '75');
INSERT INTO attr_value (jft_id, av_text) VALUES (38, 'Marchandise C');
INSERT INTO attr_value (jft_id, av_text) VALUES (39, '20');
INSERT INTO attr_value (jft_id, av_text) VALUES (48, '70003');
INSERT INTO attr_value (jft_id, av_text) VALUES (40, '200');
INSERT INTO attr_value (jft_id, av_text) VALUES (41, '100');
INSERT INTO attr_value (jft_id, av_text) VALUES (107, 'Marchandise Ex');
INSERT INTO attr_value (jft_id, av_text) VALUES (108, '10');
INSERT INTO attr_value (jft_id, av_text) VALUES (111, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (109, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (110, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (30, 'Marchandise A');
INSERT INTO attr_value (jft_id, av_text) VALUES (46, '70001');
INSERT INTO attr_value (jft_id, av_text) VALUES (32, '100');
INSERT INTO attr_value (jft_id, av_text) VALUES (33, '120');
INSERT INTO attr_value (jft_id, av_text) VALUES (31, '1');
INSERT INTO attr_value (jft_id, av_text) VALUES (112, '4400003');
INSERT INTO attr_value (jft_id, av_text) VALUES (113, 'Fournisseur E');
INSERT INTO attr_value (jft_id, av_text) VALUES (114, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (115, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (116, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (117, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (118, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (119, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (120, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (121, '4400004');
INSERT INTO attr_value (jft_id, av_text) VALUES (122, 'Propri�taire bureau');
INSERT INTO attr_value (jft_id, av_text) VALUES (123, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (124, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (125, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (126, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (127, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (128, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (129, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (73, 'Loyer');
INSERT INTO attr_value (jft_id, av_text) VALUES (74, '4');
INSERT INTO attr_value (jft_id, av_text) VALUES (72, '610002');
INSERT INTO attr_value (jft_id, av_text) VALUES (75, '400');
INSERT INTO attr_value (jft_id, av_text) VALUES (130, '4400005');
INSERT INTO attr_value (jft_id, av_text) VALUES (131, 'Fournisseur Eau Gaz');
INSERT INTO attr_value (jft_id, av_text) VALUES (132, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (133, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (134, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (135, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (136, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (137, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (138, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (140, 'eau, gaz electricit�');
INSERT INTO attr_value (jft_id, av_text) VALUES (141, '1');
INSERT INTO attr_value (jft_id, av_text) VALUES (139, '610003');
INSERT INTO attr_value (jft_id, av_text) VALUES (142, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (2, 'Banque 1');
INSERT INTO attr_value (jft_id, av_text) VALUES (3, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (4, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (1, '550');
INSERT INTO attr_value (jft_id, av_text) VALUES (5, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (6, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (7, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (8, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (9, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (10, 'a');
INSERT INTO attr_value (jft_id, av_text) VALUES (11, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (106, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (144, 'Achat Marchandise A');
INSERT INTO attr_value (jft_id, av_text) VALUES (145, '3');
INSERT INTO attr_value (jft_id, av_text) VALUES (143, '6040001');
INSERT INTO attr_value (jft_id, av_text) VALUES (146, '350');
INSERT INTO attr_value (jft_id, av_text) VALUES (147, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (148, '1');


--
-- Data for TOC entry 52 (OID 109815)
-- Name: jnt_fic_attr; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 3);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 4);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 12);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 13);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 14);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 15);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 16);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 17);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 18);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 12);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 13);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 14);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 15);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 16);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 17);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 18);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 2);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 6);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 7);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (4, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (4, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (4, 12);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (4, 13);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (4, 14);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (4, 15);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (4, 16);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (4, 17);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (4, 18);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (5, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (5, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (5, 2);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (5, 7);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 19);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 19);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (6, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (6, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (6, 2);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (6, 6);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (6, 7);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (6, 19);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (7, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (7, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (7, 2);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (7, 6);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (7, 7);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (7, 19);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (8, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (8, 2);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (8, 6);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (8, 7);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (8, 19);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (9, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (9, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (9, 9);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (9, 12);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (9, 14);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (9, 15);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (9, 17);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (9, 18);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (9, 16);


--
-- Data for TOC entry 53 (OID 109819)
-- Name: jrn_rapt; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 54 (OID 109828)
-- Name: jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 55 (OID 109836)
-- Name: stock_goods; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 56 (OID 109846)
-- Name: attr_min; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 2);
INSERT INTO attr_min (frd_id, ad_id) VALUES (2, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (2, 2);
INSERT INTO attr_min (frd_id, ad_id) VALUES (3, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (3, 2);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 3);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 4);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 13);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 14);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 15);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 16);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 17);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 18);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 13);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 14);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 15);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 16);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 17);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 18);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 13);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 14);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 16);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 17);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 18);
INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 6);
INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 7);
INSERT INTO attr_min (frd_id, ad_id) VALUES (2, 6);
INSERT INTO attr_min (frd_id, ad_id) VALUES (2, 7);
INSERT INTO attr_min (frd_id, ad_id) VALUES (3, 7);
INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 19);
INSERT INTO attr_min (frd_id, ad_id) VALUES (2, 19);
INSERT INTO attr_min (frd_id, ad_id) VALUES (14, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 4);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 5);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 10);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 4);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 5);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 10);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (10, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (10, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (10, 5);
INSERT INTO attr_min (frd_id, ad_id) VALUES (11, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (11, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (11, 5);
INSERT INTO attr_min (frd_id, ad_id) VALUES (12, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (12, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (12, 5);
INSERT INTO attr_min (frd_id, ad_id) VALUES (13, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (13, 9);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 8);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 5);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 9);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 10);
INSERT INTO attr_min (frd_id, ad_id) VALUES (13, 5);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 11);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 11);
INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 15);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 15);
INSERT INTO attr_min (frd_id, ad_id) VALUES (15, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (15, 9);


--
-- TOC entry 2 (OID 109657)
-- Name: s_periode; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_periode', 39, true);


--
-- TOC entry 3 (OID 109659)
-- Name: s_currency; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_currency', 1, true);


--
-- TOC entry 4 (OID 109672)
-- Name: s_jrn_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_def', 5, false);


--
-- TOC entry 5 (OID 109674)
-- Name: s_grpt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_grpt', 1, false);


--
-- TOC entry 6 (OID 109676)
-- Name: s_jrn_op; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_op', 461, true);


--
-- TOC entry 7 (OID 109678)
-- Name: s_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn', 139, true);


--
-- TOC entry 8 (OID 109694)
-- Name: s_jrnx; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrnx', 1, false);


--
-- TOC entry 9 (OID 109712)
-- Name: s_formdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_formdef', 1, false);


--
-- TOC entry 10 (OID 109714)
-- Name: s_form; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_form', 1, false);


--
-- TOC entry 11 (OID 109728)
-- Name: s_idef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_idef', 2, true);


--
-- TOC entry 12 (OID 109730)
-- Name: s_centralized; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_centralized', 1, false);


--
-- TOC entry 13 (OID 109739)
-- Name: s_user_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_user_jrn', 12, true);


--
-- TOC entry 14 (OID 109741)
-- Name: s_user_act; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_user_act', 15, true);


--
-- TOC entry 15 (OID 109760)
-- Name: s_jrnaction; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrnaction', 7, true);


--
-- TOC entry 16 (OID 109775)
-- Name: s_fiche; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_fiche', 21, true);


--
-- TOC entry 17 (OID 109777)
-- Name: s_fiche_def_ref; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_fiche_def_ref', 14, true);


--
-- TOC entry 18 (OID 109779)
-- Name: s_fdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_fdef', 10, true);


--
-- TOC entry 19 (OID 109781)
-- Name: s_attr_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_attr_def', 19, true);


--
-- TOC entry 20 (OID 109783)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jnt_fic_att_value', 148, true);


--
-- TOC entry 21 (OID 109817)
-- Name: s_jrn_rapt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_rapt', 13, true);


--
-- TOC entry 22 (OID 109826)
-- Name: s_stock_goods; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_stock_goods', 24, true);


--
-- TOC entry 23 (OID 111122)
-- Name: s_central; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_central', 1, false);


--
-- TOC entry 24 (OID 111133)
-- Name: s_central_order; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_central_order', 1, false);


--
-- TOC entry 25 (OID 111138)
-- Name: s_internal; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_internal', 1, false);


--
-- TOC entry 26 (OID 111144)
-- Name: s_jrn_4; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_4', 1, false);


--
-- TOC entry 27 (OID 111147)
-- Name: s_jrn_3; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_3', 1, false);


--
-- TOC entry 28 (OID 111150)
-- Name: s_jrn_2; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_2', 1, false);


--
-- TOC entry 29 (OID 111153)
-- Name: s_jrn_1; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_1', 1, false);


