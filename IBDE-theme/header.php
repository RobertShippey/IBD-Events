<!DOCTYPE html>
<!--[if lte IE 6 ]>
<html lang="en" class="ie ie6">
<![endif]--> 
<!--[if IE 7 ]>
<html lang="en" class="ie ie7">
<![endif]--> 
<!--[if IE 8 ]>
<html lang="en" class="ie ie8">
<![endif]--> 
<!--[if !IE]>-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<link href="<?php echo get_template_directory_uri(); ?>/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/bower_components/font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/bower_components/animate.css/animate.min.css" rel="stylesheet">

<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/bower_components/html5shiv/dist/html5shiv.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/bower_components/respondJS/dest/respond.min.js"></script>
<![endif]-->

<title><?php wp_title(''); ?></title>

<?php wp_head(); ?>

<script type="application/ld+json">
	{
		"@context": "http://schema.org",
		"@type": "Organization",
		"name" : "IBD Events",
		"url": "https://ibd-events.com",
		"logo": "https://ibd-events.com/ibd-red.png",
		"sameAs" : [
		"https://www.facebook.com/IBDEvents/",
		"https://twitter.com/ibd_events",
		"https://plus.google.com/+Ibdeventsdirectory"
		],
		"founder" : {
			"@type": "Person",
			"name": "Robert Shippey",
			"sameAs": "https://robertshippey.net/"
		},
		"foundingDate" : "2015"
	}
</script>

<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js"></script>
<script>
	WebFont.load({
		google: {
			families: ['Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic:latin', 'Ruda:400,900,700:latin']
		}
	});
</script>

<?php if ( ! current_user_can( 'manage_options' ) ) { ?>
<script>
 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
 ga('create', 'UA-33854210-2', 'auto');
 ga('send', 'pageview');
</script>
<?php } ?>

</head>

<?php echo '<body class="'.join(' ', get_body_class()).'">'.PHP_EOL; ?>

<?php get_template_part( 'head' ); ?>