 <?php
 /*
Template Name: Donation page
*/

get_header();


 // Set your secret key: remember to change this to your live secret key in production
// See your keys here https://dashboard.stripe.com/account/apikeys

//\Stripe\Stripe::setApiKey("sk_test_cfsiZmk0p7hbhPaMi5FG47eT"); //Test Secret Key
\Stripe\Stripe::setApiKey("sk_live_fHzVenYrFIWcvgiqEWHT0dnj");  // Live Secret Key

// Get the credit card details submitted by the form
 if (isset($_POST['stripeToken'])) {

  $token = $_POST['stripeToken'];
  $description = "Donation from {$_POST['stripeEmail']}";

  $amount = 500.00;
  if (isset($_POST['amount']) && "" != $_POST['amount']) {
    $amount = floatval($_POST['amount']) * 100.00;
  }

// Create the charge on Stripe's servers - this will charge the user's card
  try {
    $charge = \Stripe\Charge::create(array(
  "amount" => $amount, // amount in cents, again
  "currency" => "gbp",
  "source" => $token,
  "description" => $description,
  "receipt_email" => $_POST['stripeEmail'],
  "statement_descriptor" => "IBD Events"
  ));

    $displayAmount = "£" . number_format( ($amount / 100) , 2, '.', '');

    $sitename = strtolower( $_SERVER['SERVER_NAME'] );
    if ( substr( $sitename, 0, 4 ) == 'www.' ) {
     $sitename = substr( $sitename, 4 );
   }

   $from_email = 'hello@' . $sitename;

   $thanksEmail = wp_mail($_POST['stripeEmail'],
    "IBD Events Donation",
    "Thank you for your donation of $displayAmount to IBD Events, it is greatly appreciated! \r\n Kindest Regards, \r\n IBD Events Team",
    'From: "IBD Events" <' . $from_email . '>');

   $thanks = $thanksEmail ? "has been" : "has not been";

   $message = "Woohoo! $description for $displayAmount has been recieved!" . "\r\n\r\n" 
   . "Thank you email " . $thanks . " sent \r\n" 
   . "IP address used is: " . $_SERVER['REMOTE_ADDR'] . "\r\n"
   . "Unix timestamp is: " . time() . "\r\n"
   . "User agent details: " . $_SERVER['HTTP_USER_AGENT'] . "\r\n";

   wp_mail( "hello@ibd-events.com", 
    "IBD Events Donation Received", 
    $message, 
    'From: "IBD Events" <' . $from_email . '>');

   echo '<div class="alert alert-success" role="alert"><strong>Thank you!</strong> Your donation of ' . $displayAmount . ' has been gratefully received!</div>';


 }

 catch(\Stripe\Error\Card $e) {
  // Since it's a decline, \Stripe\Error\Card will be caught

  $body = $e->getJsonBody();
  $err  = $body['error'];
  echo '<div class="alert alert-danger" role="alert"><strong>Sorry!</strong> ' .  $err['message'] . '</div>';


  wp_mail( "robert@robertshippey.net", 
    "IBD Events Donation Exception", 
    sprintf("Exception: %s \r\n\r\n POST: %s \r\n\r\n Server: %s", print_r($e->getJsonBody(), true), print_r($_POST, true), print_r($_SERVER, true)));

} catch (\Stripe\Error\Base $e) {
  // Display a very generic error to the user, and maybe send
  // yourself an email
 $body = $e->getJsonBody();
 $err  = $body['error'];
 echo '<div class="alert alert-danger" role="alert"><strong>Error!</strong> Something went wrong but your card has not been charged.</div>';

 wp_mail( "robert@robertshippey.net", 
  "IBD Events Donation Exception", 
  sprintf("Exception: %s \r\n\r\n POST: %s \r\n\r\n Server: %s", print_r($e->getJsonBody(), true), print_r($_POST, true), print_r($_SERVER, true)));

} catch (Exception $e) {
  // Something else happened, completely unrelated to Stripe
 echo '<div class="alert alert-danger" role="alert"><strong>Error!</strong> Something went wrong, but your card has not been charged.</div>';

 wp_mail( "robert@robertshippey.net", 
  "IBD Events Donation Exception", 
  sprintf("Exception: %s \r\n\r\n POST: %s \r\n\r\n Server: %s", print_r($e->getJsonBody(), true), print_r($_POST, true), print_r($_SERVER, true)));
}

}

 // Test Publishable key
//$pub_key = "pk_test_nlPNsaPkFQXXXhBMIJKsAMWG";
 
 // Live Publishable Key
 $pub_key = "pk_live_1sR8cpQ3fU2deN1NJsOt0kYv";

global $current_user;
get_currentuserinfo();

 //data-amount="<?php echo $pencePayable; 
 //data-description="<?php echo $row['title']; 
?>

<h1><?php the_title(); ?></h1>
<div class="row">

  <div class="col-sm-8">
  <?php the_content(); ?>
  </div>

    <div class="col-sm-4">

      <form action="" method="POST" id="stripeform">
       <div class="form-group">
        <div class="input-group">
          <span class="input-group-addon">£</span>
          <input type="text" class="form-control" aria-label="Amount" placeholder="5.00" name="amount" id="amount-field">
        </div>
        <small>Values are in British Pounds</small>
      </div>
      <script src="https://checkout.stripe.com/checkout.js"></script>
      <div class="form-group">
        <button id="donateButton" class="btn btn-primary btn-lg">Donate</button>
      </div>
      <script>
        var handler = StripeCheckout.configure({
          key: '<?php echo $pub_key; ?>',
          image: '//dev.ibd-events.com/wp-content/uploads/2015/11/cropped-ibd-red.png',
          locale: 'auto',
          token: function(token) {
            var $id = $('<input type=hidden name=stripeToken />').val(token.id);
            var $email = $('<input type=hidden name=stripeEmail />').val(token.email);
            $('#stripeform').append($id).append($email).submit();
          }
        });

        $('#donateButton').on('click', function(e) {
    // Open Checkout with further options

    var amount = Number($("#amount-field").val()) * 100;

    handler.open({
      name: 'IBD Events',
      currency: "GBP",
      amount: amount.toFixed(2),
      email: <?php echo json_encode($current_user->user_email); ?>,
      panelLabel:  'Donate {{amount}}'
    });
    e.preventDefault();
  });

  // Close Checkout on page navigation
  $(window).on('popstate', function() {
    handler.close();
  });
</script>
</form>

</div>

<div class="col-sm-12">
  <?php the_field('sidebar_content'); ?>
</div>

<?php /*<div class="col-sm-12">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
         ['Month', 'Incomes', 'Costs', 'Balance'],
         ['Oct 2015',   0,  -16.57,    -16.57],
         ['Nov 2015',   0,   -4.52,    -21.09]
         ]);

        var options = {
          vAxis: {title: 'Balances'},
          hAxis: {title: 'Month'},
          seriesType: 'bars',
          isStacked: true,
          series: {
            0: { color: '#222' },
            1: { color: '#B54' },
            2: { color: '#4ab', type: 'line', lineWidth: 5}
          }
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
    <div id="chart_div" style="width: 100%; height: 500px;"></div>
  </div> */ ?>
</div>

<?php get_footer(); ?>