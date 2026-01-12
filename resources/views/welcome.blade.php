<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Accueil — Plateforme multi-acteurs contre la violence faite aux femmes</title>
  @vite(['resources/css/app.css', 'resources/css/welcome.css', 'resources/js/app.js'])
  
  <style>
   
  </style>
</head>
<body>

  <div class="container">
    
    <!-- Hero -->
    <section class="hero">
      <h1>Plateforme multi-acteurs contre la violence faite aux femmes</h1>
      <p>Notre objectif est de fournir un espace sécurisé où victimes, associations, professionnels et bénévoles peuvent se connecter, partager des ressources et accéder à de l'aide.</p>
      <div>
        <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
        <a href="{{ route('register') }}" class="btn btn-ghost">S'inscrire</a>
      </div>
    </section>

    <!-- Acteurs -->
    <section>
      <h2 style="color:var(--accent);margin-bottom:20px;">Nos acteurs partenaires</h2>
      <div class="actors">
        <div class="actor-card">
          <h3>Association A</h3>
          <p>Accompagnement psychologique et juridique pour les victimes.</p>
        </div>
        <div class="actor-card">
          <h3>Service B</h3>
          <p>Numéro d’urgence et hébergement sécurisé.</p>
        </div>
        <div class="actor-card">
          <h3>Professionnel C</h3>
          <p>Consultations spécialisées et soutien médical.</p>
        </div>
        <div class="actor-card">
          <h3>Bénévoles D</h3>
          <p>Guides, conseils et accompagnement dans vos démarches.</p>
        </div>
      </div>
    </section>

    <!-- Resources -->
    <section>
      <h2 style="color:var(--accent);margin-bottom:20px;">Ressources et conseils</h2>
      <div class="resources">
        <div class="resource">
          <h4>Guide pratique pour se protéger</h4>
          <p>Conseils pour sécuriser votre environnement et savoir vers qui se tourner en urgence.</p>
        </div>
        <div class="resource">
          <h4>Numéros d’aide</h4>
          <p>Lignes d’écoute disponibles 24h/24 et 7j/7.</p>
        </div>
        <div class="resource">
          <h4>Information légale</h4>
          <p>Vos droits et démarches pour signaler un abus ou une violence.</p>
        </div>
      </div>
    </section>
    
  </div>

  <footer>
    © {{ date('Y') }} — Plateforme multi-acteurs de soutien contre la violence faite aux femmes. Confidentiel & sécurisé.
  </footer>

  <script>
  // Animation des sections au scroll
  const sections = document.querySelectorAll('section');

  const animateOnScroll = () => {
    sections.forEach((sec, index) => {
      const rect = sec.getBoundingClientRect();
      if(rect.top < window.innerHeight * 0.85) {
        // Apparition avec délai en fonction de l'index pour effet cascade
        sec.style.transition = `all 0.8s ease-out ${index * 0.15}s`;
        sec.style.opacity = 1;
        sec.style.transform = 'translateY(0) scale(1)';
      } else {
        sec.style.transition = 'all 0.8s ease-out';
        sec.style.opacity = 0;
        sec.style.transform = 'translateY(30px) scale(0.98)';
      }
    });
  };

  window.addEventListener('scroll', animateOnScroll);
  window.addEventListener('load', animateOnScroll);

  // Animation hover pour les cards
  const cards = document.querySelectorAll('.actor-card, .resource');
  cards.forEach(card => {
    card.addEventListener('mouseenter', () => {
      card.style.transform = 'translateY(-5px) scale(1.02)';
      card.style.transition = 'all 0.3s ease-out';
      card.style.boxShadow = '0 15px 30px rgba(0,0,0,0.12)';
    });
    card.addEventListener('mouseleave', () => {
      card.style.transform = 'translateY(0) scale(1)';
      card.style.transition = 'all 0.3s ease-out';
      card.style.boxShadow = '0 6px 18px rgba(0,0,0,0.06)';
    });
  });

  // Dégradé animé sur titres H1
  const heroTitle = document.querySelector('.hero h1');
  if(heroTitle){
    heroTitle.style.background = 'linear-gradient(90deg, #008C95, #59BEC9, #9B7EA4)';
    heroTitle.style.backgroundSize = '300% 300%';
    heroTitle.style.webkitBackgroundClip = 'text';
    heroTitle.style.webkitTextFillColor = 'transparent';
    heroTitle.style.animation = 'gradientMove 6s ease infinite';
  }
</script>

<style>
  @keyframes gradientMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }
</style>


</body>
</html>
