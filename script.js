 function calculateAttendance() {
  const table = document.getElementById("attendanceTable");
  const rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

  for (let row of rows) {
    const cells = row.getElementsByTagName("td");
    let attendanceMarks = Array.from(cells).slice(2, 8);
    let participationMarks = Array.from(cells).slice(8, 14);
    let absences = 0, participations = 0;

    attendanceMarks.forEach(cell => {
      if (cell.textContent.trim() !== "✓") absences++;
    });
    participationMarks.forEach(cell => {
      if (cell.textContent.trim() === "✓") participations++;
    });

    if (absences < 3) {
      row.style.backgroundColor = "#d9fdd3";
      cells[14].textContent = "Good attendance – Excellent participation";
    } else if (absences <= 4) {
      row.style.backgroundColor = "#fff8b3";
      cells[14].textContent = "Warning – attendance low – You need to participate more";
    } else {
      row.style.backgroundColor = "#ffcccc";
      cells[14].textContent = "Excluded – too many absences – You need to participate more";
    }
  }
}

function validateForm() {
  let valid = true;

  const id = document.getElementById("studentId").value.trim();
  const last = document.getElementById("lastName").value.trim();
  const first = document.getElementById("firstName").value.trim();
  const email = document.getElementById("email").value.trim();

  const idError = document.getElementById("idError");
  const lastError = document.getElementById("lastError");
  const firstError = document.getElementById("firstError");
  const emailError = document.getElementById("emailError");

  idError.textContent = lastError.textContent = firstError.textContent = emailError.textContent = "";

  if (!id.match(/^[0-9]+$/)) {
    idError.textContent = "Student ID must contain numbers only.";
    valid = false;
  }

  if (!last.match(/^[A-Za-z]+$/)) {
    lastError.textContent = "Last name must contain letters only.";
    valid = false;
  }

  if (!first.match(/^[A-Za-z]+$/)) {
    firstError.textContent = "First name must contain letters only.";
    valid = false;
  }

  if (!email.match(/^[^@]+@[^@]+\.[^@]+$/)) {
    emailError.textContent = "Enter a valid email address.";
    valid = false;
  }

  return valid;
}
