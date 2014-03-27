function playSound(primary, secondary) {
	document.getElementById("sound").innerHTML=
	'<audio autoplay="autoplay">' +
	'<source src="' + primary + '">' +
	'<source src="' + secondary + '">' +
	'Your browser does not support the audio tag.' +
	'</audio>';
}

function matchWord(id, guess) {
	var getWord = "get_word.php";
	var scripts = document.getElementsByTagName ( "script" );
	var l = scripts.length;
	var i = 0
	for (i = 0; i < l; ++i )
	{
		if ( scripts[i].src == getWord )
		{
			scripts[i].src += "?id=" + id;
			break;
		}
	}
	scripts[i] = getWord;
}