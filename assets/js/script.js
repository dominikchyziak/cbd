$(document).ready(function(){

Modernizr.on('webp', function(result){});

openmenu = 1;

function przeloncznik_menu()
{
   $('.menu-strony-przycisk-mobilny').click(function(){

        
          $('.menu-strony-nav').removeClass('menu-strony-nav').addClass('menu-strony-nav-open');
          $('.naglowek').css('position','absolute');
          $( window ).scrollTop(0);
          openmenu = 2;        

   });
   
   $('.menu-strony-zam span').click(function(){
      
        $('.menu-strony-nav-open').removeClass('menu-strony-nav-open').addClass('menu-strony-nav');
        openmenu = 1;
   });

   $('.naglowek-menu-zaw-info-p').click(function(){
      
      $('.naglowek-menu-zaw-info-p-pod').toggle();
        
   });

   


   
  
}


przeloncznik_menu();





function belka_zmniejszyj()
{

      $( window ).scroll(function(){

        zscrol = $(this).scrollTop();

        szerw = $(window).width();



        if(zscrol > 100)
        {
           $('.naglowek').removeClass('naglowek').addClass('naglowek-f');
      


        }
        else
        {
           $('.naglowek-f').removeClass('naglowek-f').addClass('naglowek');
        
        }
        

     });
}

belka_zmniejszyj();


$('.cycle-slideshow').on( 'cycle-before', function(event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag) {
                             
     
     if(optionHash.currSlide == 0)
     {
       var ktin = 1
     }
     if(optionHash.currSlide == 1)
     {
       var ktin = 2
     }
     if(optionHash.currSlide == 2)
     {
       var ktin = 3
     }
     if(optionHash.currSlide == 3)
     {
       var ktin = 0
     } 

     $('body .baner-strony-k').removeClass('baner-strony-poz-akt').addClass('baner-strony-poz'); 

     $('body .baner-strony-k').each(function(index){

        if(ktin ==  index)
        {
           $(this).removeClass('baner-strony-poz').addClass('baner-strony-poz-akt');
        }

     })    
     
})


szerokoscoknastart = $(window).width();

$(window).bind('resize', function(e)
{
    window.resizeEvt;
    $(window).resize(function()
    {
        clearTimeout(window.resizeEvt);
        window.resizeEvt = setTimeout(function()
        {


            if(szerokoscoknastart != $(window).width())
            {        
              
              
             
            }


         }, 50);
    });
});

 



   
});