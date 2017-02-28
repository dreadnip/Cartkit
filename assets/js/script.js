//set stripe config
if(window.StripeCheckout){
  var handler = StripeCheckout.configure({
    key: 'pk_test_AFhA7f5ciyhHgz5VAVr3FbAU',
    name: 'Cartkit',
    image: 'http://localhost/personal/cartkit/assets/images/logo.svg',
    locale: 'auto',
    zipCode: true,
    currency: 'eur',
    billingAddress: true,
    token: function(token, args) {
      //send to server
      var data = { 'token': token, 'args': args };
      var request = new XMLHttpRequest();
      request.open('POST', 'cart/charge', true);
      request.setRequestHeader("Content-Type", "application/json");
      request.send(JSON.stringify(data));
    }
  });
}

// open click
document.getElementById('pay-button').addEventListener('click', function(e) {
  var desc = this.getAttribute('data-description');
  var amm = this.getAttribute('data-amount');

  handler.open({
    description: desc,
    amount: amm 
  });
  e.preventDefault();
});

// close
window.addEventListener('popstate', function() {
  handler.close();
});