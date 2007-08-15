
<style type="text/css">
<!--
h2.info {
	color:green;
	font-size:20px;
}
h2.error {
	color:red;
	font-size:20px;
}
-->
</style>
<?php
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
/* $Revision*/
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
include_once("ac_common.php");
include_once("postgres.php");
// Test the connection
echo __FILE__.":".__LINE__;

require_once('class_plananalytic.php');
$cn=DbConnect(14);
echo "<h1>Plan analytique : test</h1>";
echo "clean";
ExecSql($cn,"delete from plan_analytique");

$p=new PlanAnalytic($cn);
echo "<h2>Add</h2>";
$p->name="Nouveau 1";
$p->description="C'est un test";
echo "Add<hr>";
$p->add();
$p->name="Nouveau 2";
$p->add();
$pa_id=$p->id;
echo $p->id."/";
$p->name="Nouveau 3";
$p->add();
echo $p->id."/";


$p->name="Nouveau 4";
$p->add();
echo $p->id;

echo "<h2>get</h2>";
$p->get();
var_dump($p);
echo "<h2>Update</h2> ";
$p->name="Update ";
$p->description="c'est changé";
$p->update();
$p->get;
var_dump($p);
echo "<h2>get_list</h2>";
$a=$p->get_list();
var_dump($a);
echo "<h2>delete </h2>";
$p->delete();

//--------------------------------------------------------------------------------
require_once("class_poste_analytic.php");
$o=new Poste_Analytique($cn);
echo "<h1>Poste_Analytique</h1>";
echo "<h2>get_list</h2>";
$ee=$o->get_list();

//var_dump($ee);
echo "<h2>Add some </h2>";
$o->pa_id=$pa_id;
$o->name="test1";
$o->add();


$o->name="test2";
$o->add();

$o->name="test3";
$o->add();

$o->name="test4";
$o->add();

$o->name="test5";
$o->add();

echo "<h2> remove test1</h2>";
$o->get_by_name("test1");
$o->delete();
$o->display_list();

$o->get_by_name("test4");
echo "<hr>".$o->id."<hr>";
$o->name="Test Four";
$o->update();
$o->display_list();
$o->delete();
$o->display_list();
