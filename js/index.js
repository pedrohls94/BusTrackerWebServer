$('document').ready(function(){
    document.getElementById("defaultOpen").click();
});

function createLine() {
    var name = $('input:text[name="name"]').val();
    $.get('/php/request.php?action=createLine&name="' + name + '"', function(data, status){
        var id = JSON.parse(data);
        window.location.href = "/map.html?lineId="+id;
    });
}

function findLine() {
    var idToSearch = $('input:text[name="id"]').val();
    $.get("/php/request.php?action=findLine&id=" + idToSearch, function(data, status){
        if(JSON.parse(data) == null) {
            alert("There's no line registered with such id!");
        } else {
            var id = JSON.parse(data)["Id"];
            window.location.href = "/map.html?lineId="+id;
        }
    });
}

function fetchAllLines() {
    var idToSearch = $('input:text[name="id"]').val();
    $.get("/php/request.php?action=fetchAllLines", function(data, status){
        if(JSON.parse(data) == null) {
            alert("There are no lines registered!");
        } else {
            var lines = JSON.parse(data);
            console.log(lines);
            //TODO
        }
    });
}

function changeTab(evt, tabName) {
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}
