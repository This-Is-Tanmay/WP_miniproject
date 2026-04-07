/**
 * login.js — AJAX Login Form
 */
'use strict';

document.addEventListener('DOMContentLoaded', () => {
  const form        = document.getElementById('loginForm');
  const loginBtn    = document.getElementById('loginBtn');
  const btnText     = document.getElementById('loginBtnText');
  const spinner     = document.getElementById('loginSpinner');
  const emailInput  = document.getElementById('email');
  const passInput   = document.getElementById('password');
  const togglePass  = document.getElementById('togglePassword');
  const fillUser    = document.getElementById('fillUser');
  const fillAdmin   = document.getElementById('fillAdmin');

  // Toggle password visibility
  if (togglePass) {
    togglePass.addEventListener('click', () => {
      passInput.type = passInput.type === 'password' ? 'text' : 'password';
      togglePass.textContent = passInput.type === 'password' ? '👁️' : '🙈';
    });
  }

  // Quick fill demo credentials
  if (fillUser) {
    fillUser.addEventListener('click', () => {
      emailInput.value = 'demo@carrental.com';
      passInput.value  = 'password123';
      Toast.info('Demo user credentials filled!');
    });
  }
  if (fillAdmin) {
    fillAdmin.addEventListener('click', () => {
      emailInput.value = 'admin@carrental.com';
      passInput.value  = 'password123';
      Toast.info('Admin credentials filled!');
    });
  }

  // Form submit
  form?.addEventListener('submit', e => {
    e.preventDefault();
    AlertBox.clear('loginAlert');

    const email    = emailInput.value.trim();
    const password = passInput.value.trim();
    let valid = true;

    // Client-side validation
    if (!email) {
      document.getElementById('emailError').textContent = 'Email is required.';
      document.getElementById('emailError').classList.add('show');
      emailInput.classList.add('is-invalid');
      valid = false;
    } else {
      document.getElementById('emailError').classList.remove('show');
      emailInput.classList.remove('is-invalid');
    }

    if (!password || password.length < 6) {
      document.getElementById('passwordError').textContent = 'Password must be at least 6 characters.';
      document.getElementById('passwordError').classList.add('show');
      passInput.classList.add('is-invalid');
      valid = false;
    } else {
      document.getElementById('passwordError').classList.remove('show');
      passInput.classList.remove('is-invalid');
    }

    if (!valid) return;

    // Show loading
    loginBtn.disabled = true;
    btnText.textContent = 'Signing in...';
    spinner.style.display = 'inline-block';

    Ajax.send('/car_rental/ajax/login.php', {
      data: { email, password },
      onSuccess: data => {
        if (data.success) {
          AlertBox.show('loginAlert', data.message, 'success');
          Toast.success(data.message);
          setTimeout(() => { window.location.href = data.redirect; }, 1000);
        } else {
          AlertBox.show('loginAlert', data.message, 'danger');
          loginBtn.disabled = false;
          btnText.textContent = 'Sign In';
          spinner.style.display = 'none';
          passInput.value = '';
          passInput.classList.add('is-invalid');
        }
      },
      onError: err => {
        AlertBox.show('loginAlert', err.message, 'danger');
        loginBtn.disabled = false;
        btnText.textContent = 'Sign In';
        spinner.style.display = 'none';
      }
    });
  });
});
