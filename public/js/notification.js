const Dialog = Swal.mixin({
    position: "center",
    showConfirmButton: false,
    timer: 3000,
});

const dialogBox = Swal.mixin({
    position: "center",
    showConfirmButton: false,
});

const confirmDialog = Swal.mixin({
    position: "center",
    showConfirmButton: true,
    showCancelButton: true,
    confirmButtonColor: "#1a9300",
    cancelButtonColor: "#008477",
});

const blackToast = Swal.mixin({
    toast: true,
    position: "bottom",
    showConfirmButton: false,
    background: "#000",
    timer: 3000,
    customClass: {
        title: "blackToast-title",
    },
});

function notifyToast(status,message){

    let Toast = Swal.mixin({
        toast: true,
        position: "bottom",
        showConfirmButton: false,
        background: status === 'success' ? "#008000" : (status === 'error' ? "#980000" : "#fdfdfd"),
        timer: 3000,
    });
    Toast.fire({
        html: '<span style="color:#fff">' + message + "</span>",
    });
}

function notifyDialogBox(icon,title, message) {
    dialogBox.fire({
        icon: icon,
        title: title,
        html: message,
    });
}

function notifyDialogBoxReDirect(icon,title, message,url) {
    dialogBox.fire({
        icon: icon,
        title: title,
        html: message,
        onClose: () => {
            location.href = url;
        },
    });
}

function notifyBlackToast(message) {
    blackToast.fire({
        html: '<span style="color:#fff">' + message + "</span>",
    });
}

function notifyBlackToastWithRedirect(message, url) {
    blackToast.fire({
        html: '<span style="color:#fff">' + message + "</span>",
        timerProgressBar: true,
        onClose: () => {
            location.href = url;
        },
    });
}

function notifyDialog(message, icon) {
    Dialog.fire({
        icon: icon,
        title: message,
    });
}

function notifyConfirmDialog(title, message, icon, callFunction) {
    confirmDialog
        .fire({
            title: title,
            text: message,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result) {
                return callFunction;
            }
        });
}

function notifyRedirect(title, message, icon, url, confirm_text, cancel_text) {
    confirmDialog
        .fire({
            title: title,
            text: message,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: (confirm_text!='')? confirm_text:"Continue",
            cancelButtonText: (cancel_text!='')? cancel_text:"Cancel",

        })
        .then((result) => {
            console.log(result);
            if (result.value) {
                location.href = url;
            }
        });
}
