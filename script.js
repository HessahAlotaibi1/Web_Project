function saveUserData(role) {
  let userData = {};

  let gender = document.querySelector('input[name="gender"]:checked')?.value;

  if (role === "doctor") {
      userData = {
          firstName: document.getElementById("firstName").value,
          lastName: document.getElementById("lastName").value,
          id: document.getElementById("doctorID").value,
          email: document.getElementById("email").value,
          speciality: document.getElementById("speciality").value,
          gender: gender, 
          photo: document.getElementById("photo").files[0]?.name
      };
  } else if (role === "patient") {
      userData = {
          firstName: document.getElementById("patientFirstName").value,
          lastName: document.getElementById("patientLastName").value,
          id: document.getElementById("patientID").value,
          email: document.getElementById("patientEmail").value,
          dob: document.getElementById("dob").value, 
          gender: gender  
      };
  }

  localStorage.setItem("userData", JSON.stringify(userData));

  if (role === "doctor") {
      window.location.href = "doctor.html";
  } else {
      window.location.href = "patient.html";
  }
}

function loadUserData() {
  let userData = localStorage.getItem("userData");
  if (userData) {
      userData = JSON.parse(userData);
      document.getElementById("userInfo").innerHTML = `
          <h2>Welcome, ${userData.firstName}!</h2>
          <p><strong>ID:</strong> ${userData.id}</p>
          <p><strong>Email:</strong> ${userData.email}</p>
          ${userData.speciality ? `<p><strong>Speciality:</strong> ${userData.speciality}</p>` : ""}
          ${userData.dob ? `<p><strong>DOB:</strong> ${userData.dob}</p>` : "<p><strong>DOB:</strong> Not Provided</p>"}
          ${userData.gender ? `<p><strong>Gender:</strong> ${userData.gender}</p>` : ""}
      `;
  }
  
}
