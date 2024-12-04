<?php
if (isset($addRJS)) $addRJS="r=".rand(1212,83833);
echo '
<link href="'.$tppath.'assets/css/layout2.css" rel="stylesheet" />
<link href="'.$tppath.'assets/plugins/flot/examples/examples.css" rel="stylesheet" />
<link rel="stylesheet" href="'.$tppath.'assets/plugins/timeline/timeline.css" />

<link rel="stylesheet" href="'.$tppath.'assets/plugins/bootstrap/css/bootstrap.css?'.$addRJS.'" />
<link rel="stylesheet" href="'.$tppath.'assets/css/main.css?'.$addRJS.'" />
<link rel="stylesheet" href="'.$tppath.'assets/css/theme.css?'.$addRJS.'" />
<link rel="stylesheet" href="'.$tppath.'assets/css/MoneAdmin.css?'.$addRJS.'" />
<link rel="stylesheet" href="'.$tppath.'assets/plugins/Font-Awesome/css/font-awesome.css?'.$addRJS.'" />
<link rel="stylesheet" href="'.$tppath.'assets/css/custom.css?'.$addRJS.'" />
<link rel="stylesheet" href="'.$toroot.'css/custom.css?'.$addRJS.'" />
';
