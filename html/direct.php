<?
require_once ("class_database.php");
require_once 'class_user.php';
$cn=new Database($_GET['gDossier']);
$user=new User($cn);
$user->Check();
$user->check_dossier($_GET['gDossier']);
$res=$cn->exec_sql("select code,description from get_profile_menu($1) where code ~* $2 or description ~* $3 limit 8",array($user->login,$_POST['acs'],$_POST['acs']));
$nb=Database::num_row($res);
	echo "<ul>";
for ($i = 0;$i< $nb;$i++)
{
	$row=Database::fetch_array($res,$i);
	echo "<li>";
	echo $row['code'];
	echo '<span class="informal"> '.$row['description'].'</span></li>';
}
	echo "</ul>";
?>
