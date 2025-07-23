$(document).ready(function() {
    $(".home").show();
    $(".addUserForm").hide();
    $(".searchForm").hide();
    $(".Users_List").hide();
    $(".pation").hide();
    $(".doctorAssist").hide();
    $(".archive").hide();
});

$("a[href='#Home'] , #home-a").click(function(event) {
    event.preventDefault();
    $(".home").show();
    $(".addUserForm , .searchForm , .Users_List , .pation , .doctorAssist , .archive").hide();
});

$("a[href='#Users_List'] , #users_list-a").click(function(event) {
    event.preventDefault();
    $(".Users_List").show();
    $(".addUserForm , .searchForm , .pation , .doctorAssist , .archive , .home").hide();
    $.post("admin.php", {action: "get_users"}, function (response) {
        response = JSON.parse(response)
        updateTable(response, 'UsersList');
        
    })
});

$("a[href='#addUserForm'] , #add_user-a").click(function(event) {
    event.preventDefault();
    $(".addUserForm").show();
    $(".searchForm , .Users_List , .pation , .doctorAssist , .archive , .home").hide();
});

$("a[href='#searchForm'] , #search-a").click(function(event) {
    event.preventDefault();
    $(".searchForm").show();
    $(".addUserForm , .Users_List , .pation , .doctorAssist , .archive , .home").hide();
});

$("a[href='#archive'] , #archive-a").click(function(event) {
    event.preventDefault();
    $(".archive").show();
    $(".addUserForm , .searchForm , .Users_List , .pation , .home , .doctorAssist").hide();
    archive()
});

$("a[href='#pation'] , #patients-a").click(function(event) {
    event.preventDefault();
    $(".pation").show();
    $(".addUserForm , .searchForm , .Users_List , .home , .doctorAssist , .archive").hide();
    get_pation();
});
$("a[href='#doctorAssist'] , #doctor_assist-a").click(function(event) {
    event.preventDefault();
    $(".doctorAssist").show();
    $(".addUserForm , .searchForm , .Users_List , .pation , .home , .archive").hide();
    getopt();
    getAssist()
});

function togglePassword(id, btn) {
    $.post("admin.php", {action: "get_password", id: id}, function(response) {
        response = JSON.parse(response);
        $("#"+btn.id).hide()
        document.getElementById("show"+btn.id).innerHTML = response.pass;
    })
    
}

$("#addUserForm").submit(function(event) {
    event.preventDefault();
    $.post("admin.php", {
        action: "add",
        name: $("#name").val(),
        password: $("#password").val(),
        status: $("#status").val(),
        role: $("#role").val()
    }, function(response) {
        Swal.fire({
            title: "User was added",
            text: $("#name").val(),
            icon: "success"
        });
        $("#addUserForm")[0].reset();
    });
});


function deleteUser(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You won’t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("admin.php", { action: "delete", id: id }, function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        updateTable(JSON.parse(data.massege), "UsersList");
                    Swal.fire(
                        'Deleted!',
                        'The user has been deleted.',
                        'success'
                    );} else {
                        Swal.fire(
                            'Error!',
                            'An error occurred while deleting the user.',
                            'error'
                        );
                    }
                } catch (e) {
                    console.error("Invalid JSON response", e);
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the user.',
                        'error'
                    );
                }
            });


        }
    });
}

async function editUser(id, name, status, role) {
    const { value: formValues } = await Swal.fire({
        title: 'Edit User',
        html:
            `<input id="swal-input1" class="swal2-input" placeholder="Name" value="${name}">` +
            `<select id="swal-input2" class="swal2-input">
                <option value="Active" ${status === 'Active' ? 'selected' : ''}>Active</option>
                <option value="Inactive" ${status === 'Inactive' ? 'selected' : ''}>Inactive</option>
            </select>` +
            `<select id="swal-input3" class="swal3-input">
                <option value="Doctor" ${role === 'Doctor' ? 'selected' : ''}>Doctor</option>
                <option value="Doctor_assist" ${role === 'Doctor_assist' ? 'selected' : ''}>Doctor assist</option>
                <option value="Nurse" ${role === 'Nurse' ? 'selected' : ''}>Nurse</option>
                <option value="laboratory" ${role === 'laboratory' ? 'selected' : ''}>laboratory</option>
                <option value="receptionist" ${role === 'receptionist' ? 'selected' : ''}>Receptionist</option>
                <option value="Admin" ${role === 'Admin' ? 'selected' : ''}>Admin</option>
            </select>`,
        focusConfirm: false,
        preConfirm: () => {
            return [
                document.getElementById('swal-input1').value,
                document.getElementById('swal-input2').value,
                document.getElementById('swal-input3').value
            ]
        }
    });

    if (formValues) {
        const [newName, newStatus, newRole] = formValues;
        if (newName && newStatus && newRole) {
            $.post("admin.php", {
                action: "update",
                id: id,
                name: newName,
                status: newStatus,
                role: newRole
            }, function(response) {
                const data = JSON.parse(response);
                    if (data.success) {
                        updateTable(JSON.parse(data.massege), "UsersList");
                        Swal.fire({
                            title: "Details updated successfully!",
                            icon: "success"
                });
            }else{
                Swal.fire({
                    title: "Error!",
                    text: "An error occurred while updating the details.",
                    icon: "error"
                });
            }
        });
        }
    }
}

$("#searchForm").submit(function(event) {
    event.preventDefault();
    $.post("admin.php", {
        action: "search",
        name: $("#searchName").val(),
        status: $("#searchStatus").val(),
        role: $("#searchRole").val()
    }, function(response) {
        const users = JSON.parse(response);
        updateTable(users,"searchResult");
    });
});


function get_pation(){
    $.post("admin.php", { action: "get_pation" }, function(response) {
        let data = JSON.parse(response);
        updateTable(data,"pationTable");
    });
}

function deletepation(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you really want to delete this user?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("admin.php", { action: "deletepation", id: id }, function(response) {
                const data = JSON.parse(response);
                    if (data.success) {
                        updateTable(JSON.parse(data.massege), "pationTable");
                    Swal.fire(
                        'Deleted!',
                        'The user has been deleted.',
                        'success'
                    );} else {
                        Swal.fire(
                            'Error!',
                            'An error occurred while deleting the user.',
                            'error'
                        );
                    }
            }).fail(function() {
                Swal.fire(
                    'Error!',
                    'There was an issue deleting the user.',
                    'error'
                );
            });
        }
    });
}

function getopt() {
    $.post("admin.php", { action: "getassist" }, function(response) {
        let data = JSON.parse(response);
        $("#Doctor_name").empty();
        $("#assist_name").empty();

        $("#Doctor_name").append(`
            <option value="" disabled selected>Select Doctor</option>
        `);
        $("#assist_name").append(`
            <option value="" disabled selected>Select Assist name</option>
        `);
        for(let i = 0; i < data.length; i++) {
            if(data[i].role === "Doctor"){
                $("#Doctor_name").append(`
                    <option value="${data[i].name}">${data[i].name}</option>
                `);  // add doctor  to dropdown list
            } else {
                $("#assist_name").append(`
                    <option value="${data[i].name}">${data[i].name}</option>
                `);  // add doctor assistant list
            }
        }
    });
}

function getAssist(){
    $.post("admin.php", { action: "get_assist" }, function(response) {
        let data_table = JSON.parse(response);
        updateTable(data_table,"assistTable");
    });
}

$("#addassist").submit(function(event) {
    event.preventDefault();
    $.post("admin.php", {
        action: "addassist",
        Doctor_name: $("#Doctor_name").val(),
        assist_name: $("#assist_name").val()
    }, function(response) {
        response = JSON.parse(response);
        if(response.success) {
            Swal.fire({
                title: "Doctor Assistant was added",
                text: $("#assistName").val(),
                icon: "success"
            });
            setTimeout(() => {
                updateTable(JSON.parse(response.mas),"assistTable");
            },1000)
        } else {
            Swal.fire({
                title: "Error adding Doctor Assistant",
                icon: "error"
            });
        }
    });
});

function deleteAssist(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "Do you really want to remove this assistant?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, remove it!",
        cancelButtonText: "Cancel",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("admin.php", { action: "removeAssist", id: id }, function(response) {
                try {
                    let data_assist = JSON.parse(response);
                    if (data_assist.success) {
                        Swal.fire("Success", "Doctor Assistant was removed", "success");
                        updateTable(JSON.parse(data_assist.mas), "assistTable"); // Directly update the table
                    } else {
                        Swal.fire("Error", "Error removing Doctor Assistant", "error");
                    }
                } catch (e) {
                    Swal.fire("Error", "Invalid server response", "error");
                    console.error("Response parsing error:", e);
                }
            }).fail(function() {
                Swal.fire("Error", "Failed to communicate with the server", "error");
            });
        }
    });
}

function archive(){
    $.post("admin.php", { action: "archive" }, function(response) {
        response = JSON.parse(response);
        updateTable(response, "archiveTable");
    })
}

function updateTable(users, tableId) {
    let table = document.getElementById(tableId);
    table.innerHTML = '';
    if(tableId == 'UsersList'){
        users.forEach(user => {
            let row = document.createElement('tr');
            row.innerHTML = `
            <td>${user.id}</td>
            <td>${user.name}</td>
            <td>${user.status}</td>
            <td>${user.role}</td>
            <td><button id="${user.id}" onclick='togglePassword(${user.id},this)'>show password</button>
            <span id="show${user.id}"></span>
            <td>
            <button onclick="editUser(${user.id}, '${user.name}', '${user.status}', '${user.role}')">Edit</button>
            <button onclick="deleteUser(${user.id})">Delete</button></td>
            `;
            table.appendChild(row);
        });
    }else if(tableId == 'searchResult'){
        users.forEach(user => {
            let row = document.createElement('tr');
            row.innerHTML = `
            <td>${user.id}</td>
            <td>${user.name}</td>
            <td>${user.status}</td>
            <td>${user.role}</td>
            <td><button id="${user.id}" onclick='togglePassword(${user.id},this)'>show password</button>
            <span id="show${user.id}"></span>
            <td>
            <button onclick="editUser(${user.id}, '${user.name}', '${user.status}', '${user.role}')">Edit</button>
            <button onclick="deleteUser(${user.id})">Delete</button></td>
            `;
            table.appendChild(row);
        });
    }else if(tableId == 'pationTable'){
        users.forEach(patient => {
            let row = document.createElement('tr');
            row.innerHTML = `
            <td>${patient.name}</td>
            <td>${patient.age}</td>
            <td>${patient.patient_gender}</td>
            <td>${patient.condition}</td>
            <td>${patient.phone_number}</td>
            <td>${patient.doctor_name}</td>
            <td>${patient.created_at}</td>
            <td>${patient.room}</td>
            <td><button class='delete-btn' onclick="deletepation(${patient.id})">Delete</button></td>
            `;
            table.appendChild(row);
        });

    }else if(tableId == 'assistTable'){
        try {
            const assistArray = users;
            assistArray.forEach(assist => {
                let row = document.createElement('tr');
                row.innerHTML = `
                <td>${assist.name_doctor}</td>
                <td>${assist.name_assist}</td>
                <td><button class='delete-btn' onclick="deleteAssist(${assist.id})">Delete</button></td>
                `
                table.appendChild(row);
            });
        } catch (error) {
            console.error("Error updating assistant table:", error);
        }
    }
    else if(tableId == 'archiveTable'){
        users.forEach(patient => {
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
            <td>${patient.statu}</td>
            `;
            table.appendChild(row);
        });
    }
}
