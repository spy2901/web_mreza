var span = document.getElementsByClassName('upload-path');
var uploader = document.getElementsByName('image')[0];
var uploadButton = document.getElementById('buttonid');
// image preview
var image = document.getElementById('img');

uploader.onchange = function() {
  if (uploader.files.length > 0) {
    span[0].innerHTML = uploader.files[0].name;
    uploadButton.style.display = "none";
    
    var reader = new FileReader(); // Create a file reader
    reader.onload = function(e) {
      image.src = e.target.result; // Set the src attribute of the image to the uploaded image
    };
    reader.readAsDataURL(uploader.files[0]); // Read the uploaded file as a data URL
  } else {
    span[0].innerHTML = "";
    uploadButton.style.display = "inline-block";
    image.src = ""; // Clear the image source
  }
};

$('#password, #confirm_password').on('keyup', function () {
  if ($('#password').val() == $('#confirm_password').val()) {
    $('#message').html('Matching').css('color', 'green');
  } else 
    $('#message').html('Not Matching').css('color', 'red');
});