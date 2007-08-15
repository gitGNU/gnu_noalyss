<?
header("Content-type: text/xml charset=\"ISO8859-1\"",true);

include ("constant.php");
include ("postgres.php");
include("user_common.php");
include("debug.php");

echo_debug('fid.php',__LINE__,"Recherche fid.php".$_GET["FID"]);
$cn=DbConnect($_SESSION['g_dossier']);
if ( isset($_SESSION['isValid']) && $_SESSION['isValid'] == 1)
{ 
  $d=$_GET['d'];
  $jrn=$_GET['j'];

  switch ($d) {
  case 'cred':
    $filter_jrn=Getdbvalue($cn,"select jrn_def_fiche_cred from jrn_def where jrn_def_id=$jrn");
    $filter_card="and fd_id in ($filter_jrn)";
    break;
  case 'deb':
    $filter_jrn=Getdbvalue($cn,"select jrn_def_fiche_deb from jrn_def where jrn_def_id=$jrn");
    $filter_card="and fd_id in ($filter_jrn)";
    break;
  case 'all':
    $filter_card="";
    break;
  }


  $array=GetArray($cn,"select vw_name,vw_addr,vw_cp,vw_buy,vw_sell,tva_id 
                    from vw_fiche_attr 
                    where quick_code=upper('".$_GET['FID']."') $filter_card"
		    );
  echo_debug("fid",__LINE__,$array);
  $name=$array[0]['vw_name']." ".$array[0]['vw_addr']." ".$array[0]['vw_cp'];
  $sell=$array[0]['vw_sell'] ;
  $buy=$array[0]['vw_buy'];
  $tva_id=$array[0]['tva_id'];

  // Check null
  $name=($name==null)?" ":$name;
  $sell=($sell==null)?" ":$sell;
  $buy=($buy==null)?" ":$buy;
  $tva_id=($tva_id==null)?" ":$tva_id;

  $a='<?xml version="1.0" encoding="iso8859-1" standalone="yes"?>';
  $a.="<data>";
  $a.="<name>".$name."</name>"; 
  $a.="<sell>".$sell."</sell>";
  $a.="<buy>".$buy."</buy>";
  $a.="<tva_id>".$tva_id."</tva_id>";
  $a.="</data>";

}
     else
     $a="not connected";
echo_debug("fid.php",__LINE__,"Answer is \n $a");

print $a;
?>
