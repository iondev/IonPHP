<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" prefix="og: http://ogp.me/ns#" lang="en-GB" dir="ltr"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" prefix="og: http://ogp.me/ns#" lang="en-GB" dir="ltr"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" prefix="og: http://ogp.me/ns#" lang="en-GB" dir="ltr"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" prefix="og: http://ogp.me/ns#" lang="en-GB" dir="ltr"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo (Config::rw("Controller") == "main") ? "Welcome" : ucfirst(Config::rw("Controller")); ?></title>
        <meta property="og:title" content="<?php echo (Config::rw("Controller") == "main") ? "Welcome" : ucfirst(Config::rw("Controller")); ?>">
        <meta property="og:url" content="<?php echo Network::full_url().$_SERVER['REQUEST_URI']; ?>">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,900,300italic" type="text/css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="public/css/style.css">
        <link rel="stylesheet" type="text/css" href="public/css/style-wide.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	    <script>window.jQuery || document.write('<script src="public/js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
		<script src="public/js/skel.min.js">
		{
			prefix: "style",
		    esetCSS: true,
		    boxModel: "border",
		      grid: { gutters: 2 },
		      breakpoints: {
		        wide: { range: "1200-", containers: 1140, grid: { gutters: 4 } },
		        narrow: { range: "481-1199", containers: 960 },
		        mobile: { range: "-480", containers: "fluid", lockViewport: true, grid: { collapse: true } }
		      }
		}
		</script>
		<script src="public/js/skel-ui.min.js"></script>
        <script src="public/js/vendor/modernizr-2.6.2.min.js"></script>
        <!--[if lte IE 8]><script src="public/js/html5shiv.js"></script><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
	</head>
	<body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->  
  		{PAGE_CONTENT}
    <div class="container">
        <div class="4u">
            <small>Current Framework Version: {APP_VERSION}<br> Follow the project on <a href="https://github.com/iondev/IonPHP" style="color: #00AEFF">Github</a></small>
        </div>
        <div class="4u">
            <small>Render: <?php echo round(Config::rw('clock_start') - Config::rw('clock_end'), 4); ?></small>
        </div>
    </div>
	</body>
</html>