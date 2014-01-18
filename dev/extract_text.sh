#!/bin/bash
# Brief : extract strings from the file, in order to update a
# po file. It is used for the translation
#
#
# This file is a part of NOALYSS under GPL
# Author D. DE BONTRIDDER danydb@aevalys.eu
echo "Extract"
cd ..
xgettext -L PHP -j --from-code=UTF-8 -p html/lang/en_US/LC_MESSAGES/ html/*.php include/*.php include/template/*.php
xgettext -L PHP -j --from-code=UTF-8 -p html/lang/nl_NL/LC_MESSAGES/ html/*.php include/*.php include/template/*.php 
