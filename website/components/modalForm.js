document.addEventListener('DOMContentLoaded', () => {
    // Supercategory form

    // Get the modal
    var modal = document.getElementById("sCategoryForm");
    var btn = document.getElementById("newSCat");
    var quit = document.getElementById("SCcancel");

    btn.onclick = function() {
        modal.style.display = "block";
    }

    quit.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    } 
});

document.addEventListener('DOMContentLoaded', () => {
    // Category form

    // Get the modal
    var modal = document.getElementById("categoryForm");
    var btn = document.getElementById("newCat");
    var quit = document.getElementById("Ccancel");

    // button is not created in the archive
    if (btn != null) {
        btn.onclick = function() {
            modal.style.display = "block";
        }
    
        quit.onclick = function() {
            modal.style.display = "none";
        }
    
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        } 
    }
});

document.addEventListener('DOMContentLoaded', () => {
    // Task form

    // Get the modal
    var modal = document.getElementById("taskForm");
    var modalLink = modal.querySelector("#linked");
    var quit = document.getElementById("Tcancel");
    var btnContainer = document.querySelector("#container");
    
    btnContainer.addEventListener("click", function(event) {
        var btn = event.target.closest("#newTask")
        if (btn) {
            const id = btn.parentNode.getAttribute("data-id");
            modalLink.value = id;
            modal.style.display = "block";
        }
    })

    quit.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    } 
});

document.addEventListener('DOMContentLoaded', () => {
    // Subtask menu

    // Get the modal
    var modal = document.getElementById("subtaskMenu");
    var modalContent = modal.querySelector(".formContent");
    var btnContainer = document.querySelector("#container");
    
    btnContainer.addEventListener("click", function(event) {
        var btn = event.target.closest(".task")
        if (btn) {
            // Clear existing children
            while (modal.firstChild.firstChild) {
                modal.firstChild.removeChild(modal.firstChild.firstChild);
            }
            const id = btn.getAttribute("data-id");

            const xhttp = new XMLHttpRequest();
            xhttp.open("GET",`./BuildSubtasks.php?id=${id}`)
            
            xhttp.onload = function() {
                modalContent.innerHTML = this.responseText;
                console.log(this.responseText);
            }

            xhttp.send();

            modal.style.display = "block";
        }
    })

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    } 
});