#!/bin/bash
# Brief : compite  the file .mo, 
# It is used for the translation
#
#
# This file is a part of PhpCompta under GPL
# Author D. DE BONTRIDDER ddebontridder@yahoo.fr
cd ..
cd html/lang/en_US/LC_MESSAGES
msgfmt -c -v messages.po

