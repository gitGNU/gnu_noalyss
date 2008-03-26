
SET client_encoding = 'utf8';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;


SELECT pg_catalog.setval('action_gestion_ag_id_seq', 1, false);



SELECT pg_catalog.setval('bilan_b_id_seq', 4, true);



SELECT pg_catalog.setval('document_d_id_seq', 1, false);



SELECT pg_catalog.setval('document_modele_md_id_seq', 1, false);



SELECT pg_catalog.setval('document_seq', 1, false);



SELECT pg_catalog.setval('document_state_s_id_seq', 3, true);



SELECT pg_catalog.setval('document_type_dt_id_seq', 10, false);



SELECT pg_catalog.setval('historique_analytique_ha_id_seq', 1, false);



SELECT pg_catalog.setval('s_jnt_id', 53, true);



SELECT pg_catalog.setval('op_def_op_seq', 1, false);



SELECT pg_catalog.setval('op_predef_detail_opd_id_seq', 1, false);



SELECT pg_catalog.setval('s_oa_group', 1, false);



SELECT pg_catalog.setval('plan_analytique_pa_id_seq', 1, false);



SELECT pg_catalog.setval('poste_analytique_po_id_seq', 1, false);



SELECT pg_catalog.setval('s_attr_def', 27, true);



SELECT pg_catalog.setval('s_cbc', 1, false);



SELECT pg_catalog.setval('s_central', 1, false);



SELECT pg_catalog.setval('s_central_order', 1, false);



SELECT pg_catalog.setval('s_centralized', 1, false);



SELECT pg_catalog.setval('s_currency', 1, true);



SELECT pg_catalog.setval('s_fdef', 6, true);



SELECT pg_catalog.setval('s_fiche', 79, true);



SELECT pg_catalog.setval('s_fiche_def_ref', 18, true);



SELECT pg_catalog.setval('s_form', 1, false);



SELECT pg_catalog.setval('s_formdef', 1, false);



SELECT pg_catalog.setval('s_grpt', 95, true);



SELECT pg_catalog.setval('s_idef', 1, false);



SELECT pg_catalog.setval('s_internal', 94, true);



SELECT pg_catalog.setval('s_invoice', 1, false);



SELECT pg_catalog.setval('s_isup', 1, false);



SELECT pg_catalog.setval('s_jnt_fic_att_value', 875, true);



SELECT pg_catalog.setval('s_jrn', 1, false);



SELECT pg_catalog.setval('s_jrn_1', 1, false);



SELECT pg_catalog.setval('s_jrn_2', 1, false);



SELECT pg_catalog.setval('s_jrn_3', 1, false);



SELECT pg_catalog.setval('s_jrn_4', 1, false);



SELECT pg_catalog.setval('s_jrn_def', 5, false);



SELECT pg_catalog.setval('s_jrn_op', 1, false);



SELECT pg_catalog.setval('s_jrn_rapt', 18, true);



SELECT pg_catalog.setval('s_jrnaction', 5, true);



SELECT pg_catalog.setval('s_jrnx', 1, false);



SELECT pg_catalog.setval('s_periode', 104, true);



SELECT pg_catalog.setval('s_quantity', 10, true);



SELECT pg_catalog.setval('s_stock_goods', 1, false);



SELECT pg_catalog.setval('s_user_act', 1, false);



SELECT pg_catalog.setval('s_user_jrn', 8, true);



SELECT pg_catalog.setval('seq_doc_type_1', 1, false);



SELECT pg_catalog.setval('seq_doc_type_2', 1, false);



SELECT pg_catalog.setval('seq_doc_type_3', 1, false);



SELECT pg_catalog.setval('seq_doc_type_4', 1, false);



SELECT pg_catalog.setval('seq_doc_type_5', 1, false);



SELECT pg_catalog.setval('seq_doc_type_6', 1, false);



SELECT pg_catalog.setval('seq_doc_type_7', 1, false);



SELECT pg_catalog.setval('seq_doc_type_8', 1, false);



SELECT pg_catalog.setval('seq_doc_type_9', 1, false);



INSERT INTO "action" (ac_id, ac_description) VALUES (4, 'Impression');
INSERT INTO "action" (ac_id, ac_description) VALUES (6, 'Mise à jour Plan Comptable');
INSERT INTO "action" (ac_id, ac_description) VALUES (7, 'Gestion Journaux');
INSERT INTO "action" (ac_id, ac_description) VALUES (8, 'Paramètres');
INSERT INTO "action" (ac_id, ac_description) VALUES (10, 'Centralise');
INSERT INTO "action" (ac_id, ac_description) VALUES (16, 'Voir le stock');
INSERT INTO "action" (ac_id, ac_description) VALUES (17, 'Modifie le stock');
INSERT INTO "action" (ac_id, ac_description) VALUES (20, 'Voir la balance des comptes');
INSERT INTO "action" (ac_id, ac_description) VALUES (21, 'Import et export des écritures d''ouverture');
INSERT INTO "action" (ac_id, ac_description) VALUES (28, 'Module Suivi Document');
INSERT INTO "action" (ac_id, ac_description) VALUES (22, 'Module Client');
INSERT INTO "action" (ac_id, ac_description) VALUES (24, 'Module Fournisseur');
INSERT INTO "action" (ac_id, ac_description) VALUES (26, 'Module Administration');
INSERT INTO "action" (ac_id, ac_description) VALUES (30, 'Module Gestion');
INSERT INTO "action" (ac_id, ac_description) VALUES (1, 'Lecture du Grand-Livre');
INSERT INTO "action" (ac_id, ac_description) VALUES (31, 'Gestion des périodes comptables');
INSERT INTO "action" (ac_id, ac_description) VALUES (3, 'Lecture des fiches');
INSERT INTO "action" (ac_id, ac_description) VALUES (15, 'Ajout de fiche et modification');
INSERT INTO "action" (ac_id, ac_description) VALUES (5, 'Création et modifications des rapports');
INSERT INTO "action" (ac_id, ac_description) VALUES (50, 'Definir les Plans Analytiques et les postes');
INSERT INTO "action" (ac_id, ac_description) VALUES (51, 'Impression CA');
INSERT INTO "action" (ac_id, ac_description) VALUES (52, 'Operations Diverses CA');






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
INSERT INTO attr_def (ad_id, ad_text) VALUES (16, 'pays ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (17, 'téléphone ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (18, 'email ');
INSERT INTO attr_def (ad_id, ad_text) VALUES (19, 'Gestion stock');
INSERT INTO attr_def (ad_id, ad_text) VALUES (20, 'Partie fiscalement non déductible');
INSERT INTO attr_def (ad_id, ad_text) VALUES (21, 'TVA non déductible');
INSERT INTO attr_def (ad_id, ad_text) VALUES (22, 'TVA non déductible récupérable par l''impôt');
INSERT INTO attr_def (ad_id, ad_text) VALUES (23, 'Quick Code');
INSERT INTO attr_def (ad_id, ad_text) VALUES (24, 'Ville');
INSERT INTO attr_def (ad_id, ad_text) VALUES (25, 'Société');
INSERT INTO attr_def (ad_id, ad_text) VALUES (26, 'Fax');
INSERT INTO attr_def (ad_id, ad_text) VALUES (27, 'GSM');
INSERT INTO attr_def (ad_id, ad_text) VALUES (15, 'code postal');



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
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 10);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 4);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 10);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (10, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (10, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (11, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (11, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (12, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (12, 12);
INSERT INTO attr_min (frd_id, ad_id) VALUES (13, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (13, 9);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 8);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 9);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 10);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 11);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 11);
INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 15);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 15);
INSERT INTO attr_min (frd_id, ad_id) VALUES (15, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (15, 9);
INSERT INTO attr_min (frd_id, ad_id) VALUES (1, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (2, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (3, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (4, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (5, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (6, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (10, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (11, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (12, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (13, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (14, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (15, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (7, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (9, 24);
INSERT INTO attr_min (frd_id, ad_id) VALUES (8, 24);
INSERT INTO attr_min (frd_id, ad_id) VALUES (14, 24);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 17);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 18);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 25);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 26);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 27);
INSERT INTO attr_min (frd_id, ad_id) VALUES (16, 23);
INSERT INTO attr_min (frd_id, ad_id) VALUES (17, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (17, 9);
INSERT INTO attr_min (frd_id, ad_id) VALUES (18, 1);
INSERT INTO attr_min (frd_id, ad_id) VALUES (18, 9);



INSERT INTO attr_value (jft_id, av_text) VALUES (366, 'Fournisseurs et Comptes rattachés');
INSERT INTO attr_value (jft_id, av_text) VALUES (367, '400');
INSERT INTO attr_value (jft_id, av_text) VALUES (368, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (369, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (370, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (371, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (372, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (373, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (374, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (375, 'Q400');
INSERT INTO attr_value (jft_id, av_text) VALUES (376, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (377, 'autres charges externes - Services extérieurs');
INSERT INTO attr_value (jft_id, av_text) VALUES (378, '101');
INSERT INTO attr_value (jft_id, av_text) VALUES (379, '61');
INSERT INTO attr_value (jft_id, av_text) VALUES (380, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (381, 'Q61');
INSERT INTO attr_value (jft_id, av_text) VALUES (393, 'Q53');
INSERT INTO attr_value (jft_id, av_text) VALUES (394, 'valeurs mobilières de placement');
INSERT INTO attr_value (jft_id, av_text) VALUES (395, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (396, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (397, '50');
INSERT INTO attr_value (jft_id, av_text) VALUES (398, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (399, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (400, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (401, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (402, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (403, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (404, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (405, 'Q50');
INSERT INTO attr_value (jft_id, av_text) VALUES (417, 'Q51');
INSERT INTO attr_value (jft_id, av_text) VALUES (418, 'virements internes');
INSERT INTO attr_value (jft_id, av_text) VALUES (419, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (420, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (421, '58');
INSERT INTO attr_value (jft_id, av_text) VALUES (422, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (423, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (424, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (425, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (426, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (427, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (428, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (429, 'Q58');
INSERT INTO attr_value (jft_id, av_text) VALUES (382, 'La caisse Monpognon');
INSERT INTO attr_value (jft_id, av_text) VALUES (383, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (384, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (385, '53');
INSERT INTO attr_value (jft_id, av_text) VALUES (386, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (387, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (388, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (389, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (390, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (391, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (392, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (406, 'La banque Gripsous');
INSERT INTO attr_value (jft_id, av_text) VALUES (407, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (408, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (409, '51');
INSERT INTO attr_value (jft_id, av_text) VALUES (410, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (411, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (412, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (413, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (414, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (415, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (416, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (441, 'Achats (sauf 603)');
INSERT INTO attr_value (jft_id, av_text) VALUES (442, '101');
INSERT INTO attr_value (jft_id, av_text) VALUES (443, '60');
INSERT INTO attr_value (jft_id, av_text) VALUES (444, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (445, 'Q60');
INSERT INTO attr_value (jft_id, av_text) VALUES (446, 'Produits financiers');
INSERT INTO attr_value (jft_id, av_text) VALUES (447, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (448, '76');
INSERT INTO attr_value (jft_id, av_text) VALUES (449, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (450, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (451, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (452, 'Q76');
INSERT INTO attr_value (jft_id, av_text) VALUES (453, 'Charges financières');
INSERT INTO attr_value (jft_id, av_text) VALUES (454, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (455, '66');
INSERT INTO attr_value (jft_id, av_text) VALUES (456, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (457, 'Q66');
INSERT INTO attr_value (jft_id, av_text) VALUES (430, 'Fournisseur 1 : Potdecolle');
INSERT INTO attr_value (jft_id, av_text) VALUES (431, '400');
INSERT INTO attr_value (jft_id, av_text) VALUES (432, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (433, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (434, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (435, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (436, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (437, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (438, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (439, 'Q400001');
INSERT INTO attr_value (jft_id, av_text) VALUES (440, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (473, 'Q400002');
INSERT INTO attr_value (jft_id, av_text) VALUES (464, 'Fournisseur 2 Kisgratpas');
INSERT INTO attr_value (jft_id, av_text) VALUES (465, '400');
INSERT INTO attr_value (jft_id, av_text) VALUES (466, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (467, 'FR00 123 456 789');
INSERT INTO attr_value (jft_id, av_text) VALUES (468, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (469, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (470, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (471, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (472, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (474, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (479, 'Q607');
INSERT INTO attr_value (jft_id, av_text) VALUES (475, 'achat de marchandises');
INSERT INTO attr_value (jft_id, av_text) VALUES (476, '101');
INSERT INTO attr_value (jft_id, av_text) VALUES (477, '607');
INSERT INTO attr_value (jft_id, av_text) VALUES (478, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (480, 'Fournisseurs et Comptes rattachés');
INSERT INTO attr_value (jft_id, av_text) VALUES (481, '400');
INSERT INTO attr_value (jft_id, av_text) VALUES (482, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (483, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (484, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (485, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (486, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (487, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (488, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (489, 'Q408FACTàRECEVOIR');
INSERT INTO attr_value (jft_id, av_text) VALUES (490, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (491, 'achat de marchandises');
INSERT INTO attr_value (jft_id, av_text) VALUES (492, '101');
INSERT INTO attr_value (jft_id, av_text) VALUES (493, '607');
INSERT INTO attr_value (jft_id, av_text) VALUES (494, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (495, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (497, 'Q607BIS');
INSERT INTO attr_value (jft_id, av_text) VALUES (507, 'Q410001PIGEON');
INSERT INTO attr_value (jft_id, av_text) VALUES (515, 'Q707');
INSERT INTO attr_value (jft_id, av_text) VALUES (522, 'Q708');
INSERT INTO attr_value (jft_id, av_text) VALUES (509, 'Ventes de marchandises');
INSERT INTO attr_value (jft_id, av_text) VALUES (510, '101');
INSERT INTO attr_value (jft_id, av_text) VALUES (511, '707');
INSERT INTO attr_value (jft_id, av_text) VALUES (512, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (513, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (514, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (516, 'Produits des activités annexes');
INSERT INTO attr_value (jft_id, av_text) VALUES (517, '101');
INSERT INTO attr_value (jft_id, av_text) VALUES (518, '708');
INSERT INTO attr_value (jft_id, av_text) VALUES (519, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (520, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (521, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (523, 'Capital');
INSERT INTO attr_value (jft_id, av_text) VALUES (524, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (525, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (526, '101');
INSERT INTO attr_value (jft_id, av_text) VALUES (527, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (528, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (529, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (530, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (531, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (532, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (533, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (534, 'Q101');
INSERT INTO attr_value (jft_id, av_text) VALUES (539, 'Q62DEPLACEMENTS');
INSERT INTO attr_value (jft_id, av_text) VALUES (540, 'autres charges externes - Autres services extérieurs');
INSERT INTO attr_value (jft_id, av_text) VALUES (541, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (542, '62');
INSERT INTO attr_value (jft_id, av_text) VALUES (543, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (544, 'Q62FRAISMISSIONS');
INSERT INTO attr_value (jft_id, av_text) VALUES (549, 'Q62FRAISTELECOM');
INSERT INTO attr_value (jft_id, av_text) VALUES (545, 'autres charges externes - Autres services extérieurs');
INSERT INTO attr_value (jft_id, av_text) VALUES (546, '101');
INSERT INTO attr_value (jft_id, av_text) VALUES (547, '62');
INSERT INTO attr_value (jft_id, av_text) VALUES (548, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (550, 'Associés - Comptes courants');
INSERT INTO attr_value (jft_id, av_text) VALUES (551, '455');
INSERT INTO attr_value (jft_id, av_text) VALUES (552, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (553, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (554, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (555, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (556, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (557, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (558, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (559, 'Q455ASSOCIE1');
INSERT INTO attr_value (jft_id, av_text) VALUES (560, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (535, 'autres charges externes - Autres services extérieurs');
INSERT INTO attr_value (jft_id, av_text) VALUES (536, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (537, '62');
INSERT INTO attr_value (jft_id, av_text) VALUES (538, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (561, 'Impôts, taxes et versements assimiles');
INSERT INTO attr_value (jft_id, av_text) VALUES (562, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (563, '63');
INSERT INTO attr_value (jft_id, av_text) VALUES (564, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (565, 'Q63TAXEPROFESSION');
INSERT INTO attr_value (jft_id, av_text) VALUES (566, 'Autres charges de gestion courante');
INSERT INTO attr_value (jft_id, av_text) VALUES (567, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (568, '65');
INSERT INTO attr_value (jft_id, av_text) VALUES (569, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (570, 'Q65');
INSERT INTO attr_value (jft_id, av_text) VALUES (571, 'Autres produits de gestion courante');
INSERT INTO attr_value (jft_id, av_text) VALUES (572, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (573, '75');
INSERT INTO attr_value (jft_id, av_text) VALUES (574, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (575, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (576, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (577, 'Q75');
INSERT INTO attr_value (jft_id, av_text) VALUES (583, 'Rémunération du travail de l''exploitant');
INSERT INTO attr_value (jft_id, av_text) VALUES (584, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (585, '644');
INSERT INTO attr_value (jft_id, av_text) VALUES (586, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (587, 'Q644');
INSERT INTO attr_value (jft_id, av_text) VALUES (579, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (603, 'Cotisations sociales personnelles de l''exploitant');
INSERT INTO attr_value (jft_id, av_text) VALUES (604, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (605, '646');
INSERT INTO attr_value (jft_id, av_text) VALUES (606, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (607, 'Q646');
INSERT INTO attr_value (jft_id, av_text) VALUES (652, 'Personnel - Rémunérations dues');
INSERT INTO attr_value (jft_id, av_text) VALUES (653, '421');
INSERT INTO attr_value (jft_id, av_text) VALUES (654, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (655, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (656, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (657, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (658, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (659, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (660, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (661, 'Q421SALAIRENETNICOLE');
INSERT INTO attr_value (jft_id, av_text) VALUES (662, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (663, 'Personnel - Rémunérations dues');
INSERT INTO attr_value (jft_id, av_text) VALUES (664, '421');
INSERT INTO attr_value (jft_id, av_text) VALUES (665, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (666, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (667, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (668, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (669, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (670, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (671, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (672, 'Q421SALAIRENETPIERRE');
INSERT INTO attr_value (jft_id, av_text) VALUES (673, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (578, 'Rémunérations du personnel');
INSERT INTO attr_value (jft_id, av_text) VALUES (580, '641');
INSERT INTO attr_value (jft_id, av_text) VALUES (581, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (582, 'Q641SALAIREBRUTNICOLE');
INSERT INTO attr_value (jft_id, av_text) VALUES (674, 'Rémunérations du personnel');
INSERT INTO attr_value (jft_id, av_text) VALUES (675, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (676, '641');
INSERT INTO attr_value (jft_id, av_text) VALUES (677, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (678, 'Q641SALAIREBRUTPIERRE');
INSERT INTO attr_value (jft_id, av_text) VALUES (617, 'Q431RETENUESURSSAFNICOLE');
INSERT INTO attr_value (jft_id, av_text) VALUES (608, 'Sécurité sociale');
INSERT INTO attr_value (jft_id, av_text) VALUES (609, '431');
INSERT INTO attr_value (jft_id, av_text) VALUES (610, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (611, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (612, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (613, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (614, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (615, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (616, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (618, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (679, 'Sécurité sociale');
INSERT INTO attr_value (jft_id, av_text) VALUES (680, '431');
INSERT INTO attr_value (jft_id, av_text) VALUES (681, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (682, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (683, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (684, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (685, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (686, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (687, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (688, 'Q431RETENUESURSSAFPIERRE');
INSERT INTO attr_value (jft_id, av_text) VALUES (689, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (619, 'Cotis.Sal.+Pat. Retraite salariés dûes');
INSERT INTO attr_value (jft_id, av_text) VALUES (620, '43731');
INSERT INTO attr_value (jft_id, av_text) VALUES (621, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (622, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (623, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (624, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (625, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (626, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (627, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (628, 'Q43731RETRAITESALARIESNICOLE');
INSERT INTO attr_value (jft_id, av_text) VALUES (629, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (699, 'Q43731RETRAITESALARIESPIERRE');
INSERT INTO attr_value (jft_id, av_text) VALUES (630, 'Cotis.Sal.+Pat. Retraite cadres dûes');
INSERT INTO attr_value (jft_id, av_text) VALUES (631, '43732');
INSERT INTO attr_value (jft_id, av_text) VALUES (632, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (633, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (634, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (635, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (636, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (637, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (638, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (639, 'Q43732RETAITECADRESNICOLE');
INSERT INTO attr_value (jft_id, av_text) VALUES (640, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (710, 'Q43732RETAITECADRESPIERRE');
INSERT INTO attr_value (jft_id, av_text) VALUES (650, 'Q4374CHOMAGENICOLE');
INSERT INTO attr_value (jft_id, av_text) VALUES (721, 'Q4374CHOMAGEPIERRE');
INSERT INTO attr_value (jft_id, av_text) VALUES (723, 'URSSAF');
INSERT INTO attr_value (jft_id, av_text) VALUES (724, '431');
INSERT INTO attr_value (jft_id, av_text) VALUES (725, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (726, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (727, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (728, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (729, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (730, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (731, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (732, 'Q431');
INSERT INTO attr_value (jft_id, av_text) VALUES (733, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (641, 'Cotis.Sal.+Pat. Chômage dûes');
INSERT INTO attr_value (jft_id, av_text) VALUES (642, '4374');
INSERT INTO attr_value (jft_id, av_text) VALUES (643, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (644, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (645, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (646, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (647, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (648, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (649, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (651, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (712, 'Cotis.Sal.+Pat. Chômage dûes');
INSERT INTO attr_value (jft_id, av_text) VALUES (713, '4374');
INSERT INTO attr_value (jft_id, av_text) VALUES (714, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (715, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (716, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (717, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (718, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (719, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (720, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (722, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (701, 'Cotis.Sal.+Pat. Retraite cadres dûes');
INSERT INTO attr_value (jft_id, av_text) VALUES (702, '43732');
INSERT INTO attr_value (jft_id, av_text) VALUES (703, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (704, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (705, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (706, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (707, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (708, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (709, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (711, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (690, 'Cotis.Sal.+Pat. Retraite salariés dûes');
INSERT INTO attr_value (jft_id, av_text) VALUES (691, '43731');
INSERT INTO attr_value (jft_id, av_text) VALUES (692, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (693, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (694, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (695, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (696, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (697, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (698, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (700, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (734, 'Cotis.Sal. Pat. Retraite salariés dûes');
INSERT INTO attr_value (jft_id, av_text) VALUES (735, '43731');
INSERT INTO attr_value (jft_id, av_text) VALUES (736, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (737, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (738, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (739, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (740, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (741, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (742, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (743, 'Q43731');
INSERT INTO attr_value (jft_id, av_text) VALUES (744, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (754, 'Q43732');
INSERT INTO attr_value (jft_id, av_text) VALUES (756, 'Cotis.Sal. Pat. Chômage dûes');
INSERT INTO attr_value (jft_id, av_text) VALUES (757, '4374');
INSERT INTO attr_value (jft_id, av_text) VALUES (758, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (759, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (760, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (761, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (762, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (763, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (764, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (765, 'Q4374');
INSERT INTO attr_value (jft_id, av_text) VALUES (766, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (745, 'Cotis.Sal.+Pat. Retraite cadres dûes');
INSERT INTO attr_value (jft_id, av_text) VALUES (746, '43732');
INSERT INTO attr_value (jft_id, av_text) VALUES (747, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (748, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (749, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (750, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (751, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (752, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (753, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (755, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (767, 'Clients et Comptes rattachés');
INSERT INTO attr_value (jft_id, av_text) VALUES (768, '410');
INSERT INTO attr_value (jft_id, av_text) VALUES (769, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (770, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (771, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (772, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (773, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (774, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (775, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (776, 'Q410');
INSERT INTO attr_value (jft_id, av_text) VALUES (777, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (498, 'Client 1 Pigeon');
INSERT INTO attr_value (jft_id, av_text) VALUES (499, '410');
INSERT INTO attr_value (jft_id, av_text) VALUES (500, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (501, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (502, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (503, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (504, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (505, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (506, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (508, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (778, 'Client 2 Pignouf');
INSERT INTO attr_value (jft_id, av_text) VALUES (779, '410');
INSERT INTO attr_value (jft_id, av_text) VALUES (780, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (781, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (782, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (783, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (784, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (785, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (786, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (787, 'Q410002PIGNOUF');
INSERT INTO attr_value (jft_id, av_text) VALUES (788, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (789, 'Client 3 Pinailleur');
INSERT INTO attr_value (jft_id, av_text) VALUES (790, '410');
INSERT INTO attr_value (jft_id, av_text) VALUES (791, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (792, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (793, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (794, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (795, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (796, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (797, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (798, 'Q410003PINAILLEUR');
INSERT INTO attr_value (jft_id, av_text) VALUES (799, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (800, 'Frais d''établissement');
INSERT INTO attr_value (jft_id, av_text) VALUES (801, '201');
INSERT INTO attr_value (jft_id, av_text) VALUES (802, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (803, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (804, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (805, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (806, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (807, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (808, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (809, 'Q2011');
INSERT INTO attr_value (jft_id, av_text) VALUES (810, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (811, 'Fournisseurs et Comptes rattachés');
INSERT INTO attr_value (jft_id, av_text) VALUES (812, '400');
INSERT INTO attr_value (jft_id, av_text) VALUES (813, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (814, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (815, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (816, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (817, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (818, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (819, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (820, 'Q4041FOURNISSEURIMMO');
INSERT INTO attr_value (jft_id, av_text) VALUES (821, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (838, 'Q281');
INSERT INTO attr_value (jft_id, av_text) VALUES (834, 'Amortissements des immobilisations corporelles');
INSERT INTO attr_value (jft_id, av_text) VALUES (835, '104');
INSERT INTO attr_value (jft_id, av_text) VALUES (836, '281');
INSERT INTO attr_value (jft_id, av_text) VALUES (837, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (839, 'Autres impôts, taxes et versements assimilés');
INSERT INTO attr_value (jft_id, av_text) VALUES (840, '447');
INSERT INTO attr_value (jft_id, av_text) VALUES (841, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (842, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (843, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (844, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (845, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (846, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (847, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (848, 'Q447');
INSERT INTO attr_value (jft_id, av_text) VALUES (849, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (850, 'Prestations de services');
INSERT INTO attr_value (jft_id, av_text) VALUES (851, '101');
INSERT INTO attr_value (jft_id, av_text) VALUES (852, '706');
INSERT INTO attr_value (jft_id, av_text) VALUES (853, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (854, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (855, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (856, 'Q706');
INSERT INTO attr_value (jft_id, av_text) VALUES (857, 'Ventes de produits finis');
INSERT INTO attr_value (jft_id, av_text) VALUES (858, '101');
INSERT INTO attr_value (jft_id, av_text) VALUES (859, '701');
INSERT INTO attr_value (jft_id, av_text) VALUES (860, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (861, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (862, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (863, 'Q701');
INSERT INTO attr_value (jft_id, av_text) VALUES (864, 'Rabais, remises et ristournes accordés par l''entreprise');
INSERT INTO attr_value (jft_id, av_text) VALUES (865, '101');
INSERT INTO attr_value (jft_id, av_text) VALUES (866, '709');
INSERT INTO attr_value (jft_id, av_text) VALUES (867, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (868, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (869, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (870, 'Q709');
INSERT INTO attr_value (jft_id, av_text) VALUES (871, 'immobilisations corporelles');
INSERT INTO attr_value (jft_id, av_text) VALUES (872, '104');
INSERT INTO attr_value (jft_id, av_text) VALUES (873, '21');
INSERT INTO attr_value (jft_id, av_text) VALUES (874, '');
INSERT INTO attr_value (jft_id, av_text) VALUES (875, 'Q21');



INSERT INTO bilan (b_id, b_name, b_file_template, b_file_form, b_type) VALUES (5, 'Comptes de rÃ©sultat', 'document/fr_fr/fr_plan_abrege_perso_cr1000.rtf', 'document/fr_fr/fr_plan_abrege_perso_cr1000.form', 'rtf');
INSERT INTO bilan (b_id, b_name, b_file_template, b_file_form, b_type) VALUES (1, 'Bilan franÃ§ais', 'document/fr_fr/fr_plan_abrege_perso_bil10000.ods', 'document/fr_fr/fr_plan_abrege_perso_bil10000.form', 'ods');












INSERT INTO document_state (s_id, s_value) VALUES (1, 'Envoyé');
INSERT INTO document_state (s_id, s_value) VALUES (2, 'Brouillon');
INSERT INTO document_state (s_id, s_value) VALUES (3, 'A envoyer');
INSERT INTO document_state (s_id, s_value) VALUES (4, 'Reçu');



INSERT INTO document_type (dt_id, dt_value) VALUES (1, 'Document Interne');
INSERT INTO document_type (dt_id, dt_value) VALUES (2, 'Bons de commande client');
INSERT INTO document_type (dt_id, dt_value) VALUES (3, 'Bon de commande Fournisseur');
INSERT INTO document_type (dt_id, dt_value) VALUES (4, 'Facture');
INSERT INTO document_type (dt_id, dt_value) VALUES (5, 'Lettre de rappel');
INSERT INTO document_type (dt_id, dt_value) VALUES (6, 'Courrier');
INSERT INTO document_type (dt_id, dt_value) VALUES (7, 'Proposition');
INSERT INTO document_type (dt_id, dt_value) VALUES (8, 'Email');
INSERT INTO document_type (dt_id, dt_value) VALUES (9, 'Divers');



INSERT INTO fiche (f_id, fd_id) VALUES (21, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (22, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (23, 3);
INSERT INTO fiche (f_id, fd_id) VALUES (24, 3);
INSERT INTO fiche (f_id, fd_id) VALUES (25, 3);
INSERT INTO fiche (f_id, fd_id) VALUES (26, 3);
INSERT INTO fiche (f_id, fd_id) VALUES (27, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (28, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (29, 6);
INSERT INTO fiche (f_id, fd_id) VALUES (30, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (32, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (33, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (34, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (35, 1);
INSERT INTO fiche (f_id, fd_id) VALUES (36, 2);
INSERT INTO fiche (f_id, fd_id) VALUES (37, 6);
INSERT INTO fiche (f_id, fd_id) VALUES (38, 6);
INSERT INTO fiche (f_id, fd_id) VALUES (39, 3);
INSERT INTO fiche (f_id, fd_id) VALUES (40, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (41, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (42, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (43, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (44, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (45, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (46, 6);
INSERT INTO fiche (f_id, fd_id) VALUES (47, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (48, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (52, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (53, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (54, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (55, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (56, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (57, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (58, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (59, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (60, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (61, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (62, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (63, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (64, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (65, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (66, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (67, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (68, 2);
INSERT INTO fiche (f_id, fd_id) VALUES (69, 2);
INSERT INTO fiche (f_id, fd_id) VALUES (70, 2);
INSERT INTO fiche (f_id, fd_id) VALUES (71, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (72, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (74, 5);
INSERT INTO fiche (f_id, fd_id) VALUES (75, 4);
INSERT INTO fiche (f_id, fd_id) VALUES (76, 6);
INSERT INTO fiche (f_id, fd_id) VALUES (77, 6);
INSERT INTO fiche (f_id, fd_id) VALUES (78, 6);
INSERT INTO fiche (f_id, fd_id) VALUES (79, 5);



INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (2, 400, 'Client', true, 9);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (1, 604, 'Marchandises', true, 2);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (3, 5500, 'Banque', true, 4);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (4, 440, 'Fournisseur', true, 8);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (5, 61, 'S & B D', true, 3);
INSERT INTO fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) VALUES (6, 700, 'Vente', true, 1);



INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (13, 'Dépenses non admises', 674);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (14, 'Administration des Finances', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (15, 'Autres fiches', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (4, 'Banque', 51);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (5, 'Prêt > a un an', 27);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (8, 'Fournisseurs', 400);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (6, 'Prêt < a un an', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (16, 'Contact', NULL);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (1, 'Vente Service', 706);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (2, 'Achat Marchandises', 603);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (9, 'Clients', 410);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (10, 'Salaire Administrateur', 644);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (11, 'Salaire Ouvrier', 641);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (12, 'Salaire Employé', 641);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (7, 'Matériel à amortir, immobilisation corporelle', 21);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (3, 'Achat Service et biens divers', 61);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (17, 'Escomptes accordées', 66);
INSERT INTO fiche_def_ref (frd_id, frd_text, frd_class_base) VALUES (18, 'Produits Financiers', 76);



INSERT INTO form (fo_id, fo_fr_id, fo_pos, fo_label, fo_formula) VALUES (3000398, 3000000, 1, 'Prestation [ case 03 ]', '[700%]-[7000005]');
INSERT INTO form (fo_id, fo_fr_id, fo_pos, fo_label, fo_formula) VALUES (3000399, 3000000, 2, 'Prestation intra [ case 47 ]', '[7000005]');
INSERT INTO form (fo_id, fo_fr_id, fo_pos, fo_label, fo_formula) VALUES (3000400, 3000000, 3, 'Tva due   [case 54]', '[4513]+[4512]+[4511] FROM=01.2005');
INSERT INTO form (fo_id, fo_fr_id, fo_pos, fo_label, fo_formula) VALUES (3000401, 3000000, 4, 'Marchandises, matière première et auxiliaire [case 81 ]', '[60%]');
INSERT INTO form (fo_id, fo_fr_id, fo_pos, fo_label, fo_formula) VALUES (3000402, 3000000, 7, 'Service et bien divers [case 82]', '[61%]');
INSERT INTO form (fo_id, fo_fr_id, fo_pos, fo_label, fo_formula) VALUES (3000403, 3000000, 8, 'bien d''invest [ case 83 ]', '[2400%]');
INSERT INTO form (fo_id, fo_fr_id, fo_pos, fo_label, fo_formula) VALUES (3000404, 3000000, 9, 'TVA déductible [ case 59 ]', 'abs([4117]-[411%])');
INSERT INTO form (fo_id, fo_fr_id, fo_pos, fo_label, fo_formula) VALUES (3000405, 3000000, 8, 'TVA non ded -> voiture', '[610022]*0.21/2');
INSERT INTO form (fo_id, fo_fr_id, fo_pos, fo_label, fo_formula) VALUES (3000406, 3000000, 9, 'Acompte TVA', '[4117]');



INSERT INTO format_csv_banque (name, include_file) VALUES ('Fortis', 'fortis_be.inc.php');
INSERT INTO format_csv_banque (name, include_file) VALUES ('EUB', 'eub_be.inc.php');
INSERT INTO format_csv_banque (name, include_file) VALUES ('ING', 'ing_be.inc.php');
INSERT INTO format_csv_banque (name, include_file) VALUES ('CBC', 'cbc_be.inc.php');
INSERT INTO format_csv_banque (name, include_file) VALUES ('Argenta Belgique', 'argenta_be.inc.php');
INSERT INTO format_csv_banque (name, include_file) VALUES ('CBC Belgique', 'cbc_be.inc.php');
INSERT INTO format_csv_banque (name, include_file) VALUES ('Dexia', 'dexia_be.inc.php');



INSERT INTO formdef (fr_id, fr_label) VALUES (3000000, 'TVA déclaration Belge');









INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (366, 21, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (367, 21, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (368, 21, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (369, 21, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (370, 21, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (371, 21, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (372, 21, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (373, 21, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (374, 21, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (375, 21, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (376, 21, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (377, 22, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (378, 22, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (379, 22, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (380, 22, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (381, 22, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (382, 23, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (383, 23, 3);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (384, 23, 4);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (385, 23, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (386, 23, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (387, 23, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (388, 23, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (389, 23, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (390, 23, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (391, 23, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (392, 23, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (393, 23, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (394, 24, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (395, 24, 3);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (396, 24, 4);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (397, 24, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (398, 24, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (399, 24, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (400, 24, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (401, 24, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (402, 24, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (403, 24, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (404, 24, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (405, 24, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (406, 25, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (407, 25, 3);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (408, 25, 4);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (409, 25, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (410, 25, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (411, 25, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (412, 25, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (413, 25, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (414, 25, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (415, 25, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (416, 25, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (417, 25, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (418, 26, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (419, 26, 3);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (420, 26, 4);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (421, 26, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (422, 26, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (423, 26, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (424, 26, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (425, 26, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (426, 26, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (427, 26, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (428, 26, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (429, 26, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (430, 27, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (431, 27, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (432, 27, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (433, 27, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (434, 27, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (435, 27, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (436, 27, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (437, 27, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (438, 27, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (439, 27, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (440, 27, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (441, 28, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (442, 28, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (443, 28, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (444, 28, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (445, 28, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (446, 29, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (447, 29, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (448, 29, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (449, 29, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (450, 29, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (451, 29, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (452, 29, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (453, 30, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (454, 30, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (455, 30, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (456, 30, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (457, 30, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (464, 32, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (465, 32, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (466, 32, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (467, 32, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (468, 32, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (469, 32, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (470, 32, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (471, 32, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (472, 32, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (473, 32, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (474, 32, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (475, 33, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (476, 33, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (477, 33, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (478, 33, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (479, 33, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (480, 34, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (481, 34, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (482, 34, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (483, 34, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (484, 34, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (485, 34, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (486, 34, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (487, 34, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (488, 34, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (489, 34, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (490, 34, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (491, 35, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (492, 35, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (493, 35, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (494, 35, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (495, 35, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (497, 35, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (498, 36, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (499, 36, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (500, 36, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (501, 36, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (502, 36, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (503, 36, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (504, 36, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (505, 36, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (506, 36, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (507, 36, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (508, 36, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (509, 37, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (510, 37, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (511, 37, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (512, 37, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (513, 37, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (514, 37, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (515, 37, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (516, 38, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (517, 38, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (518, 38, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (519, 38, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (520, 38, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (521, 38, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (522, 38, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (523, 39, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (524, 39, 3);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (525, 39, 4);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (526, 39, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (527, 39, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (528, 39, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (529, 39, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (530, 39, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (531, 39, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (532, 39, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (533, 39, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (534, 39, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (535, 40, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (536, 40, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (537, 40, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (538, 40, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (539, 40, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (540, 41, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (541, 41, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (542, 41, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (543, 41, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (544, 41, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (545, 42, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (546, 42, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (547, 42, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (548, 42, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (549, 42, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (550, 43, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (551, 43, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (552, 43, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (553, 43, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (554, 43, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (555, 43, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (556, 43, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (557, 43, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (558, 43, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (559, 43, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (560, 43, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (561, 44, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (562, 44, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (563, 44, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (564, 44, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (565, 44, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (566, 45, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (567, 45, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (568, 45, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (569, 45, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (570, 45, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (571, 46, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (572, 46, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (573, 46, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (574, 46, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (575, 46, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (576, 46, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (577, 46, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (578, 47, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (579, 47, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (580, 47, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (581, 47, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (582, 47, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (583, 48, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (584, 48, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (585, 48, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (586, 48, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (587, 48, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (603, 52, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (604, 52, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (605, 52, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (606, 52, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (607, 52, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (608, 53, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (609, 53, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (610, 53, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (611, 53, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (612, 53, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (613, 53, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (614, 53, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (615, 53, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (616, 53, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (617, 53, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (618, 53, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (619, 54, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (620, 54, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (621, 54, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (622, 54, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (623, 54, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (624, 54, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (625, 54, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (626, 54, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (627, 54, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (628, 54, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (629, 54, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (630, 55, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (631, 55, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (632, 55, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (633, 55, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (634, 55, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (635, 55, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (636, 55, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (637, 55, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (638, 55, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (639, 55, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (640, 55, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (641, 56, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (642, 56, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (643, 56, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (644, 56, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (645, 56, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (646, 56, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (647, 56, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (648, 56, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (649, 56, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (650, 56, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (651, 56, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (652, 57, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (653, 57, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (654, 57, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (655, 57, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (656, 57, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (657, 57, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (658, 57, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (659, 57, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (660, 57, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (661, 57, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (662, 57, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (663, 58, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (664, 58, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (665, 58, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (666, 58, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (667, 58, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (668, 58, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (669, 58, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (670, 58, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (671, 58, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (672, 58, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (673, 58, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (674, 59, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (675, 59, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (676, 59, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (677, 59, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (678, 59, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (679, 60, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (680, 60, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (681, 60, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (682, 60, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (683, 60, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (684, 60, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (685, 60, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (686, 60, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (687, 60, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (688, 60, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (689, 60, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (690, 61, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (691, 61, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (692, 61, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (693, 61, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (694, 61, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (695, 61, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (696, 61, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (697, 61, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (698, 61, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (699, 61, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (700, 61, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (701, 62, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (702, 62, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (703, 62, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (704, 62, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (705, 62, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (706, 62, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (707, 62, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (708, 62, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (709, 62, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (710, 62, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (711, 62, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (712, 63, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (713, 63, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (714, 63, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (715, 63, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (716, 63, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (717, 63, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (718, 63, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (719, 63, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (720, 63, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (721, 63, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (722, 63, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (723, 64, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (724, 64, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (725, 64, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (726, 64, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (727, 64, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (728, 64, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (729, 64, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (730, 64, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (731, 64, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (732, 64, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (733, 64, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (734, 65, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (735, 65, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (736, 65, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (737, 65, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (738, 65, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (739, 65, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (740, 65, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (741, 65, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (742, 65, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (743, 65, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (744, 65, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (745, 66, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (746, 66, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (747, 66, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (748, 66, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (749, 66, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (750, 66, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (751, 66, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (752, 66, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (753, 66, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (754, 66, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (755, 66, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (756, 67, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (757, 67, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (758, 67, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (759, 67, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (760, 67, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (761, 67, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (762, 67, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (763, 67, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (764, 67, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (765, 67, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (766, 67, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (767, 68, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (768, 68, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (769, 68, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (770, 68, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (771, 68, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (772, 68, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (773, 68, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (774, 68, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (775, 68, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (776, 68, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (777, 68, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (778, 69, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (779, 69, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (780, 69, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (781, 69, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (782, 69, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (783, 69, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (784, 69, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (785, 69, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (786, 69, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (787, 69, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (788, 69, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (789, 70, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (790, 70, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (791, 70, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (792, 70, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (793, 70, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (794, 70, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (795, 70, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (796, 70, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (797, 70, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (798, 70, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (799, 70, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (800, 71, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (801, 71, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (802, 71, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (803, 71, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (804, 71, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (805, 71, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (806, 71, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (807, 71, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (808, 71, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (809, 71, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (810, 71, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (811, 72, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (812, 72, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (813, 72, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (814, 72, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (815, 72, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (816, 72, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (817, 72, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (818, 72, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (819, 72, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (820, 72, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (821, 72, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (834, 74, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (835, 74, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (836, 74, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (837, 74, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (838, 74, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (839, 75, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (840, 75, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (841, 75, 12);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (842, 75, 13);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (843, 75, 14);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (844, 75, 15);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (845, 75, 16);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (846, 75, 17);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (847, 75, 18);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (848, 75, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (849, 75, 24);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (850, 76, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (851, 76, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (852, 76, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (853, 76, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (854, 76, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (855, 76, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (856, 76, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (857, 77, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (858, 77, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (859, 77, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (860, 77, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (861, 77, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (862, 77, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (863, 77, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (864, 78, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (865, 78, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (866, 78, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (867, 78, 6);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (868, 78, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (869, 78, 19);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (870, 78, 23);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (871, 79, 1);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (872, 79, 2);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (873, 79, 5);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (874, 79, 7);
INSERT INTO jnt_fic_att_value (jft_id, f_id, ad_id) VALUES (875, 79, 23);



INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (1, 5, 1);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (1, 1, 2);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (1, 2, 3);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (1, 6, 4);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (1, 7, 5);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 5, 6);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 1, 7);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 12, 8);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 13, 9);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 14, 10);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 15, 11);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 16, 12);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 17, 13);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 18, 14);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 5, 15);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 1, 16);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 3, 17);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 4, 18);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 12, 19);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 13, 20);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 14, 21);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 15, 22);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 16, 23);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 17, 24);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 18, 25);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 5, 26);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 1, 27);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 12, 28);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 13, 29);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 14, 30);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 15, 31);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 16, 32);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 17, 33);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 18, 34);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (5, 5, 35);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (5, 1, 36);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (5, 2, 37);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (5, 7, 38);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 5, 39);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 1, 40);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 2, 41);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 6, 42);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 7, 43);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 19, 44);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 23, 45);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (1, 23, 46);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (3, 23, 47);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 23, 48);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (5, 23, 49);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (6, 23, 50);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (2, 24, 51);
INSERT INTO jnt_fic_attr (fd_id, ad_id, jnt_id) VALUES (4, 24, 52);






INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (2, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (4, 'Voir Impayés', 'Voir toutes les factures non payées', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (1, 'Nouvelle', 'Création d''une facture', 'user_jrn.php', 'action=insert_vente&blank', 'FR', 'VEN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (10, 'Nouveau', 'Encode un nouvel achat (matériel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (12, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (14, 'Voir Impayés', 'Voir toutes les factures non payées', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'ACH');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (20, 'Nouveau', 'Encode un nouvel achat (matériel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'FIN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (22, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'FIN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (40, 'Soldes', 'Voir les soldes des comptes en banques', 'user_jrn.php', 'action=solde', 'FR', 'FIN');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (30, 'Nouveau', NULL, 'user_jrn.php', 'action=new&blank', 'FR', 'ODS');
INSERT INTO jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) VALUES (32, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'ODS');



INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (3, 'Achat', '6*', '4*', '5', '4', 1, 3, true, 'échéance', 'ACH', 'ACH-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (2, 'Vente', '4*', '7*', '2', '6', 2, 1, true, 'échéance', 'VEN', 'VEN-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (4, 'Opération Diverses', NULL, NULL, NULL, NULL, 5, 5, false, NULL, 'ODS', 'OD-01');
INSERT INTO jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) VALUES (1, 'Financier', '5*', '5*', '3,2,4,5', '3,2,4,5', 5, 5, false, NULL, 'FIN', 'FIN-01');






INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('VEN', 'Vente');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('ACH', 'Achat');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('ODS', 'Opérations Diverses');
INSERT INTO jrn_type (jrn_type_id, jrn_desc) VALUES ('FIN', 'Banque');















INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_NAME', 'LaMule');
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_TVA', 'FR33 123 456 789');
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_STREET', '');
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_NUMBER', '');
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_CP', '');
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_TEL', '');
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_PAYS', '');
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_COMMUNE', '');
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_FAX', '');
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_ANALYTIC', 'nu');
INSERT INTO parameter (pr_id, pr_value) VALUES ('MY_COUNTRY', 'FR');



INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('BANQUE', '51', 'Poste comptable par défaut pour les banques');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('CAISSE', '53', 'Poste comptable par défaut pour les caisses');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('CUSTOMER', '410', 'Poste comptable par défaut pour les clients');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('VENTE', '707', 'Poste comptable par défaut pour les ventes');
INSERT INTO parm_code (p_code, p_value, p_comment) VALUES ('VIREMENT_INTERNE', '58', 'Poste comptable par défaut pour les virements internes');



INSERT INTO parm_money (pm_id, pm_code, pm_rate) VALUES (1, 'EUR', 1.0000);



INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (92, '2007-01-01', '2007-01-31', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (93, '2007-02-01', '2007-02-28', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (94, '2007-03-01', '2007-03-31', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (95, '2007-04-01', '2007-04-30', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (96, '2007-05-01', '2007-05-31', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (97, '2007-06-01', '2007-06-30', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (98, '2007-07-01', '2007-07-31', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (99, '2007-08-01', '2007-08-31', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (100, '2007-09-01', '2007-09-30', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (101, '2007-10-01', '2007-10-31', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (102, '2007-11-01', '2007-11-30', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (103, '2007-12-01', '2007-12-30', '2007', false, false);
INSERT INTO parm_periode (p_id, p_start, p_end, p_exercice, p_closed, p_central) VALUES (104, '2007-12-31', '2007-12-31', '2007', false, false);


















INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (1, 'comptes de capitaux', 0);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (101, 'Capital', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (105, 'Ecarts de réévaluation', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (1061, 'Réserve légale', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (1063, 'Réserves statutaires ou contractuelles', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (1064, 'Réserves réglementées', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (1068, 'Autres réserves', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (108, 'Compte de l''exploitant', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (12, 'résultat de l''exercice (bénéfice ou perte)', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (145, 'Amortissements dérogatoires', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (146, 'Provision spéciale de réévaluation', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (147, 'Plus-values réinvesties', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (148, 'Autres provisions réglementées', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (15, 'Provisions pour risques et charges', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (16, 'emprunts et dettes assimilees', 1);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (2, 'comptes d''immobilisations', 0);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (20, 'immobilisations incorporelles', 2);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (201, 'Frais d''établissement', 20);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (206, 'Droit au bail', 20);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (207, 'Fonds commercial', 20);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (208, 'Autres immobilisations incorporelles', 20);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (21, 'immobilisations corporelles', 2);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (23, 'immobilisations en cours', 2);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (27, 'autres immobilisations financieres', 2);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (280, 'Amortissements des immobilisations incorporelles', 2);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (281, 'Amortissements des immobilisations corporelles', 2);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (290, 'Provisions pour dépréciation des immobilisations incorporelles', 2);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (291, 'Provisions pour dépréciation des immobilisations corporelles (même ventilation que celle du compte 21)', 2);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (297, 'Provisions pour dépréciation des autres immobilisations financières', 2);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (3, 'comptes de stocks et en cours', 0);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (31, 'matieres premières (et fournitures)', 3);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (32, 'autres approvisionnements', 3);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (33, 'en-cours de production de biens', 3);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (34, 'en-cours de production de services', 3);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (35, 'stocks de produits', 3);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (37, 'stocks de marchandises', 3);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (391, 'Provisions pour dépréciation des matières premières (et fournitures)', 3);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (392, 'Provisions pour dépréciation des autres approvisionnements', 3);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (393, 'Provisions pour dépréciation des en-cours de production de biens', 3);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (394, 'Provisions pour dépréciation des en-cours de production de services', 3);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (395, 'Provisions pour dépréciation des stocks de produits', 3);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (397, 'Provisions pour dépréciation des stocks de marchandises', 3);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (4, 'comptes de tiers', 0);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (400, 'Fournisseurs et Comptes rattachés', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (409, 'Fournisseurs débiteurs', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (410, 'Clients et Comptes rattachés', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (419, 'Clients créditeurs', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (421, 'Personnel - Rémunérations dues', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (428, 'Personnel - Charges à payer et produits à recevoir', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (43, 'Sécurité sociale et autres organismes sociaux', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (431, 'Sécurité sociale', 43);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (43731, 'Cotis.Sal.+Pat. Retraite salariés dûes', 43);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (43732, 'Cotis.Sal.+Pat. Retraite cadres dûes', 43);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (4374, 'Cotis.Sal.+Pat. ASSEDIC dûes', 43);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (444, 'Etat - Impôts sur les bénéfices', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (445, 'Etat - Taxes sur le chiffre d''affaires', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (44562, 'T.V.A. sur immobilisations', 445);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (445661, 'T.V.A. déductible 19,6% sur autres biens et services', 445);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (445662, 'T.V.A. déductible 5,5% sur autres biens et services', 445);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (445663, 'T.V.A. déductible 2,1% sur autres biens et services', 445);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (44571, 'T.V.A. collectée 19,6%', 445);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (44572, 'T.V.A. collectée 5,5%', 445);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (44573, 'T.V.A. collectée 2,1%', 445);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (447, 'Autres impôts, taxes et versements assimilés', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (45, 'Groupe et associes', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (455, 'Associés - Comptes courants', 45);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (46, 'Débiteurs divers et créditeurs divers', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (47, 'comptes transitoires ou d''attente', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (481, 'Charges à répartir sur plusieurs exercices', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (486, 'Charges constatées d''avance', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (487, 'Produits constatés d''avance', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (491, 'Provisions pour dépréciation des comptes de clients', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (496, 'Provisions pour dépréciation des comptes de débiteurs divers', 4);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (5, 'comptes financiers', 0);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (50, 'valeurs mobilières de placement', 5);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (51, 'banques, établissements financiers et assimilés', 5);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (53, 'Caisse', 5);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (54, 'régies d''avance et accréditifs', 5);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (58, 'virements internes', 5);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (590, 'Provisions pour dépréciation des valeurs mobilières de placement', 5);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (6, 'comptes de charges', 0);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (60, 'Achats (sauf 603)', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (607, 'Achats de marchandises', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (603, 'variations des stocks (approvisionnements et marchandises)', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (6031, 'Variation des stocks de matières premières (et fournitures)', 603);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (6032, 'Variation des stocks des autres approvisionnements', 603);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (6037, 'Variation des stocks de marchandises', 603);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (61, 'autres charges externes - Services extérieurs', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (62, 'autres charges externes - Autres services extérieurs', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (63, 'Impôts, taxes et versements assimiles', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (641, 'Rémunérations du personnel', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (644, 'Rémunération du travail de l''exploitant', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (6451, 'Cotisations à l''URSSAF', 645);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (6453, 'Cotisations aux caisses de retraites', 645);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (6454, 'Cotisations aux ASSEDIC', 645);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (646, 'Cotisations sociales personnelles de l''exploitant', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (65, 'Autres charges de gestion courante', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (66, 'Charges financières', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (67, 'Charges exceptionnelles', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (681, 'Dotations aux amortissements et aux provisions - Charges d''exploitation', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (6811, 'Dotations aux amortissements sur immobilisations incorporelles et corporelles', 681);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (6815, 'Dotations aux provisions pour risques et charges d''exploitation', 681);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (6816, 'Dotations aux provisions pour dépréciation des immobilisations incorporelles et corporelles', 681);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (6817, 'Dotations aux provisions pour dépréciation des actifs circulants', 681);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (686, 'Dotations aux amortissements et aux provisions - Charges financières', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (687, 'Dotations aux amortissements et aux provisions - Charges exceptionnelles', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (691, 'Participation des salariés aux résultats', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (695, 'Impôts sur les bénéfices', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (697, 'Imposition forfaitaire annuelle des sociétés', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (699, 'Produits - Reports en arrière des déficits', 6);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (7, 'comptes de produits', 0);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (701, 'Ventes de produits finis', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (706, 'Prestations de services', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (707, 'Ventes de marchandises', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (708, 'Produits des activités annexes', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (709, 'Rabais, remises et ristournes accordés par l''entreprise', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (713, 'Variation des stocks (en-cours de production, produits)', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (72, 'Production immobilisée', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (74, 'Subventions d''exploitation', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (75, 'Autres produits de gestion courante', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (753, 'Jetons de présence et rémunérations d''administrateurs, gérants,...', 75);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (754, 'Ristournes perçues des coopératives (provenant des excédents)', 75);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (755, 'Quotes-parts de résultat sur opérations faites en commun', 75);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (76, 'Produits financiers', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (77, 'Produits exceptionnels', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (781, 'Reprises sur amortissements et provisions (à inscrire dans les produits d''exploitation)', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (786, 'Reprises sur provisions pour risques (à inscrire dans les produits financiers)', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (787, 'Reprises sur provisions (à inscrire dans les produits exceptionnels)', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (79, 'Transferts de charges', 7);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (8, 'Comptes spéciaux', 0);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (80, 'Engagements hors bilan', 8);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (801, 'Engagements donnés par l''entité', 80);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (8011, 'Avals, cautions, garanties', 801);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (8014, 'Effets circulant sous l''endos de l''entité', 801);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (8016, 'Redevances crédit-bail restant à courir', 801);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (80161, 'Crédit-bail mobilier', 8016);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (80165, 'Crédit-bail immobilier', 8016);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (8018, 'Autres engagements donnés', 801);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (802, 'Engagements reçus par l''entité', 80);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (8021, 'Avals, cautions, garanties', 802);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (8024, 'Créances escomptées non échues', 802);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (8026, 'Engagements reçus pour utilisation en crédit-bail', 802);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (80261, 'Crédit-bail mobilier', 8026);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (80265, 'Crédit-bail immobilier', 8026);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (8028, 'Autres engagements reçus', 802);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (809, 'Contrepartie des engagements', 80);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (8091, 'Contrepartie 801', 809);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (8092, 'Contrepartie 802', 809);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (88, 'Résultat en instance d''affectation', 8);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (89, 'Bilan', 8);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (890, 'Bilan d''ouverture', 89);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (891, 'Bilan de clôture', 89);
INSERT INTO tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent) VALUES (9, 'Comptes hors Compta', 0);



INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (101, 'TVAFranceNormal', 0.1960, 'TVA 19,6% - France métropolitaine - Taux normal', '445661,44571');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (102, 'TVAFranceRéduit', 0.0550, 'TVA 5,5% - France métropolitaine - Taux réduit', '445662,44572');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (103, 'TVAFranceSuperRéduit', 0.0210, 'TVA 2,1% - France métropolitaine - Taux super réduit', '445663,44573');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (104, 'TVAFranceImmos', 0.1960, 'TVA 19,6% - France métropolitaine - Taux immobilisations', '44562,0');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (105, 'TVAFranceAnciens', 0.0000, 'TVA x% - France métropolitaine - Taux anciens', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (201, 'TVADomNormal', 0.0850, 'TVA 8,5%  - DOM - Taux normal', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (202, 'TVADomNPR', 0.0850, 'TVA 8,5% - DOM - Taux normal NPR', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (203, 'TVADomRéduit', 0.0210, 'TVA 2,1% - DOM - Taux réduit', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (204, 'TVADom-I', 0.0175, 'TVA 1,75% - DOM - Taux I', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (205, 'TVADomPresse', 0.0105, 'TVA 1,05% - DOM - Taux publications de presse', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (206, 'TVADomOctroi', 0.0000, 'TVA x% - DOM - Taux octroi de mer', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (207, 'TVADomImmos', 0.0000, 'TVA x% - DOM - Taux immobilisations', '445,0');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (301, 'TVACorse-I', 0.1300, 'TVA 13% - Corse - Taux I', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (302, 'TVACorse-II', 0.0800, 'TVA 8% - Corse - Taux II', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (303, 'TVACorse-III', 0.0210, 'TVA 2,1% - Corse - Taux III', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (304, 'TVACorse-IV', 0.0090, 'TVA 0,9% - Corse - Taux IV', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (305, 'TVACorseImmos', 0.0000, 'TVA x% - Corse - Taux immobilisations', '445,0');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (401, 'TVAacquisIntracom', 0.0000, 'TVA x% - Acquisitions intracommunautaires/Pays', '445,445');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (402, 'TVAacquisIntracomImmos', 0.0000, 'TVA x% - Acquisitions intracommunautaires immobilisations/Pays', '445,0');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (501, 'TVAfranchise', 0.0000, 'TVA x% - Non imposable : Achats en franchise', '');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (502, 'TVAexport', 0.0000, 'TVA x% - Non imposable : Exports hors CE/Pays', '');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (503, 'TVAautres', 0.0000, 'TVA x% - Non imposable : Autres opérations', '');
INSERT INTO tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) VALUES (504, 'TVAlivrIntracom', 0.0000, 'TVA x% - Non imposable : Livraisons intracommunautaires/Pays', '');












INSERT INTO version (val) VALUES (35);



