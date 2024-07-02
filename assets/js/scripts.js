function openModal() {
    document.getElementById('studentModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('studentModal').style.display = 'none';
}

function editStudent(id) {
    // Fetch student details using AJAX and fill the form
    fetch(`get_student.php?id=${id}`)
        .then(response => response.json())
        .then(student => {
            document.getElementById('studentId').value = student.id;
            document.getElementById('studentName').value = student.name;
            document.getElementById('studentSubject').value = student.subject;
            document.getElementById('studentMarks').value = student.marks;
            openModal();
        });
}

function deleteStudent(id) {
    if (confirm('Are you sure you want to delete this student?')) {
        fetch('delete_student.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
        }).then(() => {
            window.location.reload();
        });
    }
}
