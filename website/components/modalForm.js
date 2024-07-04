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