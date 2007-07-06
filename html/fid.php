<?
include ("constant.php");
include ("postgres.php");
include("user_common.php");
include("debug.php");
echo_debug('fid.php',__LINE__,"Recherche fid.php".$_GET["FID"]);
$cn=DbConnect($_SESSION['g_dossier']);
if ( isset($_SESSION['isValid']) && $_SESSION['isValid'] == 1)
{ 
  $deb_cred=($_GET['d']=='cred')?"jrn_def_fiche_cred":"jrn_def_fiche_deb";
  $jrn=$_GET['j'];
  $filter_jrn=Getdbvalue($cn,"select $deb_cred from jrn_def where jrn_def_id=$jrn");
  $array=GetArray($cn,"select vw_name,vw_addr,vw_cp,vw_buy,vw_sell,tva_id 
                    from vw_fiche_attr 
                    where quick_code=upper('".$_GET['FID']."') and fd_id in ($filter_jrn)"
		    );
   	echo_debug("fid",__LINE__,$array);
   	$name=$array[0]['vw_name']." ".$array[0]['vw_addr']." ".$array[0]['vw_cp'];
   	$sell=$array[0]['vw_sell'];
   	$buy=$array[0]['vw_buy'];
   	$tva_id=$array[0]['tva_id'];
	$a=( $array != null)?"$name/$sell/$buy/$tva_id":"";
}
     else
     $a="not connected";
echo_debug("fid.php",__LINE__,"Answer is $a");
print $a;
?>