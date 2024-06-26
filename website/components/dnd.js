document.addEventListener('DOMContentLoaded', (event) => {

    var dragSrcEl = null;
    var dragClass = null;
    
    function handleDragStart(e) {
      this.style.opacity = '0.4';

      if (this.classList.contains('box')) {
        dragClass = 'box';
      } else if (this.classList.contains('sCategory')) {
        dragClass = 'sCategory';
      }
      
      dragSrcEl = this;
  
      e.dataTransfer.effectAllowed = 'move';
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
          dragSrcEl.innerHTML = this.innerHTML;
          this.innerHTML = e.dataTransfer.getData('text/html');
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
    
    
    let items = document.querySelectorAll('.container .box');
    items.forEach(function(item) {
      item.addEventListener('dragstart', handleDragStart, false);
      item.addEventListener('dragenter', handleDragEnter, false);
      item.addEventListener('dragover', handleDragOver, false);
      item.addEventListener('dragleave', handleDragLeave, false);
      item.addEventListener('drop', handleDrop, false);
      item.addEventListener('dragend', handleDragEnd, false);
    });

    let itemsSC = document.querySelectorAll('.sCategory');
    itemsSC.forEach(function(item) {
      item.addEventListener('dragstart', handleDragStart, false);
      item.addEventListener('dragenter', handleDragEnter, false);
      item.addEventListener('dragover', handleDragOver, false);
      item.addEventListener('dragleave', handleDragLeave, false);
      item.addEventListener('drop', handleDrop, false);
      item.addEventListener('dragend', handleDragEndSC, false);
    });
  });