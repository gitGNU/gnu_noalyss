//scripts library.

// Set the focus to the specified field,
// and select it if requested by SelectIt

function GetID(ID) {
  if (document.all) {
    return document.all[ID];
  } else if (document.getElementById) {
    return document.getElementById(ID);
  } else {
    return undefined;
  }
}
function GetFormField(Field) {
  var elem = document.forms ? document.forms[0] : false;
  return elem.elements ? elem.elements[Field] : false;
}

function AttachEvent(Obj, EventType, Function, Capture) {
  Capture = Capture || false;
  if (Obj == document && !document.all) { Obj = window; }
  if (Obj.addEventListener) {
    Obj.addEventListener(EventType, Function, Capture);
  } else if (Obj.attachEvent) {
    Obj.attachEvent("on" + EventType, Function);
  }
}

function SetFocus(Field,SelectIt) {
  var elem = GetFormField(Field);
  if (!elem) { elem = GetID(Field); }

  if (elem) {
    elem.focus();
    if (SelectIt > 0 && elem.select) {
      elem.select();
    }
    var Type = elem.type ? elem.type : false;
    if (SelectIt <= 0 && Type && (Type == "text" || Type == "textarea")) {
      // IE version
      var TxtPos = SelectIt < 0 ? elem.value.length : 0;
      if (elem.createTextRange) {
        var rng = elem.createTextRange()
        rng.collapse();
        rng.moveStart("character", TxtPos);
        rng.select();
      // Moz version
      } else if (elem.setSelectionRange) {
        elem.setSelectionRange(TxtPos,TxtPos);
        elem.focus();
      }
    }
  }
  return true;
}

function HandleSubmit(e) {
  SubmitButton = GetID('SubmitButton');
  var code = e.charCode || e.keyCode;
  if ( (code == 13) && e.ctrlKey ) 
  {     
    SubmitButton.click();    
    return true;
  }
}
