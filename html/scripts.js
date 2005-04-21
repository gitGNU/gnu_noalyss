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

