<?php
	global $theme_key;
	global $base_url;
	$logopath = '/'. theme_get_setting('logo_path', $theme_key);
	// debug($logopath,1);
	?>
	<style>
	#footer,.form_headings,.border-bottom{
		display: none;
	}
</style>

<div id="Resetpassword" class="Resetpassword_center">
<div id="login-logo" style="background-image: url('<?php echo $logopath;?>');"></div>
	<div class="fw-bold fs-4 text-center pt-3 pb-3 login-title" style="letter-spacing: 1.6px;">Reset Your Password</div>
	<?php
	print drupal_render($form['message']);
	?>
	<div class="ui-btn-inline">
		<?php
		print drupal_render($form['submit']);
		?>
	</div>
</div>

<script>
	function loginOnLoad() {
		if (document.getElementById("Resetpassword")) {
			var ch = document.getElementById('content-header');
			//if(ch) ch.parentNode.removeChild(ch);
			setPageHeight();
			window.addEventListener("resize", setPageHeight);
		}
	}

	function setPageHeight() {
		var loginElem = document.getElementById("Resetpassword");
		var loginContainer = document.getElementById("login-container");
		loginElem.parentNode.style.height = window.innerHeight + "px";
		loginElem.parentNode.style.width = window.innerWidth + "px";
		if (window.innerHeight < 440) {
			document.body.style.overflowY = "scroll";
		} else {
			document.body.style.overflowY = "hidden";
		}
	}
	window.addEventListener("load", loginOnLoad);
</script>