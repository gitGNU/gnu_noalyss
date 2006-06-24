# -*- coding: latin-1 -*-
#simple python script to check bank vs. accounts.

import sys
import csv
import time
from datetime import date

#bank account input file sample line:
#"Code interne";"Date";"Description";"Débit";"Crédit"
#"OD-01-00001";"18.12.2003";"versement initial capital";6200.0000;  0.0000

DATE_OUT_FORMAT = "%d/%m/%Y"

def toDateString(date):
  return time.strftime(DATE_OUT_FORMAT, date)

class Extract:
  def __repr__(self):
    return "[%s]: %s %s" % (self.code, toDateString(self.date), self.amount)

class Operation:
  def __repr__(self):
    return "[%s]: %s D: %s C: %s" % (self.code, toDateString(self.date), self.debit, self.credit)

accountFile = sys.argv[1]
reader = csv.reader(open(accountFile, "rb"), delimiter=";")
operations = []
reader.next()
for row in reader:
  op = Operation()
  #print row
  if len(row) == 5:
    op.code, op.date, op.desc, op.debit, op.credit = row
    op.date = time.strptime(op.date, "%d.%m.%Y")
    op.debit = float(op.debit)
    op.credit = float(op.credit)
    operations.append(op)

print "loaded %s bank operations from %s" % (len(operations), accountFile)

bankExtractsFile = sys.argv[2]
reader = csv.reader(open(bankExtractsFile, "rb"), delimiter=";")
extracts = []
reader.next()
for row in reader:   
  ex = Extract()
  #print row
  ex.code, ex.date, ex.desc, ex.amount, ex.currency, ex.val_date, ex.from_account, ex.from_name, ex.com1, ex.com2, ex.ref = row
  ex.com = "%s %s" % (ex.com1, ex.com2)
  #transform 6.200,00 EUR in 6200.00 EUR
  ex.amount = float(ex.amount.replace(".", "").replace(",", "."))
  ex.date = time.strptime(ex.date, "%d/%m/%Y")
  extracts.append(ex)

print "loaded %s bank extracts from %s" % (len(extracts), bankExtractsFile)      

#now match the two sets:
#1: we want to find one operation in financial ledger per bank extract. Amount must match. 

verbose = False

if len(sys.argv) > 3:
  operationToCheck = int(sys.argv[3])  
  extracts = extracts[operationToCheck:operationToCheck+1]
  verbose = True

def matchAmount(extract, operation):
  if extract.amount > 0:
    return operation.debit == extract.amount
  else:
    return operation.credit == -extract.amount

def checkExtract(extract, operations, verbose):    
  #1: get all fin. ledger operations at that date.
  if verbose:
    print "extract: %s" % extract
  candidates = filter(lambda x: x.date == extract.date, operations)    
  errorMessage = None  
  if candidates == []:
    #it may have been posted later on in the accounting.    
    candidates = filter(lambda x: x.date > extract.date and matchAmount(extract,x), operations)    
  if verbose:
      print "candidates: %s " % candidates
  if candidates == []:
    errorMessage = "Found no ledger operations for extract: %s" % extract    
  else:
    #2: find the one.      
    matches = filter(lambda x: matchAmount(extract, x) and x.code != "ANNULE", candidates)
    if len(matches) != 1:            
      if len(matches) == 0:
        errorMessage = "Problem: no candidates for extract %s" % extract                    
      else:
        errorMessage = "Problem. Multiple candidates: %s for extract %s" % (matches, extract)
    else:
      #remove the matched from the operations list.
      operations.remove(matches[0])
  return errorMessage


errorCount = 0
  
for extract in extracts:        
  error = checkExtract(extract, operations, verbose)
  if error:
    errorCount = errorCount + 1
    print "%s \n" % error
  

print "Error count: %s" % errorCount
