--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';

SET search_path = public, pg_catalog;

--
-- Data for TOC entry 31 (OID 108273)
-- Name: tmp_pcmn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (10, 'Capital ', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (100, ' Capital souscrit', 10, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (11, 'Prime d''émission ', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (12, 'Plus Value de réévaluation ', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (13, 'Réserve ', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (130, 'Réserve légale', 13, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (131, 'Réserve indisponible', 13, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1310, 'Réserve pour actions propres', 131, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1311, ' Autres réserves indisponibles', 131, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (132, ' Réserves immunisées', 13, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (133, 'Réserves disponibles', 13, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (14, 'Bénéfice ou perte reportée', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (140, 'Bénéfice reporté', 14, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (141, 'Perte reportée', 14, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (15, 'Subside en capital', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (16, 'Provisions pour risques et charges', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (160, 'Provisions pour pensions et obligations similaires', 16, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (161, 'Provisions pour charges fiscales', 16, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (162, 'Provisions pour grosses réparation et gros entretien', 16, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (17, ' Dettes à plus d''un an', 1, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (170, 'Emprunts subordonnés', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1700, 'convertibles', 170, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1701, 'non convertibles', 170, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (171, 'Emprunts subordonnés', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1710, 'convertibles', 170, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1711, 'non convertibles', 170, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (172, ' Dettes de locations financement', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (173, ' Etablissement de crédit', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1730, 'Dettes en comptes', 173, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1731, 'Promesses', 173, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1732, 'Crédits d''acceptation', 173, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (174, 'Autres emprunts', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (175, 'Dettes commerciales', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1750, 'Fournisseurs', 175, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1751, 'Effets à payer', 175, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (176, 'Acomptes reçus sur commandes', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (178, 'Cautionnement reçus en numéraires', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (179, 'Dettes diverses', 17, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (20, 'Frais d''établissement', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (200, 'Frais de constitution et d''augmentation de capital', 20, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (201, ' Frais d''émission d''emprunts et primes de remboursement', 20, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (202, 'Autres frais d''établissement', 20, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (204, 'Frais de restructuration', 20, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (21, 'Immobilisations incorporelles', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (210, 'Frais de recherche et de développement', 21, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (211, 'Concessions, brevet, licence savoir faire, marque et droit similaires', 21, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (212, 'Goodwill', 21, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (213, 'Acomptes versés', 21, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (22, 'Terrains et construction', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (220, 'Terrains', 22, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (221, 'Construction', 22, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (222, 'Terrains bâtis', 22, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (223, 'Autres droits réels sur des immeubles', 22, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (23, ' Installations, machines et outillages', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (24, 'Mobilier et Matériel roulant', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (25, 'Immobilisations détenus en location-financement et droits similaires', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (250, 'Terrains', 25, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (251, 'Construction', 25, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (252, 'Terrains bâtis', 25, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (253, 'Mobilier et matériels roulants', 25, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (26, 'Autres immobilisations corporelles', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (27, 'Immobilisations corporelles en cours et acomptes versés', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (28, 'Immobilisations financières', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (280, 'Participation dans des entreprises liées', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2800, 'Valeur d''acquisition', 280, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2801, 'Montants non-appelés(-)', 280, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2808, 'Plus-values actées', 280, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2809, 'Réductions de valeurs actées', 280, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (281, 'Créance sur  des entreprises liées', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2810, 'Créance en compte', 281, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2811, 'Effets à recevoir', 281, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2812, 'Titre à reveny fixe', 281, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2817, 'Créances douteuses', 281, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2819, 'Réduction de valeurs actées', 281, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (282, 'Participations dans des entreprises avec lesquelles il existe un lien de participation', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2820, 'Valeur d''acquisition', 282, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2821, 'Montants non-appelés(-)', 282, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2828, 'Plus-values actées', 282, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2829, 'Réductions de valeurs actées', 282, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (283, 'Créances sur des entreprises avec lesquelles existe un lien de participation', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2830, 'Créance en compte', 283, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2831, 'Effets à recevoir', 283, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2832, 'Titre à revenu fixe', 283, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2837, 'Créances douteuses', 283, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2839, 'Réduction de valeurs actées', 283, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (284, 'Autres actions et parts', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2840, 'Valeur d''acquisition', 284, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2841, 'Montants non-appelés(-)', 284, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2848, 'Plus-values actées', 284, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2849, 'Réductions de valeurs actées', 284, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (285, 'Autres créances', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2850, 'Créance en compte', 285, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2851, 'Effets à recevoir', 285, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2852, 'Titre à revenu fixe', 285, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2857, 'Créances douteuses', 285, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2859, 'Réductions de valeurs actées', 285, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (288, 'Cautionnements versés en numéraires', 28, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (29, 'Créances à plus d''un an', 2, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (290, 'Créances commerciales', 29, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2900, 'Clients', 290, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2901, 'Effets à recevoir', 290, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2906, 'Acomptes versés', 290, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2907, 'Créances douteuses', 290, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2909, 'Réductions de valeurs actées', 290, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (291, 'Autres créances', 29, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2910, 'Créances en comptes', 291, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2911, 'Effets à recevoir', 291, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2917, 'Créances douteuses', 291, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2919, 'Réductions de valeurs actées(-)', 291, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (30, 'Approvisionements - Matières premières', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (300, 'Valeur d''acquisition', 30, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (309, 'Réductions de valeur actées', 30, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (31, 'Approvisionnements - fournitures', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (310, 'Valeur d''acquisition', 31, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (319, 'Réductions de valeurs actées(-)', 31, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (32, 'En-cours de fabrication', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (320, 'Valeurs d''acquisition', 32, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (329, 'Réductions de valeur actées', 32, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (33, 'Produits finis', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (330, 'Valeur d''acquisition', 33, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (339, 'Réductions de valeur actées', 33, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (34, 'Marchandises', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (340, 'Valeur d''acquisition', 34, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (349, 'Réductions de valeur actées', 34, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (35, 'Immeubles destinés à la vente', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (350, 'Valeur d''acquisition', 35, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (359, 'Réductions de valeur actées', 35, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (36, 'Acomptes versés sur achats pour stocks', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (360, 'Valeur d''acquisition', 36, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (369, 'Réductions de valeur actées', 36, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (37, 'Commandes en cours éxécution', 3, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (370, 'Valeur d''acquisition', 37, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (371, 'Bénéfice pris en compte ', 37, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (379, 'Réductions de valeur actées', 37, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (40, 'Créances commerciales', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (400, 'Clients', 40, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (401, 'Effets à recevoir', 40, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (404, 'Produits à recevoir', 40, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (406, 'Acomptes versés', 40, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (407, 'Créances douteuses', 40, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (409, 'Réductions de valeur actées', 40, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (41, 'Autres créances', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (410, 'Capital appelé non versé', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (411, 'TVA à récupérer', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4111, 'TVA à récupérer 21%', 411, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4112, 'TVA à récupérer 12%', 411, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4113, 'TVA à récupérer 6% ', 411, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4114, 'TVA à récupérer 0%', 411, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (412, 'Impôts et précomptes à récupérer', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4120, 'Impôt belge sur le résultat', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4121, 'Impôt belge sur le résultat', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4122, 'Impôt belge sur le résultat', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4123, 'Impôt belge sur le résultat', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4124, 'Impôt belge sur le résultat', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4125, 'Autres impôts et taxes belges', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4126, 'Autres impôts et taxes belges', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4127, 'Autres impôts et taxes belges', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4128, 'Impôts et taxes étrangers', 412, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (414, 'Produits à recevoir', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (416, 'Créances diverses', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4160, 'Comptes de l''exploitant', 416, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (417, 'Créances douteuses', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (418, 'Cautionnements versés en numéraires', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (419, 'Réductions de valeur actées', 41, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (42, 'Dettes à plus dun an échéant dans l''année', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (420, 'Emprunts subordonnés', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4200, 'convertibles', 420, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4201, 'non convertibles', 420, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (421, 'Emprunts subordonnés', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4210, 'convertibles', 420, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4211, 'non convertibles', 420, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (422, ' Dettes de locations financement', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (423, ' Etablissement de crédit', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4230, 'Dettes en comptes', 423, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4231, 'Promesses', 423, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4232, 'Crédits d''acceptation', 423, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (424, 'Autres emprunts', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (425, 'Dettes commerciales', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4250, 'Fournisseurs', 425, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4251, 'Effets à payer', 425, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (426, 'Acomptes reçus sur commandes', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (428, 'Cautionnement reçus en numéraires', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (429, 'Dettes diverses', 42, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (43, 'Dettes financières', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (430, 'Etablissements de crédit - Emprunts à compte à terme fixe', 43, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (431, 'Etablissements de crédit - Promesses', 43, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (432, ' Etablissements de crédit - Crédits d''acceptation', 43, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (433, 'Etablissements de crédit -Dettes en comptes courant', 43, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (439, 'Autres emprunts', 43, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (44, 'Dettes commerciales', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (440, 'Fournisseurs', 44, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (441, 'Effets à payer', 44, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (444, 'Factures à recevoir', 44, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (45, 'Dettes fiscales, salariales et sociales', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (450, 'Dettes fiscales estimées', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4500, 'Impôts belges sur le résultat', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4501, 'Impôts belges sur le résultat', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4502, 'Impôts belges sur le résultat', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4503, 'Impôts belges sur le résultat', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4504, 'Impôts belges sur le résultat', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4505, 'Autres impôts et taxes belges', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4506, 'Autres impôts et taxes belges', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4507, 'Autres impôts et taxes belges', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4508, 'Impôts et taxes étrangers', 450, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (451, 'TVA à payer', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4511, 'TVA à payer 21%', 451, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4512, 'TVA à payer 12%', 451, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4513, 'TVA à payer 6%', 451, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4514, 'TVA à payer 0%', 451, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (452, 'Impôts et taxes à payer', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4520, 'Impôts belges sur le résultat', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4521, 'Impôts belges sur le résultat', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4522, 'Impôts belges sur le résultat', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4523, 'Impôts belges sur le résultat', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4524, 'Impôts belges sur le résultat', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4525, 'Autres impôts et taxes belges', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4526, 'Autres impôts et taxes belges', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4527, 'Autres impôts et taxes belges', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4528, 'Impôts et taxes étrangers', 452, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (453, 'Précomptes retenus', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (454, 'Office National de la Sécurité Sociales', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (455, 'Rémunérations', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (456, 'Pécules de vacances', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (459, 'Autres dettes sociales', 45, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (46, 'Acomptes reçus sur commandes', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (47, 'Dettes découlant de l''affectation du résultat', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (470, 'Dividendes et tantièmes d''exercices antérieurs', 47, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (471, 'Dividendes de l''exercice', 47, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (472, 'Tantièmes de l''exercice', 47, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (473, 'Autres allocataires', 47, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (48, 'Dettes diverses', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (480, 'Obligations et coupons échus', 48, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (488, 'Cautionnements reçus en numéraires', 48, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (489, 'Autres dettes diverses', 48, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4890, 'Compte de l''exploitant', 489, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (49, 'Comptes de régularisation', 4, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (490, 'Charges à reporter', 49, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (491, 'Produits acquis', 49, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (492, 'Charges à imputer', 49, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (493, 'Produits à reporter', 49, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (499, 'Comptes d''attentes', 49, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (50, 'Actions propres', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (51, 'Actions et parts', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (510, 'Valeur d''acquisition', 51, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (511, 'Montant non appelés', 51, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (519, 'Réductions de valeur actées', 51, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (52, 'Titres à revenu fixe', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (520, 'Valeur d''acquisition', 52, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (529, 'Réductions de valeur actées', 52, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (53, 'Dépôts à terme', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (530, 'de plus d''un an', 53, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (531, 'de plus d''un mois et d''un an au plus', 53, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (532, 'd''un mois au plus', 53, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (539, 'Réductions de valeur actées', 53, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (54, 'Valeurs échues à l''encaissement', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (55, 'Etablissement de crédit', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (550, 'Banque 1', 55, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5500, 'Comptes courants', 550, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5501, 'Chèques émis (-)', 550, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5509, 'Réduction de valeur actée', 550, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5510, 'Comptes courants', 551, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5511, 'Chèques émis (-)', 551, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5519, 'Réduction de valeur actée', 551, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5520, 'Comptes courants', 552, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5521, 'Chèques émis (-)', 552, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5529, 'Réduction de valeur actée', 552, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5530, 'Comptes courants', 553, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5531, 'Chèques émis (-)', 553, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5539, 'Réduction de valeur actée', 553, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5540, 'Comptes courants', 554, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5541, 'Chèques émis (-)', 554, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5549, 'Réduction de valeur actée', 554, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5550, 'Comptes courants', 555, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5551, 'Chèques émis (-)', 555, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5559, 'Réduction de valeur actée', 555, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5560, 'Comptes courants', 556, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5561, 'Chèques émis (-)', 556, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5569, 'Réduction de valeur actée', 556, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5570, 'Comptes courants', 557, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5571, 'Chèques émis (-)', 557, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5579, 'Réduction de valeur actée', 557, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5580, 'Comptes courants', 558, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5581, 'Chèques émis (-)', 558, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5589, 'Réduction de valeur actée', 558, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5590, 'Comptes courants', 559, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5591, 'Chèques émis (-)', 559, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5599, 'Réduction de valeur actée', 559, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (56, 'Office des chèques postaux', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (560, 'Compte courant', 56, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (561, 'Chèques émis', 56, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (578, 'Caisse timbre', 57, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (58, 'Virement interne', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (60, 'Approvisionnement et marchandises', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (600, 'Achats de matières premières', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (601, 'Achats de fournitures', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (602, 'Achats de services, travaux et études', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (603, 'Sous-traitances générales', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (604, 'Achats de marchandises', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (605, 'Achats d''immeubles destinés à la vente', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (608, 'Remises, ristournes et rabais obtenus(-)', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (609, 'Variation de stock', 60, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6090, 'de matières premières', 609, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6091, 'de fournitures', 609, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6094, 'de marchandises', 609, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6095, 'immeubles achetés destinés à la vente', 609, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (61, 'Services et biens divers', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (62, 'Rémunérations, charges sociales et pensions', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (620, 'Rémunérations et avantages sociaux directs', 62, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6200, 'Administrateurs ou gérants', 620, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6201, 'Personnel de directions', 620, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6202, 'Employés,620', 6202, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6203, 'Ouvriers', 620, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6204, 'Autres membres du personnel', 620, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (621, 'Cotisations patronales d''assurances sociales', 62, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (622, 'Primes partonales pour assurances extra-légales', 62, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (623, 'Autres frais de personnel', 62, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (624, 'Pensions de retraite et de survie', 62, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6240, 'Administrateurs ou gérants', 624, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6241, 'Personnel', 624, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (63, 'Amortissements, réductions de valeurs et provisions pour risques et charges', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (630, 'Dotations aux amortissements et réduction de valeurs sur immobilisations', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6300, ' Dotations aux amortissements sur frais d''établissement', 630, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (705, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6301, 'Dotations aux amortissements sur immobilisations incorporelles', 630, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6302, 'Dotations aux amortissements sur immobilisations corporelles', 630, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6308, 'Dotations aux réductions de valeur sur immobilisations incorporelles', 630, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6309, 'Dotations aux réductions de valeur sur immobilisations corporelles', 630, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (631, 'Réductions de valeur sur stocks', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6310, 'Dotations', 631, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6311, 'Reprises(-)', 631, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (632, 'Réductions de valeur sur commande en cours d''éxécution', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6320, 'Dotations', 632, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6321, 'Reprises(-)', 632, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (633, 'Réductions de valeurs sur créances commerciales à plus d''un an', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6330, 'Dotations', 633, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6331, 'Reprises(-)', 633, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (634, 'Réductions de valeur sur créances commerciales à un an au plus', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6340, 'Dotations', 634, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6341, 'Reprise', 634, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (635, 'Provisions pour pensions et obligations similaires', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6350, 'Dotations', 635, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6351, 'Utilisation et reprises', 635, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (636, 'Provisions pour grosses réparations et gros entretien', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6360, 'Dotations', 636, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6361, 'Reprises(-)', 636, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (637, 'Provisions pour autres risques et charges', 63, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6370, 'Dotations', 637, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6371, 'Reprises(-)', 637, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (64, 'Autres charges d''exploitation', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (640, 'Charges fiscales d''exploitation', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (641, 'Moins-values sur réalisations courantes d''immobilisations corporelles', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (642, 'Moins-value sur réalisation de créances commerciales', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (643, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (644, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (645, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (646, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (647, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (648, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (649, 'Charges d''exploitation portées à l''actif au titre de frais de restructuration(-)', 64, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (65, 'Charges financières', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (650, 'Charges des dettes', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6500, 'Intérêts, commmissions et frais afférents aux dettes', 650, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6501, 'Amortissements des frais d''émissions d''emrunts et des primes de remboursement', 650, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6502, 'Autres charges des dettes', 650, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6503, 'Intérêts intercalaires portés à l''actif(-)', 650, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (651, 'Réductions de valeur sur actifs circulants', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6510, 'Dotations', 651, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6511, 'Reprises(-)', 651, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (652, 'Moins-value sur réalisation d''actifs circulants', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (653, 'Charges d''escompte de créances', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (654, 'Différences de changes', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (655, 'Ecarts de conversion des devises', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (656, 'Charges financières diverses', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (657, 'Charges financières diverses', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (658, 'Charges financières diverses', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (659, 'Charges financières diverses', 65, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (66, 'Charges exceptionnelles', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (660, 'Amortissements et réductions de valeur exceptionnels (dotations)', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6600, 'sur frais d''établissement', 660, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6601, 'sur immobilisations incorporelles', 660, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6602, 'sur immobilisations corporelles', 660, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (661, 'Réductions de valeur sur immobilisations financières (dotations)', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (662, 'Provisions pour risques et charges exceptionnels', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (663, 'Moins-values sur réalisations d''actifs immobilisés', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (664, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (665, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (666, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (667, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (668, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (669, ' Charges exceptionnelles portées à l''actif au titre de frais de restructuration', 66, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (67, 'impôts sur le résultat', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (670, 'Impôts belge sur le résultat de l''exercice', 67, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6700, 'Impôts et précomptes dus ou versés', 670, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6701, 'Excédents de versement d''impôts et de précomptes portés à l''actifs (-)', 670, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6702, 'Charges fiscales estimées', 670, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (671, 'Impôts belges sur le résultats d''exercices antérieures', 67, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6710, 'Suppléments d''impôt dus ou versés', 671, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6711, 'Suppléments d''impôts estimés', 671, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6712, 'Provisions fiscales constituées', 671, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (672, 'Impôts étrangers sur le résultat de l''exercice', 67, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (673, 'Impôts étrangers sur le résultat d''exercice antérieures', 67, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (68, 'Transferts aux réserves immunisées', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (69, 'Affectations et prélévements', 6, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (690, 'Perte reportée de l''exercice précédent', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (691, 'Dotation à la réserve légale', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (692, 'Dotation aux autres réserves', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (693, 'Bénéfice à reporter', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (694, 'Rémunération du capital', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (695, 'Administrateurs ou gérants', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (696, 'Autres allocataires', 69, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (70, 'Chiffre d''affaire', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (700, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (701, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (702, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (703, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (704, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (706, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (707, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (709, 'Remises, ristournes et rabais accordés(-)', 70, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (71, 'Variations des stocks et commandes en cours d''éxécution', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (712, 'des en-cours de fabrication', 71, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (713, 'des produits finis', 71, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (715, 'des immeubles construits destinés à la vente', 71, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (717, ' des commandes  en cours d''éxécution', 71, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7170, 'Valeur d''acquisition', 717, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7171, 'Bénéfice pris en compte', 717, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (72, 'Production immobilisée', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (74, 'Autres produits d''exploitation', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (740, 'Subsides d'' exploitation  et montants compensatoires', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (741, 'Plus-values sur réalisation courantes d'' immobilisations corporelles', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (742, 'Plus-values sur réalisations de créances commerciales', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (743, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (744, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (745, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (746, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (747, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (748, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (749, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (75, 'Produits financiers', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (750, 'Produits sur immobilisations financières', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (751, 'Produits des actifs circulants', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (752, 'Plus-value sur réalisations d''actis circulants', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (753, 'Subsides en capital et intérêts', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (754, 'Différences de change', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (755, 'Ecarts de conversion des devises', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (756, 'Produits financiers divers', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (757, 'Produits financiers divers', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (758, 'Produits financiers divers', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (759, 'Produits financiers divers', 75, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (76, 'Produits exceptionnels', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (760, 'Reprise d''amortissements et de réductions de valeur', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7601, 'sur immobilisations corporelles', 760, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7602, 'sur immobilisations incorporelles', 760, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (761, 'Reprises de réductions de valeur sur immobilisations financières', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (762, 'Reprises de provisions pour risques et charges exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (763, 'Plus-value sur réalisation d''actifs immobilisé', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (764, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (765, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (766, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (767, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (768, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (769, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (77, 'Régularisations d''impôts et reprises de provisions fiscales', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (771, 'impôts belges sur le résultat', 77, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7710, 'Régularisations d''impôts dus ou versé', 771, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7711, 'Régularisations d''impôts estimés', 771, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7712, 'Reprises de provisions fiscales', 771, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (773, 'Impôts étrangers sur le résultats', 77, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (79, 'Affectations et prélévements', 7, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (790, 'Bénéfice reporté de l''exercice précédent', 79, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (791, 'Prélévement sur le capital et les primes d''émission', 79, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (792, 'Prélévement sur les réserves', 79, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (793, 'Perte à reporter', 79, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (794, 'Intervention d''associés (ou du propriétaire) dans la perte', 79, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (1, 'Fonds propres, provisions pour risques et charges à plus d''un an', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (2, 'Frais d''établissement, actifs immobilisés et créances à plus d''un an', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (3, 'Stocks et commandes en cours d''éxécution', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4, 'Créances et dettes à un an au plus', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (5, 'Placements de trésorerie et valeurs disponibles', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6, 'Charges', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7, 'Produits', 0, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4000001, 'Client 1', 400, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4000002, 'Client 2', 400, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4000003, 'Client 3', 400, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6040001, 'Electricité', 604, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6040002, 'Loyer', 604, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6040003, 'Petit matériel', 604, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (6040004, 'Assurance', 604, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (55000001, 'Caisse', 5500, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (57, 'Caisse', 5, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (55000002, 'Banque 1', 5500, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (55000003, 'Banque 2', 5500, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4400001, 'Fournisseur 1', 440, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4400002, 'Fournisseur 2', 440, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (4400003, 'Fournisseur 4', 440, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (610001, 'Electricité', 61, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (610002, 'Loyer', 61, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (610003, 'Assurance', 61, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (610004, 'Matériel bureau', 61, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7000002, 'Marchandise A', 700, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7000001, 'Prestation', 700, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (7000003, 'Déplacement', 700, 'BE');
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) VALUES (101, 'Capital non appelé', 10, 'BE');


--
-- Data for TOC entry 32 (OID 108280)
-- Name: version; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO "version" (val) VALUES (4);


--
-- Data for TOC entry 33 (OID 108286)
-- Name: parm_money; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO parm_money (pm_id, pm_code, pm_rate) VALUES (1, 'EUR', 1);


--
-- Data for TOC entry 34 (OID 108289)
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
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (40, '2004-01-01', '2004-01-31', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (41, '2004-02-01', '2004-02-28', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (42, '2004-03-01', '2004-03-31', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (43, '2004-04-01', '2004-04-30', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (44, '2004-05-01', '2004-05-31', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (45, '2004-06-01', '2004-06-30', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (46, '2004-07-01', '2004-07-31', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (47, '2004-08-01', '2004-08-31', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (48, '2004-09-01', '2004-09-30', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (49, '2004-10-01', '2004-10-30', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (50, '2004-11-01', '2004-11-30', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (51, '2004-12-01', '2004-12-31', '2004', false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed) VALUES (52, '2004-12-31', NULL, '2004', false);


--
-- Data for TOC entry 35 (OID 108305)
-- Name: jrn_type; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('FIN', 'Financier');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('VEN', 'Vente');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('ACH', 'Achat');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('OD ', 'Opérations Diverses');


--
-- Data for TOC entry 36 (OID 108310)
-- Name: jrn_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (4, 'Opération Diverses', NULL, NULL, NULL, NULL, 5, 5, false, NULL, 'OD ', 'OD-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (1, 'Financier', '5* ', '5*', '3,2,4', '3,2,4', 5, 5, false, NULL, 'FIN', 'FIN-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (3, 'Achat', '6*', '4*', '5', '4', 1, 3, true, 'échéance', 'ACH', 'ACH-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (2, 'Vente', '4*', '7*', '2', '6', 2, 1, true, 'échéance', 'VEN', 'VEN-01');


--
-- Data for TOC entry 37 (OID 108321)
-- Name: jrnx; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 38 (OID 108332)
-- Name: user_pref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO user_pref (pref_user, pref_periode) VALUES ('dany', 30);


--
-- Data for TOC entry 39 (OID 108341)
-- Name: formdef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 40 (OID 108347)
-- Name: form; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 41 (OID 108359)
-- Name: centralized; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 42 (OID 108370)
-- Name: user_sec_jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 43 (OID 108376)
-- Name: action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO "action" (ac_id, ac_description) VALUES (1, 'Journaux');
INSERT INTO "action" (ac_id, ac_description) VALUES (2, 'Facturation');
INSERT INTO "action" (ac_id, ac_description) VALUES (4, 'Impression');
INSERT INTO "action" (ac_id, ac_description) VALUES (5, 'Formulaire');
INSERT INTO "action" (ac_id, ac_description) VALUES (6, 'Mise à jour Plan Comptable');
INSERT INTO "action" (ac_id, ac_description) VALUES (7, 'Gestion Journaux');
INSERT INTO "action" (ac_id, ac_description) VALUES (8, 'Paramètres');
INSERT INTO "action" (ac_id, ac_description) VALUES (9, 'Sécurité');
INSERT INTO "action" (ac_id, ac_description) VALUES (10, 'Centralise');
INSERT INTO "action" (ac_id, ac_description) VALUES (3, 'Fiche Read');
INSERT INTO "action" (ac_id, ac_description) VALUES (16, 'Voir le stock');
INSERT INTO "action" (ac_id, ac_description) VALUES (17, 'Modifie le stock');
INSERT INTO "action" (ac_id, ac_description) VALUES (14, 'Achat');
INSERT INTO "action" (ac_id, ac_description) VALUES (15, 'Fiche écriture');


--
-- Data for TOC entry 44 (OID 108381)
-- Name: user_sec_act; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 45 (OID 108389)
-- Name: jrn_action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (2, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (4, 'Voir Impayés', 'Voir toutes les factures non payées', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (1, 'Nouvelle', 'Création d''une facture', 'user_jrn.php', 'action=insert_vente&blank', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (10, 'Nouveau', 'Encode un nouvel achat (matériel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (12, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (14, 'Voir Impayés', 'Voir toutes les factures non payées', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (20, 'Nouveau', 'Encode un nouvel achat (matériel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'FIN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (22, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'FIN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (30, 'Nouveau', NULL, 'user_jrn.php', 'action=new&blank', 'FR', 'OD ');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (32, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'OD ');


--
-- Data for TOC entry 46 (OID 108396)
-- Name: tva_rate; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (1, '21%', 0.20999999999999999, 'Tva applicable à tout ce qui bien et service divers', '4111,4511');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (2, '12%', 0.12, 'Tva ', '4112,4512');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (3, '6%', 0.059999999999999998, 'Tva applicable aux journaux et livres', '4113,4513');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (4, '0%', 0, 'Tva applicable lors de vente/achat intracommunautaire', '4114,4514');


--
-- Data for TOC entry 47 (OID 108412)
-- Name: fiche_def_ref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (1, 'Vente Service', 700);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (2, 'Achat Marchandises', 604);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (3, 'Achat Service et biens divers', 61);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (4, 'Banque', 5500);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (5, 'Prêt > a un an', 17);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (6, 'Prêt < a un an', 430);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (8, 'Fournisseurs', 440);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (9, 'Clients', 400);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (10, 'Salaire Administrateur', 6200);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (11, 'Salaire Ouvrier', 6203);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (12, 'Salaire Employé', 6202);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (13, 'Dépenses non admises', 674);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (7, 'Matériel à amortir', 24);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (14, 'Administration des Finances', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (15, 'Autres fiches', NULL);


--
-- Data for TOC entry 48 (OID 108418)
-- Name: fiche_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (2, 400, 'Client', true, 9);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (1, 604, 'Marchandises', true, 2);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (3, 5500, 'Banque', true, 4);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (4, 440, 'Fournisseur', true, 8);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (5, 61, 'S & B D', true, 3);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (6, 700, 'Vente', true, 1);


--
-- Data for TOC entry 49 (OID 108425)
-- Name: attr_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO attr_def (ad_id, ad_text) VALUES (1, 'Nom');
INSERT INTO attr_def (ad_id, ad_text) VALUES (2, 'Taux TVA');
INSERT INTO attr_def (ad_id, ad_text) VALUES (3, 'Numéro de compte');
INSERT INTO attr_def (ad_id, ad_text) VALUES (4, 'Nom de la banque');
INSERT INTO attr_def (ad_id, ad_text) VALUES (5, 'Poste Comptable');
INSERT INTO attr_def (ad_id, ad_text) VALUES (6, 'Prix vente');
INSERT INTO attr_def (ad_id, ad_text) VALUES (7, 'Prix achat');
INSERT INTO attr_def (ad_id, ad_text) VALUES (8, 'Durée Amortissement');
INSERT INTO attr_def (ad_id, ad_text) VALUES (9, 'Description');
INSERT INTO attr_def (ad_id, ad_text) VALUES (10, 'Date début');
INSERT INTO attr_def (ad_id, ad_text) VALUES (11, 'Montant initial');
INSERT INTO attr_def (ad_id, ad_text) VALUES (12, 'Personne de contact ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (13, 'numéro de tva ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (14, 'Adresse ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (15, 'code postale ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (16, 'pays ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (17, 'téléphone ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (18, 'email ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (19, 'Gestion stock');


--
-- Data for TOC entry 50 (OID 108431)
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
-- Data for TOC entry 51 (OID 108433)
-- Name: fiche; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche (f_id, fd_id) VALUES (1, 1);
INSERT INTO fiche (f_id, fd_id) VALUES (2, 1);
INSERT INTO fiche (f_id, fd_id) VALUES (3, 1);
INSERT INTO fiche (f_id, fd_id) VALUES (4, 1);
INSERT INTO fiche (f_id, fd_id) VALUES (5, 2);
INSERT INTO fiche (f_id, fd_id) VALUES (6, 2);
INSERT INTO fiche (f_id, fd_id) VALUES (7, 2);
INSERT INTO fiche (f_id, fd_id) VALUES (8, 3);
INSERT INTO fiche (f_id, fd_id) VALUES (9, 3);
INSERT INTO fiche (f_id, fd_id) VALUES (10, 3);
INSERT INTO fiche (f_id, fd_id) VALUES (11, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (12, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (13, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (14, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (15, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (16, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (17, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (18, 6);
INSERT INTO fiche (f_id, fd_id) VALUES (19, 6);
INSERT INTO fiche (f_id, fd_id) VALUES (20, 6);


--
-- Data for TOC entry 52 (OID 108436)
-- Name: jnt_fic_att_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (1, 1, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (2, 1, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (3, 1, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (4, 1, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (5, 1, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (6, 1, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (7, 2, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (8, 2, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (9, 2, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (10, 2, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (11, 2, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (12, 2, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (13, 3, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (14, 3, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (15, 3, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (16, 3, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (17, 3, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (18, 3, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (19, 4, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (20, 4, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (21, 4, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (22, 4, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (23, 4, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (24, 4, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (25, 5, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (26, 5, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (27, 5, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (28, 5, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (29, 5, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (30, 5, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (31, 5, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (32, 5, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (33, 5, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (34, 6, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (35, 6, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (36, 6, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (37, 6, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (38, 6, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (39, 6, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (40, 6, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (41, 6, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (42, 6, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (43, 7, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (44, 7, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (45, 7, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (46, 7, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (47, 7, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (48, 7, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (49, 7, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (50, 7, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (51, 7, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (52, 8, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (53, 8, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (54, 8, 3);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (55, 8, 4);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (56, 8, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (57, 8, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (58, 8, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (59, 8, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (60, 8, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (61, 8, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (62, 8, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (63, 9, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (64, 9, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (65, 9, 3);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (66, 9, 4);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (67, 9, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (68, 9, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (69, 9, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (70, 9, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (71, 9, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (72, 9, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (73, 9, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (74, 10, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (75, 10, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (76, 10, 3);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (77, 10, 4);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (78, 10, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (79, 10, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (80, 10, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (81, 10, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (82, 10, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (83, 10, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (84, 10, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (85, 11, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (86, 11, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (87, 11, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (88, 11, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (89, 11, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (90, 11, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (91, 11, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (92, 11, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (93, 11, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (94, 12, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (95, 12, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (96, 12, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (97, 12, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (98, 12, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (99, 12, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (100, 12, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (101, 12, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (102, 12, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (103, 13, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (104, 13, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (105, 13, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (106, 13, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (107, 13, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (108, 13, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (109, 13, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (110, 13, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (111, 13, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (112, 14, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (113, 14, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (114, 14, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (115, 14, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (116, 15, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (117, 15, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (118, 15, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (119, 15, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (120, 16, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (121, 16, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (122, 16, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (123, 16, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (124, 17, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (125, 17, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (126, 17, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (127, 17, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (128, 18, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (129, 18, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (130, 18, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (131, 18, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (132, 18, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (133, 18, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (134, 19, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (135, 19, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (136, 19, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (137, 19, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (138, 19, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (139, 19, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (140, 20, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (141, 20, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (142, 20, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (143, 20, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (144, 20, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (145, 20, 19);


--
-- Data for TOC entry 53 (OID 108439)
-- Name: attr_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO attr_value (jft_id, av_text) VALUES (25, '4000001');
INSERT INTO attr_value (jft_id, av_text) VALUES (26, 'Client 1');
INSERT INTO attr_value (jft_id, av_text) VALUES (27, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (28, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (29, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (30, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (31, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (32, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (33, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (34, '4000002');
INSERT INTO attr_value (jft_id, av_text) VALUES (35, 'Client 2');
INSERT INTO attr_value (jft_id, av_text) VALUES (36, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (37, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (38, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (39, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (40, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (41, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (42, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (43, '4000003');
INSERT INTO attr_value (jft_id, av_text) VALUES (44, 'Client 3');
INSERT INTO attr_value (jft_id, av_text) VALUES (45, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (46, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (47, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (48, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (49, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (50, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (51, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (2, 'Marchandise A');
INSERT INTO attr_value (jft_id, av_text) VALUES (3, '1');
INSERT INTO attr_value (jft_id, av_text) VALUES (1, '6040001');
INSERT INTO attr_value (jft_id, av_text) VALUES (4, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (5, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (6, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (8, 'Marchandise B');
INSERT INTO attr_value (jft_id, av_text) VALUES (9, '3');
INSERT INTO attr_value (jft_id, av_text) VALUES (7, '6040002');
INSERT INTO attr_value (jft_id, av_text) VALUES (10, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (11, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (12, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (14, 'Marchandise C');
INSERT INTO attr_value (jft_id, av_text) VALUES (15, '1');
INSERT INTO attr_value (jft_id, av_text) VALUES (13, '6040003');
INSERT INTO attr_value (jft_id, av_text) VALUES (16, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (17, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (18, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (20, 'Marchandise D');
INSERT INTO attr_value (jft_id, av_text) VALUES (21, '3');
INSERT INTO attr_value (jft_id, av_text) VALUES (19, '6040004');
INSERT INTO attr_value (jft_id, av_text) VALUES (22, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (23, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (24, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (53, 'Caisse');
INSERT INTO attr_value (jft_id, av_text) VALUES (54, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (55, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (52, '57');
INSERT INTO attr_value (jft_id, av_text) VALUES (56, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (57, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (58, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (59, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (60, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (61, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (62, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (63, '55000002');
INSERT INTO attr_value (jft_id, av_text) VALUES (64, 'Banque 1');
INSERT INTO attr_value (jft_id, av_text) VALUES (65, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (66, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (67, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (68, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (69, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (70, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (71, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (72, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (73, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (74, '55000003');
INSERT INTO attr_value (jft_id, av_text) VALUES (75, 'Banque 2');
INSERT INTO attr_value (jft_id, av_text) VALUES (76, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (77, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (78, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (79, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (80, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (81, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (82, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (83, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (84, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (85, '4400001');
INSERT INTO attr_value (jft_id, av_text) VALUES (86, 'Fournisseur 1');
INSERT INTO attr_value (jft_id, av_text) VALUES (87, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (88, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (89, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (90, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (91, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (92, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (93, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (94, '4400002');
INSERT INTO attr_value (jft_id, av_text) VALUES (95, 'Fournisseur 2');
INSERT INTO attr_value (jft_id, av_text) VALUES (96, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (97, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (98, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (99, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (100, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (101, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (102, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (103, '4400003');
INSERT INTO attr_value (jft_id, av_text) VALUES (104, 'Fournisseur 4');
INSERT INTO attr_value (jft_id, av_text) VALUES (105, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (106, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (107, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (108, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (109, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (110, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (111, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (112, '610001');
INSERT INTO attr_value (jft_id, av_text) VALUES (113, 'Electricité');
INSERT INTO attr_value (jft_id, av_text) VALUES (114, '1');
INSERT INTO attr_value (jft_id, av_text) VALUES (115, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (117, 'Loyer');
INSERT INTO attr_value (jft_id, av_text) VALUES (118, '3');
INSERT INTO attr_value (jft_id, av_text) VALUES (116, '610002');
INSERT INTO attr_value (jft_id, av_text) VALUES (119, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (121, 'Assurance');
INSERT INTO attr_value (jft_id, av_text) VALUES (122, '3');
INSERT INTO attr_value (jft_id, av_text) VALUES (120, '610003');
INSERT INTO attr_value (jft_id, av_text) VALUES (123, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (124, '610004');
INSERT INTO attr_value (jft_id, av_text) VALUES (125, 'Matériel bureau');
INSERT INTO attr_value (jft_id, av_text) VALUES (126, '1');
INSERT INTO attr_value (jft_id, av_text) VALUES (127, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (134, '7000002');
INSERT INTO attr_value (jft_id, av_text) VALUES (135, 'Marchandise A');
INSERT INTO attr_value (jft_id, av_text) VALUES (136, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (137, '200');
INSERT INTO attr_value (jft_id, av_text) VALUES (138, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (139, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (129, 'Prestation');
INSERT INTO attr_value (jft_id, av_text) VALUES (130, '1');
INSERT INTO attr_value (jft_id, av_text) VALUES (128, '7000001');
INSERT INTO attr_value (jft_id, av_text) VALUES (131, '15');
INSERT INTO attr_value (jft_id, av_text) VALUES (132, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (133, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (140, '7000003');
INSERT INTO attr_value (jft_id, av_text) VALUES (141, 'Déplacement');
INSERT INTO attr_value (jft_id, av_text) VALUES (142, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (143, '50');
INSERT INTO attr_value (jft_id, av_text) VALUES (144, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (145, '');


--
-- Data for TOC entry 54 (OID 108444)
-- Name: jnt_fic_attr; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 2);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 6);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 7);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (1, 19);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 12);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 13);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 14);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 15);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 16);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 17);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (2, 18);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 3);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 4);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 12);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 13);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 14);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 15);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 16);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 17);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (3, 18);
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
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (6, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (6, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (6, 2);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (6, 6);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (6, 7);
INSERT INTO jnt_fic_attr (fd_id, ad_id) VALUES (6, 19);


--
-- Data for TOC entry 55 (OID 108454)
-- Name: jrn_rapt; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 56 (OID 108457)
-- Name: jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 57 (OID 108465)
-- Name: stock_goods; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- TOC entry 2 (OID 108282)
-- Name: s_periode; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_periode', 52, true);


--
-- TOC entry 3 (OID 108284)
-- Name: s_currency; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_currency', 1, true);


--
-- TOC entry 4 (OID 108297)
-- Name: s_jrn_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_def', 5, false);


--
-- TOC entry 5 (OID 108299)
-- Name: s_grpt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_grpt', 1, false);


--
-- TOC entry 6 (OID 108301)
-- Name: s_jrn_op; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_op', 1, false);




--
-- TOC entry 8 (OID 108319)
-- Name: s_jrnx; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrnx', 1, false);


--
-- TOC entry 9 (OID 108337)
-- Name: s_formdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_formdef', 1, false);


--
-- TOC entry 10 (OID 108339)
-- Name: s_form; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_form', 1, false);


--
-- TOC entry 11 (OID 108353)
-- Name: s_isup; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_isup', 1, false);


--
-- TOC entry 12 (OID 108355)
-- Name: s_idef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_idef', 1, false);


--
-- TOC entry 13 (OID 108357)
-- Name: s_centralized; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_centralized', 1, false);


--
-- TOC entry 14 (OID 108366)
-- Name: s_user_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_user_jrn', 1, false);


--
-- TOC entry 15 (OID 108368)
-- Name: s_user_act; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_user_act', 1, false);


--
-- TOC entry 16 (OID 108387)
-- Name: s_jrnaction; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrnaction', 5, true);


--
-- TOC entry 17 (OID 108402)
-- Name: s_fiche; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_fiche', 20, true);


--
-- TOC entry 18 (OID 108404)
-- Name: s_fiche_def_ref; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_fiche_def_ref', 1, false);


--
-- TOC entry 19 (OID 108406)
-- Name: s_fdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_fdef', 6, true);


--
-- TOC entry 20 (OID 108408)
-- Name: s_attr_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_attr_def', 1, false);


--
-- TOC entry 21 (OID 108410)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jnt_fic_att_value', 145, true);


--
-- TOC entry 22 (OID 108450)
-- Name: s_stock_goods; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_stock_goods', 1, false);


--
-- TOC entry 23 (OID 108452)
-- Name: s_jrn_rapt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval('s_jrn_rapt', 1, false);

