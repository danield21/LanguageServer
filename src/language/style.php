<?php
	header("Content-type: text/css; charset: UTF-8");
	require_once 'php/Color.php';
	$background = new Color(119, 119, 119);
	$main = new Color(18, 55, 137);
	$nav = new Color(60, 170, 7);
	$font = new Color(255, 255, 255);
	$mob_background = new Color(136, 136, 136);
	$mob_main = new Color(193, 240, 0);
	$mob_nav = new Color(100, 160, 170);
	$mob_font = new Color(0, 0, 0);
?>
html, body {
	margin: 0px;
}
audio {
	width: 150pt;
}
form {
	margin:0;
}
table { 
    border-spacing: 0;
    border-collapse: collapse;
}
body, nav{
	font-size: 13pt;
	font-family: Georgia, san-serif;
}
body {
	background-color:<?php echo $background; ?>;
	text-align:center;
}
#background {
	background-color:<?php echo $main; ?>;
	position: relative;
	top: 0;
	max-width: 800pt;
	min-width: 500pt;
	width:100%;
	height:100%;
	margin: 0px auto;
}
header {
	background-color:<?php echo $main; ?>;
	position:absolute;
	top:0;
	width:100%;
	z-index:2;
}
img {
	border-radius: 10pt;
	-moz-border-radius: 10pt;
	-webkit-border-radius: 10pt;
	display:block;
}
img.logo {
	margin-left:auto;
	margin-right:auto;
	width:80%;
	height:60pt;
}
nav {
	background: <?php echo $nav; ?>;
	background: linear-gradient(top, <?php echo $nav->lighter(16); ?> 0%, <?php echo $nav->darker(16); ?> 100%);
	background: -moz-linear-gradient(top, <?php echo $nav->lighter(16); ?> 0%, <?php echo $nav->darker(16); ?> 100%);
	background: -webkit-linear-gradient(top, <?php echo $nav->lighter(16); ?> 0%, <?php echo $nav->darker(16); ?> 100%);
	height: 28pt;
	z-index:100;
}
body, a, p {
	color: <?php echo $font; ?>;
}
a:link, a:visited, a:active {
	text-decoration:none;
}
a:hover {
	text-decoration:underline;
}
p {
	display: block;
}
nav {
	display: block;
	margin: 0px auto; 
	text-align: center;
}
nav > section {
	background: <?php echo $nav; ?>;
	background: linear-gradient(top, <?php echo $nav->lighter(16); ?> 0%, <?php echo $nav->darker(16); ?> 100%);
	background: -moz-linear-gradient(top, <?php echo $nav->lighter(16); ?> 0%, <?php echo $nav->darker(16); ?> 100%);
	background: -webkit-linear-gradient(top, <?php echo $nav->lighter(16); ?> 0%, <?php echo $nav->darker(16); ?> 100%);
	position: relative;
}
nav > section.left_menu {
	float:left;
}
nav > section.right_menu {
	float:right;
}
nav section.right_menu section {
	right: 100%;
	margin-right: -100%
}
nav > section:hover {
	position: relative;
}
nav section:after {
	content: "";
	clear: both;
	display: block;
}
nav section div {
	float: left;
}
nav section div:hover {
	background: <?php echo $nav->darker(16); ?>;
	background: linear-gradient(top, <?php echo $nav; ?> 0%, <?php echo $nav->darker(32); ?> 100%);
	background: -moz-linear-gradient(top, <?php echo $nav; ?> 0%, <?php echo $nav->darker(32); ?> 100%);
	background: -webkit-linear-gradient(top, <?php echo $nav; ?> 0%, <?php echo $nav->darker(32); ?> 100%);
}
nav section div a, nav section div p {
	margin: 0px;
	display: block;
	padding: 0.5em;
	color: <?php echo $font; ?>;
	text-decoration: none;
}
nav section div:hover a, nav section div:hover p {
	color: <?php echo $font; ?>;
}
nav section section {
	display: none;
	background: <?php echo $nav->darker(32); ?>;
	border-bottom-right-radius: 10pt;
	border-bottom-left-radius: 10pt;
	padding: 5pt;
	position: absolute;
}
nav section div:hover > section {
	display: block;
}
nav section section a, nav section section p {
	margin: 0px;
	float: none;
	position: relative;
	padding: 0.5em;
}
nav section section a:hover, nav section section p:hover {
	background: <?php echo $nav->darker(48); ?>;
	border-radius: 10px;
	color: <?php echo $font; ?>;
}
nav section section section {
	position: absolute; left: 100%; top:0;
}
section#body_content {
	width:100%;
	position:absolute;
	text-align:center;
	height: 100%;
	overflow:auto;
}
section#body_content > div {
	height:100pt;
}
table.activities {
	margin: 0 auto;
}
table.activities th a {
	display:block;
	width:100%;
	height:100%;
}
table.activities td a {
	text-align:center;
	display:table-cell;
	height:225pt;
	width:225pt;
	vertical-align:middle;
	background: <?php echo $font; ?>;
	color: #A00000;
	font-size: 50pt;
	font-family: "Times New Roman";
	border-radius: 25pt;
	-moz-border-radius: 25pt;
	-webkit-border-radius: 25pt;
}
table.activities td a:hover {
	text-decoration:none;
	background: #DDDDDD;
	color: #800000;
}
table.categories {
	margin: 0 auto;
}
table.categories th {
	background: <?php echo $nav; ?>;
	background: linear-gradient(top, <?php echo $nav->lighter(16); ?> 0%, <?php echo $nav->darker(16); ?> 100%);
	background: -moz-linear-gradient(top, <?php echo $nav->lighter(16); ?> 0%, <?php echo $nav->darker(16); ?> 100%);
	background: -webkit-linear-gradient(top, <?php echo $nav->lighter(16); ?> 0%, <?php echo $nav->darker(16); ?> 100%);
	border-radius: 7pt;
}
table.categories th:hover {
	background: <?php echo $nav; ?>;
	background: linear-gradient(top, <?php echo $nav->darker(16); ?> 0%, <?php echo $nav->lighter(16); ?> 100%);
	background: -moz-linear-gradient(top, <?php echo $nav->darker(16); ?> 0%, <?php echo $nav->lighter(16); ?> 100%);
	background: -webkit-linear-gradient(top, <?php echo $nav->darker(16); ?> 0%, <?php echo $nav->lighter(16); ?> 100%);
}
table.categories th a {
	display:block;
	width:100%;
	height:100%;
}
table.categories img {
	position:relative;
	margin:0pt -10pt 10pt -10pt;
	display: inline;
	height: 70pt;
	width: 70pt;
}
table.categories img:hover {
	z-index:4;
	margin: 0pt -15pt;
	height: 80pt;
	width: 80pt;
}
#filter_options {
	width:100%;
}
#filter_options table {
	max-width:100%;
	overflow:auto;
}
#filter_options input {
	width:auto;
}
section#column1 {
	width:50%;
	position:absolute;
	left:0pt;
}
section#column2 {
	width:50%;
	position:absolute;
	right:0pt;
}
section#column1 table, section#column2 table {
	width:100%;
}
.highlight {
	background-color: <?php echo $main->lighter(32)?>;
}
.lowlight {
	background-color: <?php echo $main->darker(32)?>;
}
th {
	background: <?php echo $nav; ?>;
	background: linear-gradient(top, <?php echo $nav->lighter(16); ?> 0%, <?php echo $nav->darker(16); ?> 100%);
	background: -moz-linear-gradient(top, <?php echo $nav->lighter(16); ?> 0%, <?php echo $nav->darker(16); ?> 100%);
	background: -webkit-linear-gradient(top, <?php echo $nav->lighter(16); ?> 0%, <?php echo $nav->darker(16); ?> 100%);
}
.highlight th {
	background: <?php echo $nav; ?>;
	background: linear-gradient(top, <?php echo $nav->darker(16)?> 0%, <?php echo $nav->lighter(16); ?> ; ?> 100%);
	background: -moz-linear-gradient(top, <?php echo $nav->darker(16)?> 0%, <?php echo $nav->lighter(16); ?> ; ?> 100%);
	background: -webkit-linear-gradient(top, <?php echo $nav->darker(16)?> 0%, <?php echo $nav->lighter(16); ?> ; ?> 100%);
}
table.word_list {
	margin-left:auto;
	margin-right:auto;
}
table.word img {
	display: inline;
	width:90%;
	height:90%;
}
table.word img:hover {
	width:95%;
	height:95%;
}
table.word td {
	width:200;
	text-align:center;
}
table.word td.word_pic {
	height: 200;
}
input[type=button], input[type=submit] {
	border:0pt;
	border-radius:4pt;
	-moz-border-radius:4pt;
	-webkit-border-radius:4pt;
	width:60pt;
	background: linear-gradient(top, #EEEEEE, #AAAAAA);
	background: -moz-linear-gradient(top, #EEEEEE, #AAAAAA);
	background: -o-linear-gradient(top, #EEEEEE, #AAAAAA);
	background: -webkit-linear-gradient(top, #EEEEEE, #AAAAAA);
}
input[type=submit]:hover , input[type=button]:hover {
	background: linear-gradient(top, #AAAAAA, #EEEEEE);
	background: -moz-linear-gradient(top, #AAAAAA, #EEEEEE);
	background: -o-linear-gradient(top, #AAAAAA, #EEEEEE);
	background: -webkit-linear-gradient(top, #AAAAAA, #EEEEEE);
}
#column2 input[type=button], #column2 input[type=button]:hover {
	border-radius:4pt;
	-moz-border-radius:4pt;
	-webkit-border-radius:4pt;
	width:60pt;
	color:#AAAAAA;
	background: linear-gradient(to bottom, #BBBBBB, #FFFFFF);
	background: -moz-linear-gradient(bottom, #BBBBBB, #FFFFFF);
	background: -o-linear-gradient(bottom, #BBBBBB, #FFFFFF);
	background: -webkit-linear-gradient(top, #BBBBBB, #FFFFFF);
}
@media only screen and (max-device-width: 480px) {
	body, nav{
		font-size: 18pt;
	}
	body{
		background-color:<?php echo $mob_background; ?>;
	}
	#background {
		position:absolute;
		left:50%;
		margin-left: -50%;
		min-height:50%;
		width:100%;
		background-color:<?php echo $mob_main; ?>;
	}
	header {
		background-color:<?php echo $mob_main; ?>;
	}
	img.logo {
		width:80%;
	}
	body, a:link, a:visited, a:hover, a:active {
		color: #000000;
	}
	nav {
		background: <?php echo $mob_nav; ?>;
		background: linear-gradient(top, <?php echo $mob_nav->lighter(16); ?> 0%, <?php echo $mob_nav->darker(16); ?> 100%);
		background: -moz-linear-gradient(top, <?php echo $mob_nav->lighter(16); ?> 0%, <?php echo $mob_nav->darker(16); ?> 100%);
		background: -webkit-linear-gradient(top, <?php echo $mob_nav->lighter(16); ?> 0%, <?php echo $mob_nav->darker(16); ?> 100%);
		height: 2.4em;
	}
	nav > section {
		background: <?php echo $mob_nav; ?>;
		background: linear-gradient(top, <?php echo $mob_nav->lighter(16); ?> 0%, <?php echo $mob_nav->darker(16); ?> 100%);
		background: -moz-linear-gradient(top, <?php echo $mob_nav->lighter(16); ?> 0%, <?php echo $mob_nav->darker(16); ?> 100%);
		background: -webkit-linear-gradient(top, <?php echo $mob_nav->lighter(16); ?> 0%, <?php echo $mob_nav->darker(16); ?> 100%);
	}
	nav section div:hover {
		background: <?php echo $mob_nav->darker(16); ?>;
		background: linear-gradient(top, <?php echo $mob_nav; ?> 0%, <?php echo $mob_nav->darker(32); ?> 100%);
		background: -moz-linear-gradient(top, <?php echo $mob_nav; ?> 0%, <?php echo $mob_nav->darker(32); ?> 100%);
		background: -webkit-linear-gradient(top, <?php echo $mob_nav; ?> 0%, <?php echo $mob_nav->darker(32); ?> 100%);
	}
	nav section div a, nav section div p {
		padding: 0.5em;
		color: <?php echo $mob_font; ?>;
	}
	nav section div:hover a, nav section div:hover p {
		color: <?php echo $mob_font; ?>;
	}
	nav section section {
		background: <?php echo $mob_nav->darker(32); ?>;
		border-bottom-right-radius: 10pt;
		border-bottom-left-radius: 10pt;
		padding: 5pt;
	}
	nav section section a, nav section section p {
		padding: 0.5em;
	}
	nav section section a:hover, nav section section p:hover {
		background: <?php echo $mob_nav->darker(48); ?>;
		color: <?php echo $mob_font; ?>;
	}
	section#body_content div {
		height:200pt;
	}
	section#body_content table.choose img {
		margin:10pt 5pt 0pt 5pt;
	}
	section#body_content table.choose img:hover {
		margin:0pt 0pt 0pt 0pt;
	}
	th {
		background: <?php echo $mob_nav; ?>;
		background: linear-gradient(top, <?php echo $mob_nav->lighter(16); ?> 0%, <?php echo $mob_nav->darker(16); ?> 100%);
		background: -moz-linear-gradient(top, <?php echo $mob_nav->lighter(16); ?> 0%, <?php echo $mob_nav->darker(16); ?> 100%);
		background: -webkit-linear-gradient(top, <?php echo $mob_nav->lighter(16); ?> 0%, <?php echo $mob_nav->darker(16); ?> 100%);
	}
	.highlight th {
		background: <?php echo $mob_nav; ?>;
		background: linear-gradient(top, <?php echo $mob_nav->darker(16); ?> 0%, <?php echo $mob_nav->lighter(16); ?> 100%);
		background: -moz-linear-gradient(top, <?php echo $mob_nav->darker(16); ?> 0%, <?php echo $mob_nav->lighter(16); ?> 100%);
		background: -webkit-linear-gradient(top, <?php echo $mob_nav->darker(16); ?> 0%, <?php echo $mob_nav->lighter(16); ?> 100%);
	}
	table.word td {
		width: 20em;
	}
	table.word td.word_pic {
		height: 20em;
	}
}
@media print {
	body {
		background:#FFFFFF;
	}
	#background {
		background:#FFFFFF;
	}
	header {
		display:none;
	}
	section#body_content {
		width:1000px;
		color:#000000;
		overflow:visible;
		height:auto;
	}
	section#body_content div {
		display:none;
	}
	table.word_list td {
		border:1pt solid #000000;
		height:125px;
		width:auto;
	}
	table.word img, table.word img:hover {
		width:150px;
		height:150px;
	}
	table.word td {
		border:0pt none;
		color:#000000;
		font-size:17px;
		height:auto;
		width:auto;
	}
	table.word td.word_pic {
		height: auto;
	}
	input {
		display:none;
	}
}
