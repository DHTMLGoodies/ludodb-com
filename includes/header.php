<?php
$prefix = file_exists("index.php") ? '' : '../';
?>
<!DOCTYPE html>
<html>
<head>
    <title>LudoDB - PHP Framework</title>
    <link rel="stylesheet" href="<?php echo $prefix; ?>css/index.css" type="text/css"/>
    <script type="text/javascript">
        if (location.hostname.indexOf('ludodb.com') >= 0) {
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-38791888-1']);
            _gaq.push(['_trackPageview']);

            (function () {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();
        }

    </script>
</head>
<body>
<div class="content">
    <div class="heading">
        <div class="logo">
            <a href="/"><img src="<?php echo $prefix; ?>images/logo.png"></a>
        </div>
    </div>
    <div class="body">