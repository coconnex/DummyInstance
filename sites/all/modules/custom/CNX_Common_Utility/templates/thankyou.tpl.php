
<div class="bg-gray">
<div id="thankyoubox" class="thankyou_info">
	<div class="thankyou_content">
        <div class="thankyou_page_heading">
            <h1>Thank You !</h1>
        </div>
        <div class="page_info">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed dignissimos ducimus deserunt esse et neque provident illum exercitationem nam dolores facere consequuntur doloremque doloribus nulla, quia non voluptates cum praesentium?</p>
        </div>
        <div class="call_to_action_btn">
            <button class="common_btn">call to action</button>
            <button class="common_btn">call to action</button>
        </div>
    </div>
</div>
</div>

<script>
	$('body').on('keypress', function(e) {
		var key = (e.keyCode || e.which);
		if (key == 13 || key == 3) {
			$('#user-login').submit();
		}
	});
	function loginOnLoad(){
		if(document.getElementById("thankyoubox")){
			var ch = document.getElementById('content-header');
			setPageHeight();
			window.addEventListener("resize",setPageHeight);
		}
	}
	function setPageHeight(){
		var loginElem = document.getElementById("thankyoubox");
		var thankyouContainer = document.getElementById("thankyou-container");
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