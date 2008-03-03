# Makefile for creating the doc
# html, txt format
# $Revision$

.PHONY: all html .pdf clean .txt devel

all: manuel html INSTALL-fr.pdf INSTALL-fr.txt devel

devel:
	cd developper && ./create_doc.sh
html:
	linuxdoc -B html --charset=latin INSTALL-fr.sgml
manuel:
	cp manuel-fr.pdf ../manuel-fr.pdf

INSTALL-fr.pdf:INSTALL-fr.sgml
	linuxdoc -B latex --papersize=a4 --charset=latin --output=pdf INSTALL-fr.sgml

INSTALL-fr.txt: INSTALL-fr.sgml
	linuxdoc -B txt --charset=latin INSTALL-fr.sgml
	cp INSTALL-fr.txt ..
clean:
	rm -f *~
	rm -f FAQ-fr.txt FAQ-fr*.html FAQ-fr.pdf FAQ-fr.dvi FAQ-fr.tex
	rm -f manuel-fr.txt manuel-fr*.html manuel-fr.pdf manuel-fr.dvi manuel-fr.tex
	rm -f INSTALL-fr.txt INSTALL-fr*.html INSTALL-fr.pdf INSTALL-fr.dvi INSTALL-fr.tex

