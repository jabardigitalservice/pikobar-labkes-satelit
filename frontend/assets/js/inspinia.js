/*
 *
 *   INSPINIA - Responsive Admin Theme
 *   version 2.9.3
 *
 */
$(document).ready(function () {
    if (window.init_ready) return
    window.init_ready = true
    // Fast fix bor position issue with Propper.js
    // Will be fixed in Bootstrap 4.1 - https://github.com/twbs/bootstrap/pull/24092
    Popper.Defaults.modifiers.computeStyle.gpuAcceleration = false;

    // Add body-small class if window less than 768px
    if (window.innerWidth < 769) {
        $('body').addClass('body-small')
    } else {
        $('body').removeClass('body-small')
    }

    // MetisMenu
    // var sideMenu = $('#side-menu').metisMenu();

    // Collapse ibox function
    $(document).on('click', '.collapse-link', function (e) {
        e.preventDefault();
        var ibox = $(this).closest('div.ibox');
        var button = $(this).find('i');
        var content = ibox.children('.ibox-content');
        content.slideToggle(200);
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        ibox.toggleClass('').toggleClass('border-bottom');
        setTimeout(function () {
            ibox.resize();
            ibox.find('[id^=map-]').resize();
        }, 50);
    });

    // Close ibox function
    $(document).on('click', '.close-link', function (e) {
        e.preventDefault();
        var content = $(this).closest('div.ibox');
        content.remove();
    });

    // Fullscreen ibox function
    $(document).on('click', '.fullscreen-link', function (e) {
        e.preventDefault();
        var ibox = $(this).closest('div.ibox');
        var button = $(this).find('i');
        $('body').toggleClass('fullscreen-ibox-mode');
        button.toggleClass('fa-expand').toggleClass('fa-compress');
        ibox.toggleClass('fullscreen');
        setTimeout(function () {
            $(window).trigger('resize');
        }, 100);
    });

    // Close menu in canvas mode
    $(document).on('click', '.close-canvas-menu', function (e) {
        e.preventDefault();
        $("body").toggleClass("mini-navbar");
        SmoothlyMenu();
    });

    // Run menu of canvas
    // $('body.canvas-menu .sidebar-collapse').slimScroll({
    //     height: '100%',
    //     railOpacity: 0.9
    // });

    // Open close right sidebar
    $(document).on('click', '.right-sidebar-toggle', function (e) {
        e.preventDefault();
        $('#right-sidebar').toggleClass('sidebar-open');
    });

    // Initialize slimscroll for right sidebar
    // $('.sidebar-container').slimScroll({
    //     height: '100%',
    //     railOpacity: 0.4,
    //     wheelStep: 10
    // });

    // Open close small chat
    $(document).on('click', '.open-small-chat', function (e) {
        e.preventDefault();
        $(this).children().toggleClass('fa-comments').toggleClass('fa-times');
        $('.small-chat-box').toggleClass('active');
    });

    // Initialize slimscroll for small chat
    // $('.small-chat-box .content').slimScroll({
    //     height: '234px',
    //     railOpacity: 0.4
    // });

    // Small todo handler
    $(document).on('click', '.check-link', function () {
        var button = $(this).find('i');
        var label = $(this).next('span');
        button.toggleClass('fa-check-square').toggleClass('fa-square-o');
        label.toggleClass('todo-completed');
        return false;
    });

    // Append config box / Only for demo purpose
    // Uncomment on server mode to enable XHR calls
    //$.get("skin-config.html", function (data) {
    //    if (!$('body').hasClass('no-skin-config'))
    //        $('body').append(data);
    //});

    // Minimalize menu
    $(document).on('click', '.navbar-minimalize', function (event) {
        event.preventDefault();
        $("body").toggleClass("mini-navbar");
        SmoothlyMenu();

    });

    // Move right sidebar top after scroll
    $(window).scroll(function () {
        if ($(window).scrollTop() > 0 && !$('body').hasClass('fixed-nav')) {
            $('#right-sidebar').addClass('sidebar-top');
        } else {
            $('#right-sidebar').removeClass('sidebar-top');
        }
    });

    // $("[data-toggle=popover]").popover();

    // Add slimscroll to element
    // $('.full-height-scroll').slimscroll({
    //     height: '100%'
    // })
});

// Minimalize menu when screen is less than 768px
$(window).bind("resize", function () {
    if (window.innerWidth < 769) {
        $('body').addClass('body-small')
    } else {
        $('body').removeClass('body-small')
    }
});

// Fixed Sidebar
$(window).bind("load", function () {
    if ($("body").hasClass('fixed-sidebar')) {
        // $('.sidebar-collapse').slimScroll({
        //     height: '100%',
        //     railOpacity: 0.9
        // });
    }
});


// check if browser support HTML5 local storage
function localStorageSupport() {
    return (('localStorage' in window) && window['localStorage'] !== null)
}

// Local Storage functions
// Set proper body class and plugins based on user configuration
$(document).ready(function () {
    if (localStorageSupport()) {

        var collapse = localStorage.getItem("collapse_menu");
        var fixedsidebar = localStorage.getItem("fixedsidebar");
        var fixednavbar = localStorage.getItem("fixednavbar");
        var boxedlayout = localStorage.getItem("boxedlayout");
        var fixedfooter = localStorage.getItem("fixedfooter");

        var body = $('body');

        if (fixedsidebar == 'on') {
            body.addClass('fixed-sidebar');
            // $('.sidebar-collapse').slimScroll({
            //     height: '100%',
            //     railOpacity: 0.9
            // });
        }

        if (collapse == 'on') {
            if (body.hasClass('fixed-sidebar')) {
                if (!body.hasClass('body-small')) {
                    body.addClass('mini-navbar');
                }
            } else {
                if (!body.hasClass('body-small')) {
                    body.addClass('mini-navbar');
                }

            }
        }

        if (fixednavbar == 'on') {
            $(".navbar-static-top").removeClass('navbar-static-top').addClass('navbar-fixed-top');
            body.addClass('fixed-nav');
        }

        if (boxedlayout == 'on') {
            body.addClass('boxed-layout');
        }

        if (fixedfooter == 'on') {
            $(".footer").addClass('fixed');
        }
    }
});

// For demo purpose - animation css script
function animationHover(element, animation) {
    element = $(element);
    element.hover(
        function () {
            element.addClass('animated ' + animation);
        },
        function () {
            //wait for animation to finish before removing classes
            window.setTimeout(function () {
                element.removeClass('animated ' + animation);
            }, 2000);
        });
}

function SmoothlyMenu() {
    if (!$('body').hasClass('mini-navbar') || $('body').hasClass('body-small')) {
        // Hide menu in order to smoothly turn on when maximize menu
        $('#side-menu').hide();
        // For smoothly turn on menu
        setTimeout(
            function () {
                $('#side-menu').fadeIn(400);
            }, 200);
    } else if ($('body').hasClass('fixed-sidebar')) {
        $('#side-menu').hide();
        setTimeout(
            function () {
                $('#side-menu').fadeIn(400);
            }, 100);
    } else {
        // Remove all inline style from jquery fadeIn function to reset menu state
        $('#side-menu').removeAttr('style');
    }
}

// Dragable panels
function WinMove() {
    var element = "[class*=col]";
    var handle = ".ibox-title";
    var connect = "[class*=col]";
    $(element).sortable({
            handle: handle,
            connectWith: connect,
            tolerance: 'pointer',
            forcePlaceholderSize: true,
            opacity: 0.8
        })
        .disableSelection();
}

// GLOBAL FUNCTION UNTUK TAMBAH PASIEN BARU MANDIRI
function show1() {
    document.getElementById('ifcewe').style.display = 'none';
};

function show2() {
    document.getElementById('ifcewe').style.display = 'block';
};

function simselect() {
    document.getElementById('sim').style.display = 'block';
    document.getElementById('ktp').style.display = 'none';
};

function ktpselect() {
    document.getElementById('sim').style.display = 'none';
    document.getElementById('ktp').style.display = 'block';
};

function showRDT() {
    document.getElementById('ifrdt').style.display = 'none';
};

function showRDT2() {
    document.getElementById('ifrdt').style.display = 'block';
};

var inputStart, inputStop, firstKey, lastKey, timing, userFinishedEntering;
var minChars = 3;

// handle a key value being entered by either keyboard or scanner
$("#scanInput").keypress(function (e) {
    // restart the timer
    if (timing) {
        clearTimeout(timing);
    }

    // handle the key event
    if (e.which == 13) {
        // Enter key was entered

        // don't submit the form
        e.preventDefault();

        // has the user finished entering manually?
        if ($("#scanInput").val().length >= minChars) {
            userFinishedEntering = true; // incase the user pressed the enter key
            inputComplete();
        }
    } else {
        // some other key value was entered

        // could be the last character
        inputStop = performance.now();
        lastKey = e.which;

        // don't assume it's finished just yet
        userFinishedEntering = false;

        // is this the first character?
        if (!inputStart) {
            firstKey = e.which;
            inputStart = inputStop;

            // watch for a loss of focus
            $("body").on("blur", "#scanInput", inputBlur);
        }

        // start the timer again
        timing = setTimeout(inputTimeoutHandler, 500);
    }
});

// Assume that a loss of focus means the value has finished being entered
function inputBlur() {
    clearTimeout(timing);
    if ($("#scanInput").val().length >= minChars) {
        userFinishedEntering = true;
        inputComplete();
    }
};


// reset the page
$("#reset").click(function (e) {
    e.preventDefault();
    resetValues();
});

function resetValues() {
    // clear the variables
    inputStart = null;
    inputStop = null;
    firstKey = null;
    lastKey = null;
    // clear the results
    inputComplete();
}

// Assume that it is from the scanner if it was entered really fast
function isScannerInput() {
    return (((inputStop - inputStart) / $("#scanInput").val().length) < 15);
}

// Determine if the user is just typing slowly
function isUserFinishedEntering() {
    return !isScannerInput() && userFinishedEntering;
}

function inputTimeoutHandler() {
    // stop listening for a timer event
    clearTimeout(timing);
    // if the value is being entered manually and hasn't finished being entered
    if (!isUserFinishedEntering() || $("#scanInput").val().length < 3) {
        // keep waiting for input
        return;
    } else {
        reportValues();
    }
}

// here we decide what to do now that we know a value has been completely entered
function inputComplete() {
    // stop listening for the input to lose focus
    $("body").off("blur", "#scanInput", inputBlur);
    // report the results
    reportValues();
}

function reportValues() {
    // update the metrics
    if (!inputStart) {
        // clear the results
        $("#resultsList").html("");
        $("#scanInput").focus().select();
    } else {
        // prepend another result item
        var inputMethod = isScannerInput() ? "Scanner" : "Keyboard";

        $("#resultsList").append(
            "<div class='form-group row'><label class='col-md-2 col-form-label'>Nomor Sampel : </label><input class='form-control-plaintext col-md-4' type='text' name='nomorsampel[]' value='" +
            $("#scanInput").val() +
            "' readonly><button class='btn btn-sm btn-danger remove_field'><i class='uil-trash'></i></button></div>"
        )

        $("#scanInput").focus();
        document.getElementById('scanInput').value = '';
        inputStart = null;
    }
}
$(document).on('click', '.remove_field', function () {
    $(this).parent().remove();
});

function dinkesotheroptionselect(that) {
    if (that.value == "Other") {
        document.getElementById("inputdaerahlain").style.display = "block";
    } else {
        document.getElementById("inputdaerahlain").style.display = "none";
    }
}

function rsotheroptionselect(that) {
    if (that.value == "Other") {
        document.getElementById("inputrslain").style.display = "block";
    } else {
        document.getElementById("inputrslain").style.display = "none";
    }
}