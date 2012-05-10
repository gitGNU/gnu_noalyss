#!/bin/bash

export PGUSER=dany
export PGPASSWORD=dany
export PGHOST=localhost
export PGDATABASE=trunkdossier25
export PGPORT=5000
export PGCLUSTER=9.0/main

(
echo "<?php "
psql -A -F"  " -t -c "select '\$menu[]=_('''||replace(me_menu,'''',E'\\\\''')||''');' , '\$desc[]=_('''||replace(me_description,'''',E'\\\\''')||''');' from menu_ref ;"
echo "?>"  ) > ../include/menu_translate.php
