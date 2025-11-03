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

  // Mobile nav toggle
  $(document).on('click', '#nf-nav-toggle', function(){
    const $btn = $(this);
    const $menu = $('#nf-mobile-menu');
    const expanded = $btn.attr('aria-expanded') === 'true';
    $btn.attr('aria-expanded', !expanded);
    $menu.toggleClass('hidden');
  });

  // Dropdowns for mobile: expand submenus on click of parent link
  $(document).on('click', '#primary-menu-mobile .menu-item-has-children > a', function(e){
    const $li = $(this).closest('li');
    const $submenu = $li.children('.sub-menu');
    if ($submenu.length) {
      e.preventDefault();
      $submenu.toggleClass('hidden');
    }
  });
})(jQuery);
