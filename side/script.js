//handling profile click
function profileSetting() {
  //giving animation
  document.getElementById("profile-setting").classList.toggle("slide");
  //showing and hiding setting container
  document.getElementById("profile-setting").classList.toggle("opacity-0");
  //showing and hiding profile img uploader
  document.getElementById("profile-submitter").classList.toggle("hidden");
}

//handling submitting of profile form
function submitProfileImg() {
  document.getElementById("profile-form").submit();
}

//handling menu
function menuHandler() {
  //giving animation
  document.getElementById("menu-container").classList.toggle("slide");
  //showing and hiding menu container
  document.getElementById("menu-container").classList.toggle("opacity-0");
}
