/**
 * home.js — Landing Page Logic
 */
'use strict';

document.addEventListener('DOMContentLoaded', () => {
  loadCars('All');
  initFilters();

  // Hero car auto-cycle
  const heroCar = document.getElementById('heroCar');
  const imgs = ['car_luxury.png','car_suv.png','car_sedan.png','car_convertible.png'];
  let idx = 0;
  if (heroCar) {
    setInterval(() => {
      heroCar.style.opacity = '0';
      setTimeout(() => {
        idx = (idx + 1) % imgs.length;
        heroCar.src = `/car_rental/assets/images/cars/${imgs[idx]}`;
        heroCar.style.opacity = '1';
      }, 500);
    }, 3500);
  }
});

function loadCars(type = 'All') {
  const grid = document.getElementById('carsGrid');
  if (!grid) return;
  grid.innerHTML = '<div class="cars-loading"><div class="spinner spinner-dark"></div><p>Loading cars...</p></div>';
  Ajax.get(`/car_rental/ajax/get_cars.php?type=${encodeURIComponent(type)}&available=1`, data => {
    if (data.success && data.data.length > 0) {
      grid.innerHTML = data.data.map(car => buildCarCard(car, false)).join('');
      animateCards(grid);
    } else {
      grid.innerHTML = `<div class="cars-loading" style="grid-column:1/-1"><div style="font-size:2.5rem">🚗</div><h4>No cars in this category</h4><p>Try another filter.</p></div>`;
    }
  });
}

function initFilters() {
  document.querySelectorAll('#carFilters .filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('#carFilters .filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      loadCars(btn.dataset.type || 'All');
    });
  });
}

function animateCards(container) {
  container.querySelectorAll('.car-card').forEach((card, i) => {
    card.style.cssText = `opacity:0;transform:translateY(20px);transition:opacity .5s ease ${i*0.08}s,transform .5s ease ${i*0.08}s`;
    setTimeout(() => { card.style.opacity='1'; card.style.transform='translateY(0)'; }, 50);
  });
}
