// js.js

$(document).ready(function () {
  // Check if local storage has form data and populate the fields
  if (localStorage.getItem("signupForm")) {
    var formData = JSON.parse(localStorage.getItem("signupForm"));
    $("#first_name").val(formData.first_name);
    $("#middle_name").val(formData.middle_name);
    $("#last_name").val(formData.last_name);
    // Repeat for other form fields
  }

  // Save form data to local storage on input change
  $('input[type="text"], input[type="email"], input[type="tel"], input[type="password"]').on("input", function () {
    var formData = {
      first_name: $("#first_name").val(),
      middle_name: $("#middle_name").val(),
      last_name: $("#last_name").val(),
      // Repeat for other form fields
    };
    localStorage.setItem("signupForm", JSON.stringify(formData));
  });

  // Function to handle image preview
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $("#item-image-preview").attr("src", e.target.result).show();
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  // Listen for changes to the file input field
  $(":file").change(function () {
    readURL(this);
  });

  // Confirm before logging out
  $("#logout-link").click(function (e) {
    if (!confirm("Are you sure you want to logout?")) {
      e.preventDefault(); // Prevent the default action (following the link)
    }
  });
});
