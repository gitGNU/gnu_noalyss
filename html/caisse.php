<script language="javascript">
                 function set_code(p_code)
                 {

                     for (i=0;i<10;i++)
                     {
                         if ( document.getElementById("id_"+i).value == "")
                         {
                             document.getElementById("id_"+i).value=p_code;
                             break;
                         }
                     }
                 }
                 function f_reset(p_code)
                 {
                     document.getElementById("id_"+p_code).value="";
                 }
                 </script>
                 <style type="text/css">
                             /* CSS goes here */
                             body {
                                 font-size:
                                 14px;
                                 font-color:
                                 green;
                             }

                             .btn {
                                 border-size:
                                 4px;
                                 border-color:
                                 blue;
                                 border-style:
                                 sunken;
                                 background:
                                 inherit;
                                 font:
                                 inherit;
                                 color:
                                 blue;
                                 padding:
                                 2px;
                                 width:
                                 150px;
                                 height:
                                 60px;
                             }

                             </style>
                             <?php
                             echo '<form method="post" action="result.php">';

for ($i=0;$i<10;$i++)
{
    echo 'montant <input type="TEXT" name="in'.$i.'" id="id_'.$i.'">';
    echo '<input type="BUTTON" value="Efface" onClick="f_reset('.$i.')">';
    echo '<BR>';
}



for ($i=0; $i < 5;$i++)
{
    echo '<INPUT TYPE="BUTTON" class="btn" name="button" value="code '.$i.'" onClick="set_code('.$i.')">';
}
?>
<input type="submit">
            <input type="reset">
                        </form>
