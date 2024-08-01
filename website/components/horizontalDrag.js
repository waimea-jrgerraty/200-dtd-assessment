// So that you can click and drag to scroll the content frame when it overflows

document.addEventListener('DOMContentLoaded', () => {
    const pointerScroll = (elem) => {
        console.log(elem)

        const dragStart = (ev) => elem.setPointerCapture(ev.pointerId);
        const dragEnd = (ev) => elem.releasePointerCapture(ev.pointerId);
        const drag = (ev) => elem.hasPointerCapture(ev.pointerId) && (elem.scrollLeft -= ev.movementX);
        
        elem.addEventListener("pointerdown", dragStart);
        elem.addEventListener("pointerup", dragEnd);
        elem.addEventListener("pointermove", drag);
      };
      
    pointerScroll(document.querySelector("#container"));
})