var countChecked = function() {
    var caja = $(".caja")
    caja.html("")
    $('input.nom_ciudades:checked').each(function(index) {
        caja.append("<div class='cajita' id='ciudad_" + $(this).attr("cod") + "'>\n\
        <div class='cajita_left'>"+ $(this).val() + " <input type='radio' value='"+$(this).attr("cod")+"' name='marc_ciudades' /></div>\n\
        <div class='cajita_right'></div>\n\
        </div>")
    });
};

$(function() {

    // grab the initial top offset of the navigation 
    var sticky_navigation_offset_top = $('#sticky_navigation').offset().top;
    
    // our function that decides weather the navigation bar should have "fixed" css position or not.
    var sticky_navigation = function(){
        var scroll_top = $(window).scrollTop(); // our current vertical position from the top
        
        // if we've scrolled more than the navigation, change its position to fixed to stick to top,
        // otherwise change it back to relative
        if (scroll_top > sticky_navigation_offset_top) { 
            $('#sticky_navigation').css({
                'position': 'fixed',
                'top':0,
                'width':'98%'
            });
        } else {
            $('#sticky_navigation').css({
                'position': 'relative'
            }); 
        }   
    };
    
    // run our function on load
    sticky_navigation();
    
    // and run it again every time you scroll
    $(window).scroll(function() {
        sticky_navigation();
    });
    
});