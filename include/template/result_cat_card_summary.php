<div class="content">
<table >
<tr>
<?
   echo th('Détail');
for ($i=0;$i<count($aHeading);$i++) :
   echo th($aHeading[$i]->ad_text,'style="color:blue;padding: 0 5 1 10"');
   endfor;
?>
</tr>
<?
$e=0;
foreach ($array as $row ) :
 $e++;
   if ($e%2==0)
   echo '<tr class="odd">';
   else
   echo '<tr class="even">';
   $fiche=new Fiche($cn);
   $fiche->id=$row['f_id'];
 $fiche->getAttribut();
$detail=HtmlInput::card_detail($fiche->strAttribut(ATTR_DEF_QUICKCODE));
echo td($detail);
 foreach($fiche->attribut as $attr) :
	 if ( $attr->ad_type != 'select'):
			echo td($attr->av_text,'style="padding: 0 10 1 10;white-space:nowrap;"');
	 else:
		$value=$cn->make_array($attr->ad_extra);
		for ($e=0;$e<count($value);$e++):
			if ( $value[$e]['value']==$attr->av_text):
				echo td($value[$e]['label'],'style="padding: 0 10 1 10;white-space:nowrap;"');
				break;
			endif;
		endfor;

	 endif;
 endforeach;
 echo '</tr>';
endforeach;

?>
</table>



</div>
