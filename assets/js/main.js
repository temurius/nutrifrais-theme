(function($){
  // Simple handler for AJAX cart updates
  $(document.body).on('added_to_cart', function(){
    // Woo fragments will replace .count via woocommerce_add_to_cart_fragments
  });

  // Placeholder AI assistant button
  $(document).on('click', '#nf-ai-send', function(){
    var q = $('#nf-ai-input').val();
    var $out = $('#nf-ai-output .pad');
    if(!q){ return; }
    $out.append('<p><em>'+ $('<div>').text(q).html() +'</em></p>');
    $out.append('<p>'+ (Nutrifrais && Nutrifrais.i18n ? Nutrifrais.i18n.added_to_cart : 'OK') +'</p>');
  });
})(jQuery);

