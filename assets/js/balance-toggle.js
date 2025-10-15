document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleBalance');
    const balanceValues = document.querySelectorAll('.balance-value');
    const balanceHidden = document.querySelectorAll('.balance-hidden');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeSlashIcon = document.getElementById('eyeSlashIcon');
    const toggleText = document.getElementById('toggleText');
    
    // Check if balance visibility state is stored in localStorage
    const isBalanceHidden = localStorage.getItem('balanceHidden') === 'true';
    
    // Set initial state based on localStorage or default to visible
    if (isBalanceHidden) {
        hideBalance();
    }
    
    // Toggle balance visibility when button is clicked
    toggleButton.addEventListener('click', function() {
        if (balanceValues[0].classList.contains('hidden')) {
            showBalance();
            localStorage.setItem('balanceHidden', 'false');
        } else {
            hideBalance();
            localStorage.setItem('balanceHidden', 'true');
        }
    });
    
    // Function to hide balance
    function hideBalance() {
        balanceValues.forEach(el => el.classList.add('hidden'));
        balanceHidden.forEach(el => el.classList.remove('hidden'));
        eyeIcon.classList.add('hidden');
        eyeSlashIcon.classList.remove('hidden');
        toggleText.textContent = 'Tampilkan Jasa';
    }
    
    // Function to show balance
    function showBalance() {
        balanceValues.forEach(el => el.classList.remove('hidden'));
        balanceHidden.forEach(el => el.classList.add('hidden'));
        eyeIcon.classList.remove('hidden');
        eyeSlashIcon.classList.add('hidden');
        toggleText.textContent = 'Sembunyikan Jasa';
    }
});