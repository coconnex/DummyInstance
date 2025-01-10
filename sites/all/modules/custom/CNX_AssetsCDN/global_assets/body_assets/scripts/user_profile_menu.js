  let btnProfile = document.querySelector("#user_profile");
  let contProfile = document.querySelector("#user_profile_container");
  let profileMenuShowHide;
  if(mdb.Animate && contProfile){
      profileMenuShowHide = new mdb.Animate(contProfile, {
      animation: 'fade-in',  
      animationRepeat: false,
      animationDuration: 1200,
      });
  }
  
 
  if(btnProfile){
    btnProfile.addEventListener("click", (event) => {
      event.stopPropagation();
      if(contProfile){
        if(profileMenuShowHide) profileMenuShowHide.stopAnimation();
        if(contProfile.classList.contains("d-none")){
          contProfile.classList.remove("d-none");
          if(profileMenuShowHide) profileMenuShowHide.startAnimation();                 
        }else{         
          contProfile.classList.add("d-none");         
        }
        
      }
    });
  }

  function disableDoubleClickOnAnchorElems(query){
    let menuLinks = document.querySelectorAll(query);
    for(let i = 0; i < menuLinks.length; i++){
      menuLinks[i].addEventListener('click', (event) => {
      event.preventDefault();    
      if(event.currentTarget instanceof HTMLAnchorElement){
        if(event.currentTarget.classList.contains('cnx-link-clicked')){
          return false;
        }else{
          event.currentTarget.classList.add('cnx-link-clicked');
          window.location = event.currentTarget.href;
        }        
      }
      return true;
      })
    }
  }
 
  disableDoubleClickOnAnchorElems("#nav_bar a");
  disableDoubleClickOnAnchorElems("#user_profile_container a");
  disableDoubleClickOnAnchorElems(".login_buttons a");

  document.addEventListener('click', (event) => {   
    let menuContainer = document.querySelector("#user_profile_container");
    if(menuContainer instanceof HTMLDivElement){
      if(!(menuContainer.classList.contains('d-none'))) menuContainer.classList.add('d-none');
    }
  })