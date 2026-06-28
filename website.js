let targetForm = null;

function confirmRemove(btn){

    const form = btn.closest("form");

    showError(
        "Are you sure you want to remove this item?",
        "confirm",
        () => form.submit()
    );
}


function confirmAction(redirect, link=""){
    if(redirect){
        location.replace(link);
    }
    else{
        if(targetForm){
            targetForm.submit();
        }
    }
    closeError();
}

function showError(message, type ="error", callback=null, confirmText = "Confirm", closeText = "Cancel"){

    const box = document.getElementById("errorBox");
    const confirmBtn = document.getElementById("confirmButton");
    const closeBtn = document.getElementById("closeButton");

    document.getElementById("errorText").innerHTML = message;
    document.getElementById("overlay").classList.add("show");
    document.body.style.overflow = "hidden";

    confirmBtn.textContent = confirmText;
    closeBtn.textContent = closeText;

    confirmBtn.onclick = null;
    closeBtn.onclick = closeError;

    if(type === "error"){
        box.classList.add("hideConfirm"); 
        confirmBtn.onclick = closeError;
    } else {
        box.classList.remove("hideConfirm");
        confirmBtn.onclick = () => {
            closeError();
            if (callback) callback();
        };
    }
}

function closeError(){
    document.getElementById("overlay").classList.remove("show");
    document.body.style.overflow = "";

    document.getElementById("errorBox").classList.remove("hideConfirm");

    targetForm = null;
}

function checkInventory(form){

    const formData = new FormData(form);

    fetch("checkInventory.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(result => {

        if(result.trim() === "OK"){
            form.submit();
        }
        else{
            showError(result,"error");
        }
    });

    return false;
}

function reloadOnBack() {
    window.addEventListener("pageshow", function (event) {
        if (
            event.persisted ||
            performance.getEntriesByType("navigation")[0]?.type === "back_forward"
        ) {
            location.reload();
        }
    });
}

function hideMenu(){
    document.getElementById("rentalButton").style.display="none";
    document.getElementById("rentalStatusButton").style.display="none";
}

function showMenu(){
    document.getElementById("rentalButton").style.display="flex";
    document.getElementById("rentalStatusButton").style.display="flex";
}