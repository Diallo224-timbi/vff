<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Bienvenue — Soutien contre la violence faite aux femmes</title>
  <style>
    /* Styles simples, autonomes et accessibles */
    :root{--accent:#b30059;--muted:#f6f3f6;--text:#222}
    html,body{height:100%;margin:0;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial; color:var(--text);background:linear-gradient(180deg,#fff 0%,#f9f6f8 100%)}
    .container{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
    .card{width:100%;max-width:1000px;background:white;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,.08);overflow:hidden;display:grid;grid-template-columns:1fr 420px}
    .hero{padding:48px;display:flex;flex-direction:column;gap:18px}
    .eyebrow{display:inline-block;padding:6px 12px;border-radius:999px;background:var(--muted);font-weight:600;font-size:13px;color:var(--accent);width:max-content}
    h1{margin:0;font-size:28px;line-height:1.05}
    p.lead{margin:0;color:#444}
    .links{margin-top:14px;display:flex;gap:12px}
    .btn{display:inline-flex;align-items:center;gap:10px;padding:12px 18px;border-radius:10px;border:0;cursor:pointer;font-weight:700;text-decoration:none}
    .btn-primary{background:linear-gradient(90deg,var(--accent),#ff3b7a);color:white}
    .btn-ghost{background:transparent;border:2px solid #eee;color:var(--accent)}
    .side{background:linear-gradient(180deg,#fdeef6,#fff);padding:28px;display:flex;flex-direction:column;gap:18px;align-items:center;justify-content:center}
    .logo{font-weight:800;color:var(--accent);font-size:18px}
    .stat{font-size:14px;color:#333}
    .hotline{background:#fff;border-radius:10px;padding:12px;width:100%;box-shadow:0 6px 18px rgba(179,0,89,.06);text-align:center}
    .hotline strong{display:block;font-size:20px;color:var(--accent)}
    footer{padding:18px 24px;border-top:1px solid #f1eaf0;font-size:13px;color:#666}
    @media (max-width:900px){.card{grid-template-columns:1fr;padding:0}.side{order:2;padding:20px}.hero{padding:24px}}
  </style>
</head>
<body>
  <div class="container">
    <div class="card" role="main">
      <section class="hero" aria-labelledby="welcome-title">
        <span class="eyebrow">Bienvenue</span>
        <h1 id="welcome-title">Soutien & ressources — Violence faite aux femmes</h1>
        <p class="lead">Cette plateforme propose des informations, de l'aide et un espace sûr pour s'orienter vers des services d'accompagnement. Si tu es en danger immédiat, appelle les services d'urgence locaux.</p>

        <div class="links" role="navigation" aria-label="Accès rapide">
          <a class="btn btn-primary" href="{{ route('login') }}">Se connecter</a>
          <a class="btn btn-ghost" href="{{ route('register') }}">S'inscrire</a>
        </div>

        <div style="margin-top:18px;color:#444;font-size:14px;line-height:1.4">
          <strong>Confidentialité & sécurité</strong>
          <p style="margin:6px 0 0">Ton anonymat et ta sécurité sont prioritaires. Les informations partagées ici doivent être traitées avec discrétion.</p>
        </div>
      </section>

      <aside class="side" aria-labelledby="side-title">
        <div class="logo">Plateforme d'appui</div>
        <div class="stat">Ressources locales • Numéros d'aide • Conseils pratiques</div>

        <div class="hotline" role="contentinfo">
          <div>Numéro d'urgence / Ligne d'écoute</div>
          <strong>+33 0X XX XX XX XX</strong>
          <div style="font-size:13px;color:#555;margin-top:8px">Disponible 24h/24</div>
        </div>

        <div style="font-size:13px;color:#555;text-align:center">Tu peux créer un compte sécurisé pour accéder à des guides, signaler et trouver de l'aide.</div>
      </aside>

      <footer style="grid-column:1/-1">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
          <div>© {{ date('Y') }} — Respect, soutien et ressources</div>
          <div style="font-size:13px;color:#888">Confidentiel • Accessible</div>
        </div>
      </footer>
    </div>
  </div>
</body>
</html>