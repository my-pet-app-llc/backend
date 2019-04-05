export function showMessage(messageText, className = 'alert_message', list = [], timeout = 3000) {
    const div = document.getElementById('message_wrap') ?
        document.getElementById('message_wrap') :
        document.createElement('div');
        div.setAttribute('id', 'message_wrap');

    const renderedList = list.length ? list.map((el) => `<li>${ el }</li>`) : "";
    const messageBlock = document.createElement('div');
        messageBlock.setAttribute('class', `alert ${ className }`);
        messageBlock.setAttribute('style', 'width: 260px; position: fixed; z-index: 9999; display: block; opacity: 0; right: 100px');
        messageBlock.innerHTML =
        `
        <strong>${ messageText }</strong>
        <br><br>
        <ul>
          ${ renderedList }
        </ul>
         `;
        div.appendChild(messageBlock);
        animate(messageBlock, 'opacity', 0, 1, 500);
    setTimeout(() => {
        animate(messageBlock, 'opacity', 1, 0, 500);
        setTimeout(() => div.removeChild(messageBlock), 1000);
    }, timeout);
    document.body.appendChild(div);
}

function animate(elem, property, startVal, endVal, time, dimension = "") {
    let frame = 0;
    const frameRate = 0.06; 
    const delta = (endVal - startVal) / time / frameRate;
    const handle = setInterval(function() {
        frame++;
        let value = startVal + delta * frame;
        elem.style[property] = value + dimension;
        if (value == endVal) {
            clearInterval(handle);
        }
    }, 1 / frameRate);
}