function showPwd() {
  let showCheckbox = document.getElementById("password");
  if (showCheckbox.type == "password") {
    showCheckbox.type = "text";
  } else {
    showCheckbox.type = "password";
  }
}

// Registration Modal

// Get the modal
const modal = document.getElementById("registrationModal");

// Get the button that opens the modal
const btn = document.getElementById("registrationBtn");

// Get the <span> element that closes the modal
const span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function () {
  modal.style.display = "flex";
};

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
  modal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};
// end Registration Modal
