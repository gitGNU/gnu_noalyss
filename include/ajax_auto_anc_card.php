<?
require_once ("class_database.php");
require_once 'class_user.php';
$cn=new Database($_GET['gDossier']);
$user=new User($cn);
$user->Check();
$user->check_dossier($_GET['gDossier']);
if ( isset($_REQUEST['pa_id']) )
{   
    $res=$cn->exec_sql("select po_id,po_description from  poste_analytique where pa_id=$1 ~* and (po_description ~*$2 or po_id ~* $3 order by po_id limit 12",
        array($_REQUEST['pa_id'],$_POST['anccard'],$_POST['anccard']));
}
else
{
       $res=$cn->exec_sql("select po_id,po_description from  poste_analytique where po_description ~*$2 or po_id ~* $3 order by po_id limit 12 ",
        array($_POST['anccard'],$_POST['anccard']));
}
$nb=Database::num_row($res);
	echo "<ul>";
for ($i = 0;$i< $nb;$i++)
{
	$row=Database::fetch_array($res,$i);
	echo "<li>";
	echo $row['po_id'];
	echo '<span class="informal"> '.$row['po_description'].'</span></li>';
}
	echo "</ul>";
?>