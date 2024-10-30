( function( $ ) {

function hideTL () {
  var vvvv = setTimeout( function() {
      $('.mxm_tracklist').css({'position':'fixed', 'margin-top':'0'});
  }, 600);
}

var scrollTitle;

function setAnimation () {

  $('.mxm_player').addClass('playing');
  clearInterval( scrollTitle );
  var speed = 100;
  $('.mxm_track-info a').stop().css('margin-left', '0');
      var t = setTimeout( function() {
        var boxWidth = $('.mxm_track-info').width();
        var titleLength = $('.mxm_track-info a > span').width();
        if (titleLength > boxWidth)
          {
            var time = (titleLength + boxWidth) * 1000 / speed; // Take width into account also...
            var Ttime = titleLength * 1000 / speed;
            var Btime = boxWidth * 1000 / speed;
            $('.mxm_track-info a').stop().delay( Btime ).animate({'margin-left': 0 - 20 - titleLength}, Ttime, 'linear');
            scrollTitle = setInterval( function() {
              $('.mxm_track-info a').stop().css('margin-left', boxWidth).animate({'margin-left': 0 - 20 - titleLength}, time, 'linear');
            }, time + 100 );
            $('.mxm_player').attr('ST', scrollTitle);
          }
      }, 1000);
}

$(document).ready( function() {

    $('.mxm_player .pause').click( function() {
      clearInterval( scrollTitle );
       $('.mxm_panel').attr('src', mxm_path + '/skins/default/controls.png');
      $('.mxm_track-info a').stop().animate({'margin-left': 0}, 400);
    });
    $('.mxm_player .play').click( function() {
       $('.mxm_panel').attr('src', mxm_path + '/skins/default/controls-play.png');
      setAnimation();
    });
    $('.mxm_player .next').click(function() { 
       $('.mxm_panel').attr('src', mxm_path + '/skins/default/controls-play.png');
      setAnimation();
    });
    $('.mxm_player .prev').click( function() { 
       $('.mxm_panel').attr('src', mxm_path + '/skins/default/controls-play.png');
      setAnimation();
    });
    $('.mxm_listmask').click( function() {
      $('.mxm_tracklist').removeClass('up');
      $('body').removeClass('trad');
      $(this).fadeOut(400);
      hideTL();
    });
});

$(window).bind('load resize', function() {
  var h = $('.mxm_track-info').height() * 0.8;
  $('.mxm_track-info a').css('font-size', h + 'px');
});

    (function(){

      var iframe = document.querySelector('.mxm_player > iframe');
      var widget = SC.Widget(iframe);

      var forEach = Array.prototype.forEach;

      function addEvent(element, eventName, callback) {
        if (element.addEventListener) {
          element.addEventListener(eventName, callback, false);
        } else {
          element.attachEvent(eventName, callback, false);
        }
      }

      var eventKey, eventName;
      for (eventKey in SC.Widget.Events) {
        (function(eventName, eventKey) {
          eventName = SC.Widget.Events[eventKey];
        }(eventName, eventKey))
      }

      var actionButtons = document.querySelectorAll('.actionButtons button, .mxm_player > a');
      forEach.call(actionButtons, function(button) {
        addEvent(button, 'click', function(e) {
          if (e.target !== this) {
            e.stopPropagation();
            return false;
          }
          var input = this.querySelector('input');
          var value = input && input.value;
          widget[this.className](value);
        });
      });


      var getterButtons = document.querySelectorAll('.getterButtons button');
      forEach.call(getterButtons, function(button){
        addEvent(button, 'click', function(e) {
        });
      });


    $('.mxm_burger').click( function() {
          
          $('.mxm_listmask').fadeIn(250);
          var TLwidth = $('.mxm_tracklist').width();
          var Wwidth = $(window).width();
          $('.mxm_tracklist').css('left', (Wwidth - TLwidth) / 2);

          var vvv = setTimeout( function() {
              var extra = $(window).scrollTop();
              $('.mxm_tracklist').appendTo('body').css({'position':'absolute', 'margin-top':extra});
          }, 600);
          widget['getSounds'](function(val){
            
            $('.mxm_tracklist').addClass('up').html('');

            var givetrackstimetoload = setTimeout( function() {
              for (var i=0;i<val.length;i++) {
                  $('.mxm_tracklist').append( '<a href="javascript:void(0)" title="' + val[i].description + '" class="mxm_tracklink">' + (i + 1) + '. ' + val[i].title + '</a>');
              }

              $('a.mxm_tracklink').click( function() {
                $('.mxm_tracklist').removeClass('up');
                var tnum = $(this).index('a.mxm_tracklink');
                widget['skip'](tnum);
                $('.mxm_listmask').fadeOut(400);
                hideTL();
              });
            }, 10);

            $('.mxm_tracklist').append('<a href="javascript:void(0)" title="Close playlist" class="mxm_closer">x</a><a href="javascript:void(0)" class="SCicon" title="Open standard SoundCloud player" ><img src="' + mxm_path + '/soundcloud.png"></a>');

            $('a.SCicon').click( function() {
              $('.mxm_tracklist').removeClass('up');
              $('body').addClass('trad');
              hideTL();
            });

            $('a.mxm_closer').click( function() {
              $('.mxm_listmask').click();
            });

            });
          });


      s = setInterval ( function() {
          widget['isPaused'](function(val){
            if (val == true) { $('.mxm_player').removeClass('playing');}
            else { $('.mxm_player').addClass('playing'); }
          });

          widget['getCurrentSoundIndex'](function(val){
            $('.mxm_track-info a > span').attr('track', val + 1);
            if ( $('.mxm_player').hasClass('playing') ) {
              $('a.mxm_tracklink').eq(val).addClass('nowplaying');
              $('a.mxm_tracklink').not(':eq(' + val + ')').removeClass('nowplaying');
            } else {
              $('a.mxm_tracklink').removeClass('nowplaying');
            }

          });

          widget['getCurrentSound'](function(val){
            var listing = $('.mxm_track-info a > span').attr('track') + '. ' + val.title;
            var theTrack = $('.mxm_track-info a > span').text();
            if ( listing != theTrack || theTrack == '' ) {
              $('.mxm_track-info a > span').text( listing ).attr('title', val.description);
              setAnimation();
            }
          });

          widget['getPosition'](function(pos){
            widget['getDuration'](function(dur){
              $('.mxm_player').attr('duration', dur);
            });
            var dur = $('.mxm_player').attr('duration');
            var per = 100 * pos / dur;
            $('.mxm_progress').css('width', per + "%");
          });

      }, 100);

    }());

} )( jQuery );