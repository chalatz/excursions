(function($){

    var waitForFinalEvent = (function () {
        var timers = {};
        return function (callback, ms, uniqueId) {
          if (!uniqueId) {
            uniqueId = "Don't call this twice without a uniqueId";
          }
          if (timers[uniqueId]) {
            clearTimeout (timers[uniqueId]);
          }
          timers[uniqueId] = setTimeout(callback, ms);
        };
      })();

    // Toggles the sliding of the mobile menu
    var handle_menu = function() {

        var menu_burger = $('#site-nav__burger')
            site_nav = $('#site-nav'),
            site_menu_items = $('#site-nav__items');

        menu_burger.on('click', function(e){
            site_nav.toggleClass('site-nav--mobile');
            site_menu_items.toggleClass('site-nav__items--opened');
            site_menu_items.slideToggle();
        });

    };

    // Toggles the sliding of the mobilemenu's subitems
    var handle_submenu = function() {
    
        var parent_item = $('.site-nav__item--with-sublinks');
        
        if(window.innerWidth < 990) {

            parent_item.on('click', function(e){

                // e.preventDefault();

                var $this = $(this),
                    submenu = $this.find('.site-nav__subitems'),
                    chevron_icon = $this.find('.site-nav__chevron').find('.chevron-icon');

                $this.children().on('click', function(e){
                    e.stopPropagation();
                });

                chevron_icon.toggleClass('rotate-180');

                submenu.toggleClass('site-nav__subitems--opened');
                
            });            
            
        } else {
            
            parent_item.hoverIntent(function(){
                var $this = $(this),
                submenu = $this.find('.site-nav__subitems');
                
                submenu.toggleClass('site-nav__subitems--opened');                

            });

        }

    }

    var fill_year = function(){
        var current_year = (new Date()).getFullYear(),
            year_input = $('#year-input');

        for (var i = 0; i < 6; i++) {
            var the_option = current_year + i,
                the_option_text = '<option value="' + the_option + '">' + the_option + '</option>';
            year_input.append(the_option_text);
        }            

    };

    var goto_top = function(){

        $(window).scroll(function(event){
            var scroll = $(window).scrollTop();
            if (scroll >= 1500) {
              $(".go-top").addClass("show");
            } else {
              $(".go-top").removeClass("show");
            }
          });
  
          $('#scrollBtn').click(function(event){
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            event.preventDefault();
          });

    };    

    
    // $(window).resize(function () {
    //     waitForFinalEvent(function(){
    //         handle_submenu();
    //     }, 600, "some unique string");
    // });

    var getUrlParam= function(name) {
        var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return (results && results[1]) || undefined;
    }

    var handle_contact_form_post = function(){

        var form_status = getUrlParam('contact-form-sent');

        if (form_status === 'success') {
            var msg_div = $('#msg'),
                msg_content = 
                    '<span id="msg-close" class="msg__close"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#0000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></span>' + 
                    '<h3 class="msg__title">Email Sent!</h3>' +
                    '<p>Thank you for the interest that you\'ve shown in our services. Your request was sent successfully and will be processed soon.</p>';

            msg_div.html(msg_content);

            msg_div.show();
                                    
            $(msg_div).on('click', '#msg-close', function(e){
                $(msg_div).fadeOut(300);
            });
        }

        if (form_status === 'fail') {
            var msg_div = $('#msg'),
                msg_content = 
                    '<span id="msg-close" class="msg__close"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#0000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></span>' + 
                    '<h3 class="msg__title">Something went wrong</h3>' +
                    '<p class="centered">Please try to fill the form again.</p>' + 
                    '<p class="centered">If the problem persists, please try to contact us directly at <a href="mailto:request@RhodesShoreExcursions.com">request@RhodesShoreExcursions.com</p>' + 
                    '<p class="centered"><a id="goto-form" href="#request">Return to the Form</p>';
            
            msg_div.addClass('msg--fail');

            msg_div.html(msg_content);

            msg_div.show();
                                    
            $(msg_div).on('click', '#msg-close', function(e){
                $(msg_div).fadeOut(300);
            });

            $('#goto-form').on('click', function(e){
                e.preventDefault();
                $(msg_div).hide();
                $('html, body').animate({scrollTop: $('#request').offset().top -100 }, 'slow');
            });
        }
        
        if (form_status === 'validation-error') {
            var msg_div = $('#msg'),
                msg_close = $('#msg-close'),
                msg_content = 
                '<span id="msg-close" class="msg__close"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#0000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></span>' + 
                    '<h3 class="msg__title">Missing field(s)</h3>' +
                    '<p class="centered">Please try to fill all the fields before submitting.</p>' + 
                    '<p class="centered">If the problem persists, please try to contact us directly at <a href="mailto:request@RhodesShoreExcursions.com">request@RhodesShoreExcursions.com</p>';
            
            msg_div.addClass('msg--fail')

            msg_div.html(msg_content);

            msg_div.show();
                                    
            $(msg_div).on('click', '#msg-close', function(e){
                $(msg_div).fadeOut(300);
            });
        }
        
    };

    
    handle_menu();
    handle_submenu();

    fill_year();

    goto_top();

    handle_contact_form_post();

})(jQuery);