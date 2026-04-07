/**
 * signup.js — AJAX Signup Form
 */
'use strict';

let emailCheckTimer = null;

document.addEventListener('DOMContentLoaded', () => {
  const form     = document.getElementById('signupForm');
  const signupBtn = document.getElementById('signupBtn');
  const btnText   = document.getElementById('signupBtnText');
  const spinner   = document.getElementById('signupSpinner');

  // Password toggle
  document.getElementById('togglePassword')?.addEventListener('click', () => {
    const p = document.getElementById('password');
    p.type = p.type === 'password' ? 'text' : 'password';
    document.getElementById('togglePassword').textContent = p.type === 'password' ? '👁️' : '🙈';
  });

  // Real-time email check
  document.getElementById('email')?.addEventListener('input', function () {
    const status = document.getElementById('emailStatus');
    clearTimeout(emailCheckTimer);
    status.style.display = 'none';
    if (!this.value.trim() || !this.value.includes('@')) return;
    emailCheckTimer = setTimeout(() => {
      Ajax.send('/car_rental/ajax/validate_email.php', {
        data: { email: this.value.trim() },
        onSuccess: data => {
          status.style.display = 'block';
          if (data.available) {
            status.textContent = '✅';
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            document.getElementById('emailError').classList.remove('show');
          } else {
            status.textContent = '❌';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            document.getElementById('emailError').textContent = data.message;
            document.getElementById('emailError').classList.add('show');
          }
        }
      });
    }, 600);
  });

  // Password strength meter
  document.getElementById('password')?.addEventListener('input', function () {
    const strength = document.getElementById('passwordStrength');
    const fill     = document.getElementById('strengthFill');
    const label    = document.getElementById('strengthLabel');
    const val      = this.value;
    strength.style.display = val ? 'flex' : 'none';
    let score = 0;
    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = [
      { pct:'20%', color:'#EF4444', txt:'Very Weak' },
      { pct:'40%', color:'#F59E0B', txt:'Weak' },
      { pct:'60%', color:'#3B82F6', txt:'Fair' },
      { pct:'80%', color:'#10B981', txt:'Strong' },
      { pct:'100%',color:'#059669', txt:'Very Strong' }
    ];
    const lvl = levels[Math.min(score, 4)];
    fill.style.width = lvl.pct;
    fill.style.background = lvl.color;
    label.textContent = lvl.txt;
    label.style.color = lvl.color;
  });

  // Confirm password match
  document.getElementById('confirm_password')?.addEventListener('input', function () {
    const pass = document.getElementById('password').value;
    const err  = document.getElementById('confirmError');
    if (this.value && this.value !== pass) {
      err.textContent = 'Passwords do not match.';
      err.classList.add('show');
      this.classList.add('is-invalid');
    } else {
      err.classList.remove('show');
      this.classList.remove('is-invalid');
      if (this.value) this.classList.add('is-valid');
    }
  });

  // Form submit
  form?.addEventListener('submit', e => {
    e.preventDefault();
    AlertBox.clear('signupAlert');

    const fullName = document.getElementById('full_name').value.trim();
    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirm  = document.getElementById('confirm_password').value.trim();
    const agreed   = document.getElementById('agreeTerms').checked;
    let valid = true;

    if (fullName.length < 2) {
      showFieldError('nameError', 'full_name', 'Full name must be at least 2 characters.');
      valid = false;
    } else clearFieldError('nameError', 'full_name');

    if (!email || !email.includes('@')) {
      showFieldError('emailError', 'email', 'Please enter a valid email.');
      valid = false;
    }

    if (password.length < 6) {
      showFieldError('passwordError', 'password', 'Password must be at least 6 characters.');
      valid = false;
    } else clearFieldError('passwordError', 'password');

    if (password !== confirm) {
      showFieldError('confirmError', 'confirm_password', 'Passwords do not match.');
      valid = false;
    } else clearFieldError('confirmError', 'confirm_password');

    if (!agreed) {
      AlertBox.show('signupAlert', 'Please agree to the Terms of Service.', 'danger');
      valid = false;
    }

    if (!valid) return;

    signupBtn.disabled = true;
    btnText.textContent = 'Creating account...';
    spinner.style.display = 'inline-block';

    const fd = new FormData(form);
    Ajax.send('/car_rental/ajax/signup.php', {
      data: fd,
      onSuccess: data => {
        if (data.success) {
          AlertBox.show('signupAlert', data.message, 'success');
          Toast.success('Account created! Redirecting...');
          setTimeout(() => { window.location.href = data.redirect; }, 1200);
        } else {
          AlertBox.show('signupAlert', data.message, 'danger');
          signupBtn.disabled = false;
          btnText.textContent = '🚀 Create Account';
          spinner.style.display = 'none';
        }
      },
      onError: err => {
        AlertBox.show('signupAlert', err.message, 'danger');
        signupBtn.disabled = false;
        btnText.textContent = '🚀 Create Account';
        spinner.style.display = 'none';
      }
    });
  });
});

function showFieldError(errId, inputId, msg) {
  const err = document.getElementById(errId);
  if (err) { err.textContent = msg; err.classList.add('show'); }
  const input = document.getElementById(inputId);
  if (input) { input.classList.add('is-invalid'); input.classList.remove('is-valid'); }
}
function clearFieldError(errId, inputId) {
  const err = document.getElementById(errId);
  if (err) err.classList.remove('show');
  const input = document.getElementById(inputId);
  if (input) { input.classList.remove('is-invalid'); }
}
