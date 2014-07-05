var _ = {
	enterCaptcha: "Пожалуйста, введите капчу.",
}
var Utf8 = {
	encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
		for (var n = 0; n < string.length; n++) {
			var c = string.charCodeAt(n);
			if (c < 128) {
				utftext += String.fromCharCode(c);
			} else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			} else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
		}
		return utftext;
	},
	decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
		while ( i < utftext.length ) {
			c = utftext.charCodeAt(i);
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			} else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			} else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
		}
		return string;
	}
}
var cookie = {
	set: function (name, value, days) {
		if (name) {
			if(days) {
				var date = new Date();
				date.setTime(date.getTime()+(days*24*60*60*1000));
				var expires="; expires="+date.toGMTString();
			} else {
				expires = "";
			}
			document.cookie=name+"="+value+expires+"; path=/";
		} else {
			return null;
		}
	},
	get: function (name) {
		with(document.cookie) {
			var regexp = new RegExp("(^|;\\s+)"+name+"=(.*?)(;|$)");
			var hit = regexp.exec(document.cookie);
			if(hit && hit.length>2) return Utf8.decode(unescape(replaceAll(hit[2],'+','%20')));
			else return '';
		}
	}
}
function popupMessage(text, delay) {
	if (delay == null) delay = 1000;
	if ($('#popupMessage').get() == '') {
		$('body').children().last().after('<div id="popupMessage"></div>');
		$('#popupMessage').hide();
	}
	$('#popupMessage').html("<span class=\"pm_text\">" + text + "</span>");
	$('#popupMessage').fadeIn(150).delay(delay).fadeOut(300);
}
function AddTagToMsg(sBegin,sEnd) {
	textarea = document.getElementById( 'message' );
	if( !textarea ) return;
	if( document.selection ) {
		textarea.focus();
		sel = document.selection.createRange();
		sel.text = sBegin + sel.text + sEnd;
	} else if( textarea.selectionStart || textarea.selectionStart == '0') {
		textarea.focus();
		var startPos = textarea.selectionStart;
		var endPos = textarea.selectionEnd;
		textarea.value = textarea.value.substring(0, startPos) + sBegin + textarea.value.substring(startPos, endPos) + sEnd + textarea.value.substring( endPos, textarea.value.length );
	} else {
		textarea.value += sBegin + sEnd;
	}
	return false;
}
function insert(text) {
	var textarea=document.forms.formpost.message;
	if(textarea) {
		if(textarea.createTextRange && textarea.caretPos) { // IE 
			var caretPos=textarea.caretPos;
			caretPos.text=caretPos.text.charAt(caretPos.text.length-1)==" "?text+" ":text;
		} else if(textarea.setSelectionRange) { // Firefox 
			var start=textarea.selectionStart;
			var end=textarea.selectionEnd;
			textarea.value=textarea.value.substr(0,start)+text+textarea.value.substr(end);
			textarea.setSelectionRange(start+text.length,start+text.length);
		} else {
			textarea.value+=text+" ";
		}
	textarea.focus();
	}
}
function youtube(idvideo){
	$('#'+idvideo).replaceWith('<iframe width="360" height="228" style="vertical-align:top;" src="http://www.youtube.com/embed/'+idvideo+'?autoplay=1&theme=light" frameborder="0" allowfullscreen></iframe>');
}
function set_inputs(){
	var name = $('input[name=name]');
	if(!name.value) name.value = cookie.get("name");
}
function replaceAll(a, b, c){
	var d = a.indexOf(b);
	while (d > -1){
		a = a.replace(b, c);
		d = a.indexOf(b)
	}
	return a
}

$(function() {
	$("#captchaimg").click(function() {
		captchaReload();
	});
	$('#form').submit(function(event) {
		if ($('input[name=captcha]').val() == '') {
			$('#popupMessage').remove();
			popupMessage(_.enterCaptcha);
			return false;
		}
		return true;
	});
});