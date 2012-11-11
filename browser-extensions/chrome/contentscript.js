var $focus;

$gifbox = $('<div>').attr('id', '#gifbox-results').append(
  $('<h1>').text('Be cool guy, use gif instead of insults!').css('text-align', 'center').css('margin', '5px 0')
).append('<ul>').hide();


$('body').append($gifbox);

$gifbox.on('click', 'img', function(event){
  event.preventDefault();
  var $this = $(this);
  console.log($this);
  $focus.val($this.attr('src'));
  $gifbox.hide();
  return false;
});

function keyUp(e) {
  $focus = $(':focus');
  if ($focus.length) {
    $.getJSON('http://gifbox.co/api/search/' + encodeURIComponent($focus.val()) + '/limit:3', function(data) {
      var $ul = $('<ul>');
      var height = 150, width = 150;
      var offset = $focus.offset();
      for (var i = 0, l = data.result.length; i < l; ++i) {
        $ul.append($('<li>').css('float', 'left').css('margin-right', 50).append(
          // $('<a>').attr('href', data.result[i].url).addClass('gifbox-action').append(
            $('<img>').attr('src', data.result[i].url).css('max-height', height).css('max-width', width)
          // )
        ));
      }

      $gifbox.find('ul').replaceWith($ul);

      $gifbox
        .css('padding-left', 50)
        .css('position', 'absolute')
        .css('top', offset.top + $focus.outerHeight() + 80)
        .css('left', offset.left)
        .css('height', 200)
        .css('width', 600)
        .css('background-color', 'white')
        .css('border', '1px solid #ccc')
        .show();
    });
  }
}

(function checkForNewIframe(doc, uniq) {
    try {
        if (!doc) return; // document does not exist. Cya

        // Unique variable to make sure that we're not binding multiple times
        if (!doc.rwEventsAdded9424550) {
                doc.addEventListener('keydown', keyUp, true);
                doc.rwEventsAdded9424550 = uniq;
        } else if (doc.rwEventsAdded9424550 !== uniq) {
            // Conflict: Another script of the same type is running
            // Do not make further calls.
            return;
        }
        // var iframes = doc.getElementsByTagName('iframe'), contentDocument;
        var iframes = [];
        for (var i=0; i<iframes.length; i++) {
            contentDocument = iframes[i].contentDocument;
            if (contentDocument && !contentDocument.rwEventsAdded9424550) {
                // Add poller to the new iframe
                checkForNewIframe(iframes[i].contentDocument);
            }
        }
    } catch(e) {
        // Error: Possibly a frame from another domain?
        console.log('[ERROR] checkForNewIframe: '+e);
    }
    setTimeout(checkForNewIframe, 250, doc, uniq); //<-- delay of 1/4 second
})(document, 1+Math.random());