<?php
if ($_SERVER['SCRIPT_NAME'] != '/disp.php') {
  header("HTTP/1.1 301 Moved Permanently");
  header("Location:/");
  exit();
}

if (!$X->urlParams[1] || !is_numeric($X->urlParams[1])) {
  header("HTTP/1.1 301 Moved Permanently");
  header("Location:/");
  exit;
}

$catData = getCatsData([$X->urlParams[1]]);
if (!$catData) { #категории нет или она пуста
  header("HTTP/1.1 301 Moved Permanently");
  header("Location:/");
  exit;  
}
$X->pd->cat = $catData[$X->urlParams[1]];

$X->pd->sortMethods = (object)['views' => 'По просмотрам','published' => 'По дате'];
$X->pd->sortDirections = (object)['ASC' => 'По возрастанию','DESC' => 'По убыванию'];
$X->pd->sort = (object)['field' => $_GET['sort'] && $_GET['sort'] == 'views' ? 'views' : 'published','direction' => $_GET['direction'] == 'ASC' ? 'ASC' : 'DESC'];

$pagerQuery = [];
if ($X->pd->sort->field != 'published')
  $pagerQuery['sort'] = $X->pd->sort->field;
if ($X->pd->sort->direction != 'DESC')
  $pagerQuery['direction'] = $X->pd->sort->direction;
#можно было бы пагинацию сделать через WHERE id > ? ORDER BY ... LIMIT ? , но тогда прямо до 100500-й страницы нельзя будет добраться
$catPosts = DBQuery('SELECT COUNT(*) cnt FROM posts_cats WHERE cat = ?',$X->urlParams[1])->fetchObject();
$X->pd->pager->perpage = 2;
$X->pd->pager->records = $catPosts->cnt ?: 0;
$X->pd->pager->page = $_GET['pg'] && is_numeric($_GET['pg']) ? $_GET['pg'] : 1;
$X->pd->pager = getPagerData($X);
$X->pd->pager->url = "/category/" . $X->pd->cat->id;
if ($pagerQuery)
  $X->pd->pager->url .= '?' . http_build_query($pagerQuery);

#теперь вытащим айдишники постов, которые будем показывать
$posts = DBQuery('SELECT p.id
  FROM posts p
  STRAIGHT_JOIN posts_cats pc ON pc.post = p.id
    AND pc.cat = ?
  ORDER BY p.' . $X->pd->sort->field . ' ' . $X->pd->sort->direction . '
  LIMIT ?,?'
  ,$X->urlParams[1]
  ,$X->pd->pager->start,$X->pd->pager->perpage)->fetchAll(PDO::FETCH_CLASS);
$posts = array_map(function($post) { return $post->id; },$posts);
$X->pd->cat->posts = $posts;
$X->pd->postsData = getPostsData($posts);

$X->pageTitle = $X->pd->cat->name;