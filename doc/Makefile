# Makefile for creating the doc
# html, txt format
# $Revision$

.PHONY: all html pdf clean txt

all: html pdf txt

html:
	linuxdoc -B html --charset=latin FAQ.sgml
	linuxdoc -B html --charset=latin INSTALL-fr.sgml
pdf:
	linuxdoc -B latex --charset=latin --output=pdf FAQ.sgml
	linuxdoc -B latex --charset=latin --output=pdf INSTALL-fr.sgml
txt:
	linuxdoc -B txt --charset=latin FAQ.sgml
	linuxdoc -B txt --charset=latin INSTALL-fr.sgml
	cp INSTALL-fr.txt ..
	cp FAQ.txt ..

