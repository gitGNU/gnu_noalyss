<TABLE BORDER="0" CELLSPACING="0">
<TR>
<TD> Catégorie de fiche </TD>
<TD><INPUT TYPE="INPUT" NAME="nom_mod"></TD>
</TR>
<TR>
<TD> Classe de base </TD>
<TD><INPUT TYPE="INPUT" NAME="class_base"> <INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="<? $p_js;?>"></TD>
</TR>
<TR>
<TD> <INPUT TYPE="CHECKBOX" NAME="create" CHECKED>Cr&eacute;ation automatique du poste comptable</TD>
</TR>
<?php
  if ( sizeof($ref)  ) {
    foreach ($ref as $i=>$v) { ?>
<TR><TD COLSPAN="2">
<? echo $iradio->input("FICHE_REF",$v['frd_id']);
   echo $v['frd_text'];
   if ( sizeof ($v['frd_class_base']) != 0 ) 
	   echo "&nbsp;&nbsp<I>Class base = ".$v['frd_class_base']."</I>";
      echo "</TD></TR>";

    }
 
  }
?>

</TABLE>