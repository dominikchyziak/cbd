// Obsługa cookies
var lang=document.documentElement.lang;

function getCookie(c_name)
{
	var i,x,y,ARRcookies=document.cookie.split(";");

	for (i=0;i<ARRcookies.length;i++) {
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");

		if (x==c_name) {
			return decodeURIComponent(y);
		}
	}
}

function setCookie(c_name, value, exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=encodeURIComponent(value) + ((exdays==null) ? "": "; expires="+exdate.toUTCString())+"; path=/; domain="+location.host;
	document.cookie=c_name + "=" + c_value;
}

function checkCookie(c_name)
{
	var t=getCookie(c_name);

	if (t!=null && t!="") {
		return true;
	}

	return false;
}

$(function() {
	if (! checkCookie('polityka_cookies')) {
		if (lang === 'pl'){
			$('body').append(
				'<div id="polityka_cookies">'
					+'<div class="cookie-inblock">'
						+'<p>' + polityka_komunikat + '</p>'
						+'<p class="pull-right"><a href="/pl/polityka-prywatnosci" class="privacy-policy">Polityka prywatności</a><a href="#" class="cookie-button">Tak, akceptuję cookies</a></p>'
					+'</div>'
				+'</div>'
			);
		} else {
			$('body').append(
				'<div id="polityka_cookies">'
					+'<div class="cookie-inblock">'
						+'<p>' + polityka_komunikat + '</p>'
						+'<p class="pull-right"><a href="/en/privacy-policy" class="privacy-policy">Privacy policy</a><a href="#" class="cookie-button">Yes, I accept cookies</a></p>'
					+'</div>'
				+'</div>'
			);
		}

		var pc = $('#polityka_cookies');

		pc.find('.cookie-button')
			.click(function(e){
				e.preventDefault();
				pc.fadeOut(300);
				setCookie('polityka_cookies', '1', 9999);
			});
	}
});
