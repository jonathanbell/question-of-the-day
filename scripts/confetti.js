let windowWidth = window.innerWidth;
let windowHeight = window.innerHeight;
const canvas = document.getElementById('canvas');
const context = canvas.getContext('2d');
const maxConfettis = 150;
let particles = [];
let particleIterations = 1;
const maxParticleIterations = 1777;

const possibleColors = [
  'DodgerBlue',
  'OliveDrab',
  'Gold',
  'Pink',
  'SlateBlue',
  'LightBlue',
  'Gold',
  'Violet',
  'PaleGreen',
  'SteelBlue',
  'SandyBrown',
  'Chocolate',
  'Crimson',
];

function randomFromTo(from, to) {
  return Math.floor(Math.random() * (to - from + 1) + from);
}

function confettiParticle() {
  this.x = Math.random() * windowWidth; // x
  this.y = Math.random() * windowHeight - windowHeight; // y
  this.r = randomFromTo(11, 33); // radius
  this.d = Math.random() * maxConfettis;
  this.color = possibleColors[Math.floor(Math.random() * possibleColors.length)];
  this.tilt = Math.floor(Math.random() * 33);
  this.tiltAngleIncremental = Math.random() * 0.07 + 0.05;
  this.tiltAngle = 0;

  this.draw = function() {
    context.beginPath();
    context.lineWidth = this.r / 1.333;
    context.strokeStyle = this.color;
    context.moveTo(this.x + this.tilt + this.r / 3, this.y);
    context.lineTo(this.x + this.tilt, this.y + this.tilt + this.r / 5);

    return context.stroke();
  };
}

function Draw() {
  const results = [];

  // Magical recursive functional love
  requestAnimationFrame(Draw);

  context.clearRect(0, 0, windowWidth, window.innerHeight);

  for (let i = 0; i < maxConfettis; i++) {
    results.push(particles[i].draw());
  }

  let particle = {};
  let remainingFlakes = 0;
  for (let i = 0; i < maxConfettis; i++) {
    particle = particles[i];

    particle.tiltAngle += particle.tiltAngleIncremental;
    particle.y += (Math.cos(particle.d) + 3 + particle.r / 2) / 2;
    particle.tilt = Math.sin(particle.tiltAngle - i / 3) * 15;

    // needed?
    if (particle.y <= windowHeight) remainingFlakes++;
  }

  return results;
}

// Push new confetti objects to `particles[]`
for (var i = 0; i < maxConfettis; i++) {
  particles.push(new confettiParticle());
}

canvas.width = windowWidth;
canvas.height = windowHeight;
