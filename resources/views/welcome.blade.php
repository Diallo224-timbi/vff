<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Accueil — Plateforme multi-acteurs contre la violence faite aux femmes</title>
  <style>
    :root {
      --accent: #b30059;
      --muted: #f6f3f6;
      --text: #222;
    }

    html, body {
      margin: 0;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      color: var(--text);
      background: linear-gradient(180deg,#fff 0%,#f9f6f8 100%);
      scroll-behavior: smooth;
    }

    .container { max-width: 1200px; margin: auto; padding: 2rem; }

    .hero {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 16px;
      padding: 60px 20px;
      background: linear-gradient(180deg, #fdeef6, #fff);
      border-radius: 12px;
      margin-bottom: 40px;
    }

    .hero h1 {
      font-size: 2.5rem;
      color: var(--accent);
      margin: 0;
    }

    .hero p {
      font-size: 1.1rem;
      max-width: 700px;
      color: #444;
      line-height: 1.6;
    }

    .hero .btn {
      padding: 12px 20px;
      border-radius: 8px;
      font-weight: 700;
      text-decoration: none;
      display: inline-block;
      margin: 6px;
      transition: transform 0.2s;
    }

    .hero .btn-primary {
      background: linear-gradient(90deg, var(--accent), #ff3b7a);
      color: white;
      border: none;
    }

    .hero .btn-primary:hover { transform: translateY(-2px); }

    .hero .btn-ghost {
      background: transparent;
      border: 2px solid var(--accent);
      color: var(--accent);
    }

    .actors {
      display: grid;
      grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
      gap: 20px;
      margin-bottom: 40px;
    }

    .actor-card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 6px 18px rgba(0,0,0,0.06);
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .actor-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .actor-card h3 {
      margin-top: 10px;
      margin-bottom: 6px;
      color: var(--accent);
    }

    .actor-card p {
      font-size: 14px;
      color: #555;
      line-height: 1.4;
    }

    .resources {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-bottom: 40px;
    }

    .resource {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.06);
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .resource:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }

    .resource h4 {
      margin-top: 0;
      color: var(--accent);
    }

    footer {
      text-align: center;
      padding: 20px;
      border-top: 1px solid #f1eaf0;
      font-size: 13px;
      color: #666;
    }

    @media(max-width:768px){
      .hero h1 { font-size: 2rem; }
    }
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
    // Légère animation scroll sur les sections
    const sections = document.querySelectorAll('section');
    window.addEventListener('scroll', () => {
      sections.forEach(sec => {
        const rect = sec.getBoundingClientRect();
        if(rect.top < window.innerHeight * 0.85) {
          sec.style.opacity = 1;
          sec.style.transform = 'translateY(0)';
          sec.style.transition = 'all 0.8s ease-out';
        } else {
          sec.style.opacity = 0;
          sec.style.transform = 'translateY(30px)';
        }
      });
    });
  </script>

</body>
</html>
