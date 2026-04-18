<?php
if ($_SERVER['SCRIPT_NAME'] != '/disp.php') {
  header("HTTP/1.1 301 Moved Permanently");
  header("Location:/");
  exit();
}

function drawPostThumbnail($post) {
?>
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail">
      <?php if ($post->img) { ?>
        <img style="" src="/images/<?= $post->id ?>.png">
      <?php } else { ?>
        <img style="height: 200px; width: 100%; display: block;" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDI0MiAyMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MjAwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTlkOWQzYjFlODIgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMnB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xOWQ5ZDNiMWU4MiI+PHJlY3Qgd2lkdGg9IjI0MiIgaGVpZ2h0PSIyMDAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI4OS44MDQ2ODc1IiB5PSIxMDUuMSI+MjQyeDIwMDwvdGV4dD48L2c+PC9nPjwvc3ZnPg==">
      <?php } ?>
      <div class="caption">
        <h3><?= $post->name ?></h3>
        <p><?= $post->published ?></p>
        <p><?= $post->descr ?></p>
        <p><a href="/post/<?= $post->id ?>" class="lnk">Continue Reading</a></p>
      </div>
    </div>
  </div>
<?php
}

function drawNavBar() {
?>
		<div class='row'>
			<!-- Fixed navbar -->
			<nav class="navbar navbar-default navbar-fixed-top">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
							<span class="sr-only"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="/">TEST</a>
					</div>
				</div>
			</nav>
			<!-- / Fixed navbar -->
		</div>
    <div class='row' style='height:10em'></div>
<?php
}

function drawPager($X,$url = null) {
	if ($X->pd->pager->pages <= 1)
		return;

	if (preg_match("/\?/",$url ?: '')) {
		$urlArray = explode("?",$url);
		$url = $urlArray[0];
		$urlAddition = "&" . $urlArray[1];
	}

	$outPages = []; #0 => [page => 5, active => true/false] etc

	for ($i = $X->pd->pager->page - 2; $i <= $X->pd->pager->page + 2; $i++) {
		if ($i > 0 && $i <= $X->pd->pager->pages) {
			if ($i == $X->pd->pager->page)
				$outPages[] = ['page' => $X->pd->pager->page, 'active' => true];
			else
				$outPages[] = ['page' => $i];
		}
	}
?>
<div align='center'>
	<ul class="pagination">
		<?php if ($X->pd->pager->page == 1) { ?>
			<li class='disabled'><span>&laquo;&laquo;</span></li>
			<li class='disabled'><span>&laquo;</span></li>
		<?php } else { ?>
			<li><a href="<?= $url . '?pg=1' . $urlAddition ?>">&laquo;&laquo;</a></li>
			<li><a href="<?= $url . '?pg=' . ($X->pd->pager->page - 1) . $urlAddition ?>">&laquo;</a></li>
		<?php } ?>
		<?php
		foreach ($outPages as $outPage) {
			if ($outPage['active']) {
			?>
			<li class='active'><span><?= $outPage['page'] ?></span></li>
			<?php
			} else {
			?>
			<li><a href="<?= $url . '?pg=' . $outPage['page'] . $urlAddition ?>"><?= $outPage['page'] ?></a></li>
			<?php
			}
		}
		?>
		<?php if ($X->pd->pager->page == $X->pd->pager->pages) { ?>
			<li class='disabled'><span>&raquo;</span></li>
			<li class='disabled'><span>&raquo;&raquo;</span></li>
		<?php } else { ?>
			<li><a href="<?= $url . '?pg=' . ($X->pd->pager->page + 1) . $urlAddition ?>">&raquo;</a></li>
			<li><a href="<?= $url . '?pg=' . $X->pd->pager->pages . $urlAddition ?>">&raquo;&raquo;</a></li>
		<?php } ?>
	</ul>
</div>
<?php
}