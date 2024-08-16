var currentModal = null;
var openSubtask = null;
var openSubtaskId = null;

document.addEventListener('DOMContentLoaded', () => {
    // Supercategory form

    // Get the modal
    var modal = document.getElementById("sCategoryForm");
    var btn = document.getElementById("newSCat");
    var quit = document.getElementById("SCcancel");

    btn.onclick = function() {
        modal.style.display = "block";
        currentModal = modal;
    }

    quit.onclick = function() {
        modal.style.display = "none";
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
            currentModal = modal;
        }
    
        quit.onclick = function() {
            modal.style.display = "none";
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
            currentModal = modal;
        }
    })

    quit.onclick = function() {
        modal.style.display = "none";
    }
});

function waitForElm(selector) {
    return new Promise(resolve => {
        if (document.querySelector(selector)) {
            return resolve(document.querySelector(selector));
        }

        const observer = new MutationObserver(mutations => {
            if (document.querySelector(selector)) {
                observer.disconnect();
                resolve(document.querySelector(selector));
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // Subtask form

    // Set the users timezone
    var timezoneInput = document.getElementById("userTimezone");
    const timezoneOffset = new Date().getTimezoneOffset();
    timezoneInput.value = timezoneOffset;

    // Get the modal
    var modal = document.getElementById("subtaskForm");
    var modalLink = modal.querySelector("#linkedST");
    var quit = document.getElementById("STcancel");
    var btnContainer = document.querySelector("#container");
    
    btnContainer.addEventListener("click", function() {
        waitForElm("#newSubtask").then((btn) => {
            btn.addEventListener("click", function() {
                console.log(btn.getAttribute("data-id"), btn);
                modalLink.value = btn.getAttribute("data-id");
                modal.style.display = "block";
                currentModal = modal;
            });
        });
    })

    quit.onclick = function() {
        modal.style.display = "none";
        location.reload();
    }
});

function openMenu(modal, id) {
    // Opens the subtask menu when clicking on a task
    var modalContent = modal.querySelector(".formContent");
    
    // Clear existing children
    while (modal.firstChild.firstChild) {
        modal.firstChild.removeChild(modal.firstChild.firstChild);
    }

    const xhttp = new XMLHttpRequest();
    xhttp.open("GET",`./BuildSubtasks.php?id=${id}`)
    
    xhttp.onload = function() {
        modalContent.innerHTML = this.responseText;
        
        const btn = modalContent.querySelector("#newSubtask");
        if (btn) {
            btn.setAttribute("data-id", id)
        }

        const dateTimes = modalContent.querySelectorAll(".datetimeToConvert");
        dateTimes.forEach(function(d) {
            var date = new Date(d.innerHTML);
            
            const options = {
                year: 'numeric',
                month: 'numeric',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            };
            const formattedDate = date.toLocaleString(undefined, options);
            d.innerHTML = formattedDate;
        })

        const deleteButtons = modalContent.querySelectorAll(".subtaskDelete");
        deleteButtons.forEach(function(button) {
            button.onclick = function() {
                // Ask the user to confirm the action
                if (window.confirm("Are you sure you want to delete this subtask?")) {
                    const id = button.parentNode.getAttribute('data-id');
                    // Send delete action
                    const xhttp = new XMLHttpRequest();
                    xhttp.open("POST","./ServerFunctions.php")
                    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          
                    xhttp.send(`type=subtaskDelete&id=${id}`);
                    button.parentNode.remove(); //csp
                }
            }
        })

        const completionButtons = modalContent.querySelectorAll("input");
        completionButtons.forEach(function(input) {
            const subtask = input.parentNode.parentNode.parentNode.parentNode
            const prevClass = subtask.getAttribute('data-uncheckedClass');
            const label = input.parentNode.querySelector('label');
            input.oninput = function() {
                if (input.checked) {
                    subtask.classList.remove(prevClass);
                    subtask.classList.add('finished');
                    label.innerHTML = "Completed";
                } else {
                    subtask.classList.remove('finished');
                    subtask.classList.add(prevClass);
                    label.innerHTML = "Incompleted";
                }

                const id = subtask.getAttribute('data-id');
                // Tell server to swap the completion value of this subtask
                const xhttp = new XMLHttpRequest();
                xhttp.open("POST","./ServerFunctions.php")
                xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhttp.send(`type=subtaskCompletion&id=${id}`);
            }
        })
    }

    xhttp.send();

    modal.style.display = "block";
    currentModal = modal;
    openSubtask = modal;
    openSubtaskId = id;
}

document.addEventListener('DOMContentLoaded', () => {
    // Subtask menu

    // Get the modal
    var modal = document.getElementById("subtaskMenu");
    var btnContainer = document.querySelector("#container");
    
    btnContainer.addEventListener("click", function(event) {
        var btn = event.target.closest(".task")
        if (btn) {
            const id = btn.getAttribute("data-id");
            openMenu(modal, id);
        }
    })
});

document.addEventListener('DOMContentLoaded', () => {
    // Close windows when clicking outside of the modal

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (currentModal != null & event.target == currentModal) {
            currentModal.style.display = "none";
            if (currentModal.id == "subtaskMenu") {
                openSubtask = null;
                openSubtaskId = null;
                location.reload();
            }
            if (currentModal.id == "subtaskForm") {
                location.reload();
            }
            currentModal = openSubtask;
        }
    } 
});
