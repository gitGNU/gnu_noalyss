#!/bin/bash
# Brief : compite  the file .mo, 
# It is used for the translation
#
#
# This file is a part of PhpCompta under GPL
# Author D. DE BONTRIDDER ddebontridder@yahoo.fr
cd ../html/lang
cd en_US/LC_MESSAGES
msgfmt -c -v messages.po
cd ../..
cd nl_NL/LC_MESSAGES
msgfmt -c -v messages.po

