create table parm_code (
	p_code text primary key,
	p_value text,
	p_comment text
);
INSERT INTO parm_code VALUES ('DNA', '6740', 'D�pense non d�ductible');
INSERT INTO parm_code VALUES ('CUSTOMER', '400', 'Poste comptable de base pour les clients');
INSERT INTO parm_code VALUES ('COMPTE_TVA', '451', 'TVA � payer');
INSERT INTO parm_code VALUES ('BANQUE', '550', 'Poste comptable de base pour les banques');
INSERT INTO parm_code VALUES ('VIREMENT_INTERNE', '58', 'Poste Comptable pour les virements internes');
INSERT INTO parm_code VALUES ('COMPTE_COURANT', '56', 'Poste comptable pour le compte courant');
INSERT INTO parm_code VALUES ('CAISSE', '57', 'Poste comptable pour la caisse');
INSERT INTO parm_code VALUES ('TVA_DNA', '6740', 'Tva non d�ductible s');
INSERT INTO parm_code VALUES ('TVA_DED_IMPOT', '619000', 'Tva d�ductible par l''imp�t');


