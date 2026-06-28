function togglePassword() {
    var fields = document.getElementById('password-fields');
    var btn = document.querySelector('.change-pw-btn');

    if (fields.style.display === 'none') {
        fields.style.display = 'flex';
        btn.textContent = 'Cancel';
    } else {
        fields.style.display = 'none';
        btn.textContent = 'Change Password';
    }
}

function showEditError(message) {
    document.getElementById('edit-error-text').textContent = message;
    document.getElementById('edit-overlay').classList.add('show');
}

function closeEditError() {
    document.getElementById('edit-overlay').classList.remove('show');
}
