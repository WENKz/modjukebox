<!--
This is hopefully going to replace the current stlying with just a little bit of material design.
Namely, it will be the buttons and maybe some jQuery elements.

This new index and remix player will include elements from:
	* polymer
	* jQuery / jQuery UI
	* a few HTML5 boilerplate things
	* and of course, echonest related things
-->

<!doctype html>
<html>

	<head>
		<!-- Loading in polymer stuff -->
		<title>b∞x</title>
		<meta name="description" content="A music player with multiple songs based off of the Infinite Jukebox">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1, user-scalable=yes">
		<script src="./components/bower_components/webcomponentsjs/webcomponents.js"></script>
		<link rel="import" href="./components/bower_components/paper-button/paper-button.html">
		<link rel="stylesheet" href="my.css">
		<link rel="stylesheet" href="main.css">
		<link href='http://fonts.googleapis.com/css?family=Playfair+Display:400,400italic' rel='stylesheet' type='text/css'>
	</head>
	
	<body>
		<div id="main_content">
			<div id="title_header">
				b∞x
			</div>
			<div class="uploader">
				<form action="https://s3.amazonaws.com/static.echonest.com" method="post" enctype="multipart/form-data">
				<input id='f-filename' type="hidden" name="key" value="audio2/${filename}">
				<input id='f-key' type="hidden" name="AWSAccessKeyId" value="YOUR_AWS_ACCESS_KEY">
				<input type="hidden" name="acl" value="public-read">
				<input type="hidden" name="success_action_redirect" value="http://localhost/remix.html">
				<input id='f-policy' type="hidden" name="policy" value="YOUR_POLICY_DOCUMENT_BASE64_ENCODED">
				<input id='f-signature' type="hidden" name="signature" value="YOUR_CALCULATED_SIGNATURE">
				<input type="hidden" name="Content-Type" value="audio/mpeg"><button id="choose_button">Select MP3</button><input id="upload_input" name="file" type="file" class="file">
				<paper-button class="upload_button">
				<input id='upload' type="submit" value="Submit">
				</paper-button>
				</form>
			</div>
			<div id='global_footer'>
				powered by <a href="http://developer.echonest.com/">echonest</a>, special thanks to <a href="https://twitter.com/plamere">Paul Lamere</a> for his original <a href="http://infinitejuke.com/">concept</a>.
			</div>
		</div>
		<script type="text/javascript">
			var button = document.getElementById('choose_button');
			var input  = document.getElementById('upload_input');

			// Making input invisible, but leaving shown fo graceful degradation
			input.style.display = 'none';
			button.style.display = 'initial';

			button.addEventListener('click', function (e) {
				e.preventDefault();
 
				input.click();
			});

			input.addEventListener('change', function () {
				button.innerText = this.value; 
			});
		</script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	</body>
<!-- Ensuring data is sent to echonest properly for review -->
<script type="text/javascript">
function info(s) {
    $("#info").text(s);
}
function endsWith(str, suffix) {
    return str.indexOf(suffix, str.length - suffix.length) !== -1;
}
function fetchSignature() {
    var url = 'http://labs.echonest.com/Uploader/policy?callback=?'
    $.getJSON(url, {}, function(data) {
        console.log(url);
        policy = data.policy;
        signature = data.signature;
        $('#f-policy').val(data.policy);
        $('#f-signature').val(data.signature);
        $('#f-key').val(data.key);
    });
}
function fetchQinfo() {
    var url = 'http://labs.echonest.com/Uploader/qinfo?callback=?'
    $.getJSON(url, {}, function(data) {
        info("Estimated analysis time: " + Math.floor(data.estimated_wait * 1.2) + " seconds.");
    });
}
function randomName() {
    return new Date().getTime() + '-' + Math.floor(Math.random() * 100000000)
}
function fixFileName(name) {
    name = name.replace(/c:\\fakepath\\/i, '');
    name = name.replace(/[^A-Z0-9.\-]+/gi, ' ');
    return 'audio2/' + new Date().getTime() + '/' + name;
}
function init() {
    $("#f-filename").attr('value', 'audio2/' + randomName() + '.mp3');
    $("#file").change( 
        function() {
            var filename = $("#file").val();
            if (endsWith(filename.toLowerCase(), ".mp3")) {
                $("#f-filename").attr('value', fixFileName(filename));
                $("#upload").removeAttr('disabled');
            } else {
                alert('Sorry, this app only supports MP3s');
                $("#upload").attr('disabled', 'disabled');
            }
        }
    );
    fetchSignature();
    fetchQinfo();
}
window.onload = init;
</script>
</html>
