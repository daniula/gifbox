$(function(){
  if ($('#results').length) {
    $('#results > ul').each(function(){
      var $this = $(this);

      $.getJSON('/api/' + $this.attr('id'), function(data){
        var gif, i, l, wrapper;

        $wrapper = $('<ul>').attr('id', $this.attr('id'));

        for (i = 0, l = data.result.length; i < l; ++i) {
          gif = data.result[i];
          console.log(gif.thumbnail);
          $wrapper.append($('<li>').append(
            $('<a>').attr('href', gif.url).append(
              $('<img>').attr('src', gif.thumbnail)
            )
          ));

        }

        $this.replaceWith($wrapper);

      }).error(function() {

        console.log(this.url + ' failed');

      });
    });
  }
});