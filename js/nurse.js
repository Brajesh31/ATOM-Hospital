$(document).ready(function() {
    let name = JSON.parse(localStorage.getItem('token')).Name;
    document.getElementById('myname').innerHTML = "N/"+name;
    $.post("../bridge/nurse.php", {action: "get_all_patients"},
        function (resposnse) {
            const patients = resposnse;
            updatetable(patients);
        },
    );
});

function updatetable(data){
    let table = document.getElementById("patient")
    table.innerHTML = "";
    for(let i = 0; i < data.length; i++){
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
            </td>
            `;
            table.appendChild(row);
    }
}

function search_patient() {
    let name = document.getElementById('search').value;
    $.post("../bridge/nurse.php", {action: "search_patient", name: name},
        function (response) {
            updatetable(response)
        },
    );
}


function order(id) {
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
            
            $.post("../bridge/nurse.php", { action: "order_tests", id: id, test: test },
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
    $.post("../bridge/nurse.php", {action: "view_patient",id: id},
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
