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
        alert("❌ يرجى إدخال بريد إلكتروني صالح.");
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
            <h2>مرحبًا، ${sanitize(userData.firstName)}!</h2>
            <p><strong>المعرف:</strong> ${sanitize(userData.id)}</p>
            <p><strong>البريد الإلكتروني:</strong> ${sanitize(userData.email)}</p>
            ${userData.speciality ? `<p><strong>التخصص:</strong> ${sanitize(userData.speciality)}</p>` : ""}
            <p><strong>تاريخ الميلاد:</strong> ${sanitize(userData.dob)}</p>
            <p><strong>الجنس:</strong> ${sanitize(userData.gender)}</p>
        `;
    }
}

// دالة لاسترجاع قيمة المدخلات والتحقق من وجودها
function getValue(id) {
    return document.getElementById(id)?.value.trim() || "";
}

// دالة للتحقق من صحة البريد الإلكتروني
function validateEmail(email) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email) ? email : "";
}

// دالة لتنظيف المدخلات لمنع XSS
function sanitize(input) {
    return input.replace(/</g, "&lt;").replace(/>/g, "&gt;");
}


document.getElementById("booking-form").addEventListener("submit", function (event) {
    event.preventDefault(); // منع التحديث الافتراضي للصفحة

    const doctorName = document.getElementById("doctor-name").value;
    const appointmentDate = document.getElementById("appointment-date").value;
    const appointmentTime = document.getElementById("appointment-time").value;
    const reason = document.getElementById("reason").value;

    if (!doctorName || !appointmentDate || !appointmentTime || !reason) {
        alert("الرجاء تعبئة جميع الحقول!");
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
        alert(data); // عرض رسالة النجاح أو الفشل
        if (data.includes("تم الحجز بنجاح")) {
            window.location.href = "patient.php"; // تحويل المستخدم بعد نجاح الحجز
        }
    })
    .catch(error => console.error("Error:", error));
});