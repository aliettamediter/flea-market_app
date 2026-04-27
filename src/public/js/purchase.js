const select = document.getElementById('payment_method');
const selectedPayment = document.getElementById('selected-payment');

const paymentLabels = {
    'credit_card': 'クレジットカード',
    'konbini': 'コンビニ払い',
};

if (select && selectedPayment) {
    select.addEventListener('change', function () {
        selectedPayment.textContent = paymentLabels[this.value] || '未選択';
    });
}