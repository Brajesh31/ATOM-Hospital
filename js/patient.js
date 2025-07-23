$(document).ready(function() {
    $(".mypatient").hide();
    $(".patient").show();
    let name = JSON.parse(localStorage.getItem('token')).Name;
    document.getElementById('myname').innerHTML = "D/"+name;
    $.post("../bridge/patient.php", {action: "get_all_patients"},
        function (resposnse) {
            const patients = resposnse;
            updatetable(patients,'patient');
        },
    );
});

function updatetable(data,tabletype){
    document.getElementById(tabletype).innerHTML = '';
    for(let i = 0; i < data.length; i++){
        if(data[i].doctor_name === JSON.parse(localStorage.getItem('token')).Name && tabletype == "patient"){
            let row = document.createElement('tr');
            row.innerHTML = `<td>${data[i].id}</td>
                        <td>${data[i].name}</td>
                        <td>${data[i].age}</td>
                        <td>${data[i].patient_gender}</td>
                        <td>${data[i].condition}</td>
                        <td>${data[i].doctor_name}</td>
                        <td>${data[i].created_at}</td>
                        <td>${data[i].room}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="order('${data[i].id}')">Order Medical tests</button>
                            <button class="btn btn-primary btn-sm" onclick="view_patient('${data[i].id}')">Medical tests</button>
                            <button class="btn btn-primary btn-sm" onclick="archive(${data[i].id})">Archive</button>
                        </td>
                        `;
                        document.getElementById(tabletype).appendChild(row);
        }else if(data[i].doctor_name == "" && tabletype == "patient"){
            let row = document.createElement('tr');
            row.innerHTML = `<td>${data[i].id}</td>
                        <td>${data[i].name}</td>
                        <td>${data[i].age}</td>
                        <td>${data[i].patient_gender}</td>
                        <td>${data[i].condition}</td>
                        <td>${data[i].doctor_name}</td>
                        <td>${data[i].created_at}</td>
                        <td>${data[i].room}</td>
                        <td><button class="btn btn-primary btn-sm" onclick="Take_patient(${data[i].id})">Take patient</button>
                        </td>
                        `;
                        document.getElementById(tabletype).appendChild(row);
        }else if(data[i].doctor_name === JSON.parse(localStorage.getItem('token')).Name && tabletype == "mypatient"){
            let row = document.createElement('tr');
            row.innerHTML = `<td>${data[i].id}</td>
                        <td>${data[i].name}</td>
                        <td>${data[i].age}</td>
                        <td>${data[i].patient_gender}</td>
                        <td>${data[i].condition}</td>
                        <td>${data[i].created_at}</td>
                        <td>${data[i].room}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="order('${data[i].id}')">Order Medical tests</button>
                            <button class="btn btn-primary btn-sm" onclick="view_patient('${data[i].id}')">Medical tests</button>
                            <button class="btn btn-primary btn-sm" onclick="archive(${data[i].id})">Archive</button>
                        </td>
                        `;
                        document.getElementById(tabletype).appendChild(row);
        }
        else{
            let row = document.createElement('tr');
            row.innerHTML = `<td>${data[i].id}</td>
                        <td>${data[i].name}</td>
                        <td>${data[i].age}</td>
                        <td>${data[i].patient_gender}</td>
                        <td>${data[i].condition}</td>
                        <td>${data[i].doctor_name}</td>
                        <td>${data[i].created_at}</td>
                        <td>${data[i].room}</td>
                        <td><button class="btnwa" style="background:#bc1000">was taken</button>
                        </td>
                        `;
                        document.getElementById(tabletype).appendChild(row);
        }
        
        
    }
}

function my_patients(){
    $(".mypatient").show();
    $(".patient").hide();
    $("#my_patient").css("background", "#c2c2c2");
    $("#all_patient").css("background", "#f9f9f9");
    let doctor_id = JSON.parse(localStorage.getItem('token')).Name;
    $.post("../bridge/patient.php", {action: "get_my_patients", name: doctor_id},
         function(data){
            const res = data;
            updatetable(data,'mypatient');
         })
}

function patient(){
    $(".mypatient").hide();
    $(".patient").show();
    $("#my_patient").css("background", "#f9f9f9");
    $("#all_patient").css("background", "#c2c2c2");
    $.post("../bridge/patient.php", {action: "get_all_patients"},
        function (resposnse) {
            const patients = resposnse;
            updatetable(patients,'patient');
        },
    );
}

function order(id) {
    let name = JSON.parse(localStorage.getItem('token')).Name;
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
            
            $.post("../bridge/patient.php", { action: "order_tests", id: id, name: name, test: test },
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

function view_patient(id){
    let name = JSON.parse(localStorage.getItem('token')).Name;
    $.post("../bridge/patient.php", {action: "view_patient",id: id, name: name},
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
function archive(id){
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
            let name = JSON.parse(localStorage.getItem('token')).Name;
            $.post("../bridge/patient.php", {action: "archive", id: id, name: name, status: status},
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
function Take_patient(id){
    let name = JSON.parse(localStorage.getItem('token')).Name;
    $.post("../bridge/patient.php", {action: "take_patient", id: id, name: name},
        function(response){
            const res = typeof response === 'object' ? response : JSON.parse(response);
            if(res.status == "success"){
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Patient taken successfully!',
                });
                patient();
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to take patient!',
                });
            }
    });
}
