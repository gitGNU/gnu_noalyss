# Makefile for creating the doc
# html, txt format
# $Revision$

.PHONY: all html .pdf clean .txt

all: manuel-fr.pdf html FAQ-fr.pdf INSTALL-fr.pdf INSTALL-fr.txt FAQ-fr.txt

html:
	linuxdoc -B html --charset=latin FAQ-fr.sgml
	linuxdoc -B html --charset=latin INSTALL-fr.sgml
	linuxdoc -B html --charset=latin manuel-fr.sgml
FAQ-fr.pdf: FAQ-fr.sgml
	linuxdoc -B latex --papersize=a4 --charset=latin --output=pdf FAQ-fr.sgml
manuel-fr.pdf:manuel-fr.sgml
	linuxdoc -B latex --papersize=a4 --charset=latin --output=pdf manuel-fr.sgml
INSTALL-fr.pdf:INSTALL-fr.sgml
	linuxdoc -B latex --papersize=a4 --charset=latin --output=pdf INSTALL-fr.sgml
FAQ-fr.txt: FAQ-fr.sgml
	linuxdoc -B txt --charset=latin FAQ-fr.sgml
	cp FAQ-fr.txt ..
INSTALL-fr.txt: INSTALL-fr.sgml
	linuxdoc -B txt --charset=latin INSTALL-fr.sgml
	cp INSTALL-fr.txt ..
clean:
	rm -f *~
	rm -f FAQ-fr.txt FAQ-fr*.html FAQ-fr.pdf FAQ-fr.dvi FAQ-fr.tex
	rm -f manuel-fr.txt manuel-fr*.html manuel-fr.pdf manuel-fr.dvi manuel-fr.tex
	rm -f INSTALL-fr.txt INSTALL-fr*.html INSTALL-fr.pdf INSTALL-fr.dvi INSTALL-fr.tex

