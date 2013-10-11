$(function () {

    updateMenu();

})

getQuotes();

function getQuotes () {

    $.ajax({
        url: "https://dl.dropboxusercontent.com/u/817108/screenclub/quotes.txt",
        context: document.body
    }).done(function(data) {

            var headlines = data.split(",");
            updateHeadline(headlines)

        });

}

function updateHeadline (headlines) {


    var index = parseInt(Math.random() * (headlines.length -1));


    $("#shuffle").shuffleLetters({
        "text": headlines[index]
    });


}


var win = jQuery(window);
win.bind('resize scroll', updateMenu);

function updateMenu () {

    // assign collabible menu
    $(".logo-header .logo").unbind("click");

    var defaultContainerMarginTop = $(".container-fixed-menu").css("margin-top");
    var menuElem =  $(".nav-section-menu");

    if (win.width() < 992 ) {


        $(".logo-header .logo").click(function () {


            menuElem.toggle(200);

            if ( menuElem.css("display") != "none") {

                $(".container-fixed-menu").removeAttr( 'style' );
                $(".nav-menu-container").removeAttr( 'style' );

            } else {

                $(".nav-menu-container").css('top', "10px");
                $(".container-fixed-menu").css("margin-top", "60px");

            }

        })

    } else {

        // show menu
       // menuElem.css("display", "block");
        menuElem.removeAttr( 'style' );
        $(".container-fixed-menu").removeAttr( 'style' );
        $(".nav-menu-container").removeAttr( 'style' );

    }


    var scrolled = win.scrollTop();

    var max = 20;
    var min =  (win.width() < 992) ? -20 : -10;
    var ratio = max * scrolled / 40;
    ratio = (max - ratio);

    var value = ( ratio < min ) ?min : ratio;

    if(win.width() < 992 ) {
        value = min;
    }

    $(".page-header").css('margin-top', value+'px');

}

