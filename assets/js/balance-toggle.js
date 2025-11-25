document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleBalance');
    const balanceValues = document.querySelectorAll('.balance-value');
    const balanceHidden = document.querySelectorAll('.balance-hidden');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeSlashIcon = document.getElementById('eyeSlashIcon');
    const toggleText = document.getElementById('toggleText');
    const hasIcons = !!(eyeIcon && eyeSlashIcon && toggleText);

    const isBalanceHidden = localStorage.getItem('balanceHidden') !== 'false';

    if (isBalanceHidden) {
        hideBalance();
    } else {
        showBalance();
    }

    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            const firstValue = balanceValues[0];
            if (firstValue && firstValue.classList.contains('hidden')) {
                showBalance();
                localStorage.setItem('balanceHidden', 'false');
            } else {
                hideBalance();
                localStorage.setItem('balanceHidden', 'true');
            }
        });
    }

    function hideBalance() {
        balanceValues.forEach(el => { if (el && el.classList) el.classList.add('hidden'); });
        balanceHidden.forEach(el => { if (el && el.classList) el.classList.remove('hidden'); });
        if (hasIcons) {
            eyeIcon.classList.add('hidden');
            eyeSlashIcon.classList.remove('hidden');
            toggleText.textContent = 'Tampilkan Jasa';
        }
    }

    function showBalance() {
        balanceValues.forEach(el => { if (el && el.classList) el.classList.remove('hidden'); });
        balanceHidden.forEach(el => { if (el && el.classList) el.classList.add('hidden'); });
        if (hasIcons) {
            eyeIcon.classList.remove('hidden');
            eyeSlashIcon.classList.add('hidden');
            toggleText.textContent = 'Sembunyikan Jasa';
        }
    }
});