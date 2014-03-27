<?php
ini_set('user_agent', 'Mozilla/5.0 (compatible; Xkcdbot/2.1)');
define('PREFIX', 'http://xkcd.com/');
define('SUFFIX', '/');
define('SEARCH_URL', 'http://www.google.com/search?q=site%3Axkcd.com+-site%3Awhat-if.xkcd.com+-site%3Aforums.xkcd.com+-site%3Am.xkcd.com+'); //http://www.google.com/cse?cx=012652707207066138651%3Azudjtuwe28q&siteurl=xkcd.com%2F&ref=xkcd.com%2F&q=');
define('XP_URL', 'http://www.explainxkcd.com/wiki/index.php?title=');
$num = $_GET['num'];
if(isset($_GET['search'])) die(file_get_contents(SEARCH_URL . str_replace(" ","+",str_replace("%20","+",$_GET['search']))));
if(isset($_GET['xp'])) die(file_get_contents(XP_URL . $_GET['xp']));
if(isset($_GET['fetch'])) {
	$fc = file_get_contents(PREFIX . $num . SUFFIX);
	function find($fc, $s, $e) {
		$x = explode($s, $fc);
		$x = explode($e, $x[1]);
		return $x;
	}
	$imgd = find($fc, '<img src="http://imgs.xkcd.com/comics/', '"');
	$imgurl = 'http://imgs.xkcd.com/comics/' . $imgd[0];
	$caption = $imgd[2];
	$title = find($fc, '<div id="ctitle">', '</div>');
	$title = $title[0];
	$transcript = find($fc, '<div id="transcript" style="display: none">', '</div>');
	$transcript = $transcript[0];
	if($num == null) {
		$num = find($fc, 'Permanent link to this comic: http://xkcd.com/', '/<br />');
		$num = (int)$num[0];
	}
	$d = array('num' => $num, 'title' => $title, 'url' => $imgurl, 'caption' => $caption, 'transcript' => $transcript);
	die(json_encode($d));
}
?>
<!doctype html>
<html>
<head>
	<title>xkcd</title>
	<link rel='icon' type='image/x-icon' href='favicon.ico' />
	<link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />
	<!--link rel='stylesheet' href='../../i3/rewrite0/base.css' /-->
	<style type='text/css'>
	@import url('http://fonts.googleapis.com/css?family=Open+Sans:100italic,400italic,700italic,100,400,700');
	@import url('base.ui.css');
	body {
		width: 100%;
		height: 100%;
		font-family: "Open Sans", Arial, Helvetica, Roboto, "Droid Sans", sans-serif;
	    background-color: #F0F0F0;
	    overflow-x: hidden;
	}


	header {
		position: fixed;
		width: 100%;
		background: #f0f0f0;
		top: 0;
	}

	.fav {
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABsklEQVR4Xo2SMWhTURSGvytBC0IpAYtCB0sKQmqyOSovGRzcRIOhs4NZdFRB8HUTB0EcFHQrVE0VN4eCvqjgkEqFRDMZ2kGIVI1FENS+9ngOF8rz8Sj94Of8/Oe/lztc12+yE4LHkQ25rS2YOtcnzafHhTB/9BLG8MPtUDthRoc9m5tkovn1/PRFTOazO5DTpbpfJOnNl8MDpcZ2ni826M0VwuJMJ0xf4EQE/r4hSXf+hJRmXqczNHMk2Xsct3yfEP5/4qFSnYOqJF+6jxioUsy69l3k2IU+/FmF3yvsipFJ2HeYpXsF3Ns7/gXT1fOMjk+yG36urfDx5QP/gle3MAIgKlfOMDY+wU6sr32mEz0FqAAtXtwEEbEZqGTYroqs1jNlO+tY15+BXBxjUD1daS0uREj8AzaELCRex/ona5UWoD5xwfOHUQCQH4th4xtp/M4O+e6ps+WWef8TuxPoDKaOjEI83Nb3wcCUzLCOddXbhGfXQERsytfFEZH3+22K5qbIlLEzj054chXknU1Ep/TmEPWmwHKTeZXufMe8z8E1L0PthrBwxfkfCbNASDbpDv8AjqMNNGMVxw0AAAAASUVORK5CYII=);
		background-repeat: no-repeat;
		background-position: top center;
	}

	.logo {
		position: absolute;
		top: 0;
		left: 0;
		/*background-image: url(http://imgs.xkcd.com/static/terrible_small_logo.png);
		background-position: left top;
		background-repeat: no-repeat;
		width: 185px;
		height: 83px;*/
		font-size: 32px;
		padding: 5px 0 0 10px;
		cursor: pointer;
		text-align: left;
	}

	.logo div:hover:after {
		padding-left: 10px;
		font-size: 12px;
		content: "random comic";
	}

	.logo div:after:hover:after {
		content: "a";
	}

	.logo span {
		display: block;
		font-size: 12px;
	}

	.comic .nav {
		margin-left: auto;
		margin-right: auto;
		text-align: center;
		font-size: 18px;;
	}

	.comic input.clk {
		background: none;
		border: 0;
		text-align: center;
	}

	.comic input.t {
		width: 100%;
	}

	.comic input.n {
		font-size: 22px;
		width: 60px;
	}

	.comic button {
		padding: 10px;
		padding-left: 20px;
		padding-right: 20px;
		text-transform: uppercase;
	}

	.comic .transcript {
		max-width: 50%;
		overflow: auto;
		text-align: left;
		white-space: pre-wrap;
		word-wrap: break-word;
		display: none;
	}

	.comic input.t {
		text-align: center;
		font-size: 24px;
	}

	.comic .content {
		margin-top: 145px;
	}

	.comic img[max-height~=100px] {
		width: 50%;
	}

	.comic p.caption.fixed {
		position: fixed;
		bottom: 0;
		left: 0;
	}

	.comic p.caption {
		width: 80%;
		background-color: #f0f0f0;
		z-index: 10;
		margin-bottom: 0;
		padding: 10px;
		max-height: 75px;
		padding-left: 10%;
		padding-right: 10%;
	}

	.comic p.caption a.explain {
		position: absolute;
		padding-left: 50px;
		z-index: 3;
		right: 10%;
		font-size: 16px;
		color: black;
		text-decoration: none;
	}

	.comic p.caption a.explain:hover:before {
		content: "explain";
	}

	.comic p.padding {
		padding-bottom: 75px;
	}

	.comic a {
		color: black;
	}

	.arr-l {
		left: 0;
	}

	.arr-l, .arr-r {
		position: absolute;
		top: 65px;
		width: 10%;
		height: 95%;
		height: calc(100% - 65px);
		cursor: pointer;
	}

	.arr-r {
		right: 0;
	}

	.arr-l:hover, .arr-r:hover {
		background-color: rgba(168, 168, 168, 0.3);
	}

	.saved {
		position: fixed;
		top: 0;
		right: 16px;
		height: 200px;
		width: 20%;
		min-width: 200px;
		border: 1px solid black;
		border-top: none;
		padding: 5px;
		padding-right: 0;
		background-color: #f0f0f0;
		z-index: 500;
	}

	.saved .sx {
		position: absolute;
		top: 0;
		right: 0;
		border-left: 1px solid black;
		border-bottom: 1px solid black;
		padding-left: 3px;
		padding-right: 3px;
		cursor: pointer;
	}

	.saved .ax {
		color: black;
		text-decoration: none;
		text-align: center;
	}

	@media (max-width: 900px) {

		.logo span {
			display: none;
		}
		.comic p.caption {
			width: 100%;
			max-width: 100%;
			padding: 5px;
		}
	}
	@media (max-width: 600px) {
		.logo {
			font-size: 24px;
		}
	}

	@media (max-height: 600px) {
		.comic header {
			position: absolute;
		}
	}
	</style>
	<script type='text/javascript' src='xkcd.js'></script>
	<script type='text/html' id='xkcd'>
	"
	<center>
		<header>
			<div class='logo' onclick='$rand()'><div>xkcd</div><span>A webcomic of romance, sarcasm, math, and language.</span></div>
			<h2><input type='text' class='clk t' onclick='select()' value='{{title}}' onchange='$search(this.value)' /></h2>
			<p class='nav'><button class='gba' onclick='$g(1)'>|&lt;</button><button class='gb' onclick='$g(null, -1)'>Back</button> &nbsp; <input type='text' class='clk n' value='{{num}}' onchange='$g(this.value)' onblur='$g(this.value)' valign='center' /></b> &nbsp; <button class='gf' onclick='$g(null, 1)'>Next</button><button class='gfa' onclick='$g(&quot;&quot;)'>&gt;|</button></p>
		</header>
		<div class='content'>
			<p class='img'><img src='{{url}}' /></p>
			<pre class='transcript'><span class='ct'>{{{transcript}}}</span></pre>
		</div>
		<p class='caption'>
			<span>{{{caption}}}</span>
			<a title='Explain this xkcd!' class='explain' href='http://www.explainxkcd.com/wiki/index.php?title={{num}}' onclick='$xp({{num}}); return false'>?</a>
		</p>
		<p class='padding'></p>
	</center>
	"
	</script>
	<script type='text/html' id='search'>
"
<a href='javascript:;' onclick='$g(this.title)' title='{{num}}'><b>{{title}} - {{num}}</b></a><br />
"
	</script>
	<script type='text/html' id='saved'>
"
	<span class='sx' onclick='parent.window.$slx()'>&times;</span>
	<span style='font-size: 22px'>Saved items:</span><br />
	<table>
		{{{data}}}
	</table>
"
	</script>
	<script type='text/html' id='savedi'>
"<tr>
	<td width='100%'>
		<a href='#?num={{num}}' onclick='parent.window.$g({{num}}, null);return false' target=_top>{{name}}</a>
	</td>
	<td>
		<a class='ax' href='#' onclick='parent.window.$sua({{num}});parent.window.$sl()'>&times;</a>
	</td>
</tr>
"
	</script>
	<script type='text/javascript'>
	FULL_REWRITE = true;
	tpl = function(n, d) {
	    return (z=ich[n](d)).substring(1, z.length - 1);
	}, $xp = function(num) {
		if(typeof num == 'undefined') num = $('input.n').attr('value');
		$.get('/?xp=' + num, {}, function(d) {
			ps = $('h2', d).nextUntil('h2', '*'), t = '';
			ps.each(function() {
				t+= '<p>' + $(this).html() + '</p>';
			});
			$('p.img').dblclick();
			$('.comic .transcript .ct').hide();
			$('.comic .transcript').append("<span class='xp'>" + t + "</span>").dblclick(function() {
				$('.comic .transcript .xp').hide();
				$('.comic .transcript .ct').show();
			});
		});
	}, $sa = function() {
		/* save */
		saved = sessionStorage.getItem('saved');
		d = JSON.parse(saved);
		if(d == null) d = [];
		for(i=0; i<d.length; i++) {
			if(typeof d[i] != 'undefined' && d[i] != null && d[i][0] != null && d[i][0] == $('input.n').attr('value')) {
				$('header').removeClass('fav');
				return console.log('Already saved!');
			}
		}
		d.push([$('input.n').attr('value'), $('input.t').attr('value')]);
		$('header').addClass('fav');
		sessionStorage.setItem('saved', JSON.stringify(d));
		console.log(sessionStorage.getItem('saved'));
		console.log('Saved');
	}, $sua = function(dv) {
		/* unsave */

		if(typeof dv == 'undefined') dv = $('input.n').attr('value');
		saved = sessionStorage.getItem('saved');
		d = JSON.parse(saved);
		if(d == null) d = [];
		for(i=0; i<d.length; i++) {
			if(typeof d[i] != 'undefined' && d[i] != null && d[i][0] != null && d[i][0] == dv) {
				d[i] = null;
				d.splice(i);
				$('header').removeClass('fav');
			}
		}
		sessionStorage.setItem('saved', JSON.stringify(d));
		console.log(sessionStorage.getItem('saved'));
		console.log('Unsaved');
	}, $sl = function() {
		/* list */
		saved = sessionStorage.getItem('saved');
		if(typeof saved == 'undefined') saved = JSON.stringify([]);
		d = JSON.parse(saved);
		h = "";
		for(i=0; i<d.length; i++) {
			if(typeof d[i] != 'undefined' && d[i] != null && d[i][0] != null) {
				h+= tpl('savedi', {'num': d[i][0], 'name': d[i][1]});
			}
		}
		ht = tpl('saved', {'data': h});
		ifr = $('<div />');
		ifr.html(ht);
		ifr.addClass('saved');
		$('body').append(ifr);
		console.log(sessionStorage.getItem('saved'));
	}, $slx = window.$slx = function() {
		$('.saved').remove();
	}, $jq = function() {
		$('p.img').dblclick($jqH = function() {
			$(this).slideUp();
			$('pre.transcript').slideDown().css('min-height', $(this).height());
		});
		$('pre.transcript').dblclick($jqHo = function() {
			$(this).slideUp().css('min-height', null);
			$('p.img').slideDown();
		});

		$('.arr-l').click(function() {
			$g(null, -1);
		});

		$('.arr-r').click(function() {
			$g(null, 1);
		});
		
		$(document).bind('keydown', 'left', function() {
			$('.gb').click();
		});
		$(document).bind('keydown', 'right', function() {
			$('.gf').click();
		});

		$(document).bind('keydown', 'r', function() {
			$('.logo').click();
		});

		$(document).bind('keydown', 's', function() {
			$sa();
		});

		$(document).bind('keydown', 'a', function() {
			$sl();
		});
		_cx = false;
		$(document).bind('keydown', 'x', function() {
			if(_cx == true) return;
			_cx = true;
			if($('p.img').css('display') == 'none') {
				$('pre.transcript').dblclick();
			} else {
				$xp();
			}
			setTimeout(function() {_cx = false}, 500);
		});
		_cc = false;
		$(document).bind('keydown', 'c', function() {
			if(_cc == true) return;
			_cc = true;
			if($('p.img').css('display') == 'none') {
				$('pre.transcript').dblclick();
			} else {
				$('p.img').dblclick();
			}
			setTimeout(function() {_cc = false}, 500);
		});

		/*$(document).bind('keydown', 'down', function() {
			$.proxy($jqHo, $('pre.transcript'));
		});*/

		$('.gfa').attr('disabled', (window.lnb ? 'disabled' : null));
		$('.gba').attr('disabled', (window.fnb ? 'disabled' : null));

		$res();
	}, $res = function() {
		expr = ($('img').height() + 200) > $(window).height();

		if(expr) {
			$('.comic p.caption').addClass('fixed');
			$('.comic p.padding').show();
		} else {
			$('.comic p.caption').removeClass('fixed');
			$('.comic p.padding').hide();
		}
		$e = $('.img img');
		width = ($e.width() > $(window).width());
		if(width) {
			console.log('res');
			i = 1;
			while(i > 0 && ($e.width() > $(window).width())) {
				i = i - 0.01;
				console.log($e.width() + ', ' + $(window).width() + ', ' + i);
				w = parseInt($e.css('width').split('px')[0]), h = parseInt($e.css('height').split('px')[0]);
				$e.css({'width': (w * i) + 'px', 'height': (h * i) + 'px'});
			}
		} else {
			if(($e.width() < $(window).width()) && ($e.width() < parseInt($e.attr('origwidth')))) {
				console.log('bres');
				$e.css('width', $e.attr('origwidth'));
				$e.css('height', $e.attr('origheight'));
				return $res();
			}
		}
	}, $err = function() {
		$('.comic').html(tpl('xkcd', {'title': '&nbsp;', 'num': window.location.search.split('num=')[1]}));
		$('.img').html('An error occurred loading the page.');
	}, $t = function(t) {
		$('title').html('xkcd - ' + t);
	}, $fetchl = function() {
		if(typeof window.ln != 'undefined') return window.ln;
		$.get('/?fetch', {}, function(d) {
			try {
				j = JSON.parse(d);
			} catch(e) {return $err()}
			window.ln = j.num;
		})
	}, $fetch = function(f) {
		try {
			console.debug(f);
			history.pushState(null, null, FULL_REWRITE ? f.substr(4) : '?' + f);
		} catch(e) {}
		if(typeof _ng == 'undefined') _ng = true;
		else if(!!_ng) return;
		$.get('/?fetch&' + f, {}, function(d) {
			$fetchd(d, f);
		});
	}, $fetchd = function(d, f) {
		_ng = undefined;
		try {
			j = JSON.parse(d);
		} catch(e) {return $err()}
		if(f == '' || f == 'num=' && typeof window.ln == 'undefined') {
			window.ln = parseInt(j.num);
		}
		window.lnb = (window.ln == parseInt(j.num));
		window.fnb = (parseInt(j.num) == 1);
		$t(j.title + ' (' + j.num + ')');
		$('.comic').html(tpl('xkcd', j));
		$('.img img').attr('origwidth', $('.img img').width());
		$('.img img').attr('origheight', $('.img img').height());
		$jq();
		setTimeout($res, 100);
		}, $g = window.$g = function(id, inc) {
		c = (typeof id != 'undefined' && id != null) ? id : parseInt($('.n').attr('value'));
		if(typeof inc != 'undefined') {
			c+= inc;
		}
		if(c == 404 && inc == 1) c++;
		if(c == 404 && inc == -1) c--;
		$fetch('num=' + c);
		console.log('num=' + c);
	}, $rand = function(seed) {

		if(typeof _ng == 'undefined') _ng = true;
		else if(!!_ng) return;
		$.get('/?fetch&num=', {}, function(d) {
			_ng = undefined;
			try {
				j = JSON.parse(d);
			} catch(e) {return $err()}
			max = parseInt(j.num);
			if(typeof MersenneTwister != 'undefined') {
				if(typeof seed == 'undefined') seed = +new Date();
				rnd = new MersenneTwister().random(seed);
			} else {
				rnd = Math.random();
			}
			rnd = Math.floor(rnd * max);
			console.log(rnd);
			$g(rnd);
		})

	}, $search = function(q) {
		$('.caption').hide();
		searchurl = '/?search=';
		searchurl+= q;
		$.get(searchurl, {}, function(d) {
			links = $('h3 a', $(d));
			window.d = d = [];
			for(i=0; i<links.length; i++) {
				try {
					if($(links[i]).attr('href').indexOf('/url?q=http://xkcd.com/') != -1) {
						lid = $(links[i]).attr('href').split('/url?q=http://xkcd.com/');
						lid = unescape(lid[1]).split('&sa')[0].replace('/', '');
						d.push({'title': $(links[i]).text().replace('xkcd: ', ''), 'num': lid});
					}
				} catch(e) {continue}
			}
			console.log(d);
			th = '';
			for(i=0; i<d.length; i++) {
				th+= tpl('search', d[i]);
			}
			$('.comic p.img').html(th);
		});
	}, $init = function() {
		fquery = window.location.search.substring(1);
		fpath = window.location.pathname.substring(1);
		if(fpath !== '' && fpath !== 'index.php') {
			$fetch('num='+fpath);
		} else if(fquery !== '' && fquery !== 'num=') {
			$fetch(fquery);
		} else $fetchl();
		window.onresize = $res;
		$res();
	}
	$(function() {
		$init();
		window.addEventListener('popstate', function(e) {
			setTimeout(function() {
				q = document.location.search.substring(1);
				p = window.location.pathname.substring(1);
				if(p !== '' && p !== 'index.php') $fetch(p);
				else $fetch(q);
			}, 100);
			
		});
	});
	</script>
</head>
<body>
	<div class='comic'>


	</div>

	<div class='arr-l'></div>
	<div class='arr-r'></div>
</body>
</html>
