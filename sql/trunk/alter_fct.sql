alter function account_add( p_id poste_comptable, p_name character varying) set schema comptaproc ;
alter function account_auto( p_fd_id integer) set schema comptaproc ;
alter function account_compute( p_f_id integer) set schema comptaproc ;
alter function account_insert( p_f_id integer, p_account text) set schema comptaproc ;
alter function account_parent( p_account poste_comptable) set schema comptaproc ;
alter function account_update( p_f_id integer, p_account text) set schema comptaproc ;
alter function action_gestion_ins_upd() set schema comptaproc ;
alter function attribut_insert( p_f_id integer, p_ad_id integer, p_value character varying) set schema comptaproc ;
alter function attribute_correct_order() set schema comptaproc ;
alter function bud_card_ins_upd() set schema comptaproc ;
alter function bud_detail_ins_upd() set schema comptaproc ;
alter function card_class_base( p_f_id integer) set schema comptaproc ;
alter function check_balance( p_grpt integer) set schema comptaproc ;
alter function correct_sequence( p_sequence text, p_col text, p_table text) set schema comptaproc ;
alter function create_missing_sequence() set schema comptaproc ;
alter function drop_index( p_constraint character varying) set schema comptaproc ;
alter function drop_it( p_constraint character varying) set schema comptaproc ;
alter function extension_ins_upd() set schema comptaproc ;
alter function fiche_account_parent( p_f_id integer) set schema comptaproc ;
alter function fiche_attribut_synchro( p_fd_id integer) set schema comptaproc ;
alter function fiche_def_ins_upd() set schema comptaproc ;
alter function find_pcm_type( pp_value numeric) set schema comptaproc ;
alter function group_analytic_ins_upd() set schema comptaproc ;
alter function group_analytique_del() set schema comptaproc ;
alter function html_quote( p_string text) set schema comptaproc ;
alter function info_def_ins_upd() set schema comptaproc ;
alter function insert_jrnx( p_date character varying, p_montant numeric, p_poste poste_comptable, p_grpt integer, p_jrn_def integer, p_debit boolean, p_tech_user text, p_tech_per integer, p_qcode text, p_comment text) set schema comptaproc ;
alter function insert_quant_purchase( p_internal text, p_j_id numeric, p_fiche character varying, p_quant numeric, p_price numeric, p_vat numeric, p_vat_code integer, p_nd_amount numeric, p_nd_tva numeric, p_nd_tva_recup numeric, p_dep_priv numeric, p_client character varying) set schema comptaproc ;
alter function insert_quant_sold( p_internal text, p_jid numeric, p_fiche character varying, p_quant numeric, p_price numeric, p_vat numeric, p_vat_code integer, p_client character varying) set schema comptaproc ;
alter function insert_quick_code( nf_id integer, tav_text text) set schema comptaproc ;
alter function jrn_check_periode() set schema comptaproc ;
alter function jrn_def_add() set schema comptaproc ;
alter function jrn_def_delete() set schema comptaproc ;
alter function jrn_del() set schema comptaproc ;
alter function jrnx_del() set schema comptaproc ;
alter function plan_analytic_ins_upd() set schema comptaproc ;
alter function poste_analytique_ins_upd() set schema comptaproc ;
alter function proc_check_balance() set schema comptaproc ;
alter function t_document_modele_validate() set schema comptaproc ;
alter function t_document_type_insert() set schema comptaproc ;
alter function t_document_validate() set schema comptaproc ;
alter function t_jrn_def_sequence() set schema comptaproc ;
alter function tmp_pcmn_ins() set schema comptaproc ;
alter function trim_cvs_quote() set schema comptaproc ;
alter function trim_space_format_csv_banque() set schema comptaproc ;
alter function tva_delete( integer) set schema comptaproc ;
alter function tva_insert( text, numeric, text, text) set schema comptaproc ;
alter function tva_modify( integer, text, numeric, text, text) set schema comptaproc ;
alter function update_quick_code( njft_id integer, tav_text text) set schema comptaproc ;
