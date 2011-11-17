
insert into menu_ref(me_code,me_menu,me_file,me_description,me_type) 
values ('ANCHOP','Historique','anc_history.inc.php','Historique des imputations analytiques','ME'),
('ANCGL','Grand''Livre','anc_great_ledger.inc.php','Grand livre d''plan analytique','ME'),
('ANCBS','Balance simple','anc_balance_simple.inc.php','Balance simple des imputations analytiques','ME'),
('ANCBC2','Balance croisée double','anc_balance_double.inc.php','Balance double croisées des imputations analytiques','ME'),
('ANCTAB','Tableau','anc_acc_table.inc.php','Tableau lié à la comptabilité','ME'),
('ANCBCC','Balance Analytique/comptabilité','anc_acc_balance.inc.php','Lien entre comptabilité et Comptabilité analytique','ME'),
('ANCGR','Groupe','anc_group_balance.inc.php','Balance par groupe','ME');

update menu_ref set me_file=null where me_code='ANCIMP';

insert into profile_menu (me_code,me_code_dep,p_id,p_order,p_type_display,pm_default)
values 
('ANCHOP','ANCIMP',1,10,'E',0),
('ANCGL','ANCIMP',1,20,'E',0),
('ANCBS','ANCIMP',1,30,'E',0),
('ANCBC2','ANCIMP',1,40,'E',0),
('ANCTAB','ANCIMP',1,50,'E',0),
('ANCBCC','ANCIMP',1,60,'E',0),
('ANCGR','ANCIMP',1,70,'E',0);