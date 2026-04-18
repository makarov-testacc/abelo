<?php
require_once("libraries/init_settings.php");
require_once("libraries/allowed_pages.php");

$X = new stdClass;

$X->pd 		    = new stdClass(); // pagedata
$X->pd->pager	= new stdClass(); // pager

$X->dbh 	    = false;

require_once("libraries/common.lib.php");
$X->urlParams = $_GET['q'] ? explode("/",$_GET['q']) : ['homepage'];
if (ALLOWED_PAGES[$X->urlParams[0]] && ALLOWED_PAGES[$X->urlParams[0]]['libs']) {
  if (!is_array(ALLOWED_PAGES[$X->urlParams[0]]['libs']))
    die("libs shd be an array");
  foreach (ALLOWED_PAGES[$X->urlParams[0]]['libs'] as $lib) {
    require_once("libraries/$lib.lib.php");
  }
}
if ($_GET['asjson']) {
  $X->needJSON = true;
}

if (array_key_exists($X->urlParams[0],ALLOWED_PAGES)) {
  require_once("pages/" . $X->urlParams[0] . ".php");
  if ($X->needJSON) {
    header("Content-Type:application/json;charset=utf-8");
    print asjson($X);
    exit;
  }
  require_once("templates/common.tpl.php");
  require_once("views/" . $X->urlParams[0] . ".php");
} else {
  die("No page for " . $X->urlParams[0]);
}
