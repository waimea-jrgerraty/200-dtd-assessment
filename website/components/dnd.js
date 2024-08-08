document.addEventListener('DOMContentLoaded', () => {

    var dragSrcEl = null;
    var dragClass = null;
    
    function handleDragStart(e) {
      e.stopPropagation();
      this.style.opacity = '0.4';

      if (this.classList.contains('category')) {
        dragClass = 'category';
      } else if (this.classList.contains('sCategory')) {
        dragClass = 'sCategory';
      } else if (this.classList.contains('task')) {
        dragClass = 'task';
      }
      
      dragSrcEl = this;
      
      const attribute = this.getAttribute('data-id');

      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData("text/plain", attribute);
      e.dataTransfer.setData('text/html', this.innerHTML);
    }
  
    function handleDragOver(e) {
      if ((dragClass && this.classList.contains(dragClass))) {
        if (e.preventDefault) {
          e.preventDefault();
        }
    
        e.dataTransfer.dropEffect = 'move';
        }
      return false;
    }
  
    function handleDragEnter(e) {
      if ((dragClass && this.classList.contains(dragClass))) {
        this.classList.add('over');
      }
    }
  
    function handleDragLeave(e) {
      if (this.classList.contains('over')) {
        this.classList.remove('over');
      }
    }
  
    function handleDrop(e) {
      if ((dragClass && this.classList.contains(dragClass))) {
        if (e.stopPropagation) {
          e.stopPropagation(); // stops the browser from redirecting.
        }
        
        if (dragSrcEl != this) {
          const newID = this.getAttribute('data-id');
          const oldID = e.dataTransfer.getData('text/plain');
          dragSrcEl.innerHTML = this.innerHTML;
          dragSrcEl.setAttribute('data-id', newID);
          this.innerHTML = e.dataTransfer.getData('text/html');
          this.setAttribute('data-id', oldID);
          
          // reorder the elements in the database so that they are in the same order when the page reloads.
          // return false;
          switch (dragClass) {
            case "sCategory":
                let sCategories = document.querySelectorAll('.sCategory');

                for (let i = 0; i < sCategories.length; i++) {
                  // If I could be bothered I would have a single xhttp for drag and drop but this is way easier. 
                  const xhttp = new XMLHttpRequest();
                  xhttp.open("POST","./ServerFunctions.php")
                  xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                  
                  xhttp.send(`type=sCategoryReorder&id=${sCategories[i].getAttribute("data-id")}&order=${i+1}`);
                }
                break;
            case "category":
                let categories = document.querySelectorAll('.category');
  
                for (let i = 0; i < categories.length; i++) {
                  const xhttp = new XMLHttpRequest();
                  xhttp.open("POST","./ServerFunctions.php")
                  xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                  
                  xhttp.send(`type=categoryReorder&id=${categories[i].getAttribute("data-id")}&order=${i+1}`);
                }
                break;
            case "task":
                var tasks = this.parentNode.querySelectorAll('.task');
                var cat = this.parentNode.getAttribute("data-id");
                
                for (let i = 0; i < tasks.length; i++) {
                  const xhttp = new XMLHttpRequest();
                  xhttp.open("POST","./ServerFunctions.php")
                  xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                  
                  xhttp.send(`type=taskReorder&id=${tasks[i].getAttribute("data-id")}&order=${i+1}&cat=${cat}`);
                }

                // Update the one that was moved as well incase the user moves the task to another category
                if (dragSrcEl.parentNode != this.parentNode) {
                  tasks = dragSrcEl.parentNode.querySelectorAll('.task');
                  cat = dragSrcEl.parentNode.getAttribute("data-id");
                
                  for (let i = 0; i < tasks.length; i++) {
                    const xhttp = new XMLHttpRequest();
                    xhttp.open("POST","./ServerFunctions.php")
                    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    
                    xhttp.send(`type=taskReorder&id=${tasks[i].getAttribute("data-id")}&order=${i+1}&cat=${cat}`);
                  }
                }
                break;
          }
        }
      }
      return false;
    }
  
    function handleDragEnd(e) {
      this.style.opacity = '1';
      
      items.forEach(function (item) {
        item.classList.remove('over');
      });
    }

    function handleDragEndSC(e) {
      this.style.opacity = '1';
      
      itemsSC.forEach(function (item) {
        item.classList.remove('over');
      });
    }

    let itemsSC = document.querySelectorAll('.sCategory');
    itemsSC.forEach(function(item) {
      item.addEventListener('dragstart', handleDragStart, false);
      item.addEventListener('dragenter', handleDragEnter, false);
      item.addEventListener('dragover', handleDragOver, false);
      item.addEventListener('dragleave', handleDragLeave, false);
      item.addEventListener('drop', handleDrop, false);
      item.addEventListener('dragend', handleDragEndSC, false);

      // Delete button logic here
      const button = item.querySelector('button');
      var ClickedDelete = false;
      button.onclick = function () {
        // Ask for confirmation
        ClickedDelete = true;
        if (window.confirm("Are you sure you want to delete this supercategory?")) {
          const id = button.parentNode.getAttribute('data-id');
          // Send delete action
          const xhttp = new XMLHttpRequest();
          xhttp.open("POST","./ServerFunctions.php")
          xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

          xhttp.onload = function () {
            // If we are on the thing we deleted then reset the url
            const urlParams = new URLSearchParams(window.location.search);
            const currentId = urlParams.get('id');
            if (currentId == id) {
              window.location.replace('index.php');
            }
          }

          xhttp.send(`type=sCategoryRemove&id=${id}`);
          item.remove(); //csp
        }
      }

      // Load the supercategory when you click the button
      item.addEventListener('click', function () {
        if (ClickedDelete == false) {
          const id = item.getAttribute('data-id');
          window.location.replace(`index.php?id=${id}`);
        } 
        ClickedDelete = false;
      });
    });

    let items = document.querySelectorAll('.category');
    items.forEach(function(item) {
      item.addEventListener('dragstart', handleDragStart, false);
      item.addEventListener('dragenter', handleDragEnter, false);
      item.addEventListener('dragover', handleDragOver, false);
      item.addEventListener('dragleave', handleDragLeave, false);
      item.addEventListener('drop', handleDrop, false);
      item.addEventListener('dragend', handleDragEnd, false);

      // Delete button logic here
      const button = item.querySelector('#header button');
      button.onclick = function () {
        // Ask for confirmation
        if (window.confirm("Are you sure you want to delete this category?")) {
          const id = button.parentNode.parentNode.getAttribute('data-id');
          // Send delete action
          const xhttp = new XMLHttpRequest();
          xhttp.open("POST","./ServerFunctions.php")
          xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

          xhttp.send(`type=categoryRemove&id=${id}`);
          item.remove(); //csp
        }
      }
    });

    let tasks = document.querySelectorAll(".task");
    tasks.forEach(function (item) {
      item.addEventListener('dragstart', handleDragStart, false);
      item.addEventListener('dragenter', handleDragEnter, false);
      item.addEventListener('dragover', handleDragOver, false);
      item.addEventListener('dragleave', handleDragLeave, false);
      item.addEventListener('drop', handleDrop, false);
      item.addEventListener('dragend', handleDragEnd, false);

      // Delete button logic here
      const button = item.querySelector('button');
      button.onclick = function () {
        // Ask for confirmation
        if (window.confirm("Are you sure you want to delete this task?")) {
          const id = button.parentNode.parentNode.getAttribute('data-id');
          // Send delete action
          const xhttp = new XMLHttpRequest();
          xhttp.open("POST","./ServerFunctions.php")
          xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

          xhttp.send(`type=taskRemove&id=${id}`);
          item.remove(); //csp
        }
      }
    });
  });