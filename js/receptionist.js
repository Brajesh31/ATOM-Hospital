$(document).ready(function() {
    $(".form").show();
    $(".list").show();
    $("#Search").hide();
    $(".archive").hide();
    getpatients('tbody');

    $("a[href='#Add']").click((e) => {
        e.preventDefault();
        $(".form").show();
        $(".list").show();
        $("#Search").hide();
        $(".archive").hide();
        getpatients('tbody');
    });

    $("a[href='#Search']").click((e) => {
        e.preventDefault();
        $(".form").hide();
        $(".list").hide();
        $("#Search").show();
        $(".archive").hide();
    });

    $("a[href='#archive']").click((e) => {
        e.preventDefault();
        $(".form").hide();
        $(".list").hide();
        $("#Search").hide();
        $(".archive").show();
        $.post("../bridge/receptionist.php", {action: "getarchive"}, function (response) {
            response = JSON.parse(response);
            updatetable(response, 'archive');
        });
    });
});

$("#adduser").submit(function (e) { 
    e.preventDefault();
    $.post("../bridge/receptionist.php", {
        action: "adduser",
        patient_name: $("#patient_name").val(),
        patient_age: $("#patient_age").val(),
        patient_gender: $("#patient_gender").val(),
        phone_number: $("#phone_number").val(),
        condition: $("#condition").val(),
        room: $("#room").val(),
        doctor_name: $("#doctor_name").val()
    }, function (response) {
        response = JSON.parse(response);        
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'User added successfully',
                showConfirmButton: false,
                timer: 1500
            });
            $("#adduser")[0].reset();
            getpatients('tbody');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Failed to add user',
                text: response.error || 'An error occurred',
            });
        }
    });
});

function delete_patient(id, mood) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../bridge/receptionist.php", {action: "delete_patient", id: id}, function (response) {
                response = JSON.parse(response);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Patient deleted successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    getpatients(mood);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to delete patient',
                        text: response.error || 'An error occurred',
                    });
                }
            });
        }
    });
}

function archive(id, mood) {
    Swal.fire({
        title: 'Update Patient Status',
        input: 'select',
        inputOptions: {
            'alive': 'Alive',
            'dead': 'Dead'
        },
        inputPlaceholder: 'Select status',
        showCancelButton: true,
        inputValidator: (value) => {
            return new Promise((resolve) => {
                if (value) {
                    resolve();
                } else {
                    resolve('You need to select a status!');
                }
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const statu = result.value;
            $.post("../bridge/receptionist.php", {action: "setarchive", id: id, statu: statu}, function (response) {
                response = JSON.parse(response);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Patient archived successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    getpatients(mood);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to archive patient',
                        text: response.error || 'An error occurred',
                    });
                }
            });
        }
    });
}

function update(id, mood) {
    $.post('../bridge/receptionist.php', { action: 'getDoctors' }, (response) => {
        const doctors = JSON.parse(response);
        let doctorOptions = '';

        doctors.forEach((doctor) => {
            doctorOptions += `<option value="${doctor.name}" selected>${doctor.name}</option>`;
        });

        $.post('../bridge/receptionist.php', { action: 'getPatient', id: id }, (response) => {
            const data = JSON.parse(response);

            Swal.fire({
                title: 'Update Patient',
                html: `
                    <input type="text" id="name" class="swal2-input" placeholder="Name" value="${data.name}">
                    <input type="number" id="age" class="swal2-input" placeholder="Age" value="${data.age}">
                    <input type="text" id="gender" class="swal2-input" placeholder="Gender" value="${data.patient_gender}">
                    <input type="text" id="condition_opt" class="swal2-input" placeholder="Condition" value="${data.condition}">
                    <input type="text" id="phone" class="swal2-input" placeholder="Phone" value="${data.phone_number}">
                    <select id="doctor" class="swal2-input">
                    <option value=""></option>
                    ${doctorOptions}
                    </select>
                    <input type="text" id="room_num" class="swal2-input" placeholder="Room" value="${data.room}">
                `,
                focusConfirm: false,
                preConfirm: () => {
                    const updatedData = {
                        id: data.id,
                        name: $('#name').val(),
                        age: $('#age').val(),
                        gender: $('#gender').val(),
                        condition: $('#condition_opt').val(),
                        phone: $('#phone').val(),
                        doctor: $('#doctor').val(),
                        room: $('#room_num').val()
                    };
                    
                    $.post('../bridge/receptionist.php', { action: 'updatePatient', ...updatedData }, (response) => {
                        response = JSON.parse(response);
                        if (response.success) {
                            Swal.fire('Updated!', 'Patient information has been updated.', 'success');
                            getpatients(mood);
                        } else {
                            Swal.fire('Error!', 'There was an error updating the patient information.', 'error');
                        }
                    });
                }
            });
        });
    });
}

$("#search-form").submit(function (e) {
    e.preventDefault();
    search();
});

function search() {
    let name = $("#search-input").val();
    let room = $("#search-room").val();
    let doctor = $("#search-doctor").val();

    $.post("../bridge/receptionist.php", {action: "search", name: name, room: room, doctor: doctor}, function (response) {
        response = JSON.parse(response);
        console.log(response);
        
        updatetable(response, "search-results-table");
    });
}

function getpatients(mood) {
    $.post("../bridge/receptionist.php", {action: "getpatients"}, function (response) {
        response = JSON.parse(response);
        updatetable(response, mood);
    });
}

function updatetable(data, table) {
    const tableBody = document.getElementById(table);
    tableBody.innerHTML = '';
    if (table === "tbody" || table === "search-results-table") {
        data.forEach(patient => {
            let row = document.createElement('tr');
            row.innerHTML = `<td>${patient.name}</td>
            <td>${patient.age}</td>
            <td>${patient.patient_gender}</td>
            <td>${patient.condition}</td>
            <td>${patient.phone_number}</td>
            <td>${patient.doctor_name}</td>
            <td>${patient.created_at}</td>
            <td>${patient.room}</td>
            <td>
            <button class="btn btn-primary btn-sm" onclick="update(${patient.id}, '${table}')">Update</button>
            <button class="btn btn-primary btn-sm" onclick="delete_patient(${patient.id}, '${table}')">Delete</button>
            <button class="btn btn-primary btn-sm" onclick="archive(${patient.id}, '${table}')">Archive</button>
            </td>`;
            tableBody.appendChild(row);
        });
    } else if (table === "archive") {
        data.forEach(patient => {
            let row = document.createElement('tr');
            row.innerHTML = `
            <td>${patient.id}</td>
            <td>${patient.name}</td>
            <td>${patient.age}</td>
            <td>${patient.patient_gender}</td>
            <td>${patient.condition}</td>
            <td>${patient.phone_number}</td>
            <td>${patient.doctor_name}</td>
            <td>${patient.created_at}</td>
            <td>${patient.room}</td>
            <td>${patient.end_at}</td>
            <td>${patient.statu}</td>`;
            tableBody.appendChild(row);
        });
    }
}
