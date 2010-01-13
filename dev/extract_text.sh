#!/bin/bash
# Brief : extract strings from the file, in order to update a
# po file. It is used for the translation
#
#
# This file is a part of PhpCompta under GPL
# Author D. DE BONTRIDDER ddebontridder@yahoo.fr
echo "Extract"
cd ..
xgettext -L PHP -j --from-code=UTF-8 -p html/lang/en_US/LC_MESSAGES/ html/*.php include/*.php include/template/*.php
