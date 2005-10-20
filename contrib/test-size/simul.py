#!/usr/bin/python
#
#
import random
import getopt 
import sys


	 
			 
def usage():
	print """
	-h help
	-s generate a sql file for a small database test
	-l generate a sql file for a large database test
	-x generate a extra large sql for a huge database test
	"""
	 sys.exit(-1)

def Add_Attribut_Fiche(p_jft,p_f,p_ad_id,p_value):
	# Ajout du nom
	print "insert into jnt_fic_att_value(jft_id,f_id,ad_id) values (%d,%d,%d);" % (p_jft,p_f,p_ad_id)
	print "insert into attr_value(jft_id,av_text) values (%d,'%s');" % (p_jft,p_value)

def Creation_fiche (p_seq_f_id,p_seq_jft_id,p_fd_id,p_type,p_base_poste,p_nbfiche):
	for i in range (0,p_nbfiche):
		#def Creation fiche :
		print "insert into fiche(f_id,fd_id)values (%d,%d);" % (p_seq_f_id,p_fd_id)
		# ajout nom
		nom="%s numero %08d" % (p_type,i+100)
		Add_Attribut_Fiche(p_seq_jft_id,p_seq_f_id,1,nom)
		#poste comptable
		poste_comptable='%s%04d'% (p_base_poste,i+100)
		print "insert into tmp_pcmn (pcm_val,pcm_lib,pcm_val_parent) values (%s,'%s',%s); " % (poste_comptable,nom,p_base_poste)
		p_seq_jft_id+=1
		Add_Attribut_Fiche(p_seq_jft_id,p_seq_f_id,5,poste_comptable)
		p_seq_f_id+=1
		p_seq_jft_id+=1
def Creation_operation(p_base,p_type):
	jrn="insert into jrn (jr_def_id,jr_montant,jr_comment,jr_date,jr_grpt_id,jr_internal,jr_tech_per)"
	jrn+=" values  (%d,%8.2f,'%s',to_date('%d-%d-2005','DD-MM-YYYY'),%d,'%s',%d);"
	jrnx="insert into jrnx (j_date,j_montant,j_poste,j_grpt,j_jrn_def,j_debit,j_tech_user,j_tech_per)"
	jrnx+="values (to_date('%d-%d-2005','DD-MM-YYYY'),%8.2f,%s,%d,%d,%s,'SIMULATION',%d);"
	for loop_periode in range (53,64):
		for loop_day in range (1,28):
			for loop_op in range (0,50):
				if p_type == 'V':
					j_internal='1VEN-01-%d' % (p_base)
					j_montant=round(random.randrange(100,5000)/100.0,2)
					j_client='400%04d' % (random.randrange(1,nb_fiche)+100)
					jrnx1=jrnx % (loop_day,loop_periode-39,j_montant,j_client,p_base,2,'true',loop_periode)
					print jrnx1
					j_tva=round(j_montant*0.21,2)
					jrnx1=jrnx % (loop_day,loop_periode-39,j_tva,'4511',p_base,2,'true',loop_periode)
					print jrnx1
					jrnx1=jrnx % (loop_day,loop_periode-39,j_montant+j_tva,'700',p_base,2,'false',loop_periode)
					print jrnx1
					total=j_montant+j_tva
					jrn1=jrn%(2,total,j_internal,loop_day,loop_periode-39,p_base,j_internal,loop_periode)
					print jrn1
					p_base+=1
				if p_type== 'A':
					j_internal='1ACH-01-%d' % (p_base)
					j_montant=round(random.randrange(100,5000)/100.0,2)
					j_client='440%04d' % (random.randrange(0,nb_fiche)+100)
					j_charge='61%04d' % (random.randrange(0,350)+100)
					jrnx1=jrnx % (loop_day,loop_periode-39,j_montant,j_client,p_base,3,'false',loop_periode)
					print jrnx1
					j_tva=round(j_montant*0.21,2)
					jrnx1=jrnx % (loop_day,loop_periode-39,j_tva,'4111',p_base,3,'false',loop_periode)
					print jrnx1
					jrnx1=jrnx % (loop_day,loop_periode-39,j_montant+j_tva,j_charge,p_base,3,'true',loop_periode)
					print jrnx1
					jrn1=jrn%(3,j_montant+j_tva,j_internal,loop_day,loop_periode-39,p_base,j_internal,loop_periode)
					print jrn1
					p_base+=1
	

################################################################################
#  MAIN
################################################################################
cmd_line=sys.argv[1:]
try :
	a1,a2=getopt.getopt(cmd_line,"slxh",['small','large','extra-large','help'])
except getopt.GetoptError,msg:
	 print "ERROR "
	 print msg.msg
	 usage()
for option,value in a1:
	if option in ('-h','--help'):
		usage()
	if option in ('-s','--small'):
		nb_fiche=100
		nb_charge=50
		nb_per_day=5
		break
	if option in ('-l','--large'):
		nb_fiche=5000
		nb_charge=350
		nb_per_day=50
	if option in ('-x','--extra-large'):
		nb_fiche=10000
		nb_charge=1500
		nb_per_day=500

print "begin;"
# fd_id => client
fd_id=2
# type fiche
type='Client'

# numero de sequence fiche
f_id=1000
# numero de sequence jnt_fic_att_value
jft_id=1000
# poste comptable
base_poste='400'

Creation_fiche(f_id,jft_id,fd_id,type,'400',nb_fiche)

# fournisseur
fd_id=4
type='Fournisseur'
f_id+=nb_fiche+100
jft_id+=2*nb_fiche+100
base_poste='440'

Creation_fiche(f_id,jft_id,fd_id,type,base_poste,nb_fiche)

# Creation Service et bien divers
fd_id=5
type='Charge '
f_id+=nb_fiche+100
jft_id+=2*nb_fiche+100
base_poste='61'

Creation_fiche(f_id,jft_id,fd_id,type,base_poste,350)

#Creation_operation Vente
Creation_operation(1000,'V')

#Creation_operation Achat
Creation_operation(17000,'A')

print "commit;"
