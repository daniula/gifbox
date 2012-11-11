function keyUp(e) {
  var $focus = $(':focus');
  if ($focus.length) {
    $.getJSON('http://gifbox.co/api/search/' + encodeURIComponent($focus.val()) + '/limit:3', function(data) {
      console.log(data);
    });
  }
}

(function checkForNewIframe(doc, uniq) {
    try {
        if (!doc) return; // document does not exist. Cya
        // ^^^ For this reason, it is important to run the content script
        //    at "run_at": "document_end" in manifest.json

        // Unique variable to make sure that we're not binding multiple times
        if (!doc.rwEventsAdded9424550) {
                doc.addEventListener('keydown', keyUp, true);
                doc.rwEventsAdded9424550 = uniq;
        } else if (doc.rwEventsAdded9424550 !== uniq) {
            // Conflict: Another script of the same type is running
            // Do not make further calls.
            return;
        }
        var iframes = doc.getElementsByTagName('iframe'), contentDocument;
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