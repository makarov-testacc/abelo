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
        <div class='row'>
          <div class='col-xs-8 uc mb5'>
            <?= $X->pd->cat->name ?>
          </div>
        </div>
        <div class='row'>
          <div class='col-xs-8 mb5'>
            <?= $X->pd->cat->descr ?>
          </div>
        </div>
        <div class='row'>
          <div class='col-xs-8 mb5'>
            <form action="/category/<?= $X->pd->cat->id ?>" method="get">
              <div class="form-group">
                <select class="form-control" id="sortmethod" name="sort">
                  <?php foreach ($X->pd->sortMethods as $method => $rusName) { ?>
                  <option value="<?= $method ?>"<?= $X->pd->sort->field == $method ? ' selected="selected"' : '' ?>><?= $rusName ?></option>
                  <?php } ?>
                </select>
              </div>            
              <div class="form-group">
                <?php foreach ($X->pd->sortDirections as $direction => $rusName) { ?>
                  <label class="radio-inline">
                    <input type="radio" name="direction" value="<?= $direction ?>"<?= $X->pd->sort->direction == $direction ? ' checked="checked"' : '' ?>>
                    <?= $rusName ?>
                  </label>
                <?php } ?>
              </div>
            
              <button type="submit" class="btn btn-default">Сортировать</button>
            </form>
          </div>
        </div>
        <?php
        foreach ($X->pd->cat->posts as $post) {
          drawPostThumbnail($X->pd->postsData[$post]);
        }
        ?>
      </div>
      <div class='mt15'>
      <?php drawPager($X,$X->pd->pager->url); ?>
      </div>
		</div>
  </body>
</html>