function saveUserData(role) {
    let userData = {};
    let gender = document.querySelector('input[name="gender"]:checked')?.value || "Not Specified";

    if (role === "doctor") {
        userData = {
            firstName: getValue("firstName"),
            lastName: getValue("lastName"),
            id: getValue("doctorID"),
            email: validateEmail(getValue("email")),
            speciality: getValue("speciality"),
            gender: gender,
            photo: document.getElementById("photo")?.files[0]?.name || "No Photo"
        };
    } else if (role === "patient") {
        userData = {
            firstName: getValue("patientFirstName"),
            lastName: getValue("patientLastName"),
            id: getValue("patientID"),
            email: validateEmail(getValue("patientEmail")),
            dob: getValue("dob") || "Not Provided",
            gender: gender
        };
    }

    if (!userData.email) {
        alert("Please enter a valid email address.");
        return;
    }

    localStorage.setItem("userData", JSON.stringify(userData));

    window.location.href = role === "doctor" ? "doctor.php" : "patient.php";
}

function loadUserData() {
    let userData = localStorage.getItem("userData");
    if (userData) {
        userData = JSON.parse(userData);

        document.getElementById("userInfo").innerHTML = `
            <h2>Hello ${sanitize(userData.firstName)}!</h2>
            <p><strong>User:</strong> ${sanitize(userData.id)}</p>
            <p><strong>Email:</strong> ${sanitize(userData.email)}</p>
            ${userData.speciality ? `<p><strong>Specialization:</strong> ${sanitize(userData.speciality)}</p>` : ""}
            <p><strong>Date of birth:</strong> ${sanitize(userData.dob)}</p>
            <p><strong>Gender:</strong> ${sanitize(userData.gender)}</p>
        `;
    }
}

function getValue(id) {
    return document.getElementById(id)?.value.trim() || "";
}

function validateEmail(email) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email) ? email : "";
}

function sanitize(input) {
    return input.replace(/</g, "&lt;").replace(/>/g, "&gt;");
}
