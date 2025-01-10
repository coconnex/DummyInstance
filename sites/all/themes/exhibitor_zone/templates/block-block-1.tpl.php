<?php // AG: 25-Sep-23: THE BLOCK CONTENT IS HIDDEN BELOW WHICH MEANS ANY CONTENT ADDED TO THE BLOCK WILL NOT BE PRINTED. SO, DO NOT UNCOMMENT THE BELOW LINE.
//print $content;

use Coconnex\Utils\Config\Config;

global $theme_key;
global $base_url;
global $user;
profile_load_profile($user);
$logopath = '/' . theme_get_setting('logo_path', $theme_key);
$obj_config = new Config("d6");
$event_name = $obj_config::getvar("site_name");
// $menu_data = menu_tree_all_data('menu-exhibitor-main-menu');
// $home_data = menu_tree_all_data('menu-exhibitor-home-menu');
$user_name_initials;
if (isset($user->profile_first_name)) {
  if (!empty($user->profile_first_name)) $user_name_initials = substr($user->profile_first_name, 0, 2);
}
if (isset($user->profile_last_name)) {
  if (!empty($user->profile_last_name)) $user_name_initials = substr($user_name_initials, 0, 1) . substr($user->profile_last_name, 0, 1);
}

$menu_data = menu_tree_all_data('menu-exhibitor-main-menu');
$home_data = menu_tree_all_data('menu-exhibitormenu');
// $userInitials = getUserInitials($user->uid);


$mobile_navs = array_merge($menu_data, $home_data);
$menu_icons = array();
$menu_icons['bookinghistory'] = "fas fa-clock-rotate-left";
$menu_icons['floorplan'] = "fas fa-map";
$menu_icons['mystands'] = "fas fa-cube";
$menu_icons['myorders'] = "fas fa-pen-to-square";
// debug($mobile_navs,1);
?>
<!-- /********************************** Mobile Navbar Starts */ -->

<!-- Navbar -->
<div id="nav_bar" class="navbar d-flex" style="background: #000;">
  <div id="nav_left_block" class="log d-flex">
    <div class="border-end">
      <a href="<?php echo $base_url . "/welcome"; ?>">
        <img class="d-none d-sm-block" title="<?php echo $event_name; ?>" src="/sites/default/files/linear_logo.png" />
      </a>
      <a href="<?php echo $base_url . "/welcome"; ?>">
        <img class="d-sm-none mx-2 img-fluid" title="<?php echo $event_name; ?>" src="/sites/default/files/mobile_linear_logo.png" />
      </a>
    </div>
    <div class="mb-auto mt-auto d-flex">

      <a class="mt-auto mb-auto" href="<?php echo $base_url . "/floorplan"; ?>">
        <button type="button" class="btn-book-stand ms-3 px-3 d-none d-md-block ">
          Book Stand
        </button>
      </a>
      <a class="mt-auto mb-auto" href="<?php echo $base_url . "/floorplan/packages"; ?>">
      <span class="mb-auto mt-auto ms-4 d-none d-md-block packages-btn" ><i class="fas fa-box-archive me-1 "></i> Packages</span>
      </a>
    </div>
  </div>
  <div id="nav_right_block" class="d-flex">
    <div id="user_menu" class="mt-auto mb-auto d-none d-xl-block">
      <div class="ms-auto">
        <div class="d-flex user-menu-active">
          <?php
          foreach ($mobile_navs as $key => $mnavs) {
            if ($mnavs['link']['hidden'] == 0) {
              if (strtolower($mnavs['link']['link_path']) == 'floorplan') continue;
          ?>
              <?php if(arg(0)==$mnavs['link']['link_path']){
                    $add_active='active';
                  }else{
                    $add_active='';
                  } ?>
              <a class="text-dark ms-4" href="<?php echo $base_url . "/" . $mnavs['link']['link_path']; ?>">
                <button type="button" class="btn-menu px-3 me-2 <?php echo  $add_active; ?>">
                  <?php
                  if (key_exists($mnavs['link']['link_path'], $menu_icons)) {
                    $icon_class = $menu_icons[$mnavs['link']['link_path']];
                  ?>
                    <i class="me-1 <?php echo $icon_class; ?>"></i>
                  <?php
                  }
                  ?>
                  <?php echo $mnavs['link']['link_title']; ?>
                </button>
              </a>


          <?php
            }
          }
          ?>
        </div>
      </div>
    </div>
    <div id="user_profile" class="d-flex" style="cursor:pointer">
      <div id="user_initials_container" class="user-initials-container d-none d-sm-block">
        <div id="user_initials" class="user-initials">
          <?php echo (empty($user_name_initials)) ? "<i class='fas fa-user'></i>" : strtoupper($user_name_initials); ?>
        </div>
      </div>
      <div id="user_menu_container" class="d-flex">
        <div id="user_menu_hburger" class="d-flex d-xl-none">
          <div class="mt-auto mb-auto">
            <i class="fas fa-bars fa-2x text-white"></i>
          </div>
        </div>
        <div></div>
      </div>
    </div>
  </div>
</div>

<div id="user_profile_container" class="menu-container ms-auto mt-2 me-2 d-none">
  <div class="mb-4 d-md-none book-stand-btn">
    <a href="<?php echo $base_url . "/floorplan"; ?>">
      <button type="button" class="btn-book-stand px-5 mx-2 mt-2">
        Book Stand
      </button>
    </a>
    <a class="mt-auto mb-auto" href="<?php echo $base_url . "/floorplan/packages"; ?>">
    <span class="d-flex justify-content-center mt-2 mb-5 d-md-none packages-btn text-dark"><i class="fas fa-box-archive mt-1 me-1 "></i> Packages</span>
    </a>
  </div>
  <div class="pt-md-4 ms-4 d-flex">
    <div class="mt-auto mb-auto">
      <a class="text-dark" href="/exhibitor/profile"><i class="far fa-circle-user fa-2x text-dark me-1"></i></a>
    </div>
    <div class="ms-2 mb-auto mt-auto">
      <?php
      $user_name = $user->name;
      if (isset($user->profile_first_name)) {
        if (!empty($user->profile_first_name)) $user_name = ucwords(strtolower($user->profile_first_name));
      }
      if (isset($user->profile_last_name)) {
        if (!empty($user->profile_last_name)) $user_name .= " " .  ucwords(strtolower($user->profile_last_name));
      }
      $_roles = $user->roles;
      // $_roles[4] = "New Role"; //Delete this line
      $rem_key = array_search('authenticated user', $_roles); //remove spaces after user
      if ($rem_key) unset($_roles[$rem_key]);
      $roles = ucwords(implode(", ", $_roles));
      ?>
      <h6 class="m-0 text-dark"><?php echo $user_name; ?></h6>
      <a class="profile-edit" href="/exhibitor/profile">
        <p class="m-0 text-dark"><?php echo $roles; ?> (<span class="text-primary">View/Edit</span>)</p>
      </a>
    </div>
  </div>

  <div class="menu-list">
    <ul class="ps-0 mb-0">
      <hr>
      <div class="d-xl-none">
        <?php
        foreach ($mobile_navs as $key => $mnavs) {
          if ($mnavs['link']['hidden'] == 0) {
            if (strtolower($mnavs['link']['link_path']) == 'floorplan') continue;
        ?>

            <a class="text-dark" href="<?php echo $base_url . "/" . $mnavs['link']['link_path']; ?>">
              <li class="ms-4">
                <?php
                if (key_exists($mnavs['link']['link_path'], $menu_icons)) {
                  $icon_class = $menu_icons[$mnavs['link']['link_path']];
                ?>
                  <i class="me-2 <?php echo $icon_class; ?>"></i>
                <?php
                }
                ?>
                <?php echo $mnavs['link']['link_title']; ?>
              </li>
            </a>
            <hr>
        <?php
          }
        }
        ?>
      </div>
      <a class="text-dark" href="/logout">
        <li class="ms-4"><i class="fas fa-right-to-bracket me-2"></i> Log Out</li>
      </a>
      <hr class="mb-0">
    </ul>
  </div>
</div>

<!-- <div class="td-mobile-logo d-flex m-3" style="background-color: #000; width: fit-content; font-family: Beausite Grand Regular; height:64px; width:207px;">
  <div class="header-td text-white mb-auto mt-auto ms-2" style="font-size: 38px;">
    TD
  </div>
  <div class="header-content mb-auto mt-auto ms-3">
    <p class="text-white mb-0" style="font-size: 14px;">Olympia London</p>
    <p class="text-white mb-0" style="font-size: 14px;">12-14 January 2025</p>
  </div>
</div> -->

<!-- Navbar -->