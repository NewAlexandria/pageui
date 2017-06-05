rdr = {
    doc: document,
    win: null,
    pos: {top: 4, left: null},
    wName: {"sid": ""},
    // pageThumb: {'idx':0, 'img':[]},
    pageThumb: {idx: 0, entry: [{img:{}}], type:'page'},
    image: {idx: 0, entry: [], type: 'image'}, //{'img':{}, 'title':''}
    video: {idx: 0, entry: [], type: 'video'}, //{'img':{}, 'obj':'', 'title':''}
    minDisplayHeight: 400,
    minFrameHeight: 220,
    barHeight: 36,
    fullHeight: 509,
    curThumb: null,
    wider: 90, //150px is base
    higher: 35, //overerage for box-shadow
    baseUrl: '../',
    // baseUrl: 'http://localhost:4242/',
    tabIndex: 1003,
    expandTimer: null,
    scrape: [{w:160, h:160},{w:70, h:70},{w:20, h:20}],
    shareMax: 25,
    cssDrag: '',

    evalJSON: function(str) {
        var obj;
        try {
            obj = eval('(' + str + ')');
        }
        catch(e) { obj = rdr.wName }
        return obj;
    },
    setWName: function(o) {
        window.name = '{"sid":"'+o['sid']+'"}';
    },
    getWName: function(key) {
        var w = this.evalJSON(window.name);
        return w[key] || '';
    },
    loadScript: function( path, callback ) {
        var h = document.getElementsByTagName('head')[0] || document.documentElement;
        var s = document.createElement('script');
        s.setAttribute('type', 'text/javascript');
        s.setAttribute('src', path);
        if ( callback ) {
            var done = false;
            // Attach handlers for all browsers
            s.onload = s.onreadystatechange = function(){
                if ( !done && (!this.readyState || 
                        this.readyState == 'loaded' || this.readyState == 'complete') ) {
                    done = true;
                    setTimeout(callback, 13);
                    h.removeChild( s );
                }
            };
        }
        h.appendChild(s);
    },
    atmosphereTest: function() {
        // this.debug('atmosphereTest')
        if ( typeof Prototype == 'object' ? Prototype.Version : '' ) {
        // if ( false ) {
            // remove Prototype lib
            try {
                var str = '';
                var reverse = Array.prototype._reverse;
                for (var m in []) { str+=('\n[] '+m); delete Array.prototype[m];};
                if (reverse) {Array.prototype.reverse = reverse;}
                for (var m in Function) {str+=('\nFunction '+m); delete Function.prototype[m];};
                for (var m in {}) {str+=('\nObject '+m); delete Object.prototype[m];};
                // this.debug(str);
            }
            catch(e) {
                alert(str + '\n' + e);
            }
        }
        var d=document,l=d.location,e=encodeURIComponent,s;
        rdr.baseUrl = (s = d.getElementById('rdr-script')) ? (s.src.match(/http:\/\/[^\/]*\//) || [rdr.baseUrl])[0] : rdr.baseUrl;
        // NonHtml test
        if ( d.getElementsByTagName('html').length + d.getElementsByTagName('frameset').length == 0 ) {
        // if (false) {
            // media fallback
            var t=rdr.baseUrl+'bookmark/media',p='?u='+e(l.href)+'&v=2&adv=2',u=t+p;
            var dom = d.getElementsByTagName('body')[0] || d.documentElement;
            l.href=u+'&html=' + e(dom.innerHTML);
        }
        else {
            // this.debug('cookies: '+document.cookie)
            var headID = document.getElementsByTagName('head')[0];
            if ( ! headID ) {
                rdr.malformedPage = true;
                var head = document.createElement('head');
                document.getElementsByTagName('html')[0].appendChild(head);
                headID = document.getElementsByTagName('head')[0];
            }
            var cssNode = document.createElement('link');
            cssNode.type = 'text/css';
            cssNode.id = 'rdrId-style';
            cssNode.rel = 'stylesheet';
            cssNode.href = rdr.baseUrl + 'css/bm-play-master.css';
            cssNode.media = 'screen';
            headID.appendChild(cssNode);

            rdr.loadScript(rdr.baseUrl + 'js/lib/jquery-spotthis-1.2.6-1.5.3.js', function() {
            // rdr.loadScript(rdr.baseUrl + 'js/lib/jquery-spotthis-1.3.2.js', function() {
                rdr.$ = jQuery;
                rdr.bookmark(jQuery);
                // see if we arrived on a redirect to bookmark/media
                var pageUrl = l.href.indexOf('bookmark/media') > 0 ? 
                    l.search.replace(/[?|&]u=([^&]+).*/,'$1') : 
                    e(l.href);
                /* getScript adds time stamp, loadScript allows caching */
                var url = rdr.baseUrl + 'bookmark/login?t=1&u='+ pageUrl;
                // try getting persisted sid
                var sid = rdr.getWName('sid');
                url = sid ? url +'&sid='+ sid : url;
                $.getScript(url);
                jQuery.noConflict(true);
                jQuery = $; // restore possible site version of jquery
                // this.debug('sorry: bookmark failed to open on this page\n'+ e);
                // fallbackBasic();
            });
        }
    }
}
rdr.debug = function(msg) {
    if ( window.console ) {
        window.console.log(msg);
    }
}
rdr.bookmark = function($) {
    var d=document,w=window,l=d.location,e=encodeURIComponent;
    $.widget("ui.spotthis", {
        init: function() {
            try {
                if ( !setBigDoc() ) return;
                // $('head').append('<style type="text/css" charset="utf-8">body { background-color: yellow !important; }</style>');
                // $('head', rdr.doc).append('<style id="rdr-style" type="text/css" charset="utf-8">' + css + '</style>');
            }
            catch(e) {
                alert('sorry: bookmark failed to open on this page\n'+ e);
                fallbackBasic();
                return;
            }
            // fix yahoo
            $('#rdrId-bm', rdr.doc).bind('keydown', function(e){ e.stopPropagation(); });
            // fix flash movie layering
            var userAgent = navigator.userAgent.toLowerCase();
            if ( ! /mac os x/.test(userAgent) ) {
                $('embed:not([wmode=opaque])', rdr.doc)
                    .add($('param:not([name=wmode][value!=opaque])').parent())
                    .css('visibility', 'hidden');
            }
        },
        plugins: {},
        ui: function(e) {
            return {
                self: this,
                options: this.options
            };
        },
        propagate: function(n,e) {
            $.ui.plugin.call(this, n, [e, this.ui()]);
        },
        error: function(errorStr) {
            if ( $('#rdrId-bm').length == 0 ) {
                this.addShell();
                $('#rdrId-head', rdr.doc).append('<div id="rdrId-h1" class="rdr-oops">Error</div>');
            }
            // wait for submit animation to finish
            setTimeout(function(){
                $('#rdrId-error', rdr.doc).html(errorStr).show();
                $('#rdrId-item', rdr.doc).remove();
                $('#rdrId-body').css('padding-bottom', '1px');
                $('#rdrId-bm, #rdrId-body', rdr.doc).show();
                // $('#rdrId-button-bar button[type=submit]', rdr.doc).removeClass('disabled').removeAttr('disabled');
                // $('#rdr-spinner', rdr.doc).remove();
                backgroundSizer();
                // setTimeout(, 10);
            }, 800);
            this.makeDraggable()
        },
        loginError: function() {
            $('#rdrId-h1', rdr.doc).show();
            $('#rdrId-h1-status', rdr.doc).remove();
            $('#rdrId-error', rdr.doc)
                .show()
                .html('Login failed, please correct username or password...');
            $('#rdrId-button-bar button[type=submit]', rdr.doc).removeClass('disabled').removeAttr('disabled');
            // $('#rdr-spinner', rdr.doc).remove();
            backgroundSizer();
        },
        addSuccessful: function(itemUrl) {
            // var itemUrl = $(rdr.doc).data('spotthis.itemUrl');
            if ( false && $('#rdr-redirect', rdr.doc).is(':checked') && itemUrl )
                top.location.href = itemUrl + '?sid='+ this.options.sessionId;
            else if ( this.options.media )
                location.href = $('#rdrId-page-url', rdr.doc).val();

            $('#rdrId-button-bar button[type=submit]', rdr.doc).removeClass('disabled').attr('disabled');
            closeWidget();
        },
        appendLogin: function() {
// alert('appendLogin')
                // $('#rdrId-bm', rdr.doc).remove();
            var self = this;
            if ( $('#rdrId-signin').length == 0 ) {
                this.addShell();
                $('#rdrId-body', rdr.doc).append(signinHTML);
                $('#rdrId-head', rdr.doc).append('<div id="rdrId-h1">Sign In</div>');
                $('#rdrId-bm', rdr.doc).show();
                $('#rdrId-field-sub').click(function(){
                    w.open(rdr.baseUrl +'forgot-password', 'rdr');
                });
                // on focus select content
                $('#rdrId-signin .rdr-box input', rdr.doc).focus(function() {
                    this.select();
                });
            }
            var empty = $.grep($('#rdrId-signin .rdr-box input', rdr.doc).slice(0,2), function(i){ 
                return $(i).val() ? 0 : 1;
            });
            var field = (empty[0] || $('#rdrId-signin .rdr-box input:first', rdr.doc)[0]);
            if ( field ) field.focus();

            $('#rdrId-button-bar button[type=reset]').click(function() { closeWidget(); return false; });
            $('#rdrId-form', rdr.doc).bind('submit', function() { 
                $('#rdrId-button-bar button[type=submit]', rdr.doc).addClass('disabled').attr('disabled');
                $('#rdrId-h1', rdr.doc).hide();
                $('<div id="rdrId-h1-status" class="rdr-status">Signing in ...</div>')
                    .appendTo('#rdrId-head', rdr.doc)
                    .fadeIn('1000');

                storeState();

                $.getScript(
                    rdr.baseUrl + 'bookmark/login/?' +
                    $('#rdrId-signin input[name]', rdr.doc).serialize()
                );
                return false;
            });
            backgroundSizer();
            // alert('Okay: ' + $('#rdrId-bm #rdrId-form', rdr.doc).css('font-size') )
            this.makeDraggable();
        },
        displayThumbs: function(thumb, label) {
            // make sure thumbs have been scraped
            if ( thumb.entry.length == 0 ) return;
            rdr.curThumb = thumb;
            label = label || "Thumbnail";
            if ( rdr.doc.getElementById('rdrId-viewer-next') != null ) {
                $('#rdrId-viewer', rdr.doc).empty();
            }
            var port = $('<div id="rdrId-port"></div>')
                .css({position: 'relative'})
                .append('<div id="rdrId-zone"></div>');
            // click image or zone to advance thumbnails
            $.event.add(port[0], 'click', function(e){
                if ( e.target.nodeName.toLowerCase() == 'img' ||
                    e.target.id == 'rdrId-zone') {
                    nextImage(thumb);
                }
            });

            $(thumb.entry).each(function(i){ port.append( $(scaleImage(thumb.entry[i].img)) ) });
            port.appendTo($('#rdrId-viewer', rdr.doc));
            $('#rdrId-viewer', rdr.doc).prepend('<div class="rdr-label">'+ label +'</div>');

            setNextPrevButtons(thumb);

            port.hover(function(){ if ( thumb.type != 'video' && thumb.entry[thumb.idx].title )
                    $('#rdrId-viewer', rdr.doc).append('<div class="rdr-hover">'+ thumb.entry[thumb.idx].title + '</div>');
                },
                function() { $('#rdrId-viewer>div.rdr-hover', rdr.doc).remove() }
            )

            // show last viewed image on init
            $('#rdrId-port>img', rdr.doc).slice(thumb.idx, thumb.idx +1).show();

            var sel = $('#rdrId-categories').val();
            var disable = (sel == 'Page' || sel == 'Image') ? false : true;
            for(i=0; i < d.styleSheets.length; i++) {
                if ( d.styleSheets[i].title == 'rdr-drag' ) {
                    void(d.styleSheets[i].disabled = disable);
                }
            }
            // setTimeout(backgroundSizer, 10); // fix height after custom amazon ajax content, delay for redraw

        },
        pageThumbDisplay: function() {
            var def = d.createElement("img");
            if ( rdr.image.entry.length )
                def.src = rdr.baseUrl + 'i/bm/auto-thumbnail.gif';
            else
                def.src = rdr.baseUrl + 'i/bm/auto-thumbnail-0.gif';

            rdr.pageThumb.entry = [{img:{}}];
            rdr.pageThumb.entry[0].img = def;
            rdr.pageThumb.offset = rdr.pageThumb.entry.length; // used to maintain image selection - not used? 
            rdr.pageThumb.entry = rdr.pageThumb.entry.concat(rdr.image.entry);
            this.displayThumbs(rdr.pageThumb);
        },
        shareOptions: function(spotList, contactList) {
            function sort( list ) {
                if( $('option', list).size() > 1 ) {
                    $(list).each(function() {
                        var $select = $(this);
                        var options = $select.find('option').get();

                        options.sort(function(a, b) {
                            var keyA = $(a).text().toUpperCase();
                            var keyB = $(b).text().toUpperCase();
                            return keyA < keyB ? -1 : (keyA > keyB) ? 1 : 0;
                        });

                        $.each(options, function(i, option) {
                            $select.append(option);
                        });
                    });
                    // display number selected
                    var $p = $(list).parent();
                    $p.change(function(){
                        var count = $(':selected[value^=""]', this).length;
                        var label = $p.find('.rdr-label').text().substring(2);
                        var status = ( count > 1 ) ? count + label : 
                            ( count == 1 ) ? 1 + label.substring(0, label.length -1) : '';
                        $p.find('.rdr-field-stat').text(status);
                        if (count > rdr.shareMax)  {
                            $p.find('.rdr-field-stat')
                                .html('<div style="color: #f00; font-weight: bold;">Share limit: '+rdr.shareMax+'</div>');
                            $('#rdrId-form').data('error', "Don't spam it! You can share with up to "+ rdr.shareMax +
                                " twines and "+ rdr.shareMax +" connections at a time.");
                        }
                    });
                }
                $('option:selected', list).removeAttr('selected'); // remove requested feature JUL-3130
                $(list).prepend('<option selected="selected" value="">Choose...</option>');
                // if anything after first option selected, remove first (Choose) selection
                // if ( $('option:not(:first):selected', list).length ) {
                //     $('option:first', list).removeAttr('selected');
                //     $p.trigger('change');
                //     open = true;
                // }
            }
            var open = false; // open panel if user selection exists
            $('#rdrId-spots', rdr.doc).append( spotList );
            sort('#rdrId-spots');
            $('#rdrId-contacts', rdr.doc).append( contactList );
            sort('#rdrId-contacts');
            $('#rdrId-panel').show();
            setCommentHeight();
            $('#rdrId-panel').hide();
            // if ( open ) $('#rdrId-panel-button').click(); // JUL-3130
        },
        appendBookmark: function(categoryList, sid) {
            debug('appendBookmark')
            rdr.start = (new Date).getTime();
            var self = this;
            // o.loggedIn = 1;
            if ( sid ) { rdr.setWName({sid: sid}); }
            this.options.sessionId = sid || rdr.getWName('sid');
            /* Removing form after sign in fixs FF2 cursor bug */
            $('#rdrId-bm', rdr.doc).remove();
            this.addShell();
            $('#rdrId-form', rdr.doc).prepend(panelHTML);

            $('#rdrId-body', rdr.doc).append(spotHTML).hide();
            $('#rdrId-head', rdr.doc).append('<div id="rdrId-h1">Add to Twine</div>');
            $('#rdrId-question').click(function(){
                w.open('http://www.twine.com/item/11q0nx95t-kb/how-do-i-use-the-bookmarklet-tool', 'rdr');
            });

            $('#rdrId-categories', rdr.doc).html( categoryList );

            // TODO: make plugin for addFields
            this.addField({label: 'Title', value: getPageTitle(), dirty: 'true'});

            this.addField({name: 'summary', label: 'Description', size: 'large', dirty: 'true'});

            /* Get Tags */
            var tags = $('meta[name$=eywords]:last').attr('content');
            tags = sortTags(tags);
            this.addField({
                name: 'tags', label: 'Tag this <span id="rdrId-item-type">item</span>', 
                set: 'universal', value: tagTokenizer(tags).join(', '), dirty: 'true'
            });

            /* Get Description - truncate 1800 before last space */
            var summary = $('div.entry-content', rdr.doc).text().replace(/\n+|\s+/g, ' '); // returns string
            if ( !summary ) summary = $('meta[name$=escription]:first').attr('content'); // when not found returns undefined
            if ( summary != undefined ) this.setField('summary', this.truncateString(summary, 1800));

            /* Get selected text from page for summary */
            var a=w.getSelection, b=d.getSelection, c=d.selection;
            try {
                var selectedSummary = (a?a():(b)?b():(c?c.createRange().text:0)).toString();
                if ( selectedSummary ) this.setField('summary', this.truncateString(selectedSummary, 1800), true);
            } catch(e) { debug('JUL-2596 selectedSummary: '+ e) }

            $('#rdrId-page-url', rdr.doc).val(window.location);

            // $('#rdrId-item select:first', rdr.doc).focus(); // focus on first form element
            // submit handler
            // $('#rdrId-button-bar button[type=submit]', rdr.doc)
            $('#rdrId-form').submit(function(){
                var sel = $('#rdrId-categories option:selected', rdr.doc).attr('id');
                // exact matches, custom items matched in other functions
                if ( sel == 'rdrId-category-Image' ) {
                    self.submitSpotThis({'objURL': rdr.image.entry[rdr.image.idx].img.src});
                } else if ( sel == 'rdrId-category-Video' ) {
                    // backend parse video from page url, ingnoring objURL and thumbURL.
                    var params = {'thumbURL': rdr.video.entry[rdr.video.idx].img.src};
                    if (rdr.video.entry[rdr.video.idx].obj) {
                        $.extend(params, {'objURL': rdr.video.entry[rdr.video.idx].obj});
                    }
                    if (rdr.video.entry[rdr.video.idx].embedCode) {
                        $.extend(params, {'displayMarkup': rdr.video.entry[rdr.video.idx].embedCode});
                    }
                    self.submitSpotThis(params);
                } else if ( sel == 'rdrId-category-Page' ){
                    var t = (rdr.pageThumb.idx > 0) ? rdr.pageThumb.entry[rdr.pageThumb.idx].img.src : '';
                    self.submitSpotThis({'objURL': t});
                }
                return false;
            });
            $('#rdrId-categories', rdr.doc).change(function() {
                $('#rdrId-item-type').text(this.options[this.selectedIndex].innerHTML);
                if ( this.options[this.selectedIndex].id == "rdrId-category-Image" ) {
                    self.displayThumbs(rdr.image, "Image");
                }
                else if ( this.options[this.selectedIndex].id == "rdrId-category-Video" ) {
                    self.displayThumbs(rdr.video);
                }
                else {
                    self.pageThumbDisplay();
                }
            });
            $('#rdrId-tags', rdr.doc).bind('change', function(){
                $(this).val(tagTokenizer($(this).val()).join(', '));
            });
            $('#rdrId-body', rdr.doc).hide();

            // find youtube videos embedded in blogs, but not youtube.com
            // var youtubeEmbeds = $('param[name=movie][value^=http://www.youtube.com/v/]').get();
            var youtubeEmbeds = $('embed[src^=http://www.youtube.com/v/]').get();
            $.each(youtubeEmbeds, function(i, ele){
                var vid = ele.src.match(/\/v\/([^\&]*)/)[1];
                self.fetchYouTube(vid, i);
            });

            $('#rdrId-bm', rdr.doc).show();

            backgroundSizer(rdr.barHeight);
            rdr.now = (new Date).getTime();
            // rdr.debug('time: ' + (rdr.now - rdr.start)/100)
            setTimeout(function(){ scrapeImages(0) }, 1);
            setTimeout(function(){ self.asyncBookmarkUpdate() }, 200);
        },
        asyncBookmarkUpdate: function() {
            var o = this.options;
            this.pageThumbDisplay();

            // select first one (Page), to prevent last from being default (Book)
            $('#rdrId-categories option:first', rdr.doc).attr('selected', 'true');

            // init select menus
            // skip first option and disable the remaining unselected options
            $('#rdrId-categories option', rdr.doc).slice(1).not(':selected').attr('disabled', 'true');

            // custom website actions
            this.propagate('customType', $, rdr);

            // Show the body of the bookmarklet if it fits in the frame height.
            if ( ((window != rdr.win) && rdr.win.height > rdr.minDisplayHeight) || window == rdr.win ) {
                $('#rdrId-body', rdr.doc).show();
                // backgroundSizer(rdr.fullHeight);
                backgroundSizer();
            }
            function isValidateEmail(){
                var email = /^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$/
                var $emailField = $('#rdrId-panel input');
                var addresses = $emailField.val().split(/\s*,\s*/);
                if (addresses[addresses.length-1] == 0) {
                    // remove trailinig , and spaces
                    addresses.pop();
                    $emailField.val(addresses.join(', '));
                }
                var invalid = $.grep(addresses, function(addr){ return email.test(addr) }, true);
                if ( invalid.length ) {
                    $emailField.prev('div.rdr-error').remove(); // clear previous message
                    $emailField.parent().addClass('rdr-field-error').children('.rdr-label').html('Fix Email Addresses:');
                    $emailField.before('<div class="rdr-error"> '+ invalid.join(', ') +'</div>');
                    setCommentHeight();
                    return false;
                }
                else {
                    $emailField.prev('div.rdr-error').remove(); // clear previous message
                    $emailField.parent().removeClass('rdr-field-error').children('.rdr-label').html('Email Addresses');
                    setCommentHeight();
                    return true;
                }
            }
            $('#rdrId-bm form').submit(function(){
                isValidateEmail();
                return false;
            });

            /* item accordion */
            if ( !($.browser.msie && document.compatMode == 'BackCompat') ) {
                var $a = $('#rdrId-item-accordion');
                $a.find('textarea.rdr-char').each(function(){ 
                    var grow = function(el, now) {
                        // debug(el.clientHeight)
                        if ( el.clientHeight < el.scrollHeight ) {
                            $(el).parent('.rdr-field').addClass('rdr-grow');
                            $(el).unbind('keyup').unbind('change');
                            if ( now ) accordionRefresh(el);
                        }
                    }
                    $(this).keydown(function(){ grow(this, true)} )
                        .change(function(){ grow(this, true); return false; });
                    grow(this);
                    $(this).focus(function(){ accordionRefresh( this ); return false; });
                });

                function accordionRefresh(input) {
                    $a.find('.rdr-accord-on').removeClass('rdr-accord-on rdr-accord-on-normal rdr-accord-on-large');
                    // $a.find('.rdr-field').show().css('border', '1px solid blue'); // IE 7 fix

                    if ( input && $(input).parent('.rdr-field').hasClass('rdr-grow') ) {
                        $(input).parent('.rdr-field').addClass('rdr-accord-on')
                        $(input).parent('.rdr-field-normal').addClass('rdr-accord-on-normal')
                        $(input).parent('.rdr-field-large').addClass('rdr-accord-on-large');
                    }
                    else {
                        $a.children('div:first').addClass('rdr-accord-on');
                    }
                    return false;
                }
                $('#rdrId-item .rdr-field').click(function(e){ 
                    if ( $(this).is('.rdr-grow.rdr-accord-on') ) {
                        if (e.target.nodeName.toLowerCase() !== 'textarea') {
                            $(':input', this).blur();
                            accordionRefresh();
                        }
                    }
                });
            }
            else {
                $('#rdrId-item-accordion textarea.rdr-char').css('overflow', 'auto');
            }
            // check to see if we're looking a media in the browser: pdf, mov, swf
            if ( rdr.malformedPage ) {
                var type = getPageTitle().match(/\.(\w+)/)[1];
                if ( /jpg$|jpeg$|png$|gif$/.test(type) ) {
                    if ( rdr.image.entry.length == 0 ) scrapeImages(2); // scrape for smaller images
                    this.selectCategoryImages();
                }
                if ( /mov$|swf$/.test(type) ) {
                    var thumb = {}; thumb.img = rdr.doc.createElement('img');
                    thumb.img.src = rdr.baseUrl + 'i/bm/youtube-video.jpg';
                    // thumb.src = 'http://localhost:4242/i/bm/youtube-video.jpg';
                    thumb.img.width = '120'; thumb.img.height = '90';
                    thumb.obj = location.href;
                    rdr.video.entry.push( thumb );
                    this.selectCategoryVideo();
                }
            }
            // check to see if we're falling back to local media bookmark page
            var passedUrl = decodeURIComponent(getParam('u'));
            var pageUrl = location.href;
            if ( pageUrl.indexOf(rdr.baseUrl) == 0 && passedUrl ) {
                // Media bookmark page
                $('#rdrId-page-url', rdr.doc).val(passedUrl);
                o.media = true;
                // set title to media file name as browser would do
                var filename = passedUrl.match(/([^\/]+\.\w{3,4}$)/);
                if ( filename ) d.title = filename[1];
                // select image type from pulldown
                if ( /jpg$|jpeg$|png$|gif$/.test(passedUrl) ) {
                    if ( rdr.image.entry.length == 0 ) scrapeImages(2); // scrape for smaller images
                    this.selectCategoryImages();
                }
                if ( /mov$|swf$/.test(passedUrl) ) this.selectCategoryVideo();
                rdr.passedUrl = passedUrl;
                // TODO add other media types like flash - Kai
            }
            // fix disabled options
            if ($.browser.mozilla || $.browser.msie) {
                $('#rdrId-bm select', rdr.doc).each(function() {
                    // find index of selected not disabled option
                    var last = $('option', this).index($('option:selected:not([disabled])', this));
                    // if none then find index of first not disabled option
                    last = (last == -1) ? $('option', this).index($('option:not([disabled]):first', this)) : last;
                    $(this).bind('change', function(){
                        if ( this.options[this.selectedIndex].disabled )
                            this.selectedIndex = last;
                        else last = this.selectedIndex;
                    });
                });
            }
            // make bookmarklet droppable
            // alert($('#rdrId-bm', rdr.doc).length)
            $('#rdrId-bm', rdr.doc).droppable({
                accept: '.rdr-draggable', 
                tolerance: 'touch',
                activeClass: 'droppable-active',
                hoverClass: 'droppable-hover',
                activate: function(ev, ui) {
                    $('#rdrId-viewer>.label').trigger('click');
                },
                drop: function(ev, ui) { 
                    ui.draggable.clone().fadeOut('fast', 
                    function() {
                        showDroppedImage($(this).attr('src'))
                    });
                } 
            });

            scrapeImages(1);

            // open window, source it to twine which drops session cookie

            // disabling this feature since it conflicts with pop-up blockers and
            // dosen't help the bookmarklet remain signed in since it can't read the 
            // cookie that was dropped by the pop-up.
            if (o.sessionId && false) {
                var pop = w.open(rdr.baseUrl +'bookmark/media?sid='+ o.sessionId, 'pop', 'toolbar=0,status=0,width=1,height=1');
                if ( !pop ) {
                    // last resort, after adding item, redirect to twine item to force login cookie drop

                    // TODO: alert suggesting solutiont 3rd party cookies
                    // $('#rdr-redirect')[0].checked = true;
                }
                else {
                    // bury and close signin window after cookie drop
                    pop.blur();
                    setTimeout(function(){ pop.close() }, 5000);
                }
                setTimeout(function(){ rdr.win.focus() }, 1);
            }

            // Panel init
            var itemHeight = $('#rdrId-item').height();
            // $('#rdrId-panel-open-button-bk').css({'top': (itemHeight -38)});
            $('#rdrId-panel').height(itemHeight -5); /*from the top for ie */
            $('#rdrId-panel .rdr-placeholder')
                .focus(function(){ 
                    $(this).removeClass('rdr-placeholder');
                })
                .blur( function(){ 
                    if ( $.trim(this.value) ) { $(this).removeClass('rdr-placeholder') } 
                    else { $(this).addClass('rdr-placeholder') }
                });

            $('#rdrId-panel input').blur(function(){ isValidateEmail() });
            $('#rdrId-panel-button').toggle(
                function(){
                    $('#rdrId-panel-open-button-bk').show();
                    $('#rdrId-panel').animate({width: 240}, 'fast',
                         function(){$('#rdrId-panel-body').fadeIn('fast') }
                    );
                },
                function(){
                    $('#rdrId-panel-body').hide();
                    $('#rdrId-panel').stop().animate({width: 0}, 'fast', 
                        function(){ $('#rdrId-panel-open-button-bk').hide() }
                    );
                }
            );
            $.get(rdr.baseUrl + 'bookmark/connect', this.sidParam(), null, 'script');
            $('#rdrId-button-bar button[type=submit]', rdr.doc).focus();
            this.makeDraggable();
        },
        addShell: function() {
            var o = this.options;
            $(rdr.doc.body).append(thingHTML);
            if ( rdr.pos.left ) $('#rdrId-bm', rdr.doc).css({top: rdr.pos.top, left: rdr.pos.left});
            // position fixed seems to break draggable, fixed in 1.3.2
            // if (! $.browser.msie) $('#rdrId-bm').css('position', 'fixed');
            $('#rdrId-bm', rdr.doc).hide(); // IE7 really wants this for clean display
            // close box
            $('#rdrId-close-x', rdr.doc).click(function() { closeWidget(); return false; });
            // this.makeDraggable();
        },
        makeDraggable: function() {
            // window shade
            debug('makeDraggable')
            $('#rdrId-bm #rdrId-head')
                .hover(
                    function(){$('.rdr-handle-shade', this).addClass('rdr-handle-shade-hover')},
                    function(){$('.rdr-handle-shade', this).removeClass('rdr-handle-shade-hover')}
                )
                .bind('dblclick', function(){
                    // $('#rdrId-bm div.rdr-handle-shade').bind('dblclick', function(){
                if ( $('#rdrId-body').is(':visible') ) {
                    this.formHeight = $('#rdrId-form').height();
                    headHeight = $('#rdrId-head').height();
                    backgroundSizer(rdr.barHeight, 'contract');
                }
                else {
                    backgroundSizer(this.formHeight, 'expand');
                }
            });

            // fix ie backgrounds
            if ( $.browser.msie ) {
                $('#rdrId-bm div.handle-shade', rdr.doc)
                    .css({'background-image': 'url('+ rdr.baseUrl +'i/bookmark/drag-grip.gif)'});
            }

            // make bookmarklet draggable
            $('#rdrId-bm', rdr.doc).draggable({handle: '#rdrId-head', cancel: '#rdrId-body, #rdrId-panel'});
            // soft mouseover on head grip
            $('#rdrId-head', rdr.doc).hover(function() {
                $('div.handle-shade', this).css('cursor', 'move').stop().animate({opacity: .6 }, 200);
            }, function() {
                $('div.handle-shade', this).css('cursor', 'default').stop().animate({opacity: 0 }, 300);
            });

            // test fix flash object overlay
            // $('object, embed', rdr.doc).droppable({
            //     accept: '#rdrId-bm', 
            //     tolerance: 'touch',
            //     activeClass: 'tst-active',
            //     hoverClass: 'tst-hover',
            //     activate: function(ev, ui) {
            //         debug('active')
            //     },
            //     drop: function(ev, ui) { 
            //         // alert('drop')
            //     } 
            // });
        },
        sidParam: function() {
            return (this.options.sessionId) ? {'sid': this.options.sessionId} : {};
        },
        encodeEntities: function(val) {
            return val.replace(/&(#\d+;|\w+;)/g, '&amp;$1');
        },
        addField: function(options) {
            var settings = {
                name: options.name || options.label.toLowerCase(),
                value: '',
                set: 'default', // custom, universal
                size: 'normal', // small, normal, large
                dirty: false
            }
            $.extend(settings, options);
            settings.value = this.encodeEntities(settings.value);
            var sizeClass = 'rdr-field-'+ settings.size;
            var id = (settings.set == 'custom') ? 'rdrId-custom-' : 'rdrId-';
            var startVal = settings.dirty ? '<input type="hidden" id="'+ id + 
                settings.name +'-start" value="'+ settings.value +'">' : '';
            var def = '<div class="rdr-field rdr-'+ settings.set +'-set '+ sizeClass +'">' +
                '<div class="rdr-label">'+ settings.label +'</div>' +
                '<textarea id="'+ id + settings.name +'" name="'+ 
                    settings.name +'" type="text" rows="1" class="rdr-char" tabIndex="'+ rdr.tabIndex++ +'">'+ 
                    settings.value +'</textarea>'+ startVal +'</div>';

            var cust = '<div class="rdr-field rdr-'+ settings.set +'-set rdr-field-locked '+ sizeClass +'">' +
                '<div class="rdr-label">'+ settings.label +'</div> ' +
                '<div class="rdr-value">'+ settings.value +'</div>' +
                '<input type="hidden" id="'+ id + settings.name +'" name="' + 
                    settings.name +'" value="'+ settings.value +'"></div>';

            $('#rdrId-item-accordion').append(( settings.set == 'custom' ) ? cust : def );
        },
        setField: function(id, val, isUserVal) {
            val = this.encodeEntities(val);
            $('#rdrId-'+ id, rdr.doc).val(val);
            if ( !isUserVal ) $('#rdrId-'+ id +'-start', rdr.doc).val(val);
        },
        setCustomField: function(id, val) {
            // alert('#rdrId-custom-'+ id +'\n'+val+'\n'+ $('#rdrId-custom-'+ id).length);
            val = this.encodeEntities(val);
            $('#rdrId-custom-'+ id, rdr.doc).val(val);
            $('#rdrId-custom-'+ id, rdr.doc).parent().find('.rdr-value').text(val)
        },
        // adds hidden field value
        setValue: function(name, val, fieldset) {
            val = this.encodeEntities(val);
            var html = '<input type="hidden" name="'+ name +'" value="'+ val +'">';
            $('#rdrId-item-accordion .rdr-'+ fieldset +'-set:first', rdr.doc).append(html);
        },
        selectCategoryImages: function() {
            $('#rdrId-category-Image', rdr.doc).removeAttr('disabled');
            // select Image from pulldown
            $('#rdrId-category-Image', rdr.doc)
                .attr('selected', true).css('color', '#fff')
                .siblings().attr('selected', false);

            // for some reason displayThumbs isn't nessary for images, but is for video
            // this.displayThumbs(rdr.image, "Image"); 
        },
        selectCategoryVideo: function() {
            $('#rdrId-category-Video', rdr.doc).removeAttr('disabled');
            // select Video from pulldown
            $('#rdrId-category-Video', rdr.doc)
                .attr('selected', true).css('color', '#fff')
                .siblings().attr('selected', false);

            this.displayThumbs(rdr.video);
        },
        fetchYouTube: function(vid, i) {
            var self = this;
            var i = i || 0;
            var api = 'http://gdata.youtube.com/feeds/api/videos/' + vid + '?alt=json-in-script';
            var thumb = {}; thumb.img = rdr.doc.createElement('img');
            thumb.img.src = rdr.baseUrl + 'i/bm/youtube-video.jpg';
            thumb.img.width = '120'; thumb.img.height = '90';
            rdr.video.entry.push( thumb );
            // alert(i + ' - ' + vid);
            $.ajax({
                type: 'get',
                url: api,
                success: function(data) {
                    // alert(rdr.video.entry.length + "\n" + i)
                    thumb.img.src = data.entry.media$group.media$thumbnail[0].url;
                    rdr.video.entry[i].title = data.entry.title.$t;
                    rdr.video.entry[i].img = thumb.img;
                    rdr.video.entry[i].obj = 'http://www.youtube.com/swf/l.swf?video_id=' + vid; // video resource URL
                    self.selectCategoryVideo();
                },
                dataType: 'jsonp'
            });
        },
        submitSpotThis: function( prop, isCustom ) {
            var error = $('#rdrId-form').data('error');
            if ( error ) {
                // re-check
                var tooBig = $('#rdrId-panel select').filter(function(){ 
                    return $(':selected[value^=""]', this).length > rdr.shareMax
                });
                if ( tooBig.length ) {
                    alert(error);
                    return;
                    // $('#rdrId-error', rdr.doc).html(error).show();
                    // backgroundSizer();
                }
            }
            // disable submit button
            $('#rdrId-button-bar button[type=submit]', rdr.doc).addClass('disabled').attr('disabled');
            $('#rdrId-h1', rdr.doc).hide();
            backgroundSizer(rdr.barHeight, 'contract', function(){
                $('<div id="rdrId-h1-status" class="rdr-status">Saving ...</div>')
                    .appendTo('#rdrId-head', rdr.doc)
                    .fadeIn('1000');
            });
            // return false;
            // remove extra values
            if (isCustom) { $('.rdr-default-set', rdr.doc).remove() }
            else { $('.rdr-custom-set', rdr.doc).remove() }
            $('#rdrId-panel select').each(function(){ $('option:first', this).remove() });

            // check list of fields with start value for changes ones
            var dirtyList = [];
            $('#rdrId-item textarea', rdr.doc).each(function(){
                debug('start: '+$('#'+ this.id +'-start', rdr.doc).val())
                if ( $('#'+ this.id +'-start', rdr.doc).length && 
                    $(this).val() != $('#'+ this.id +'-start', rdr.doc).val() ) {
                    dirtyList.push($(this).attr('name'));
                }
            });
            dirtyList = (dirtyList) ? '&dirty='+ dirtyList.join(',') : '';
            var params = $('#rdrId-form *[name]', rdr.doc)
                .serialize()
                // encode <> so they are not stripped
                // .replace(/%3C/g, '%26lt%3b')
                // .replace(/%3E/g, '%26gt%3b')
                + dirtyList;

            // serialize additional spots which have a name of undefined
            // var spots = $.param($('#rdrId-panel option:selected', rdr.doc)).replace(/undefined=/ig,'spot=');
            // flickr ie7 replace first bug
            // if ( spots.indexOf('spot=') !== 0 ) { spots = 'spot=' + spots; }
            // params = params.replace(/&spot=[^&]*/ig, ''); // this seems to be killing the last spot - bug?

            // params += '&'+ spots;
            $.extend(prop, this.sidParam())
            var data = params + ((prop) ? '&'+ $.param(prop ) : '');
            // alert(data);
            // return;

            // Set utf-8 for backCompat pages. Required for Amazon products
            $.ajaxSetup({contentType: "application/x-www-form-urlencoded; charset=iso-8859-1"}); 
            if (data.length > 1337) {
            // if (false) {
                var postForm = $('<form method="post" accept-charset="UTF-8"></form>')
                    .attr({'action': rdr.baseUrl + 'bookmark/add?sid='+ this.options.sessionId, 'autocomplete': 'off'});

                $.each(data.split('&'), function(){
                    var pair = this.split('='), n = pair[0], v = pair[1] || '';
                    $('<input type="hidden">').attr({'name': n, 'value': decodeURIComponent(v)}).appendTo(postForm);
                });
                postForm.appendTo(rdr.doc.body).submit();
                // after post remove bookmarker, success is assumed
                setTimeout(closeWidget, 3000);
            }
            else { 
                // debug('&sid='+ this.options.sessionId);
                $.getScript(rdr.baseUrl + 'bookmark/add?' + data);
            }
        },
        truncateString: function(str, len) { return truncStr(str, len) },
        setBackgroundSize: function() { backgroundSizer() }
    });
    $.ui.spotthis.defaults = {
        iframeFix: 1,
        // loggedIn: 0,
        sessionId: null,
        media: 0
    };

    function debug( msg ) {
        if ( window.console ) {
            window.console.log(msg);
        }
    }
    function fallbackBasic() {
        var a=w.getSelection,b=d.getSelection,c=d.selection,
        s=(a?a():(b)?b():(c?c.createRange().text:0)),
        t=rdr.baseUrl+'bookmark/basic',
        p='?u='+e(l.href)+'&t='+e(d.title)+'&s='+e(s)+'&v=2&adv=2',u=t+p;
        if (!w.open(u,'w','toolbar=0,resizable=1,status=0,width=550,height=390')) l.href=u;
    }
    function fallbackFrames() {
        var d=document; // d is defined glabaly above - remove and test
        var t=rdr.baseUrl+'bookmark/frameset',p='?u='+e(l.href)+'&v=2&adv=2',u=t+p;
        // var t=rdr.baseUrl+'js/frameset.html',p='?u='+e(l.href)+'&v=2&adv=2',u=t+p;
        var dom = d.getElementsByTagName('body')[0] || d.documentElement;
        //alert(dom.innerHTML);
       l.href=u+'&html=' + e(dom.innerHTML);
    }
    function setBigDoc() {
        function frameSize(f) { return $(f).width() * $(f).height(); }
        function frameSizeCompare( f1, f2 ) { return frameSize(f1) < frameSize(f2) ? 1 : -1; }
        if ( rdr.win == null ) {
            rdr.win = window;
            var f = $('frameset>frame').get();
            if ( f.length ) {
                try {
                    var largeFrames = f.sort(frameSizeCompare);
                    // alert(largeFrames.length)
                    largeFrames = $.grep(largeFrames, function(f){ 
                        return (f.src.indexOf(rdr.doc.domain) > 0) && ($(f).height() > rdr.minFrameHeight)
                    });
                    // alert('frames length: ' + largeFrames.length)
                }
                catch(e) {
                    rdr.win = window;
                    return rdr.doc;
                }
                // fall back to basic, defer this till later JUL-2596 howard stern
                fallbackBasic();
                return;

                // if (largeFrames.length == 0) {
                //     fallbackFrames();
                //     return null;
                // }
                // else {
                //     rdr.win = largeFrames[0];
                //     rdr.doc = rdr.win.contentWindow.document;
                // }
            }
        }
        return rdr.doc;
    }
    function storeState() {
        // store drag position for bookmark display
        rdr.pos.top = $('#rdrId-bm', rdr.doc).css('top');
        rdr.pos.left = $('#rdrId-bm', rdr.doc).css('left');
    }
    function backgroundSizer(height, animate, callback) {
        if ( ((window != rdr.win) && rdr.win.height > rdr.minDisplayHeight) || window == rdr.win ) {
            var height = height || $('#rdrId-form').height();
            // var height = height || 514;
            var shadowHeight = 15;
            var outerHeight = height + shadowHeight;
            var adjust = function(){
                $('#rdrId-bm').height(outerHeight);
                $('#rdrId-background').height(outerHeight - rdr.barHeight);
                $('#rdrId-head')[(height < 40) ? 'addClass' : 'removeClass']('rdr-head-background');
            }
            if ( animate ) {
                var canvasCrop = outerHeight - 529;
                if ( animate == 'expand') {
                    // alert('canvasCrop: '+ canvasCrop)
                    // canvasCrop = 0; // fix bad math - always expands to max
                    adjust();
                }
                else {
                    storeState();
                    $('#rdrId-body, #rdrId-panel').hide();
                }
                $('#rdrId-background div.rdr-canvas')
                    .animate({top: canvasCrop}, 'fast', function(){
                        if ( animate == 'contract') adjust()
                        else $('#rdrId-body, #rdrId-panel').show();
                        if ( callback ) callback();
                    });
            }
            else {
                adjust();
                $('#rdrId-background div.rdr-canvas').css({top: outerHeight - 529});
            }

            if (outerHeight > window.innerHeight) {
                $('#rdrId-bm', rdr.doc).css('position', 'absolute');
            }
        }
    }
    function setCommentHeight() {
        $('#rdrId-comment textarea').css({height: 'auto'});
        var plus = $.browser.safari ? 9 : 12;
        var plus = $.browser.safari ? -6 : -8;
        $('#rdrId-comment textarea').height($('#rdrId-panel').height() - $('#rdrId-panel-body').height() + plus)
    }
    function scrapeImages(pass) {
        var dim = rdr.scrape[pass];
        rdr.now = (new Date).getTime();
        // debug('--ScrapeImages pass: '+ pass + ' ' +dim.w + "-" + dim.h + '\n' + (rdr.now - rdr.start)/100)

        function filterImage(imgEle) {
            var filter = new RegExp('bm-background|bm-overflow.gif|spaceball.gif|sp.gif|space.gif|spacer.gif|pixel|mask.png|auto-thumbnail');
            if ( ! filter.test(imgEle.src) ) {
                if ( imgEle.width >= dim.w && imgEle.height >= dim.h ) {
                    $(imgEle).addClass('rdr-handled');
                    var img = d.createElement('img');
                    img.src = imgEle.src;
                    img.alt = '(size ' + imgEle.width + 'x' + imgEle.height + ')';
                    // debug('imgEle.src '+ imgEle.src +'\n'+ img.alt);
                    setTimeout(function(){
                        $(imgEle).addClass('rdr-draggable').draggable({ helper: 'clone' });
                    }, 1);
                    return img;
                }
            }
        }
        function addNewImage() {
            //debug('adding New Image: ' + this.src)
            var img = filterImage(this);
            if ( img ) { 
                rdr.image.entry.push({img: img, title: img.alt});
                // trigger redraw of thumnails
                $('#rdrId-categories', rdr.doc).trigger('change');
            }
        }
        // Start walking the document tree.
        var largeImages = largeBackgrounds = [];
        $('img', rdr.doc).not('.rdr-handled').not('[desc]').each(function( i ) {
            if ( this.width && this.height && this.src) {
                if ((this.width >= dim.w) && (this.height >= dim.h)) 
                    // debug('good '+ this.width + 'x' + this.height + '\n' + this.src);
                var cleanImg = filterImage(this);
                if ( cleanImg ) largeImages.push(cleanImg);
            }
            else if (pass) {
                // debug('bad '+ this.src +'\n'+ this.width);
                $(this).not('.rdr-handled').one('load', addNewImage).addClass('rdr-handled');
            }
        });
        // Sort images, attempt to favor "certain" images.
        largeImages.sort(imageCompareFunction);

        if (rdr.image.entry.length < 20 && pass == 0) {
            // Fetch background images
            //debug('going for backgrounds')
            // Background images could be on any type of element; most likely found on divs and spans.
            $('div, span, a, body', rdr.doc).each(function( i ) {
                // debug('inside each loop: '+ i)
                var src = $(this).css('background-image');
                // if (src != 'none' && /^url\(http\:/.test(src)) {
                if (src != 'none' && /^url\([^data]/.test(src)) {
                    src = src.replace(/^url\(([^\)]*)\)/i, '$1');
                    var img = d.createElement('img'); img.src = src;
                    //debug('img.src '+ img.src);
                    if (img.width > 120 && img.height > 90) {
                        img.alt = '(background size ' + img.width + 'x' + img.height + ')';
                        var cleanImg = filterImage(img);
                        if ( cleanImg ) largeBackgrounds.push(cleanImg);
                    }
                    // alert(':'+src.replace(/^url\(([^\)]*)\)/i, '$1') + ':')
                }
            });
            largeBackgrounds.sort(imageCompareFunction);
            rdr.image.entry = dedupeImages(largeImages.concat(largeBackgrounds));
            debug('pass: '+ pass +' done - qty:'+ rdr.image.entry.length);
        }
        else {
            rdr.image.entry = rdr.image.entry.concat(dedupeImages(largeImages));
            $('#rdrId-categories', rdr.doc).trigger('change');
            debug(pass+' = qty:'+rdr.image.entry.length)
// $('head', rdr.doc).append('<style id="rdrId-style-drag" title="rdr-drag" type="text/css">' + rdr.cssDrag + '</style>');
        }
        rdr.now = (new Date).getTime();
        rdr.debug('images finished: ' + (rdr.now - rdr.start)/100)

        if ( rdr.image.entry.length > 0 ) {
            $('#rdrId-category-Image', rdr.doc).removeAttr('disabled');
            if ($.browser.mozilla || $.browser.msie) {
                // fix disabled options
                $('#rdrId-bm option:not([disabled])', rdr.doc).css('color', '#fff');
                $('#rdrId-bm option[disabled]', rdr.doc).css('color', '#666');
            }
        }
    }

    function sortTags(tags) {
        if ( tags != undefined ) {
            // strip entities eg: quotes and bullets
            tags = tags.replace(/&#\d+;/g, '');
            // remove white-space and split on ,
            var tagsArr = tags.split(/\s*,\s*/);
            // sort tags
            tagsArr.sort(function(a, b) {
                var keyA = a.toUpperCase();
                var keyB = b.toUpperCase();
                return keyA < keyB ? -1 : (keyA > keyB) ? 1 : 0;
            });
            // remove duplicates
            var a = tagsArr;
            tagsArr = [];
            $.each(a, function(i){
                if ( i>0 ) {
                    if ( a[i-1].toUpperCase() == a[i].toUpperCase() ) return;
                }
                tagsArr.push(a[i]);
            });
            tags = tagsArr.join(',');
            // truncate tags to ensure summary and comments have some char count [max 2k get].
            // alert('sort tags')
            // return $(document).spotthis('truncStr', tags, 800);
            return truncStr(tags, 800);
        }
        else
            return '';
    }

    function scoreImage( image ) {
        // Primitive scoring function, favors images that are closer
        // to a square ratio and larger. Basically divides the area by
        // the difference between the width and height.
        var w = image.width;
        var h = image.height;

        // Protect against div by 0, and make it tougher for perfect
        // square images to get through (often they are icons).
        var diff = Math.max(Math.abs(w - h), 10);

        // Multiply area to further favor large images.
        return ((w * h) * 10) / diff;
    }
    function imageCompareFunction( pic1, pic2 ) {
        var score1 = scoreImage(pic1);
        var score2 = scoreImage(pic2);
        return score1 < score2 ? 1 : (score1 > score2) ? -1 : 0;
    }
    function dedupeImages(images) {
        // Remove duplicates.
        var last = null;
        var cleanEntry = [];
        $(images).each(function( i ) {
            if ( !last || images[i].src != last.src ) {
                last = this;
                cleanEntry.push({img: this, title: this.alt});
            }
        });
        return cleanEntry;
    }
    function setNextPrevButtons(thumb) {
        $('#rdrId-port', rdr.doc)
            .find('a').remove().end() // remove old
            .append("<a id='rdrId-viewer-prev' class='disabled'>&laquo;</a>")
            .append("<a id='rdrId-viewer-next' class='disabled'>&raquo;</a>");
        if ( thumb.entry.length > 1 ) {
            $('#rdrId-viewer-prev', rdr.doc).click(function(){prevImage(thumb); return false;});
            $('#rdrId-viewer-next', rdr.doc).click(function(){nextImage(thumb); return false;});
            $('#rdrId-viewer-next', rdr.doc).removeClass('disabled');
            if ( thumb.idx != 0) $('#rdrId-viewer-prev', rdr.doc).removeClass('disabled');
        }
        matchTitleThumbnail(thumb);
    }
    function getPageTitle() {
        // get title or last section of url without query string
        var title = d.title || location.href.match(/\/([^\/]+)$/)[1].match(/([^?]+)/)[1];
        return title.replace(/\n+|\s{2}/g, '');
    }
    function matchTitleThumbnail(thumb) {
        var t = (thumb.type == 'video') ? thumb.entry[thumb.idx].title : getPageTitle();
        if ( t && $('#rdr-title-start', rdr.doc).val() == $('#rdr-title', rdr.doc).val() )
            $('#rdr-title, #rdr-title-start', rdr.doc).val(t);
    }
    function prevImage(thumb) {
        var imgs = $('#rdrId-port>img', rdr.doc);
        var $out = $(imgs).eq(thumb.idx);
        if ( thumb.idx > 0 ) {
            $('#rdrId-viewer-next', rdr.doc).removeClass('disabled');
            var $in = $(imgs).eq(--thumb.idx);
        }
        else {
            var $in = $(imgs).eq(thumb.idx = imgs.length -1);
        }
        var outLeft = $out.attr('left') +'px';
        var inLeft = $in.attr('left');

        $out.stop().css('opacity', 1)
            .animate({opacity: 'hide', left: '150px'}, 'fast', function(){
                $out.css({'left': outLeft});
            });
        $in.stop().css({'left': (parseInt(inLeft) + -150) +'px'});
        $in.animate({opacity: 'show', left: inLeft +'px'}, 'fast');

    }
    function nextImage(thumb) {
        var imgs = $('#rdrId-port>img', rdr.doc);
        if ( imgs.length == 1) return;
        if ( imgs.length > 1) $('#rdrId-viewer-prev', rdr.doc).removeClass('disabled');
        var $out = $(imgs).eq(thumb.idx);
        if ( thumb.idx + 1 < imgs.length ) {
            var $in = $(imgs).eq(++thumb.idx);
        }
        else {
            var $in = $(imgs).eq(thumb.idx = 0);
        }
        var outLeft = $out.attr('left') +'px';
        var inLeft = $in.attr('left');
        $out.stop().css('opacity', 1)
            .animate({opacity: 'hide', left: '-150px'}, 'fast', function(){
                $out.css({'left': outLeft});
            });
        $in.stop().css({'left': (parseInt(inLeft) + 150) +'px'});
        $in.animate({opacity: 'show', left: inLeft}, 'fast', function(){
            $('#rdrId-viewer>div.rdr-hover', rdr.doc).html($in.attr('desc'))
        });
        matchTitleThumbnail(thumb);
    }
    function showDroppedImage(src) {
        $('#rdrId-port>img', rdr.doc).each(function(i){
            if ( $(this).attr('src').indexOf(src) > -1 ) {
                $(this).show().siblings('img').hide();
                var thumb = ($('#rdrId-category-Image:selected').length)
                    ? rdr.image
                    : rdr.pageThumb;
                thumb.idx = i;
                setNextPrevButtons(thumb);
            }
        });
    }
    function scaleImage(image) {
        var max = Math.max(image.width, image.height);
        var scaleTo = 130;
        var rto = max > scaleTo ? scaleTo / max : 1;
        var newWidth = image.width * rto;
        var newHeight = image.height * rto;
        if (newWidth == 0) {
            newHeight = newWidth = scaleTo;
        }
        var left = ((scaleTo - newWidth + 2 + rdr.wider) / 2 -2);
        return $(d.createElement('img'))
            // Attribute left is value to restore center alignment of image if animation is intrupted
            .attr({'src': image.src, 'desc': image.alt, 'alt': '', 'left': left})
            .css({
                width: newWidth,
                height: newHeight,
                // Center in frame.
                top: ((scaleTo - newHeight + 2) / 2 +6), 
                left: left
            }
            )[0];
    }
    function removeWidget() {
        $('#rdrId-bm', rdr.doc).remove();
        $('#rdrId-style', rdr.doc).remove();
        $('#rdrId-style-drag', rdr.doc).remove();
        $('#rdr-script').remove();
        // $('iframe', rdr.doc).show();
        $('embed, object').css('visibility', 'visible');
        $('img.rdr-handled').removeClass('rdr-handled rdr-draggable');
    }
    function closeWidget() {
        backgroundSizer(rdr.barHeight, 'contract', function(){
            $('#rdrId-bm', rdr.doc).fadeOut(function(){ removeWidget() });
        });
    }
    function tagTokenizer(str) {
        // split out double quotes
        var quot = /(")(.+)\1|([^"]+)/g;
        // split out tags with delimiters: , ; tab new-line
        var delim = /([^,;\t\n]+)[,;\t\n]|([\w\s\.]+)/g;
        var tags = [], q, d;

        while ((q = quot.exec(str)) != null) {
            if (q[2]) tags.push(q[2]); // add quoted tag
            else if (q[3]) // parse others for delimeters or words
                while ((d = delim.exec(q[3])) != null) {
                    var t = d[1] || d[2]
                    if (t) tags.push($.trim(t));
                }
        }
        return tags;
    }
    function getParam( name ) {
        name = name.replace(/[\[]/,'\\\[').replace(/[\]]/,'\\\]');
        var regexS = '[\\?&]'+name+'=([^&#]*)';
        var regex = new RegExp( regexS );
        var results = regex.exec( window.location );
        if ( results == null )
            return '';
        else
            return results[1];
    }
    function truncStr(str, len) {
        str = str ? ((str.length > len) ? str.substring(0, len).replace(/(\w+)$/, '...') : str) : '';
        return $.trim(str);
    }


    var thingHTML = ['<div id="rdrId-bm" style="position: absolute; top: 14px; right: 10px;">',
        '<div id="rdrId-background"><div class="rdr-canvas"></div></div>',
        '<form id="rdrId-form" menthod="post">',
        '<div id="rdrId-head">',
        '<div class="rdr-handle-shade"></div>',
        '<div id="rdrId-logo"></div>',
        '<div id="rdrId-close-x"></div>',
        '</div>',
        '<div id="rdrId-body">',
        '<div id="rdrId-error" class="rdr-box" style="display: none;">',
        'Incorrect username or password. Please try again.',
        '</div>',
        '</div>',
        '</form>',
        '</div>'].join('');

    var signinHTML = ['<div id="rdrId-signin">',
        '<div class="rdr-box">',
        '<div class="rdr-field">',
        '<div class="rdr-label">Username or Email</div>',
        '<input name="username" class="rdr-char" type="text" tabIndex="970">',
        '</div>',
        '<div class="rdr-field">',
        '<div class="rdr-label">Password</div>',
        '<input name="password" class="rdr-char" type="password" tabIndex="980">',
        '<a id="rdrId-field-sub" tabIndex="-1">Forgot your password?</a>',
        '</div>',
        '<div class="rdr-field">',
        '<label class="rdr-label checkbox">',
        '<input type="checkbox" name="rememberMe" value="on" id="rdrId-non-member" tabIndex="-1">',
        '<span>Remember Me</span>',
        '</label>',
        '</div>',
        '</div>',
        '<div id="rdrId-button-bar">',
        '<button class="rdr-submit" type="submit" tabIndex="990">Sign In</button>',
        '<button type="reset" tabIndex="-1">Cancel</button>',
        '</div>',
        '</div>'].join('');

    var panelHTML = ['<div id="rdrId-panel">',
        '<div id="rdrId-panel-body">',
        '<div class="h2">Share With... <span class="em">(optional)</span></div>',
        '<div class="rdr-panel-info">',
        'Share this item with as many of your twines, connections or contacts as you want.',
        '</div>',
        '<div class="rdr-field">',
        '<span class="rdr-field-stat"></span>',
        '<div class="rdr-label">My Twines</div>',
        '<select id="rdrId-spots" name="spot" multiple="true"></select>',
        '</div>',
        '<div class="rdr-field">',
        '<span class="rdr-field-stat"></span>',
        '<div class="rdr-label">My Connections</div>',
        '<select id="rdrId-contacts" name="contact" multiple="true"></select>',
        '</div>',
        '<div class="rdr-field">',
        '<div class="rdr-label">Email Addresses</div>',
        '<input type="text" name="email" class="rdr-char rdr-placeholder" autocomplete="off">',
        '</div>',
        '<div id="rdrId-comment" class="rdr-field">',
        '<div class="rdr-label">Comments</div>',
        '<textarea name="comment" class="rdr-char rdr-placeholder" rows="1" autocomplete="off"></textarea>',
        '</div>',
        '<div class="rdr-panel-info em">',
        'If you don&rsquo;t share this item, it will only be viewable in "My Items". ',
        '</div>',
        '</div>',
        '</div>'].join('');

    var spotHTML = ['<div id="rdrId-item">',
        '<div class="rdr-field rdr-margin">',
        '<div id="rdrId-question" title="How do I use this bookmarklet tool?">?</div>',
        '<div class="rdr-label">Type of Item</div>',
        '<select id="rdrId-categories" tabIndex="1001" name="category"></select>',
        '</div>',
        '<div id="rdrId-item-accordion" class="rdr-margin">',
        '<div class="rdr-intro rdr-accord-on rdr-category-default">',
        '<div class="rdr-intro-inner">',
        'Here&rsquo;s what we found so far &ndash; you can add your own details. ',
        'More will be added once the item appears in Twine.',
        '</div>',
        '</div>',
        '</div>',
        '<div id="rdrId-viewer"></div>',
        '<div id="rdrId-button-bar">',
        '<div id="rdrId-panel-open-button-bk"></div>',
        '<button class="rdr-submit" type="submit" tabIndex="1100">Save</button>',
        '<button id="rdrId-panel-button" type="button" tabIndex="-1">Share This Item...</button>',
        '</div>',
        '<input id="rdrId-page-url" name="pageURL" type="hidden">',
        '</div>'].join('');

}
rdr.atmosphereTest();
