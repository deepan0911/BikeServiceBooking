function checkAvailability() {
    var service_date = document.getElementById("service_date").value;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "check_availability.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("service_date=" + service_date);
    xhr.onload = function() {
      if (xhr.status === 200) {
        document.getElementById("availability-status").innerHTML = xhr.responseText;
      }
    };
  }
  
  document.getElementById("service_date").addEventListener("change", checkAvailability);

 
  
  
  // Function to handle form submission via AJAX
  function submitForm(event) {
    event.preventDefault();
    var formData = new FormData(document.getElementById("booking-form"));
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "book_service.php", true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        var response = xhr.responseText.trim();
        if (response.includes("Booking successful!")) {
          // Hide the form and display the success message
          document.getElementById("booking-form").style.display = "none";
          document.getElementById("booking-status").innerHTML = "<div style='color: green; font-family: Cinzel, serif; font-size: 20px;'>" + response + "</div>";
        } else {
          // Display error message
          document.getElementById("booking-status").innerHTML = "<div style='color: red; font-family: Cinzel, serif; font-size: 20px;'>" + response + "</div>";
        }
     }
};
xhr.send(formData);
}

document.getElementById("booking-form").addEventListener("submit", submitForm);

// Function to handle logout
function logoutAlert(event) {
  if (!confirm("Are you sure you want to logout?")) {
      event.preventDefault(); // Prevent the default action
      return false; // Stop the link from following
  }
  return true; // Allow the logout if confirmed
}



const form = document.querySelector('.contact-form form');
const nameInput = document.querySelector('#name');
const emailInput = document.querySelector('#email');
const messageInput = document.querySelector('#message');

form.addEventListener('submit', (e) => {
  if (nameInput.value.trim() === '') {
    alert('Please enter your name');
    e.preventDefault();
  } else if (emailInput.value.trim() === '' || !emailInput.value.includes('@')) {
    alert('Please enter a valid email address');
    e.preventDefault();
  } else if (messageInput.value.trim() === '') {
    alert('Please enter a message');
    e.preventDefault();
  }
});



