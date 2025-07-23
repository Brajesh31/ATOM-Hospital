$(document).ready(function() {
    document.getElementById("myname").innerHTML = "Dr. " + JSON.parse(localStorage.getItem('token')).Name;
    let dir = document.URL.split('path=')[1];
    $.post("medical.php", {action: "get", dir: dir},
        function (response) {
            if (response.error) {
                console.error(response.error);
            } else {
                updatetable(response,dir);
            }
        }
    );
})

function updatetable(data, dir) {
    const dataShowElement = document.getElementById('data-show');
    dataShowElement.innerHTML = '';
    
    data.forEach(data_show => {
        let div = document.createElement('div');
        let i = document.createElement('i');
        let span = document.createElement('span');
        
        i.className = "fa fa-file";
        span.innerText = data_show;
        
        div.appendChild(i);
        div.appendChild(span);
        
        // Optional: Add click event to potentially open/view document
        div.addEventListener('click', () => {
            window.open(`${dir}/${data_show}`, '_blank');
        });
        
        dataShowElement.appendChild(div);
    });
}