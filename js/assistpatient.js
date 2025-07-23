let doctor_name;
$(document).ready(function() {
    let name = JSON.parse(localStorage.getItem('token')).Name;
    document.getElementById('myname').innerHTML = "Dr/"+name;
    $.post("../bridge/assistpatient.php", {action: "get_doctor_name",assist_name:name},
        function (resposnse) {
            doctor_name = resposnse.doctor_name;
            document.getElementById('myname').innerHTML += " - Dr. " + resposnse.doctor_name;
            $.post("../bridge/assistpatient.php", {action: "get_patients", doctor_name: doctor_name},
                function (response) {
                    updatetable(response,doctor_name)
                },
            );
        },
    );
});

function updatetable(data,doctor_name){
    let table = document.getElementById('patient-data');
    table.innerHTML = '';
    for(let i = 0; i < data.length; i++){
        let row = document.createElement('tr');
        row.innerHTML = `
        <td>${data[i].id}</td>
        <td>${data[i].name}</td>
        <td>${data[i].age}</td>
        <td>${data[i].patient_gender}</td>
        <td>${data[i].condition}</td>
        <td>${data[i].created_at}</td>
        <td>${data[i].room}</td>
        <td>
            <button class="btn btn-primary btn-sm" onclick="order(${data[i].id},'${doctor_name}')">Order Medical tests</button>
            <button class="btn btn-primary btn-sm" onclick="view_patient(${data[i].id},'${doctor_name}')">Medical tests</button>
            <button class="btn btn-primary btn-sm" onclick="archive(${data[i].id},'${doctor_name}')">Archive</button>
        </td>
        `;
        table.appendChild(row);
    }
}


function order(id,doctor_name) {
    let test;
    const con = Swal.fire({
        title: "Enter test name",
        input: "text",
        inputLabel: "Test Name",
        inputValue: "", // Ensure inputValue is defined
        showCancelButton: true,
        inputValidator: (value) => {
            if (!value) {
                return 'You need to write something!';
            }
        }
    });

    con.then((result) => {
        if (result.isConfirmed) {
            test = result.value;
             // Now test will have the correct value

            // Uncomment the following block when ready to make the AJAX request
            
            $.post("../bridge/assistpatient.php", { action: "order_tests", id: id, name: doctor_name, test: test },
                function (response) {
                    if (response.status == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Tests ordered successfully',
                        });
                    } else if (response.status == "wait") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: 'Patient is already under treatment',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                }
            );
            // End AJAX request
        }
    });
}

function view_patient(id,doctor_name){
    $.post("../bridge/assistpatient.php", {action: "view_patient",id: id, name: doctor_name},
        function (resposnse) {            
            if(resposnse.status == "success"){
                location.href = "../medical/medical.html?path=" + resposnse.path;
            }else{
                Swal.fire({
                icon: 'error',
                title: 'Error',
                text: resposnse.message,
                }  );
        }
    });
}
function archive(id,doctor_name){
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
            let status = result.value;
            $.post("../bridge/assistpatient.php", {action: "archive", id: id, name: doctor_name, status: status},
                function(response){
                    const res = typeof response === 'object' ? response : JSON.parse(response);
                    if(res.status == "success"){
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Patient was add to Archive successfully!',
                        });
                        patient();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add to Archive',
                        });
                    }
            });
        }
    });
}