
    document.addEventListener('DOMContentLoaded', function () {
      const togglePassword = document.getElementById('togglePassword'); 
      const password = document.getElementById('password');

      if (togglePassword && password) {
        togglePassword.addEventListener('click', function (e) {
          const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
          password.setAttribute('type', type);


          this.classList.toggle('bi-eye');
          this.classList.toggle('bi-eye-slash');
        });
      }
    });
