var handler = StripeCheckout.configure({
  key: 'pk_test_AFhA7f5ciyhHgz5VAVr3FbAU',
  name: 'Cartkit',
  image: 'http://localhost/personal/cartkit/assets/images/logo.svg',
  locale: 'auto',
  zipCode: true,
  currency: 'eur',
  billingAddress: true,
  token: function(token, args) {
    // You can access the token ID with `token.id`.
    // Get the token ID to your server-side code for use.
    $.ajax({
      type: 'POST',
      url: 'cart/charge',
      data: { 'token': token, 'args': args },
      dataType: 'json'
    }).done(function(res){
      console.log(res);
    });
  }
});

$('.pay-button').click(function(e) {
  // Open Checkout with further options:
  var desc = $(this).data('description');
  var amm = $(this).data('amount');

  handler.open({
    description: desc,
    amount: amm 
  });
  e.preventDefault();
});

// Close Checkout on page navigation:
window.addEventListener('popstate', function() {
  handler.close();
});