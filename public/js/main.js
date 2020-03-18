function loadDataWithPopup({url, message, callback}/*url, message, callback*/) {
    let myToast = $('body').toast({
            class: 'info',
            displayTime: 0,
            message: message,
            silent: true
    });
    $.ajax({
        url: url,
        error: (jqXHR) => {alert(jqXHR.responseText)},
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
