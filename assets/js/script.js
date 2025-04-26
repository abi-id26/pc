// Document ready
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart quantity validation
    document.querySelectorAll('input[name="quantity"]').forEach(input => {
        input.addEventListener('change', function() {
            if (this.value < 1) {
                this.value = 1;
            }
        });
    });
    // Form validation for checkout
    const checkoutForm = document.querySelector('form[method="post"]');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            let isValid = true;            
            // Validate all required fields
            this.querySelectorAll('[required]').forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            // Validate credit card number
            const ccNumber = document.getElementById('cc-number');
            if (ccNumber && !/^\d{13,16}$/.test(ccNumber.value.replace(/\s/g, ''))) {
                isValid = false;
                ccNumber.classList.add('is-invalid');
            } else if (ccNumber) {
                ccNumber.classList.remove('is-invalid');
            }            
            // Validate CVV
            const cvv = document.getElementById('cc-cvv');
            if (cvv && !/^\d{3,4}$/.test(cvv.value)) {
                isValid = false;
                cvv.classList.add('is-invalid');
            } else if (cvv) {
                cvv.classList.remove('is-invalid');
            }            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first invalid field
                const firstInvalid = this.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    } 
    // Toast notifications (for future enhancements)
    const toastElList = [].slice.call(document.querySelectorAll('.toast'));
    const toastList = toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl);
    });
    toastList.forEach(toast => toast.show());
});