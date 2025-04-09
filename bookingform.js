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