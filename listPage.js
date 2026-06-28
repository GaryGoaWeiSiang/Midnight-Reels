function editUser(button) {
    document.getElementById('userForm').action = "updateUser.php";
    document.querySelector('.modal-header h3').innerText = "Edit User record";
    document.querySelector('.modal-footer .save-btn').innerText = "Save Changes";
    var uID = button.getAttribute('data-id');
    var username = button.getAttribute('data-username');
    var email = button.getAttribute('data-email');
    var phone = button.getAttribute('data-phone');
    var role = button.getAttribute('data-role');
    var status = button.getAttribute('data-status');
    var customerAddress = button.getAttribute('data-address');
    var staffID = button.getAttribute('data-staffid');
    var password = button.getAttribute('data-password');
    document.getElementById('formUserID').style.display = "flex";

    document.getElementById('modalUserID').value = uID;
    document.getElementById('modalUsername').value = username;
    document.getElementById('modalEmail').value = email;
    document.getElementById('modalPassword').required = false;
    document.getElementById('modalPhone').value = phone;
    document.getElementById('modalRole').value = role;
    document.getElementById('modalAddress').value = customerAddress;
    document.getElementById('modalStaffID').value = staffID;
    document.getElementById('modalStatus').value = status;
    document.getElementById('modalCurrentPassword').value = password;

    document.getElementById('modalRole').onchange = function(){
        toggleModalRoleLayoutFields(this.value);
    }

    toggleModalRoleLayoutFields(role);

    document.getElementById('editModal').classList.add('active');
}

function toggleModalRoleLayoutFields(selectedRoleValue) {
    if (!selectedRoleValue) return;
    
    var selectedRole = selectedRoleValue.toUpperCase();
    
    var customerFormBlock = document.getElementById('formCustomerAddress');
    var staffFormBlock    = document.getElementById('formStaffID');
    
    var addressInputField = document.getElementById('modalAddress');
    var staffIdInputField = document.getElementById('modalStaffID');

    customerFormBlock.style.display = "none";
    staffFormBlock.style.display = "none";
    addressInputField.required = false;
    staffIdInputField.required = false;

    if (selectedRole === 'CUSTOMER') {
        customerFormBlock.style.display = "flex";
    } else if (selectedRole === 'STORE MANAGER' || selectedRole=== 'ADMIN') {
        staffFormBlock.style.display = "flex";
        staffIdInputField.required = true;
    }
}

function editTape(button) {
    document.getElementById('tapeForm').action = "updateTape.php";
    document.querySelector('.modal-header h3').innerText = "Edit Video Tape Detail";
    document.querySelector('.modal-footer .save-btn').innerText = "Save Changes";
    var uID = button.getAttribute('data-id');
    var name = button.getAttribute('data-name');
    var description = button.getAttribute('data-desc');
    var genre = button.getAttribute('data-genre');
    var duration = button.getAttribute('data-duration');
    var releaseDate = button.getAttribute('data-releaseDate');
    var price = button.getAttribute('data-price');
    var image = button.getAttribute('data-image');
    var status = button.getAttribute('data-status');

    document.getElementById('modalVideoID').value = uID;
    document.getElementById('modalName').value = name;
    document.getElementById('modalDescription').value = description;
    document.getElementById('modalGenre').value = genre;
    document.getElementById('modalDuration').value = duration;
    document.getElementById('modalReleaseDate').value = releaseDate;
    document.getElementById('modalPrice').value = price;
    document.getElementById('modalImage').value = image;
    document.getElementById('modalStatus').value = status;

    document.getElementById('editModal').classList.add('active');
}


function editRental(button) {
    
    document.getElementById('rentalForm').action = "updateRental.php";
    document.querySelector('.modal-header h3').innerText = "Edit Rental record";
    document.querySelector('.modal-footer .save-btn').innerText = "Save Changes";
    document.getElementById('modalRentalID').readOnly = true;
    document.getElementById('modalRentalID').required = false;
    document.getElementById('modalUserID').readOnly = true;
    document.getElementById('modalUserID').required = false;
    document.getElementById('formVideoName').style.display = "flex"
    document.getElementById('formPaymentAmount').style.display = "flex"
    
    var rentalID = button.getAttribute('data-rentalID');
    var rentalItemID = button.getAttribute('data-rentalItemID');
    var beginDate = button.getAttribute('data-beginDate');
    var duration = button.getAttribute('data-duration');
    var returnDate = button.getAttribute('data-returnDate');
    var userID = button.getAttribute('data-userID');
    var videoID = button.getAttribute('data-videoID');
    var name = button.getAttribute('data-videoName');
    var payment = button.getAttribute('data-payment');
    var method = button.getAttribute('data-paymentMethod');
    var status = button.getAttribute('data-status');

    document.getElementById('modalRentalID').value = rentalID;
    document.getElementById('modalRentalItemID').value = rentalItemID;
    document.getElementById('modalBeginDate').value = beginDate;
    document.getElementById('modalDuration').value = duration;
    document.getElementById('modalReturnDate').value = returnDate;
    document.getElementById('modalUserID').value = userID;
    document.getElementById('modalVideoID').value = videoID;
    document.getElementById('modalName').value = name;
    document.getElementById('modalPayment').value = payment;
    document.getElementById('modalPaymentMethod').value = method;
    document.getElementById('modalStatus').value = status;

    document.getElementById('editModal').classList.add('active');
}

function editInventory(button) {
    document.getElementById('inventoryForm').action = "updateInventory.php";
    document.querySelector('.modal-header h3').innerText = "Edit Inventory";
    document.querySelector('.modal-footer .save-btn').innerText = "Save Changes";
    var inventoryID = button.getAttribute('data-inventoryid');
    var videoID = button.getAttribute('data-videoid');
    var status = button.getAttribute('data-status');

    document.getElementById('modalInventoryID').value = inventoryID;
    document.getElementById('modalInventoryID').readOnly = true;
    document.getElementById('formInventoryID').style.display = "flex"
    document.getElementById('modalVideoID').value = videoID;
    document.getElementById('modalStatus').value = status;

    document.getElementById('editModal').classList.add('active');
}


function closeModal() {
    document.getElementById('editModal').classList.remove('active');
}

function deleteUser(button) {
    var uID = button.getAttribute('data-id');
    showError("Are you sure you want to delete User "+uID+"?",'confirm',() => {

            const form = document.createElement("form");
            form.method = "POST";
            form.action = "deleteUser.php";

            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "id";
            input.value = uID;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();

        },"Confirm","Cancel");
}

function deleteTape(button) {
    var uID = button.getAttribute('data-id');
    showError("Are you sure you want to delete Video Tape "+uID+"?",'confirm',() => {

            const form = document.createElement("form");
            form.method = "POST";
            form.action = "deleteTape.php";

            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "id";
            input.value = uID;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();

        },"Confirm","Cancel");
}

function deleteRental(button) {
    var rentalID = button.getAttribute('data-rentalID');
    var rentalItemID = button.getAttribute('data-rentalItemID');
    showError("Are you sure you want to delete this Rental data?",'confirm',() => {

            const form = document.createElement("form");
            form.method = "POST";
            form.action = "deleteRental.php";

            const rentalinput = document.createElement("input");
            rentalinput.type = "hidden";
            rentalinput.name = "rentalID";
            rentalinput.value = rentalID;

            const iteminput = document.createElement("input");
            iteminput.type = "hidden";
            iteminput.name = "rentalItemID";
            iteminput.value = rentalItemID;

            form.appendChild(rentalinput);
            form.appendChild(iteminput);
            document.body.appendChild(form);
            form.submit();

        },"Confirm","Cancel");
}

function deleteInventory(button) {
    var uID = button.getAttribute('data-inventoryid');
    showError("Are you sure you want to make this inventory unavailable? ",'confirm',() => {

            const form = document.createElement("form");
            form.method = "POST";
            form.action = "deleteInventory.php";

            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "id";
            input.value = uID;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();

        },"Confirm","Cancel");
}

function addTape(){
    document.getElementById('tapeForm').action = "addTape.php";
    document.querySelector('.modal-header h3').innerText = "Add New Video Tape";
    document.querySelector('.modal-footer .save-btn').innerText = "Add";
    document.getElementById('editModal').classList.add('active');
}

function addInventory(){
    document.getElementById('inventoryForm').action = "addInventory.php";
    document.querySelector('.modal-header h3').innerText = "Add New Inventory";
    document.querySelector('.modal-footer .save-btn').innerText = "Add";
    
    document.getElementById('modalInventoryID').value = "";
    document.getElementById('formInventoryID').style.display = "none"
    document.getElementById('modalVideoID').value = "";
    document.getElementById('modalStatus').value = "";
    document.getElementById('editModal').classList.add('active');
}

function addRental() {
    document.getElementById('rentalForm').action = "addRentalAndPaymentStaff.php";
    document.querySelector('.modal-header h3').innerText = "Create New Rental Contract";
    document.querySelector('.modal-footer .save-btn').innerText = "Create";
    
    document.getElementById('formVideoName').style.display = "none";
    document.getElementById('formPaymentAmount').style.display = "none";

    // Keep Rental ID blank and read-only because the DB generates it
    var rentalIdInput = document.getElementById('modalRentalID');
    rentalIdInput.value = "";
    document.getElementById('formRentalID').style.display = "none";
    
    document.getElementById('modalRentalItemID').value = "";
    document.getElementById('modalBeginDate').value = new Date().toISOString().split('T')[0];
    document.getElementById('modalDuration').value = "";
    document.getElementById('modalReturnDate').value = "";
    
    // User ID IS required here because it's a new customer order
    var userIdInput = document.getElementById('modalUserID');
    userIdInput.value = "";
    userIdInput.readOnly = false;
    userIdInput.required = true;
    
    document.getElementById('modalVideoID').value = "";
    document.getElementById('modalName').value = "";
    document.getElementById('modalPayment').value = "";
    document.getElementById('modalPaymentMethod').value = "CASH";
    document.getElementById('modalStatus').value = "NOT PAID";
    
    document.getElementById('editModal').classList.add('active');
}

// WORKFLOW 2: Pre-populate and lock values to safely add items to a specific existing rental
function addItemToExistingRental(button) {
    document.getElementById('rentalForm').action = "addRentalAndPaymentStaff.php";
    document.querySelector('.modal-header h3').innerText = "Add Extra Tape to Existing Rental";
    document.querySelector('.modal-footer .save-btn').innerText = "Add Item";
    
    document.getElementById('formVideoName').style.display = "none";
    document.getElementById('formPaymentAmount').style.display = "none";

    var existingRentalID = button.getAttribute('data-rentalID');
    var existingUserID = button.getAttribute('data-userID');

    // Populate standard visible input 
    var rentalIdInput = document.getElementById('modalRentalID');
    rentalIdInput.value = existingRentalID;
    rentalIdInput.readOnly = true;
    rentalIdInput.required = true; 
    
    // BACKEND ASSURANCE FIX: Pass it explicitly to a dedicated hidden fallback field
    var hiddenIdInput = document.getElementById('modalHiddenRentalID');
    if (hiddenIdInput) {
        hiddenIdInput.value = existingRentalID;
    }
    
    var userIdInput = document.getElementById('modalUserID');
    userIdInput.value = existingUserID;
    userIdInput.readOnly = true;
    userIdInput.required = true;

    // Clear item inputs for clean entry
    document.getElementById('modalRentalItemID').value = "";
    document.getElementById('modalBeginDate').value = new Date().toISOString().split('T')[0];
    document.getElementById('modalDuration').value = "";
    document.getElementById('modalReturnDate').value = "";
    document.getElementById('modalVideoID').value = "";
    document.getElementById('modalName').value = "";
    document.getElementById('modalPayment').value = "";
    document.getElementById('modalPaymentMethod').value = "CASH";
    document.getElementById('modalStatus').value = "NOT PAID";
    
    document.getElementById('editModal').classList.add('active');
}

function addUser() {
    document.getElementById('userForm').action = "addUserStaff.php";
    document.querySelector('.modal-header h3').innerText = "Add User record";
    document.querySelector('.modal-footer .save-btn').innerText = "Add";
    document.getElementById('formUserID').style.display = "none";
    document.getElementById('modalUserID').value = "";
    document.getElementById('modalUsername').value = "";
    document.getElementById('modalEmail').value = "";
    document.getElementById('modalPassword').required = true;
    document.getElementById('modalPhone').value = "";
    document.getElementById('modalRole').value = "CUSTOMER";
    document.getElementById('modalAddress').value = "";
    document.getElementById('modalStaffID').value = "";
    document.getElementById('modalStatus').value = "ACTIVE";

    document.getElementById('modalRole').onchange = function(){
        toggleModalRoleLayoutFields(this.value);
    }

    toggleModalRoleLayoutFields("CUSTOMER");

    document.getElementById('editModal').classList.add('active');
}

function sort(data){
        const form = document.createElement("form");
            form.method = "GET";

        const input = document.createElement("input");
            input.type = "hidden";
            input.name = "sort";
            input.value = data;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
}