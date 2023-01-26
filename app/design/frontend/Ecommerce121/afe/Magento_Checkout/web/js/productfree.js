require([
    'jquery',
    'jquery/ui'
], function ($) {
    $(document).ready(function(){
       let message = $('.cart.item.message.notice').text().trim();

       if(message == "FREE!"){
           $('.cart.item.message.notice').closest('.item-info').addClass('free');
           $('.item-info.free .col.price .cart-price').hide();
           $('.item-info.free .col.qty').hide();
           $('.item-info.free .col.subtotal .cart-price').html('FREE');
       }

    });
});
