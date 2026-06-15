<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DrillTech HDD Company</title>
    <style>
        /* ===== BASE SYSTEM CONTEXT ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, rgba(10, 15, 30, 0.94), rgba(20, 27, 45, 0.98)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #f8fafc;
            min-height: 100vh;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(255, 140, 0, 0.02) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255, 140, 0, 0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 0;
        }

        /* ===== PLATFORM NAVIGATION BAR ===== */
        .navbar {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 50px;
            height: 80px;
            border-bottom: 1px solid rgba(255, 140, 0, 0.15);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
        }

        .logo-container img {
            height: 52px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0px 0px 10px rgba(255, 255, 255, 0.3));
            animation: logoGlow 4s infinite alternate ease-in-out;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .nav-links a {
            color: #94a3b8;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            padding: 5px 0;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; width: 0; height: 2px;
            background: #ff8c00;
            box-shadow: 0 0 10px #ff8c00;
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after { width: 100%; }
        .nav-links a:hover { color: #fff; text-shadow: 0 0 8px rgba(255, 140, 0, 0.6); }

        .admin-trigger { opacity: 0.15; font-size: 11px !important; transition: opacity 0.3s; }
        .admin-trigger:hover { opacity: 1; color: #ef4444 !important; text-shadow: 0 0 10px #ef4444; }

        /* ===== HERO INTRO ARRANGEMENT ===== */
        .hero-container {
            max-width: 1300px;
            margin: 160px auto 60px auto;
            padding: 0 40px;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-left h2 {
            font-size: 16px;
            color: #ff8c00;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .hero-left h1 {
            font-size: 56px;
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 40px;
            color: #fff;
        }

        /* ===== SYSTEM ACCESS CONTROL HUB ===== */
        .dropdown { position: relative; display: inline-block; }
        
        .btn-signin {
            background: linear-gradient(135deg, #ff8c00, #d96d00);
            color: white;
            padding: 16px 45px;
            font-size: 14px;
            font-weight: 800;
            border: 1px solid rgba(255, 140, 0, 0.4);
            border-radius: 4px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 2px;
            box-shadow: 0 6px 20px rgba(255, 140, 0, 0.25);
            transition: all 0.3s ease;
        }

        /* Jambatan halimunan untuk sambungkan butang dengan menu dropdown */
.btn-signin::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -25px; /* Lebar jambatan untuk tutup gap 18px tadi */
    width: 100%;
    height: 25px;
    background: transparent; /* Halimunan, user tak nampak */
}

        .dropdown-content {
            display: none;
            position: absolute;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            min-width: 240px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.6);
            border-radius: 4px;
            margin-top: 18px;
            border: 1px solid rgba(255, 140, 0, 0.3);
            z-index: 10;
        }

        .dropdown-content::before {
            content: ''; position: absolute; top: -7px; left: 35px; width: 12px; height: 12px;
            background: rgba(15, 23, 42, 0.95);
            border-left: 1px solid rgba(255, 140, 0, 0.3); border-top: 1px solid rgba(255, 140, 0, 0.3);
            transform: rotate(45deg);
        }

        .dropdown-content a {
            color: #e2e8f0; padding: 16px 24px; text-decoration: none; display: block;
            font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;
            transition: all 0.25s ease;
        }

        .dropdown-content a:hover {
            background: rgba(255, 140, 0, 0.15); color: #ff8c00; padding-left: 32px;
        }

        .dropdown:hover .dropdown-content { display: block; }

        .hero-right .img-frame {
            border-radius: 16px; overflow: hidden;
            border: 1px solid rgba(255, 140, 0, 0.3);
            box-shadow: 0 25px 60px rgba(0,0,0,0.6);
            animation: floatingEffect 5s infinite ease-in-out;
        }

        .hero-right img { width: 100%; display: block; }

        /* ===== DOCUMENT SEGMENTS ===== */
        .info-section { max-width: 1300px; margin: 40px auto 100px auto; padding: 0 40px; display: flex; flex-direction: column; gap: 40px; }
        
        .about-box, .contact-box {
            background: rgba(15, 23, 42, 0.6);
            border-left: 4px solid #ff8c00;
            border-radius: 4px; padding: 45px;
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 45px rgba(0,0,0,0.4);
        }

        .about-box h2, .contact-box h2 { font-size: 24px; color: #fff; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 2px; }
        .about-box p { color: #94a3b8; font-size: 15px; line-height: 1.75; }

        /* ===== CONTACT GRID & PULSING RADAR SCREEN ===== */
        .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .contact-info-block { display: flex; flex-direction: column; gap: 24px; }
        .info-item { display: flex; align-items: flex-start; gap: 16px; }
        .info-icon {
            font-size: 18px; background: rgba(255, 140, 0, 0.1); border: 1px solid rgba(255, 140, 0, 0.25);
            color: #ffaa33; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 6px;
        }
        .info-text h3 { font-size: 14px; text-transform: uppercase; color: #cbd5e1; margin-bottom: 4px; }
        .info-text p { color: #94a3b8; font-size: 15px; }

        /* Tactical Live Radar Map Mockup Box */
        .contact-map-mock {
            border: 1px solid rgba(255, 140, 0, 0.3);
            background: radial-gradient(circle, rgba(15, 23, 42, 0.6) 0%, rgba(10, 15, 30, 0.95) 100%);
            border-radius: 6px; position: relative; overflow: hidden;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            padding: 40px; text-align: center; min-height: 240px;
            box-shadow: inset 0 0 30px rgba(255, 140, 0, 0.15);
        }

        .radar-pulse-ring {
            width: 70px; height: 70px; border: 2px solid #ff8c00; border-radius: 50%;
            position: absolute; margin-bottom: 50px;
            animation: radarPulseScan 2.5s infinite ease-out; opacity: 0;
        }

        .contact-map-mock::before {
            content: '🎯'; font-size: 26px; position: absolute; margin-bottom: 50px;
            animation: targetGlowPulse 1.5s infinite alternate ease-in-out;
        }

        .map-title { font-size: 14px; color: #ffaa33; text-transform: uppercase; letter-spacing: 3px; font-weight: 700; margin-top: 65px; margin-bottom: 6px; }
        .map-subtitle { font-size: 12px; color: #64748b; font-family: monospace; }

        /* ===== ANIMATION TIMELINES ===== */
        @keyframes logoGlow { 0% { filter: drop-shadow(0 0 4px rgba(255,255,255,0.2)); } 100% { filter: drop-shadow(0 0 12px rgba(255,140,0,0.5)); } }
        @keyframes floatingEffect { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        @keyframes radarPulseScan { 0% { transform: scale(0.4); opacity: 1; } 100% { transform: scale(2.8); opacity: 0; } }
        @keyframes targetGlowPulse { 0% { transform: scale(0.9); filter: drop-shadow(0 0 2px #ff8c00); } 100% { transform: scale(1.1); filter: drop-shadow(0 0 12px #ff8c00); } }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo-container"><img src="images/logo.png" alt="DrillTech Logo"></div>
        <div class="nav-links">
            <a href="#">Home</a>
            <a onclick="scrollToSection('aboutSection')">About Us</a>
            <a onclick="scrollToSection('contactSection')">Contact</a>
            <a href="loginAD.php" class="admin-trigger">⚙ Admin System</a>
        </div>
    </div>

    <div class="hero-container">
        <div class="hero-left">
            <h2>DrillTech Management System</h2>
            <h1>DRILLTECH<br>HDD COMPANY</h1>
            <div class="dropdown">
                <button class="btn-signin">Access Terminal</button>
                <div class="dropdown-content">
                    <a href="loginCL.php">🔑 Client Portal</a>
                    <a href="loginEM.php">👷 Employee Portal</a>
                </div>
            </div>
        </div>
        <div class="hero-right">
            <div class="img-frame"><img src="images/logo 2.png" alt="DrillTech Asset"></div>
        </div>
    </div>

    <div class="info-section">
        <div class="about-box" id="aboutSection">
            <h2>Industrial Profile</h2>
            <p>DrillTech HDD Company is a premier specialist providing Horizontal Directional Drilling (HDD) solutions across various utility and construction sectors. Backed by modern engineering technologies and a highly committed team of professionals, we consistently guarantee that underground pipeline and utility installations are executed safely, effectively, and with minimal environmental disruption.</p>
        </div>

        <div class="contact-box" id="contactSection">
            <h2>Registered Operations</h2>
            <div class="contact-grid">
                <div class="contact-info-block">
                    <div class="info-item">
                        <div class="info-icon">📍</div>
                        <div class="info-text"><h3>Registered Office</h3><p>DrillTech HQ Berhad<br>Pasir Mas, Kelantan, Malaysia.</p></div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">📧</div>
                        <div class="info-text"><h3>Secure Infomail</h3><p>drilltechHQ@gmail.com</p></div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">📞</div>
                        <div class="info-text"><h3>Central Office Line</h3><p>+609-791 4321<br>+609-791 8899</p></div>
                    </div>
                </div>

                <div class="contact-map-mock">
                    <div class="radar-pulse-ring"></div>
                    <div class="map-title">🛰️ Satellite Grid Active</div>
                    <div class="map-subtitle">HQ Telemetry: 6.0419° N, 102.1435° E // Pasir Mas</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function scrollToSection(id) { document.getElementById(id).scrollIntoView({ behavior: 'smooth' }); }
    </script>
</body>
</html>