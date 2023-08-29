/*POST MAKER MODAL*/
const modal = document.querySelector(".modal");
const overlay = document.querySelector(".overlay");
const openModalBtn = document.querySelector(".btn-open");
const closeModalBtn = document.querySelector(".btn-close");

// close modal function
const closeModal = function () {
  modal.classList.add("hidden");
  overlay.classList.add("hidden");
};

// close the modal when the close button and overlay is clicked
closeModalBtn.addEventListener("click", closeModal);
overlay.addEventListener("click", closeModal);

// close modal when the Esc key is pressed
document.addEventListener("keydown", function (e) {
  if (e.key === "Escape" && !modal.classList.contains("hidden")) {
    closeModal();
  }
});

// open modal function
const openModal = function () {
  modal.classList.remove("hidden");
  overlay.classList.remove("hidden");
};
// open modal event
openModalBtn.addEventListener("click", openModal);

/* GETTiNG HOW MANY CHARS ARE IN TEXTAREA ELEMENT */
const textarea = document.getElementById('myTextarea');
const lengthSpan = document.getElementById('lengthSpan');

textarea.addEventListener('input', () => {
  const length = textarea.value.length;
  lengthSpan.innerText = length;
});
/* Post DropDrown */
document.getElementById("delete_post").addEventListener("click", function(event) {
  event.preventDefault(); // Prevent the link from navigating
  
  // Find the form and submit it
  var form = document.getElementById("myForm");
  form.submit();
});