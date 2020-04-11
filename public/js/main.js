function loadDataWithPopup({url, message, callback}/*url, message, callback*/) {
    let myToast = $('body').toast({
            class: 'info',
            displayTime: 0,
            message: message,
            silent: true
    });
    $.ajax({
        url: url,
        error: (jqXHR) => {alert(jqXHR.responseText); myToast.toast('close',{silent: true})},
        success: (data) => {
            myToast.toast('close',{silent: true});
            $('body').toast({
                message: 'Data load complete',
                showProgress: 'bottom'
            });
            if (typeof callback === 'function')
                callback(data);
        }
    });
}

function buf2hex(buffer) { // buffer is an ArrayBuffer
    return Array.prototype.map.call(new Uint8Array(buffer), x => ('00' + x.toString(16)).slice(-2)).join('');
}
