/*
 *	 UK COOKIE POLICY NOTICE
 *   Written by Lee Jones (mail@leejones.me.uk)
 *   Project Home Page: https://github.com/prolificjones82/uk_cookie_policy_notice
 *   Released under GNU Lesser General Public License (http://www.gnu.org/copyleft/lgpl.html)
 *
 * 	 Please submit all problems or questions to the Issues page on the projects GitHub page:
 *   https://github.com/prolificjones82/uk_cookie_policy_notice
 *
 *
 *   jQuery Cookie Plugin v1.3.1
 *   https://github.com/carhartl/jquery-cookie
 *
 * 	 Copyright 2013 Klaus Hartl
 *   Released under the MIT license
 */
(function(factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as anonymous module.
        define(['jquery'], factory);
    } else {
        // Browser globals.
        factory(jQuery);
    }
}(function($) {
    var pluses = /\+/g;

    function decode(s) {
        if (config.raw) {
            return s;
        }
        return decodeURIComponent(s.replace(pluses, ' '));
    }

    function decodeAndParse(s) {
        if (s.indexOf('"') === 0) {
            // This is a quoted cookie as according to RFC2068, unescape...
            s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
        }
        s = decode(s);
        try {
            return config.json ? JSON.parse(s) : s;
        } catch (e) {}
    }
    var config = $.cookie = function(key, value, options) {
        // Write
        if (value !== undefined) {
            options = $.extend({}, config.defaults, options);
            if (typeof options.expires === 'number') {
                var days = options.expires,
                    t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }
            value = config.json ? JSON.stringify(value) : String(value);
            return (document.cookie = [
                config.raw ? key : encodeURIComponent(key), '=',
                config.raw ? value : encodeURIComponent(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : ''
            ].join(''));
        }
        // Read
        var cookies = document.cookie.split('; ');
        var result = key ? undefined : {};
        for (var i = 0, l = cookies.length; i < l; i++) {
            var parts = cookies[i].split('=');
            var name = decode(parts.shift());
            var cookie = parts.join('=');
            if (key && key === name) {
                result = decodeAndParse(cookie);
                break;
            }
            if (!key) {
                result[name] = decodeAndParse(cookie);
            }
        }
        return result;
    };
    config.defaults = {};
    $.removeCookie = function(key, options) {
        if ($.cookie(key) !== undefined) {
            // Must not alter options, thus extending a fresh object...
            $.cookie(key, '', $.extend({}, options, {
                expires: -1
            }));
            return true;
        }
        return false;
    };
}));
/* END OF COOKIE PLUGIN */
/* START OF COOKIE POLICY */
(function($) {
    $.fn.cookieNotify = function(options) {
        // plugin options
        var options = $.extend({
            text: 'We use cookies on this website, by continuing to be here we will take it you agree to us using them.', // information text
            btnText: 'I Agree', // agree button text
            bgColor: '#CCC', // main info bar background colour, accepts HEX or RGBA
            textColor: '#000', // main info bar text colour, accepts HEX or RGBA
            btnColor: '#000', // button background colour, accepts HEX or RGBA
            btnTextColor: '#FFF', // button text colour, accepts HEX or RGBA
            position: 'top', // info bar position
            leftPadding: '0', // info bar left spacing, accepts px or % values
            rightPadding: '0', // info bar right spacing, accepts px or % values
            hideAnimation: 'fadeOut', // on click hide animation, options are fadeOut, slideUp
            reload: false // force a page reload after save
        }, options);
        // create stylesheet
        $('head').append('<style>#cookie_container { display: none; position: fixed; ' + options.position + ': 0; left: ' + options.leftPadding + '; 	right: ' + options.rightPadding + '; z-index: 999; padding: 10px; background-color:' + options.bgColor + '; color:' + options.textColor + '; } .cookie_inner { width: 90%; margin: 0 auto; } .cookie_inner p { margin: 0; padding-top: 4px; } #setCookie { float: right; padding: 5px 10px; text-decoration: none; background-color: ' + options.btnColor + '; color: ' + options.btnTextColor + '; } #setCookie:hover { background-color: #AAAAAA !important; color: #000000 !important; }</style>');
        // create popup elements
        $('<div id="cookie_container"><div class="cookie_inner"><a id="setCookie" href="#">' + options.btnText + '</a><p>' + options.text + '</p></div></div>').appendTo(this);
        // set cookie function
        $(document.body).on('click', '#setCookie', function(e) {
            e.preventDefault();
            $.cookie('cookie_policy', 'true', {
                expires: 365,
                path: '/'
            });
            if (options.hideAnimation == 'fadeOut') {
                $('#cookie_container').fadeOut();
            } else if (options.hideAnimation == 'slideUp') {
                $('#cookie_container').slideUp();
            }
            if (options.reload == true) {
                window.location.reload();
            }
        });
        // detect cookie
        $(this).ready(function() {
            var cookie = $.cookie('cookie_policy');
            if (!cookie) {
                $('#cookie_container').show();
            }
        });
    }
    $.getJSON($('base')[0].href + 'fetchcookiepolicy', function(data) {
        console.log(data);
      $('body').cookieNotify({btnText: data.CookiePolicyButtonTitle, text: data.CookiePolicyDescription, position: data.CookiePolicyPosition, reload: data.Reload});
    });
}(jQuery));
