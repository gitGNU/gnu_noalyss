<form method="post">

<table>

<?php
for ($i=0;$i<count($aList);$i++) :
  $row=$aList[$i];
?>

<tr id="row<?=$row['dt_id']?>">
<td colspan="2">
<?=$row['dt_value'];?>
</td>
<td>
<?=$row['js_remove'];?>
</td>
</tr>
<?
endfor;
?>
<tr>
<td>
<?=$str_addCat?>
</td>
<td>
   <?=$str_submit?>
</td>
</tr>

</table>
<?
echo dossier::hidden();
echo HtmlInput::hidden('ac',$_REQUEST['ac']);
?>
</form>