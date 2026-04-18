<?php
if ($_SERVER['SCRIPT_NAME'] != '/disp.php') {
  header("HTTP/1.1 301 Moved Permanently");
  header("Location:/");
  exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="<?= $X->pageDescription ?: '' ?>">
    <title><?= $X->pageTitle ?: '' ?></title>
    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
  </head>

  <body role="document">
    <?php drawNavBar() ?>
		<div class='row'>
      <div class='container'>
        <div class="row">
          <div class="col-xs-12">
            <h1 itemprop="name"><?= $X->pd->post->name ?></h1>
          </div>
        </div>
        <?php if ($X->pd->post->img) { ?>
          <div class='row'><img class='w100' src="/images/<?= $X->pd->post->id ?>.png" /></div>
        <?php } ?>
        <div class="row">
          <div class="col-xs-12" itemprop="text"><?= $X->pd->post->content ?></div>
        </div>
        <div class="row">
          <span><?= $X->pd->post->published ?></span>
        </div>
        <div class="row">
          <?php foreach ($X->pd->cats as $id => $cat) { ?>
          <a href="/category/<?= $id ?>"><?= $cat->name ?></a>
          <?php } ?>
        </div>
        <div class='row'>
          <!-- Похожие статьи -->
          <?php
          foreach ($X->pd->similar as $id => $similar) {
            drawPostThumbnail($similar);
          }
          ?>
        </div>
      </div>
		</div>
  </body>
</html>