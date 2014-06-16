var win = jQuery(window);
win.bind('resize scroll', updateMenu);
var isMobile;
var hasMobileChanged = false;
var bColumnFixed = false;

var hr = (new Date()).getHours();

if ( hr >= 12 || hr <= 9) {
    $("head link[rel='stylesheet']").last().after("<link rel='stylesheet' href='"+assetUrl+"bundles/scrclubscrclub/css/skin/dark.css' type='text/css' media='screen'>");
    $(".logo").find("img").attr("src", assetUrl+"bundles/scrclubscrclub/images/logo.png");
    $("#prev").find("img").attr("src", assetUrl+"bundles/scrclubscrclub/images/prev.png");
    $("#next").find("img").attr("src", assetUrl+"bundles/scrclubscrclub/images/next.png");

}

$(document).ready(function () {

    //animateMenu();
    updateMenu();

    $("#home-link").hover(function () {

        getQuotes("https://dl.dropboxusercontent.com/u/817108/screenclub/home.txt");

    }, function () {
        getQuotes("https://dl.dropboxusercontent.com/u/817108/screenclub/quotes.txt");
    })

})

getQuotes("https://dl.dropboxusercontent.com/u/817108/screenclub/quotes.txt");

function animateMenu () {

    //console.log("coucou");

    $('.img-content').hover(function () {

        $(this).find(".white-block").animate({backgroundColor: '#737373', color : "#FFF"}, 100)

    }, function () {

        $(this).find(".white-block").animate({backgroundColor: '#FFF', color : "#000"}, 100)

    })

}

function getQuotes (url) {

    $.ajax({
        url: url,
        context: document.body
    }).done(function(data) {

            var headlines = data.split(",");
            updateHeadline(headlines)

        });

}

function updateHeadline (headlines) {

    // if we're in mobile mode, shuffle calls the menu
    var text;
    if (win.width() < 992 ) {

        text = "MENU";

    } else {

        var index = parseInt(Math.random() * (headlines.length -1));
        text = headlines[index];
    }

    var index = parseInt(Math.random() * (headlines.length -1));
    $("#shuffle").shuffleLetters({
        "text": text
    });


}


function updateMenu () {

    // assign collabible menu
    $("#shuffle").unbind("click");

    var defaultContainerMarginTop = $(".container-fixed-menu").css("margin-top");
    var menuElem =  $(".nav-section-menu");

    if (win.width() < 992 ) {


        $("#shuffle").click(function () {


            menuElem.toggle(200);

            if ( menuElem.css("display") != "none") {

                $(".container-fixed-menu").removeAttr( 'style' );
                $(".nav-menu-container").removeAttr( 'style' );

            } else {

                $(".nav-menu-container").css('top', "10px");
                $(".container-fixed-menu").css("margin-top", "60px");

            }

        })

        isMobile = true;

    } else {

        // show menu
       // menuElem.css("display", "block");
        menuElem.removeAttr( 'style' );
        $(".container-fixed-menu").removeAttr( 'style' );
        $(".nav-menu-container").removeAttr( 'style' );



        isMobile = false;

    }

    if(isMobile != hasMobileChanged)
        getQuotes("https://dl.dropboxusercontent.com/u/817108/screenclub/quotes.txt");

    hasMobileChanged = isMobile;

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

    if(win.width() >= 992 ) {
        $("#column-project").css('margin-top', value+'px');

        var currentPos = $("#column-project").css('position');
        $("#column-project").css('position', 'static');

        var position = $("#column-project").offset().top;
        var height =  $("#column-project").height();
        //$("#column-project").css('position', currentPos);

        /*
        if (bColumnFixed && position + height > win.height()) {
            $("#column-project").css('position', 'static');
            $("#column-project").css('width', '');
            $("#column-project").css('padding-right', '');
            bColumnFixed = false;
        } else {
            $("#column-project").css('position', 'fixed');
            $("#column-project").css('width', '25%');
            $("#column-project").css('padding-right', '45px');
            bColumnFixed = true;
        }
        */

    } else {
        bColumnFixed = false;
        $("#column-project").css('margin-top', '0px');
        $("#column-project").css('width', '');
        $("#column-project").css('position', 'static');
        $("#column-project").css('padding-right', '');
    }

    //




}

