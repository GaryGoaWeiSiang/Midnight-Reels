
const customerBtn = document.getElementById("customerBtn");
const staffBtn = document.getElementById("staffBtn");
const roleInput = document.getElementById("role");
const customerField = document.getElementById("customerField");
const phoneField = document.getElementById("phoneField");
const staffField = document.getElementById("staffField");
const form = document.querySelector("form");

function clearFormFields() {
    const inputs = form.querySelectorAll("input[type='text'], input[type='email'], input[type='password'], input[type='tel']");
    
    inputs.forEach(input => {
        input.value = "";
    });
}

customerBtn.addEventListener("click", function () {
    customerBtn.classList.add("active");
    customerBtn.classList.remove("inactive");
    staffBtn.classList.add("inactive");
    staffBtn.classList.remove("active");
    roleInput.value = "Customer";
    customerField.style.display = "flex";
    //phoneField.style.display = "flex";
    staffField.style.display = "none";
    clearFormFields();
});

staffBtn.addEventListener("click", function () {
    staffBtn.classList.add("active");
    staffBtn.classList.remove("inactive");
    customerBtn.classList.add("inactive");
    customerBtn.classList.remove("active");
    roleInput.value = "Staff";
    customerField.style.display = "none";
    //phoneField.style.display = "flex";
    staffField.style.display = "flex";
    clearFormFields();
});