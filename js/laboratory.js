$(document).ready(function () {
    document.getElementById('myname').innerHTML = "Dr. " + JSON.parse(localStorage.getItem('token')).Name;
    $.post("../bridge/laboratory.php", {action: "get_patients"},
        function (response) {
            updatetable(response)
        },
    );
})

function search_patient() {
    let name = document.getElementById('search').value;
    $.post("../bridge/laboratory.php", {action: "search_patient", name: name},
        function (response) {
            updatetable(response)
        },
    );
}

function upload(path) {
    Swal.fire({
        title: 'Upload File(s)',
        html: `<input type="file" multiple id="fileInput" class="swal2-input" accept=".jpg, .jpeg, .png, .gif, .pdf, .txt, .doc, .docx">`,
        showCancelButton: true,
        confirmButtonText: 'Upload',
        preConfirm: () => {
            const files = Swal.getPopup().querySelector('#fileInput').files;
            if (!files.length) {
                Swal.showValidationMessage('Please select at least one file');
                return false;
            }

            const allowedTypes = [
                'image/jpeg', 'image/png', 'image/gif', 
                'application/pdf', 'text/plain', 
                'application/msword', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];
            
            const maxSize = 50000000; // 500 KB
            for (let file of files) {
                if (!allowedTypes.includes(file.type)) {
                    Swal.showValidationMessage(`File type not allowed: ${file.name}`);
                    return false;
                }
                if (file.size > maxSize) {
                    Swal.showValidationMessage(`File size must be less than 500 KB: ${file.name}`);
                    return false;
                }
            }

            return files;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const files = result.value;
            const formData = new FormData();

            for (let file of files) {
                formData.append('files[]', file);
            }
            formData.append('path', path);

            Swal.fire({
                title: 'Uploading...',
                text: 'Please wait while the files are being uploaded.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '../medical/upload.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    const result = JSON.parse(response);
                    if (result.status === 'success') {
                        Swal.fire('Success', 'Files uploaded successfully', 'success');
                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'File upload failed', 'error');
                }
            });
        }
    });
}

function finesh(id){
    $.post("../bridge/laboratory.php", {action: "finesh", id: id}, function(response){
        const result = JSON.parse(response);
        console.log(result);
        if(result.status === "success"){    
            Swal.fire({
                icon: 'success',
                title: 'Patient Fineshed',
                showConfirmButton: false,
                timer: 1500
            });
            $.post("../bridge/laboratory.php", {action: "get_patients"},
                function (response) {
                    updatetable(response)
                },
            );
        } else {
            Swal.fire({
                title: 'Error Fineshing Patient',
                icon: 'error'
            });
        }
    });
}
function updatetable(data){
    let tbody = document.getElementById('patient_table');
    tbody.innerHTML = '';

    data = JSON.parse(data); // Parse the JSON string into an object

    for(let i = 0; i < data.length; i++){
        let row = document.createElement('tr');
        row.innerHTML = `
        <td>${data[i].id}</td>
        <td>${data[i].name}</td>
        <td>${data[i].age}</td>
        <td>${data[i].patient_gender}</td>
        <td>${data[i].test}</td>
        <td>${data[i].doctor_name}</td>
        <td>
        <button class="btn btn-primary btn-sm" onclick="upload('${data[i].path}')">Upload</button>
        <button class="btn btn-primary btn-sm" onclick="finesh('${data[i].path}')">Finshed</button>
        </td>`;
        tbody.appendChild(row);
    }
}
