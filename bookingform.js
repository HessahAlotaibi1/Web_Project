//we did this code in phase 1 but now it gave an error in the console so we spreated the code into two files in case we need it
//but for now we didn't had any issue in the booking form
document.getElementById("booking-form").addEventListener("submit", function (event) {
  event.preventDefault(); 

  const doctorName = document.getElementById("doctor-name").value;
  const appointmentDate = document.getElementById("appointment-date").value;
  const appointmentTime = document.getElementById("appointment-time").value;
  const reason = document.getElementById("reason").value;

  if (!doctorName || !appointmentDate || !appointmentTime || !reason) {
      alert("Please fill in all fields!");
      return;
  }

  fetch("book_appointment.php", {
      method: "POST",
      headers: {
          "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `doctor_name=${encodeURIComponent(doctorName)}&date=${encodeURIComponent(appointmentDate)}&time=${encodeURIComponent(appointmentTime)}&reason=${encodeURIComponent(reason)}`
  })
  .then(response => response.text())
  .then(data => {
      alert(data);
      if (data.includes("Booking successful")) {
          window.location.href = "patient.php";
      }
  })
  .catch(error => console.error("Error:", error));
});