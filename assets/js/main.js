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

  // Quick View: open modal and fetch content
  $(document).on('click', '.nf-quick-view', function(){
    var id = $(this).data('product-id');
    var $ov = $('#nf-qv-overlay');
    var $content = $('#nf-qv-content');
    $content.html('<div style="padding:20px">Loading...</div>');
    $ov.removeClass('hidden');
    $.get(Nutrifrais.ajaxUrl, { action: 'nf_quick_view', product_id: id })
      .done(function(res){ if(res && res.success && res.data && res.data.html){ $content.html(res.data.html); } else { $content.html('<div style="padding:20px">Error</div>'); } })
      .fail(function(){ $content.html('<div style="padding:20px">Error</div>'); });
  });
  $(document).on('click', '.nf-modal-backdrop, .nf-modal-close', function(){
    $('#nf-qv-overlay').addClass('hidden');
  });
  $(document).on('keydown', function(e){ if(e.key === 'Escape'){ $('#nf-qv-overlay').addClass('hidden'); } });

  // Calorie calculator submit
  $(document).on('click', '#nf-calc-run', function(){
    var $f = $('#nf-calc-form');
    var data = $f.serializeArray().reduce(function(acc, cur){ acc[cur.name] = cur.value; return acc; }, {});
    var $out = $('#nf-calc-result .pad');
    $out.html('<p>Calculating...</p>');
    $.post(Nutrifrais.ajaxUrl, $.extend({ action:'nf_calculate_plan' }, data))
      .done(function(res){ if(res && res.success){ $out.html(res.data.html); } else { $out.html('<p>Unable to calculate.</p>'); } })
      .fail(function(){ $out.html('<p>Unable to calculate.</p>'); });
  });
})(jQuery);
