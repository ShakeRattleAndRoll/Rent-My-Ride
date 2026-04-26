 function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        const msg = document.getElementById('passwordMessage');

        if (confirm === '') {
            msg.classList.add('hidden');
            return;
        }

        if (password === confirm) {
            msg.textContent = '✓ Passwords match';
            msg.className = 'text-sm px-2 text-green-400';
        } else {
            msg.textContent = '✗ Passwords do not match';
            msg.className = 'text-sm px-2 text-red-400';
        }
    }

    document.getElementById('registerForm').addEventListener('submit', function (e) {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;

        if (password !== confirm) {
            e.preventDefault();
            const msg = document.getElementById('passwordMessage');
            msg.textContent = '✗ Passwords do not match';
            msg.className = 'text-sm px-2 text-red-400';
        }
    });