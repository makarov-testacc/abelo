<?php
if ($_SERVER['SCRIPT_NAME'] != '/disp.php') {
  header("HTTP/1.1 301 Moved Permanently");
  header("Location:/");
  exit();
}

#для начала получим названия всех категорий, где есть статьи
$X->pd->cats = getCatsData();

#теперь вытащим айдишники постов, которые будем показывать. Полагаем, что у нас 5.7, а не 8.0, где можно через PARTITION BY и прочие ROW_NUMBER сделать
#здесь бы еще кэшировать это, дабы filesort запрос не гонять каждый раз
$sthPosts = DBQuery('SELECT p.id,pc.cat
  FROM posts p
  JOIN posts_cats pc ON pc.post = p.id
  ORDER BY pc.cat,p.published DESC');
$currCat = 0; #текущая категория
$havePosts = 0; #сколько накидали туда постов уже
$X->pd->postsData = []; #здесь будут данные постов, id => data
while ($post = $sthPosts->fetchObject()) {
  if (!$currCat)
    $currCat = $post->cat;
  if ($post->cat != $currCat) { #оп, категория сменилась
    $currCat = $post->cat;
    $havePosts = 0;
  }
  if ($havePosts < POSTS_PER_CATEGORY) {
    $X->pd->cats[$post->cat]->posts[] = $post->id;
    $X->pd->postsData[$post->id] = 1; #запишем, что этот пост мы должны будем достать
    $havePosts++;
  }
}

#достанем данные нужных постов
if ($X->pd->postsData) {
  $posts = array_keys($X->pd->postsData);
  $X->pd->postsData = getPostsData($posts);
}

$X->pageTitle = "Главная";