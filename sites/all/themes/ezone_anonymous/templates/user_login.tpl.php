
	<?php
	use Coconnex\Utils\Config\Config;

	global $theme_key;
	global $base_url;
	$logopath = '/'. theme_get_setting('logo_path', $theme_key);
	// debug($logopath,1);

	$create_new_account_link = '/registration/exhibitor';
	$obj_config = new Config('d6');
	$is_crm_integrated = $obj_config::getvar("IS_CRM_INTEGRATED");
	if($is_crm_integrated == 1){
		$create_new_account_link = '/participation/confirm';
	}
	?>


	<div id="loginbox" class="login_center">
		<div id="login-logo" style="background-image: url('<?php echo $logopath;?>');"></div>
		<div class="fw-bold fs-5 text-center pt-3 login-title"><?php echo $obj_config::getvar('site_name','');?></div>
		<div class="login-welcome-txt mb-2 text-center pt-3 login-title">Welcome to our new self-service platform, where you can view and reserve your stand. Set up your account now to get started.</div>
		<?php print $rendered; ?>
		<div class="login_buttons">
				<div class="float-start"><a href="/user/password"><u>Forgot Password?</u></a></div>
				<div class="float-end"><a href="<?php echo $create_new_account_link; ?>"><u>Create a New Account</u></a></div>
		</div>
	</div>


<script>

    const login_error = <?php echo json_encode($_SESSION['messages']); ?>

	document.body.addEventListener('keypress', (event) => {
		var key = (event.keyCode || event.which);
		if (key == 13 || key == 3) {
			if(document.querySelector('#user-login')) document.querySelector('#user-login').submit();
		}
	});

	function loginOnLoad(){

		if(document.getElementById("loginbox")){

			var ch = document.getElementById('content-header');
			//if(ch) ch.parentNode.removeChild(ch);
			setPageHeight();
			window.addEventListener("resize",setPageHeight);
		}
	}

	function setPageHeight(){
		var loginElem = document.getElementById("loginbox");
		var loginContainer = document.getElementById("login-container");
		loginElem.parentNode.style.height = window.innerHeight + "px";
		loginElem.parentNode.style.width = window.innerWidth + "px";
		if(window.innerHeight < 440){
			document.body.style.overflowY = "scroll";
		}else{
			document.body.style.overflowY = "hidden";
		}
	}

	function setErrorMsgs(){

	}
	window.addEventListener("load",loginOnLoad);


</script>