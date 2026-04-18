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
        <?php foreach ($X->pd->cats as $id => $cat) { ?>
          <div class='row'>
            <div class='col-xs-6 uc mb5'>
              <?= $cat->name ?>
            </div>
            <div class='col-xs-6'><a href='/category/<?= $id ?>' class='pull-right lnk'>View All</a></div>
          </div>
        <?php
          foreach ($cat->posts as $post) {
            drawPostThumbnail($X->pd->postsData[$post]);
          }
        }
        ?>
      </div>
		</div>
  </body>
</html>