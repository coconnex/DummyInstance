<?php
	global $theme_key;
	global $base_url;
	$logopath = '/'. theme_get_setting('logo_path', $theme_key);
	// debug($logopath,1);
	?>


<div id="UserProfileForm" class="UserProfileForm_center">
<div><a href="<?php echo $base_url; ?>" ><i class="fas fa-arrow-left mt-2 ms-2 mt-md-2 ms-md-3"></i></a></div>
<div id="login-logo" style="background-image: url('<?php echo $logopath;?>');"></div>
	<div class="fw-bold fs-4 text-center pt-3 pb-3 login-title" >Set Your Password</div>
  <?php print drupal_render($form); ?>
</div>


<script>
	function loginOnLoad(){
		if(document.getElementById("UserProfileForm")){
			var ch = document.getElementById('content-header');
			//if(ch) ch.parentNode.removeChild(ch);
			setPageHeight();
			window.addEventListener("resize",setPageHeight);
		}
	}

	function setPageHeight(){
		var loginElem = document.getElementById("UserProfileForm");
		var loginContainer = document.getElementById("login-container");
		loginElem.parentNode.style.height = window.innerHeight + "px";
		loginElem.parentNode.style.width = window.innerWidth + "px";
		if(window.innerHeight < 440){
			document.body.style.overflowY = "scroll";
		}else{
			document.body.style.overflowY = "hidden";
		}
	}
	window.addEventListener("load",loginOnLoad);

</script>