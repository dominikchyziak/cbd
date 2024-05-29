$(document).ready(function(){
    $('.sekcja-aktualnosci-poz-zaw p').matchHeight();
    $('.sekcja-aktualnosci-poz-nag').matchHeight();
    $('.menu-strony-podmenu span').matchHeight();
    $('.sekcja-aktualnosci-poz-tekst').matchHeight();
    $('.sekcja-produkty-poz').matchHeight();
    $('.sekcja-produkty-poz-tekst-cena').matchHeight();
    $('.produkt-opis-cechy .row > div').matchHeight();
    $('.sekcja-aktualnosci-blok-tekst').matchHeight();
    $('.sekcja-produkty-blok-opis').matchHeight();
    $('.sekcja-produkty-blok-cena').matchHeight();
    $('.sekcja-produkty-blok-przycisk .przycisk').matchHeight();
//
//        window.setTimeout(function(){
//            equalize_navbar();
//            autocomplete_resize();
//        }, 700);
      $('.tab-content table').addClass('table table-striped'); 
     //new WOW().init();
});

//Newsletter
$(document).ready(function(){
    $('#newsletter_form').submit(function(e){
        e.preventDefault();
        if($(this).find('input[name="rodo"]').is(":checked")){   
            var email = $('#newsletter_email').val();
            var rodo = $(this).find('input[name="rodo"]').is(":checked");
            $.ajax({
                 url: base_url+'/newsletter/subscribe',
                 type: 'POST',
                 dataType: 'JSON',
                 data: {
                     email: email,
                     rodo: rodo
                 },
                 success: function(res){
                     //console.log(res);
                     if(res[0] == '0'){
                         toastr.error(res[1]);
                     } else if(res[0] == '1'){
                         toastr.success(res[1]);
                     }
                 }
             }); 
        }
        else{
			var lang = document.documentElement.lang;
			if (lang === "pl"){
				toastr.error("Checkbox musi być zaznaczony.");
			} else {
				toastr.error("Checkbox must be checked.");
			};
        }
        

    });
});


function equalize_navbar(){
    var heights5 = $(".menu-strony-nav-produkt").map(function() {
            return $(this).height();
        }).get(),
                maxHeight5 = Math.max.apply(null, heights5);
        $(".menu_image").css('min-height', maxHeight5);
        $(".menu-strony-nav-subcategories").css('min-height', maxHeight5);
}

function autocomplete_resize(){
    var width5 = $('#szukajka-form').width();
    $('.easy-autocomplete').width(width5);
}
//equalizer
$(document).ready(function () {
    $(window).on('load', function () {
        equalize();
        setInterval(function () {
            equalize();
        }, 1000);
    });
});

function equalize(){
    var heights = $(".equalizer").map(function() {
            return $(this).height();
        }).get(),

        maxHeight = Math.max.apply(null, heights);

        $(".equalizer").height(maxHeight);

        var heights2 = $(".equalizer2").map(function() {
            return $(this).height();
        }).get(),

        maxHeight2 = Math.max.apply(null, heights2);

        $(".equalizer2").height(maxHeight2);

        var heights3 = $(".equalizer3").map(function() {
            return $(this).height();
        }).get(),

        maxHeight3 = Math.max.apply(null, heights3);

        $(".equalizer3").height(maxHeight3);
}

//autocomplete do szukajki
var options = {
//	url: function(phrase) {
//            s = phrase;
//            s = s.replace(/ę/ig,'e');
//              s = s.replace(/ż/ig,'z');
//              s = s.replace(/ó/ig,'o');
//              s = s.replace(/ł/ig,'l');
//              s = s.replace(/ć/ig,'c');
//              s = s.replace(/ś/ig,'s');
//              s = s.replace(/ź/ig,'z');
//              s = s.replace(/ń/ig,'n');
//              s = s.replace(/ą/ig,'a');
//              s = s.replace(/ /ig, 'xspacex');
//		return base_url+'/Wyszukiwarka/ajax_search_prompt/' + s;
//	},
    url: function(phrase){
        return base_url+'/Wyszukiwarka/ajax_search_prompt';
    },
    ajaxSettings: {
    dataType: "json",
    method: "POST",
    data: {
      dataType: "json"
    }
  },

  preparePostData: function(data) {
    data.phrase = $("#szukajka").val();
    return data;
  },
	getValue: "name",

        template: {
		type: "custom",
		method: function(value, item) {
			return "<div class='row'><div class='col-xs-4'><img src='" + item.icon + "' /></div><div class='col-xs-8'>" + item.name + "</div></div>";
		}
	},
        
        list: {
            onChooseEvent: function(){
                setTimeout(function(){
                    document.getElementById("szukajka-form").submit();
                }, 50);
                
            }
        },
        requestDelay: 200
        
};

$("#szukajka").easyAutocomplete(options);


var options2 = {
//	url: function(phrase) {
//            s = phrase;
//            s = s.replace(/ę/ig,'e');
//              s = s.replace(/ż/ig,'z');
//              s = s.replace(/ó/ig,'o');
//              s = s.replace(/ł/ig,'l');
//              s = s.replace(/ć/ig,'c');
//              s = s.replace(/ś/ig,'s');
//              s = s.replace(/ź/ig,'z');
//              s = s.replace(/ń/ig,'n');
//              s = s.replace(/ą/ig,'a');
//              s = s.replace(/ /ig, 'xspacex');
//		return base_url+'/Wyszukiwarka/ajax_search_prompt/' + s;
//	},
    url: function(phrase){
        return base_url+'/Wyszukiwarka/ajax_search_prompt';
    },
    ajaxSettings: {
    dataType: "json",
    method: "POST",
    data: {
      dataType: "json"
    }
  },

  preparePostData: function(data) {
    data.phrase = $("#szukajka_main").val();
    return data;
  },
	getValue: "name",

        template: {
		type: "custom",
		method: function(value, item) {
			return "<div class='row'><div class='col-xs-4'><img src='" + item.icon + "' /></div><div class='col-xs-8 i-name'>" + item.name + "</div></div>";
		}
	},
        
        list: {
            onChooseEvent: function(){
                setTimeout(function(){
                    document.getElementById("szukajka-form-main").submit();
                }, 50);
                
            }
        },
        requestDelay: 200
        
};

$("#szukajka_main").easyAutocomplete(options2);
//$(document).ready(function(){
//    $('.opis-zastosowanie table').addClass('table').wrap('<div class="table-responsive wow zoomIn"></div>');
//    $('.opis-zastosowanie h2').addClass('wow fadeInLeft');
//});



$(function(){
        
    if($('.carousel-product li').length < 2){
        $('.carousel-product .prev-box, .carousel-product .next-box').hide();
    }
    
    if ($('body').width() > 767) {
        $('.carousel-product ul').bxSlider({
            nextSelector: '.carousel-product .next-box',
            prevSelector: '.carousel-product .prev-box',	
            minSlides: 1,
            maxSlides: 1,
            moveSlides: 1,
            slideMargin: 0,
            shrinkItems: true,
            touchEnabled: true,   
            auto: true
        });
    }
    
    if ($('body').width() < 767) {
        $('.carousel-product ul').bxSlider({
            nextSelector: '.carousel-product .next-box',
            prevSelector: '.carousel-product .prev-box',	
            minSlides: 1,
            maxSlides:1,
            moveSlides: 1,
            slideMargin: 0,
            shrinkItems: true,      
            touchEnabled: true,   
            auto: true
        });
    }  
    
});

