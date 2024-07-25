document.addEventListener('DOMContentLoaded', (event) => {
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

document.addEventListener('DOMContentLoaded', (event) => {
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