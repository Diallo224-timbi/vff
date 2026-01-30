<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tous Ensembles - Plateforme Multi-Acteurs</title>

<style>
/* Votre CSS reste identique */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Segoe UI", sans-serif;
}

body {
  background: #000;
  color: #fff;
  overflow-x: hidden;
}

.header {
  position: fixed;
  top: 20px;
  left: 40px;
  right: 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  z-index: 100;
  animation: slideDown 1s ease-out;
}

@keyframes slideDown {
  from { transform: translateY(-100px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

.logo {
  font-size: 14px;
  color: #4fd1d9;
  line-height: 1.2;
  text-transform: uppercase;
  transition: all 0.3s ease;
}

.logo:hover {
  transform: scale(1.05);
  text-shadow: 0 0 10px rgba(79, 209, 217, 0.5);
}

.nav {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(15px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 40px;
  padding: 6px;
  display: flex;
  gap: 6px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
}

.nav a {
  padding: 12px 24px;
  border-radius: 30px;
  text-decoration: none;
  color: #fff;
  opacity: 0.8;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  font-size: 14px;
  font-weight: 500;
  position: relative;
  overflow: hidden;
}

.nav a::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

.nav a:hover::before {
  width: 300px;
  height: 300px;
}

.nav a:hover {
  opacity: 1;
  transform: translateY(-2px);
}

.nav a.active {
  background: linear-gradient(135deg, #4fd1d9, #3bbac1);
  color: #000;
  opacity: 1;
  box-shadow: 0 4px 20px rgba(79, 209, 217, 0.4);
}

.btn-login {
  background: linear-gradient(135deg, #4fd1d9, #3bbac1);
  color: #000;
  border: none;
  padding: 12px 28px;
  border-radius: 30px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  text-decoration: none;
  display: inline-block;
  text-align: center;
}

.btn-login::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

.btn-login:hover::after {
  width: 300px;
  height: 300px;
}

.btn-login:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(79, 209, 217, 0.5);
  color: #000;
}

/* HERO */
.hero {
  height: 100vh;
  background-image: url("{{ asset('img/photo.png') }}");
  background-size: cover;
  background-position: center;
  position: relative;
  background-attachment: fixed;
  animation: zoomIn 20s infinite alternate;
}

@keyframes zoomIn {
  0% { background-size: 100%; }
  100% { background-size: 110%; }
}

.hero::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    45deg,
    rgba(0, 0, 0, 0.9) 0%,
    rgba(0, 0, 0, 0.7) 50%,
    rgba(0, 0, 0, 0.4) 100%
  );
  z-index: 1;
}

.particles {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 2;
  pointer-events: none;
}

.particle {
  position: absolute;
  background: rgba(79, 209, 217, 0.2);
  border-radius: 50%;
  animation: float 20s infinite linear;
}

@keyframes float {
  0% { transform: translateY(100vh) rotate(0deg); }
  100% { transform: translateY(-100vh) rotate(360deg); }
}

.hero-content {
  position: relative;
  z-index: 3;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding-left: 80px;
  animation: fadeIn 1.5s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateX(-50px); }
  to { opacity: 1; transform: translateX(0); }
}

.subtitle {
  font-size: 18px;
  opacity: 0.9;
  margin-bottom: 30px;
  max-width: 500px;
  line-height: 1.6;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
  animation: slideInLeft 1s ease-out 0.3s both;
}

@keyframes slideInLeft {
  from { transform: translateX(-100px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

.hero h1 {
  font-size: 120px;
  line-height: 0.9;
  color: transparent;
  background: linear-gradient(135deg, #4fd1d9 0%, #ffffff 50%, #4fd1d9 100%);
  background-size: 200% auto;
  -webkit-background-clip: text;
  background-clip: text;
  font-weight: 900;
  letter-spacing: -2px;
  margin-bottom: 20px;
  animation: gradientShift 3s ease-in-out infinite, slideInUp 1s ease-out 0.6s both;
  text-shadow: 0 0 30px rgba(79, 209, 217, 0.3);
}

@keyframes gradientShift {
  0% { background-position: 0% center; }
  50% { background-position: 100% center; }
  100% { background-position: 0% center; }
}

@keyframes slideInUp {
  from { transform: translateY(100px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

.scroll-indicator {
  position: absolute;
  bottom: 40px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 3;
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
  40% { transform: translateX(-50%) translateY(-20px); }
  60% { transform: translateX(-50%) translateY(-10px); }
}

.scroll-indicator span {
  display: block;
  width: 24px;
  height: 40px;
  border: 2px solid rgba(255, 255, 255, 0.5);
  border-radius: 12px;
  position: relative;
}

.scroll-indicator span::before {
  content: '';
  position: absolute;
  top: 8px;
  left: 50%;
  transform: translateX(-50%);
  width: 4px;
  height: 8px;
  background: #4fd1d9;
  border-radius: 2px;
  animation: scroll 2s infinite;
}

@keyframes scroll {
  0% { transform: translateX(-50%) translateY(0); opacity: 1; }
  100% { transform: translateX(-50%) translateY(20px); opacity: 0; }
}

/* Services Section */
.services {
  position: absolute;
  bottom: 100px;
  left: 80px;
  display: flex;
  gap: 30px;
  z-index: 3;
  animation: fadeInUp 1s ease-out 0.9s both;
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(50px); }
  to { opacity: 1; transform: translateY(0); }
}

.service-item {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 20px 30px;
  border-radius: 15px;
  transition: all 0.3s ease;
  cursor: pointer;
}

.service-item:hover {
  background: rgba(79, 209, 217, 0.2);
  transform: translateY(-10px);
  box-shadow: 0 10px 30px rgba(79, 209, 217, 0.3);
}

.service-item h3 {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 5px;
  color: #4fd1d9;
}

.service-item p {
  font-size: 12px;
  opacity: 0.8;
}

/* RESPONSIVE */
@media (max-width: 900px) {
  .hero h1 {
    font-size: 80px;
  }
  
  .hero-content {
    padding-left: 40px;
  }
  
  .header {
    left: 20px;
    right: 20px;
    flex-wrap: wrap;
    gap: 15px;
  }
  
  .nav {
    order: 3;
    width: 100%;
    justify-content: center;
    margin-top: 10px;
  }
  
  .services {
    left: 20px;
    right: 20px;
    flex-wrap: wrap;
    justify-content: center;
  }
}

@media (max-width: 600px) {
  .hero h1 {
    font-size: 60px;
  }
  
  .subtitle {
    font-size: 16px;
    padding-right: 20px;
  }
  
  .service-item {
    padding: 15px 20px;
  }
}
</style>
</head>

<body>

<header class="header">
  <div class="logo">
    PLATEFORME<br>
    <strong>MULTI ACTEURS</strong>
  </div>

  <nav class="nav">
    <a href="">Guide</a>
    <a href="/login">Se connecter</a>
    <a href="">Information</a>
    <a href="" class="active">Événement</a>
  </nav>

  <!-- Solution temporaire - utilisez le chemin direct -->
  <div>
  <a href="/register" class="btn-login">S'inscrire</a>
  <a href="/login" class="btn-login">Se connecter</a>
  </div>
  <!-- Ou si vous avez une route login : -->
  <!-- <a href="{{ route('register') }}" class="btn-login">Se connecter</a> -->
</header>

<main class="hero">
  <div class="particles" id="particles"></div>
  
  <div class="hero-content">
    <p class="subtitle">
      Une plateforme, tous les acteurs,<br>
      un même objectif : des parcours fluides et sécurisés.
    </p>

    <h1>
      TOUS<br>
      ENSEMBLES
    </h1>
    
    <div class="services">
      <div class="service-item">
        <h3>Service</h3>
        <p>Solutions intégrées</p>
      </div>
      <div class="service-item">
        <h3>Information</h3>
        <p>Données partagées</p>
      </div>
      <div class="service-item">
        <h3>Événement</h3>
        <p>Rencontres collaboratives</p>
      </div>
      <div class="service-item">
        <h3>Connexion</h3>
        <p>Accès sécurisé</p>
      </div>
    </div>
  </div>
  
  <div class="scroll-indicator">
    <span></span>
  </div>
</main>

<script>
// Animation des particules
function createParticles() {
  const container = document.getElementById('particles');
  const particleCount = 50;
  
  for (let i = 0; i < particleCount; i++) {
    const particle = document.createElement('div');
    particle.className = 'particle';
    
    const size = Math.random() * 6 + 2;
    particle.style.width = `${size}px`;
    particle.style.height = `${size}px`;
    
    particle.style.left = `${Math.random() * 100}%`;
    particle.style.top = `${Math.random() * 100}%`;
    
    particle.style.animationDelay = `${Math.random() * 20}s`;
    particle.style.opacity = Math.random() * 0.3 + 0.1;
    
    container.appendChild(particle);
  }
}

// Effet de parallaxe
window.addEventListener('scroll', () => {
  const scrolled = window.pageYOffset;
  const hero = document.querySelector('.hero');
  if (hero) {
    hero.style.backgroundPosition = `center ${scrolled * 0.5}px`;
  }
});

// Effet de saisie de texte
const title = document.querySelector('.hero h1');
if (title) {
  const text = title.textContent;
  title.textContent = '';
  
  let i = 0;
  function typeWriter() {
    if (i < text.length) {
      title.textContent += text.charAt(i);
      i++;
      setTimeout(typeWriter, 50);
    }
  }
  
  typeWriter();
}

// Initialisation
window.addEventListener('load', () => {
  createParticles();
  
  // Effet de hover sur les services
  document.querySelectorAll('.service-item').forEach(item => {
    item.addEventListener('mouseenter', () => {
      item.style.transform = 'translateY(-10px) scale(1.05)';
    });
    
    item.addEventListener('mouseleave', () => {
      item.style.transform = 'translateY(0) scale(1)';
    });
  });
});

// Navigation active (pour les liens sans route)
document.querySelectorAll('.nav a').forEach(link => {
  link.addEventListener('click', e => {
    // Si c'est un lien "#", on empêche la navigation et on anime
    if (link.getAttribute('href') === '#') {
      e.preventDefault();
      document.querySelectorAll('.nav a').forEach(l => l.classList.remove('active'));
      e.target.classList.add('active');
      
      e.target.style.transform = 'scale(0.95)';
      setTimeout(() => {
        e.target.style.transform = '';
      }, 200);
    }
    // Sinon, on laisse la navigation normale se faire
  });
});

// Effet sur le bouton d'inscription
const loginBtn = document.querySelector('.btn-login');
if (loginBtn) {
  loginBtn.addEventListener('click', function(e) {
    // Animation de clic seulement si le lien fonctionne
    this.style.transform = 'scale(0.95)';
    setTimeout(() => {
      this.style.transform = '';
    }, 200);
  });
}
</script>

</body>
</html>