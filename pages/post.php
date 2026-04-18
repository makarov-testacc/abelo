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

$X->pd->post = getPost($X->urlParams[1]);
if (!$X->pd->post) {
  header("HTTP/1.1 301 Moved Permanently");
  header("Location:/");
  exit;  
}

$catIDs = explode(",",$X->pd->post->cats);
$X->pd->cats = getCatsData($catIDs,'skipDescr');

#"похожие" будем брать просто из той же категории
$similarPosts = DBQuery('SELECT DISTINCT pcs.post
  FROM posts p
  JOIN posts_cats pc ON pc.post = p.id
  JOIN posts_cats pcs ON pcs.cat = pc.cat
    AND pcs.post != ?
  WHERE p.id = ?
  LIMIT ?',$X->pd->post->id,$X->pd->post->id,SIMILAR_POSTS)->fetchAll(PDO::FETCH_CLASS);
$similarPosts = array_map(function($similar) { return $similar->post; },$similarPosts);
if ($similarPosts)
  $X->pd->similar = getPostsData($similarPosts);

$X->pageTitle = $X->pd->post->name;