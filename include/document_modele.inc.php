<?
/*
 *   This file is part of PhpCompta.
 *
 *   PhpCompta is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   PhpCompta is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with PhpCompta; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*! \file
 * \brief Manage the document template
 */

require_once("class_document_modele.php");
$sub_action=(isset ($_REQUEST['sa']))?$_REQUEST['sa']:"";

echo "<hr>";
// show the form for adding a template
//
$doc=new Document_modele($cn);

echo $doc->form('parametre.php?p_action=document');

//-----------------------------------------------------
// Document 	add a template
//-----------------------------------------------------
if ( $sub_action=='add_document') 
{
  require_once("class_document_modele.php");
  $doc=new Document_modele($cn);
  $doc->md_name=$_POST['md_name'];
  $doc->md_id=-1; // because it is a new model
  $doc->md_type=$_POST['md_type'];
  $doc->start=$_POST['start_seq'];
  $doc->Save();
  }
//-----------------------------------------------------
// Document remove a template
//-----------------------------------------------------
if ( $sub_action=='rm_template') {
  echo_debug (__FILE__,__LINE__,'Remove some templates');
  require_once("class_document_modele.php");
  // Get all the document to remove

  foreach ( $_POST as $name=>$value )
    {
      echo_debug(__FILE__,__LINE__,"name = ".$name);
      echo_debug(__FILE__,__LINE__,"value = ".$value);
      list ($id) = sscanf ($name,"dm_remove_%d");
      echo_debug(__FILE__,__LINE__,"id = ".$id);
      if ( $id == null ) continue;
      // a document has to be removed
      $doc=new Document_modele($cn);
      $doc->md_id=$id;
      echo_debug (__FILE__,__LINE__,'Removing');
      $doc->Delete();
    }

}

//-----------------------------------------------------
// Default action : Show the list
//-----------------------------------------------------
echo $doc->myList();
?>