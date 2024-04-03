//handling profile click
function profileSetting() {
  //giving animation
  document.getElementById("profile-setting").classList.toggle("slide");
  //showing and hiding setting container
  document.getElementById("profile-setting").classList.toggle("opacity-0");
  //changing z-index
  document.getElementById("profile-setting").classList.toggle("z-30");
  //showing and hiding profile img uploader
  document.getElementById("profile-img").classList.toggle("hidden");
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
  //showing and hiding logout and leave req button
  document.getElementById("logout-button").classList.toggle("hidden");
  document.getElementById("leave-button").classList.toggle("hidden");
}

//alert
function hideAlert(element) {
  //hiding alert
  element.parentNode.classList.add("opacity-0");

  //removing alert
  setTimeout(() => {
    element.parentNode.remove();
  }, 200);
}
