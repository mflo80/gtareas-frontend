const toggle = document.querySelector('.toggle');
const menu = document.querySelector('.menu');

toggle.addEventListener('click', () => {
    menu.classList.toggle('active');
});

// Arrastar y soltar tareas entre los sectores
function allowDrop(event) {
    event.preventDefault();
}

function drag(event) {
    event.dataTransfer.setData("text/plain", event.target.id);
}

function drop(event) {
    event.preventDefault();
    var data = event.dataTransfer.getData("text/plain");
    var table = document.getElementById(data);
    var target = event.target;
    while (target.tagName !== 'DIV') {
        target = target.parentNode;
    }
    var parent = document.getElementById(data).parentNode;
    if (parent !== target) {
        target.appendChild(table);
        parent.removeChild(document.getElementById(data));
    }
}
