
const paymentImages = document.querySelectorAll('.paymentMethodImage');

const paymentInput = document.getElementById('paymentMethod');

paymentImages.forEach(image => {
    image.addEventListener('click', () => {

        paymentImages.forEach(img => {
            img.classList.remove('selected');
        });

        image.classList.add('selected');

        paymentInput.value = image.dataset.payment;
    });
});

//alerts user if a selection is not submitted yet
document.querySelector('.paymentForm').addEventListener('submit', function(e) {
    if (paymentInput.value === '') {
        e.preventDefault();
        alert('Please select a payment method.');
    }
});