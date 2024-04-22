/*RESPOSNIVE NAVBAR PART USING JQUERY */
$(document).ready(function(){
    $('.hm-menu').click(function(){
        $('header').toggleClass('h-100');
        $('.hm-menu span').toggleClass('hm-100');
        $('html').toggleClass('over-x');
    });
     
     $('header nav a').click(function(){
        $('header').removeClass('h-100');
        $('.hm-menu span').removeClass('hm-100');
         $('html').removeClass('over-x');
    });
     
});
