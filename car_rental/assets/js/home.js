/**
 * home.js — Landing Page Logic
 */
'use strict';

document.addEventListener('DOMContentLoaded', () => {
  loadCars('All');
  initFilters();

  // Generate ambient particles for hero
  const particlesContainer = document.getElementById('heroParticles');
  if (particlesContainer) {
    for (let i = 0; i < 25; i++) {
      const particle = document.createElement('div');
      particle.className = 'hero-particle';
      particle.style.left = Math.random() * 100 + '%';
      particle.style.animationDuration = (6 + Math.random() * 10) + 's';
      particle.style.animationDelay = (Math.random() * 8) + 's';
      particle.style.width = (2 + Math.random() * 3) + 'px';
      particle.style.height = particle.style.width;
      particle.style.opacity = 0.2 + Math.random() * 0.5;
      particlesContainer.appendChild(particle);
    }
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
