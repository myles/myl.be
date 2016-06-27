<?php
// Get the YOURLS stuff.
require_once(dirname(__FILE__).'/includes/load-yourls.php');

// Connect to the database.
$db = yourls_db_connect();

// How many rows per page
$rows_per_page = 15;

// Get the page number
if (isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = 1;
}

// Identify how many database rows
$numrows = $rows_per_page;

if ($db) {
	$query_data = $db->get_results("SELECT count(keyword) AS count FROM `" . YOURLS_DB_TABLE_URL . "`");
	$numrows = $query_data[0]->count;
}

// Calculate the number for the last page
$lastpage = ceil($numrows/$rows_per_page);

// Ensure that the page vairable is within range
$page = (int)$page;

if ($page > $lastpage) {
	$page = $lastpage;
}

if ($page < 1) {
	$page = 1;
}

// Construct the LIMIT clause
$limit_query = 'LIMIT ' . ($page - 1) * $rows_per_page . ',' . $rows_per_page;

// Get the Short URLs
$urls = null;
if ($db) {
	$urls = $db->get_results('SELECT `keyword`, `url`, `title`, `timestamp`, `clicks` FROM `' . YOURLS_DB_TABLE_URL . '`  ORDER BY `timestamp` DESC ' . $limit_query);
}
?><!DOCTYPE html>
<html lang="en">
	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Myl.Be</title>
		
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
		
		<style type="text/css">
			.footer .fa-heart {
				font-size: 1.5em;
				vertical-align: middle;
				color: #da3a35;
			}
		</style>
    <script type="text/javascript">
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-1642439-43', 'auto');
        ga('send', 'pageview');
    </script>
    <!-- Piwik -->
    <script type="text/javascript">
      var _paq = _paq || [];
      _paq.push(["setDomains", ["myl.be","*.myl.be"]]);
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      (function() {
        var u="//piwik.mylesb.ca/";
        _paq.push(['setTrackerUrl', u+'piwik.php']);
        _paq.push(['setSiteId', 15]);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
      })();
    </script>
    <noscript><p><img src="//piwik.mylesb.ca/piwik.php?idsite=15" style="border:0;" alt="" /></p></noscript>
    <!-- End Piwik Code -->
	</head>
	<body>
        <nav class="navbar navbar-inverse navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="https://myl.be/">Myl.Be</a>
                </div>
                <div id="navbar" class="hidden-xs">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/admin/">YOURLS</a></li>
                    </ul>
                </div>
            </div>
        </nav>
		<div class="container">
			<div class="page-header">
				<h1>Myl.Be <small>Another Personal Short URL Service</small></h1>
			</div>
			
			<div class="list-group">
			<?php foreach ($urls as $short): ?>
				<a href="<?php echo YOURLS_SITE . "/" . $short->keyword; ?>" class="list-group-item" title="<?php echo $short->title; ?>">
					<h4 class="list-group-item-heading">
						<?php echo $short->title; ?>
						<span class="badge">
							<?php echo $short->clicks; ?>
						</span>
					</h4>
					
					<p class="list-group-item-text"><?php echo $short->url; ?></p>
				</a>
			<?php endforeach ?>
			</div>
			
			<nav>
				<ul class="pager">
				<?php
				if ($page == 1) {
					echo '<li class="previous disabled"><a href="#"><span aria-hidden="true">&larr;</span> Newer</a></li>';
				} else {
					$pervious_page = $page - 1;
					echo '<li class="previous"><a href="' . $_SERVER['PHP_SELF'] . '?page=' . $pervious_page . '"><span aria-hidden="true">&larr;</span> Newer</a>';
				}
				
				if ($page == $lastpage) {
					echo '<li class="next disabled"><a href="#">Older <span aria-hidden="true">&rarr;</span></a></li>';
				} else {
					$next_page = $page + 1;
					echo '<li class="next"><a href="' . $_SERVER['PHP_SELF'] . '?page=' . $next_page . '">Older <span aria-hidden="true">&rarr;</span></a>';
				}
				?>
				</ul>
			</nav>
			
			<div class="footer">
				<hr>
				
				<p>Made by <a href="http://mylesb.ca/" title="Myles Alden Braithwaite">Myles Braithwaite</a> with <i class="fa fa-heart"></i> in Toronto.</p>
				
				<?php yourls_html_footer(); ?>
			</div>
		</div>
	</body>
</html>
