<div class="content">
<table >
<tr>
<? for ($i=0;$i<count($aHeading);$i++) :
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
 $row->getAttribut();
 foreach($row->attribut as $attr) :
    echo td($attr->av_text,'','style="padding: 0 10 1 10"');
 endforeach;       
 echo '</tr>';
endforeach;       

?>
</table>



</div>
