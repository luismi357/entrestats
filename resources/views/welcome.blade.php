<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrestats — Tu progreso, medido</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:300,400,500,600,700,800&family=Inter:300,400,500,600,700" rel="stylesheet" />
    <style>
        :root {
            --bg: #0b0d12;
            --surface: #0f1218;
            --card: #141821;
            --line: rgba(255,255,255,.08);
            --gold: #e9b558;
            --gold-soft: #f4c877;
            --gold-grad: linear-gradient(135deg,#f4c877,#e9b558 45%,#c8902e);
            --text: #e8eaef;
            --text-2: #a7adbb;
            --text-3: #6f7686;
            --on-gold: #241b08;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -webkit-tap-highlight-color: transparent;
        }
        h1,h2,h3,h4 { font-family: 'Outfit', sans-serif; font-weight: 700; line-height: 1.15; letter-spacing: -0.01em; }
        a { color: var(--gold); text-decoration: none; }
        a:hover { color: var(--gold-soft); }

        /* Fondo decorativo */
        .bg-flare {
            position: fixed; inset: 0; z-index: -1; pointer-events: none;
            background:
                radial-gradient(900px 600px at 85% -10%, rgba(233,181,88,.12), transparent 60%),
                radial-gradient(700px 500px at -8% 20%, rgba(114,184,245,.05), transparent 60%),
                radial-gradient(800px 700px at 50% 120%, rgba(233,181,88,.07), transparent 55%);
        }
        .bg-grid {
            position: fixed; inset: 0; z-index: -1; pointer-events: none;
            background-image: linear-gradient(var(--line) 1px, transparent 1px), linear-gradient(90deg, var(--line) 1px, transparent 1px);
            background-size: 56px 56px;
            mask-image: radial-gradient(ellipse 75% 60% at 50% 0%, #000 30%, transparent 75%);
            -webkit-mask-image: radial-gradient(ellipse 75% 60% at 50% 0%, #000 30%, transparent 75%);
        }

        /* Header */
        header {
            position: sticky; top: 0; z-index: 50;
            background: rgba(11,13,18,.72);
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--line);
        }
        .nav {
            max-width: 1140px; margin: 0 auto; padding: 14px 24px;
            display: flex; align-items: center; justify-content: space-between; gap: 20px;
        }
        .brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .brand-mark {
            width: 42px; height: 42px; border-radius: 12px;
            background: var(--gold-grad); color: var(--on-gold);
            display: grid; place-items: center; font-weight: 800; font-size: 18px;
            font-family: 'Outfit', sans-serif;
            box-shadow: 0 8px 24px rgba(233,181,88,.35);
            flex-shrink: 0;
        }
        .brand-name { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.25rem; color: var(--text); }
        .brand-name b { color: var(--gold); font-weight: 800; }

        .nav-right { display: flex; align-items: center; gap: 28px; }
        .nav-links { display: flex; align-items: center; gap: 28px; list-style: none; }
        .nav-links a { color: var(--text-2); font-weight: 500; font-size: .92rem; transition: color .18s; }
        .nav-links a:hover { color: var(--gold); }
        .nav-actions { display: flex; align-items: center; gap: 12px; }

        /* Hamburger */
        .hamburger {
            display: none; cursor: pointer; padding: 6px;
            background: none; border: none; -webkit-tap-highlight-color: transparent;
        }
        .hamburger span {
            display: block; width: 22px; height: 2px; background: var(--text); border-radius: 2px;
            transition: transform .25s, opacity .25s;
        }
        .hamburger span + span { margin-top: 5px; }
        .hamburger.active span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.active span:nth-child(2) { opacity: 0; }
        .hamburger.active span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 9px;
            font-family: 'Outfit', sans-serif; font-weight: 600; font-size: .95rem;
            padding: 11px 22px; border-radius: 12px; cursor: pointer;
            border: 1px solid transparent; transition: all .2s ease; text-decoration: none;
            -webkit-tap-highlight-color: transparent;
        }
        .btn-gold { background: var(--gold-grad); color: var(--on-gold); box-shadow: 0 10px 28px rgba(233,181,88,.35); }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 14px 34px rgba(233,181,88,.45); color: var(--on-gold); }
        .btn-ghost { background: transparent; color: var(--text); border-color: var(--line); }
        .btn-ghost:hover { background: rgba(255,255,255,.06); color: var(--text); }

        /* Hero */
        .hero {
            max-width: 1140px; margin: 0 auto; padding: 110px 24px 60px;
            text-align: center;
        }
        .pill {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 16px; border-radius: 99px;
            background: rgba(233,181,88,.1); border: 1px solid rgba(233,181,88,.35);
            color: var(--gold-soft); font-size: .82rem; font-weight: 600; letter-spacing: .04em;
        }
        .hero h1 {
            font-size: clamp(2rem, 5.4vw, 4rem);
            margin: 26px auto 18px; max-width: 820px;
        }
        .hero h1 .grad {
            background: var(--gold-grad);
            -webkit-background-clip: text; background-clip: text; color: transparent;
        }
        .hero p.lead { max-width: 640px; margin: 0 auto 34px; color: var(--text-2); font-size: 1.1rem; }
        .hero-cta { display: flex; justify-content: center; gap: 14px; flex-wrap: wrap; }

        .hero-stats {
            margin: 70px auto 0; max-width: 720px;
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px;
        }
        .hstat {
            background: var(--card); border: 1px solid var(--line); border-radius: 16px;
            padding: 20px 12px;
        }
        .hstat .n { font-family: 'Outfit'; font-size: 1.8rem; font-weight: 800; color: var(--gold); }
        .hstat .l { color: var(--text-3); font-size: .85rem; }

        /* Secciones */
        section { max-width: 1140px; margin: 0 auto; padding: 70px 24px; }
        .sec-head { text-align: center; max-width: 600px; margin: 0 auto 46px; }
        .sec-head .kicker { color: var(--gold); text-transform: uppercase; letter-spacing: .18em; font-size: .78rem; font-weight: 700; }
        .sec-head h2 { font-size: clamp(1.6rem, 3.4vw, 2.5rem); margin: 12px 0 10px; }
        .sec-head p { color: var(--text-2); }

        .grid-features {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px;
        }
        .feat {
            background: var(--card); border: 1px solid var(--line); border-radius: 20px;
            padding: 28px; transition: all .25s ease;
        }
        .feat:hover { transform: translateY(-4px); border-color: rgba(233,181,88,.5); box-shadow: 0 20px 44px rgba(0,0,0,.4); }
        .feat .ic {
            width: 52px; height: 52px; border-radius: 14px; display: grid; place-items: center;
            font-size: 1.3rem; margin-bottom: 18px;
        }
        .feat h3 { font-size: 1.12rem; margin-bottom: 8px; }
        .feat p { color: var(--text-2); font-size: .93rem; }

        .steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px,1fr)); gap: 18px; }
        .step {
            background: var(--card); border: 1px solid var(--line); border-radius: 20px; padding: 28px; position: relative;
        }
        .step .num {
            width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center;
            background: var(--gold-grad); color: var(--on-gold); font-weight: 800; font-family: 'Outfit'; margin-bottom: 16px;
        }
        .step h3 { font-size: 1.05rem; margin-bottom: 8px; }
        .step p { color: var(--text-2); font-size: .93rem; }

        .cta-band {
            max-width: 1140px; margin: 0 auto; padding: 0 24px 90px;
        }
        .cta-card {
            background:
                radial-gradient(600px 300px at 80% 0%, rgba(233,181,88,.18), transparent 60%),
                var(--card);
            border: 1px solid var(--line); border-radius: 26px; text-align: center; padding: 60px 30px;
        }
        .cta-card h2 { font-size: clamp(1.5rem, 3vw, 2.4rem); margin-bottom: 12px; }
        .cta-card p { color: var(--text-2); max-width: 500px; margin: 0 auto 28px; }

        footer {
            border-top: 1px solid var(--line); padding: 34px 24px; text-align: center; color: var(--text-3); font-size: .88rem;
        }
        footer .brand-name { font-size: 1rem; }

        /* ===== TABLET (≤ 900px) ===== */
        @media (max-width: 900px) {
            .nav { padding: 12px 18px; }
            .nav-links { display: none; }
            .nav-actions { display: none; }
            .hamburger { display: block; }
            .hero { padding-top: 70px; padding-bottom: 40px; }
            .hero h1 { font-size: clamp(1.8rem, 5vw, 2.8rem); }
            section { padding: 50px 18px; }
            .sec-head { margin-bottom: 32px; }
        }

        /* ===== PANEL MÓVIL (menú abierto) ===== */
        .mobile-panel {
            display: none;
            position: fixed; top: 68px; left: 0; right: 0; bottom: 0; z-index: 40;
            background: rgba(11,13,18,.96);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            padding: 28px 24px 40px;
            flex-direction: column; gap: 0;
            overflow-y: auto;
        }
        .mobile-panel.open { display: flex; }
        .mobile-panel nav { display: flex; flex-direction: column; gap: 0; }
        .mobile-panel nav a {
            display: block; padding: 14px 0; border-bottom: 1px solid var(--line);
            color: var(--text); font-size: 1.1rem; font-weight: 500; text-decoration: none;
        }
        .mobile-panel nav a:last-child { border-bottom: none; }
        .mobile-panel nav a:hover { color: var(--gold); }
        .mobile-panel .mp-actions {
            margin-top: 24px; display: flex; flex-direction: column; gap: 12px;
        }
        .mobile-panel .mp-actions .btn { width: 100%; }

        /* ===== MÓVIL (≤ 540px) ===== */
        @media (max-width: 540px) {
            .nav { padding: 12px 16px; }
            .brand-mark { width: 36px; height: 36px; font-size: 15px; border-radius: 10px; }
            .brand-name { font-size: 1.1rem; }
            .hero { padding-top: 56px; padding-bottom: 32px; padding-left: 16px; padding-right: 16px; }
            .hero h1 { font-size: clamp(1.6rem, 7vw, 2.2rem); margin: 18px auto 14px; }
            .hero p.lead { font-size: .95rem; margin-bottom: 24px; }
            .hero .btn { width: 100%; padding: 13px 20px; font-size: .92rem; }
            .hero-cta { flex-direction: column; gap: 12px; }
            .hero-stats { grid-template-columns: 1fr; gap: 10px; margin-top: 44px; }
            .hstat { display: flex; align-items: center; gap: 14px; padding: 16px 18px; }
            .hstat .n { font-size: 1.4rem; }
            .hstat .l { font-size: .82rem; }
            section { padding: 40px 16px; }
            .sec-head h2 { font-size: clamp(1.4rem, 6vw, 1.8rem); }
            .sec-head p { font-size: .92rem; }
            .grid-features { grid-template-columns: 1fr; gap: 14px; }
            .feat { padding: 22px; }
            .feat .ic { width: 44px; height: 44px; border-radius: 12px; font-size: 1.1rem; margin-bottom: 14px; }
            .steps { grid-template-columns: 1fr; gap: 14px; }
            .step { padding: 22px; }
            .cta-band { padding: 0 16px 60px; }
            .cta-card { padding: 40px 20px; border-radius: 20px; }
            .cta-card p { font-size: .93rem; }
            .cta-card .btn { width: 100%; padding: 13px 20px; }
            footer { padding: 24px 16px; font-size: .82rem; }
        }

        @media (max-width: 900px) {
            .bg-grid { display: none; }
        }
    </style>
</head>
<body>
    <div class="bg-flare"></div>
    <div class="bg-grid"></div>

    <header>
        <div class="nav">
            <a class="brand" href="/">
                <span class="brand-mark">ES</span>
                <span class="brand-name"><b>Entre</b>stats</span>
            </a>

            <nav class="nav-links">
                <a href="#funciones">Funciones</a>
                <a href="#como-funciona">Cómo funciona</a>
                <a href="#empieza">Empieza ya</a>
            </nav>

            <div class="nav-actions">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-gold">Entrar al panel</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-ghost">Iniciar sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-gold">Crear cuenta</a>
                        @endif
                    @endauth
                @endif
            </div>

            <button class="hamburger" id="burger" aria-label="Abrir menú" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <div class="mobile-panel" id="mobilePanel">
        <nav>
            <a href="#funciones">Funciones</a>
            <a href="#como-funciona">Cómo funciona</a>
            <a href="#empieza">Empieza ya</a>
        </nav>
        <div class="mp-actions">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/home') }}" class="btn btn-gold">Entrar al panel</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost">Iniciar sesión</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-gold">Crear cuenta</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

    <main>
        <section class="hero">
            <span class="pill">✦ Entrenamiento inteligente</span>
            <h1>Tu progreso, <span class="grad">medido</span> y al alcance de un vistazo</h1>
            <p class="lead">Registra cada serie, consulta tus estadísticas, controla tu IMC y habla con tu entrenador. Todo en una plataforma elegante y pensada para quien entrena en serio.</p>
            <div class="hero-cta">
                @auth
                    <a href="{{ url('/home') }}" class="btn btn-gold">Abrir mi panel</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-gold">Comenzar gratis</a>
                    <a href="#funciones" class="btn btn-ghost">Ver funciones</a>
                @endauth
            </div>

            <div class="hero-stats">
                <div class="hstat"><div class="n">100%</div><div class="l">Seguimiento real</div></div>
                <div class="hstat"><div class="n">24/7</div><div class="l">Acceso a tus datos</div></div>
                <div class="hstat"><div class="n">1 clic</div><div class="l">Cada registro</div></div>
            </div>
        </section>

        <section id="funciones">
            <div class="sec-head">
                <div class="kicker">Funciones</div>
                <h2>Todo lo que necesitas para evolucionar</h2>
                <p>Diseñado para que registrar y consultar tu rendimiento sea rápido, claro y motivador.</p>
            </div>
            <div class="grid-features">
                <div class="feat">
                    <div class="ic" style="background:rgba(233,181,88,.14); color:var(--gold);">📈</div>
                    <h3>Estadísticas de entrenamiento</h3>
                    <p>Registra peso, series y repeticiones por grupo muscular y visualiza tus gráficos día a día.</p>
                </div>
                <div class="feat">
                    <div class="ic" style="background:rgba(79,212,162,.14); color:#43c793;">⚖️</div>
                    <h3>Control de IMC</h3>
                    <p>Calcula tu índice de masa corporal y conoce en qué punto de tu objetivo te encuentras.</p>
                </div>
                <div class="feat">
                    <div class="ic" style="background:rgba(114,184,245,.14); color:#72b8f5;">💬</div>
                    <h3>Chat con tu entrenador</h3>
                    <p>Consulta dudas y recibe indicaciones directamente en conversación con tu entrenador.</p>
                </div>
                <div class="feat">
                    <div class="ic" style="background:rgba(244,113,127,.14); color:#f4717f;">📋</div>
                    <h3>Cuestionario inicial</h3>
                    <p>Un onboarding personalizado que adapta tu plan de entrenamiento a tus objetivos reales.</p>
                </div>
            </div>
        </section>

        <section id="como-funciona">
            <div class="sec-head">
                <div class="kicker">Cómo funciona</div>
                <h2>De cero a resultado en tres pasos</h2>
            </div>
            <div class="steps">
                <div class="step">
                    <div class="num">01</div>
                    <h3>Crea tu cuenta</h3>
                    <p>Regístrate en segundos y completa tu cuestionario inicial para conocer tu punto de partida.</p>
                </div>
                <div class="step">
                    <div class="num">02</div>
                    <h3>Registra tu entrenamiento</h3>
                    <p>Apunta el peso y las series de cada ejercicio en tu sesión. Tardará menos de un minuto.</p>
                </div>
                <div class="step">
                    <div class="num">03</div>
                    <h3>Consulta y evoluciona</h3>
                    <p>Revisa tus estadísticas, tu IMC y el chat con tu entrenador. Ajusta y mejora semana a semana.</p>
                </div>
            </div>
        </section>

        <div class="cta-band" id="empieza">
            <div class="cta-card">
                <h2>Listo para medir tu progreso</h2>
                <p>Crea tu cuenta y empieza hoy. Tu futuro en el gimnasio se construye registro a registro.</p>
                @auth
                    <a href="{{ url('/home') }}" class="btn btn-gold">Ir a mi panel</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-gold">Crear mi cuenta gratis</a>
                @endauth
            </div>
        </div>
    </main>

    <footer>
        <div class="brand-name"><b>Entre</b>stats</div>
        <p style="margin-top:8px;">Hecho con dedicación para quienes entrenan en serio. © {{ date('Y') }} Entrestats.</p>
    </footer>

    <script>
    (function(){
        var burger = document.getElementById('burger');
        var panel  = document.getElementById('mobilePanel');
        if (!burger || !panel) return;

        burger.addEventListener('click', function(){
            var open = panel.classList.toggle('open');
            burger.classList.toggle('active', open);
            burger.setAttribute('aria-expanded', open);
        });

        panel.querySelectorAll('nav a, .mp-actions a').forEach(function(a){
            a.addEventListener('click', function(){
                panel.classList.remove('open');
                burger.classList.remove('active');
                burger.setAttribute('aria-expanded', 'false');
            });
        });
    })();
    </script>
</body>
</html>
