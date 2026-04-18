<?php
function getPlaceholders($array) {
	return join(",",array_map(function($el) { return "?"; },$array));
}
function asjson($object) {
	return json_encode($object,JSON_PARTIAL_OUTPUT_ON_ERROR);
}

function getPagerData($X) {
	$pager = $X->pd->pager;
	if ($pager->records == 0) {
		$pager->pages = 1;
		$pager->start = 0;
		return $pager;
	}
  if (!$pager->perpage)
    $pager->perpage = 20;
  if (!$pager->page)
    $pager->page = 1;
	$pager->pages = (($pager->records - 1) / $pager->perpage) + 1;
	$pager->pages =  intval($pager->pages);
	if($pager->page > $pager->pages) $pager->page = $pager->pages;
	$pager->start = $pager->page * $pager->perpage - $pager->perpage;
	return $pager;
}


function DBQuery($sql,$sqlParams = null) {
	global $X,$stmtCache;
	$args = func_get_args();
	$sql = array_shift($args);

	$sqlMd5 = md5($sql);

  if (!$X->dbh)
    DBConnect($X);

	if ($stmtCache[$sqlMd5]) {
		$sth = $stmtCache[$sqlMd5];
	} else {
    $sth = $X->dbh->prepare($sql);
		if ($sth) {
			$stmtCache[$sqlMd5] = $sth;
		} else {
			die("ERROR on prepare sql $sql: " . print_r($X->dbh->errorInfo(),true));
		}
	}

  if (is_array($args[0])) {
    $sth->execute((array)$args[0]);
  } else {
    $sth->execute($args);
  }

	return $sth;
}

function DBConnect($X) {
	try {
		$X->dbh = new PDO("mysql:host=" . DB_SETTINGS['host'] . ";port=" . DB_SETTINGS['port'] . ";dbname=" . DB_SETTINGS['name'] . ";charset=utf8mb4",
			DB_SETTINGS['user'], DB_SETTINGS['pass'],
			[
				PDO::ATTR_ERRMODE 						=> PDO::ERRMODE_SILENT,
				PDO::ATTR_DEFAULT_FETCH_MODE 	=> PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES 		=> false
			]
		);
	} catch(PDOException $e) {
		die("Error connecting to DB " . $e->getMessage());
	}
}

function getCatsData($catIDs = null,$skipDescr = false) {
	$cats = [];
	$fields = 'c.id,c.name';
	if ($catIDs && is_array($catIDs)) {
		$catIDs = array_filter($catIDs,function($cat) { return is_numeric($cat); });
	} else {
		$catIDs = null;
	}
	if ($catIDs) {
		$params = $catIDs;
		if (!$skipDescr)
			$fields .= ',c.descr';
	} else {
		$params = [];
	}
	
	$sthCats = DBQuery("SELECT $fields
  FROM cats c
  JOIN posts_cats pc ON pc.cat = c.id
	" . ($catIDs ? 'WHERE c.id IN (' . getPlaceholders($catIDs) . ')' : '') . '
  GROUP BY c.id
  ORDER BY NULL',$params);
	while ($cat = $sthCats->fetchObject()) {
		$cats[$cat->id] = $cat;
		$cats[$cat->id]->posts = [];
	}
	return $cats;
}

function getPostsData($posts) {
	if (!$posts)
		return;
	$sthPostData = DBQuery('SELECT id,img,name,descr,DATE_FORMAT(published, "%M %e, %Y") AS published FROM posts WHERE id IN (' . getPlaceholders($posts) . ')',$posts);
	$postsData = [];
  while ($post = $sthPostData->fetchObject()) {
    $postsData[$post->id] = $post;
  }
	return $postsData;
}

function getPost($postID) {
	if (!$postID)
		return;
	return DBQuery('SELECT p.id,p.img,p.name,p.descr,p.content,DATE_FORMAT(p.published, "%M %e, %Y") AS published,GROUP_CONCAT(pc.cat) AS cats
		FROM posts p
		JOIN posts_cats pc ON pc.post = p.id
		WHERE p.id = ?
		GROUP BY p.id
		ORDER BY NULL',$postID)->fetchObject();
}