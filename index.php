<?php 
include('connection.php');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnosa Jagung - Sistem Pakar Penyakit Tanaman Jagung</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --hijau-50: #f0fdf4;
            --hijau-100: #dcfce7;
            --hijau-200: #bbf7d0;
            --hijau-300: #86efac;
            --hijau-400: #4ade80;
            --hijau-500: #22c55e;
            --hijau-600: #16a34a;
            --hijau-700: #15803d;
            --hijau-800: #166534;
            --hijau-900: #14532d;
            
            --kuning-400: #facc15;
            --kuning-500: #eab308;
            
            --glass-bg: rgba(255, 255, 255, 0.08);
            --glass-bg-strong: rgba(255, 255, 255, 0.12);
            --glass-border: rgba(255, 255, 255, 0.15);
            --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            
            --radius-sm: 8px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --radius-full: 9999px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            color: white;
            line-height: 1.6;
            overflow-x: hidden;
            background: #0a0a0a;
        }

        .font-serif { font-family: 'Playfair Display', Georgia, serif; }

        /* ==================== VIDEO BACKGROUND ==================== */
        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
        }

        .video-background video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(
                180deg,
                rgba(0, 20, 10, 0.7) 0%,
                rgba(0, 30, 15, 0.6) 30%,
                rgba(0, 25, 12, 0.7) 70%,
                rgba(0, 15, 8, 0.85) 100%
            );
        }

        /* Efek partikel hijau */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--hijau-400);
            border-radius: 50%;
            opacity: 0.3;
            animation: particleFloat 20s infinite linear;
        }

        @keyframes particleFloat {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.3; }
            90% { opacity: 0.3; }
            100% { transform: translateY(-100vh) rotate(720deg); opacity: 0; }
        }

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* ==================== GLASSMORPHISM NAVBAR ==================== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1rem 0;
            transition: all 0.4s ease;
        }

        .navbar.scrolled {
            background: rgba(0, 20, 10, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .navbar-brand-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(145deg, var(--hijau-500), var(--hijau-600));
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.4);
            transition: transform 0.3s;
        }

        .navbar-brand:hover .navbar-brand-icon {
            transform: rotate(-10deg) scale(1.05);
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
        }

        .navbar-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.75rem 1.25rem;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            font-size: 0.9rem;
            border-radius: var(--radius-full);
            transition: all 0.3s;
            border: 1px solid transparent;
        }

        .navbar-link:hover {
            background: var(--glass-bg);
            border-color: var(--glass-border);
            color: white;
        }

        .navbar-cta {
            background: linear-gradient(145deg, var(--hijau-500), var(--hijau-600)) !important;
            color: white !important;
            font-weight: 600;
            border: none !important;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
        }

        .navbar-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }

        .mobile-toggle {
            display: none;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.75rem;
            border-radius: var(--radius-md);
        }

        /* ==================== HERO SECTION ==================== */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            padding: 8rem 0 4rem;
        }

        .hero-content {
            width: 100%;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-full);
            font-size: 0.9rem;
            color: var(--hijau-300);
            margin-bottom: 2rem;
            animation: fadeInUp 0.8s ease;
        }

        .hero-badge i { color: var(--hijau-400); }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 700;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
            line-height: 1.1;
            animation: fadeInUp 0.8s ease 0.1s both;
        }

        .hero-title .highlight {
            background: linear-gradient(135deg, var(--hijau-400), var(--hijau-300));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-title .serif-italic {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-weight: 500;
        }

        .hero-description {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.7);
            max-width: 600px;
            margin: 0 auto 2.5rem;
            line-height: 1.8;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            animation: fadeInUp 0.8s ease 0.3s both;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 1rem 2rem;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: var(--radius-full);
            border: none;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-primary {
            background: linear-gradient(145deg, var(--hijau-500), var(--hijau-600));
            color: white;
            box-shadow: 0 8px 30px rgba(34, 197, 94, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(34, 197, 94, 0.5);
        }

        .btn-glass {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-glass:hover {
            background: var(--glass-bg-strong);
            transform: translateY(-4px);
        }

        /* ==================== GLASS CARD SECTIONS ==================== */
        .section {
            padding: 6rem 0;
            position: relative;
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 4rem;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            padding: 0.5rem 1.25rem;
            border-radius: var(--radius-full);
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--hijau-400);
            margin-bottom: 1.25rem;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .section-description {
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.1rem;
            line-height: 1.8;
        }

        /* ==================== GLASS STATS ==================== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }

        .stat-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius-lg);
            padding: 2.5rem 2rem;
            text-align: center;
            transition: all 0.4s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            background: var(--glass-bg-strong);
            border-color: rgba(34, 197, 94, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 30px rgba(34, 197, 94, 0.1);
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            background: linear-gradient(135deg, var(--hijau-400), var(--hijau-300));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.95rem;
            font-weight: 500;
        }

        /* ==================== GLASS FEATURES ==================== */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .feature-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius-xl);
            padding: 2.5rem;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--hijau-500), var(--hijau-400));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            background: var(--glass-bg-strong);
            border-color: rgba(34, 197, 94, 0.3);
            box-shadow: var(--glass-shadow), 0 0 40px rgba(34, 197, 94, 0.1);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            transition: transform 0.4s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(-5deg);
        }

        .feature-icon.hijau {
            background: linear-gradient(145deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1));
            color: var(--hijau-400);
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .feature-icon.kuning {
            background: linear-gradient(145deg, rgba(250, 204, 21, 0.2), rgba(250, 204, 21, 0.1));
            color: var(--kuning-400);
            border: 1px solid rgba(250, 204, 21, 0.3);
        }

        .feature-icon.biru {
            background: linear-gradient(145deg, rgba(99, 102, 241, 0.2), rgba(99, 102, 241, 0.1));
            color: #818cf8;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .feature-card h4 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .feature-card p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.95rem;
            line-height: 1.7;
        }

        /* ==================== GLASS STEPS ==================== */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            position: relative;
        }

        .steps-grid::before {
            content: '';
            position: absolute;
            top: 55px;
            left: 15%;
            right: 15%;
            height: 2px;
            background: linear-gradient(90deg, 
                transparent, 
                var(--hijau-500), 
                var(--hijau-400), 
                var(--hijau-500), 
                transparent
            );
            opacity: 0.5;
        }

        .step-card {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-number {
            width: 110px;
            height: 110px;
            margin: 0 auto 1.5rem;
            background: var(--glass-bg);
            border: 2px solid var(--glass-border);
            backdrop-filter: blur(20px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--hijau-400);
            transition: all 0.4s ease;
            position: relative;
        }

        .step-number::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 1px dashed rgba(34, 197, 94, 0.3);
            animation: spinSlow 20s linear infinite;
        }

        @keyframes spinSlow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .step-card:hover .step-number {
            background: rgba(34, 197, 94, 0.15);
            border-color: var(--hijau-500);
            transform: scale(1.1);
            box-shadow: 0 0 30px rgba(34, 197, 94, 0.3);
        }

        .step-card h4 {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .step-card p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            max-width: 200px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* ==================== GLASS CTA ==================== */
        .cta-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius-xl);
            padding: 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.15) 0%, transparent 70%);
            animation: ctaPulse 5s ease-in-out infinite;
        }

        .cta-card::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -20%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.1) 0%, transparent 70%);
            animation: ctaPulse 5s ease-in-out infinite 2.5s;
        }

        @keyframes ctaPulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.8; }
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        .cta-title {
            font-size: clamp(1.75rem, 3.5vw, 2.5rem);
            margin-bottom: 1rem;
        }

        .cta-description {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
            max-width: 500px;
            margin: 0 auto 2rem;
            line-height: 1.8;
        }

        /* ==================== GLASS FOOTER ==================== */
        footer {
            background: rgba(0, 15, 8, 0.8);
            backdrop-filter: blur(20px);
            border-top: 1px solid var(--glass-border);
            padding: 4rem 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 4rem;
            margin-bottom: 3rem;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-brand i { color: var(--hijau-400); }

        .footer-description {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.95rem;
            max-width: 300px;
            line-height: 1.8;
        }

        .footer-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: white;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li { margin-bottom: 0.75rem; }

        .footer-links a {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .footer-links a:hover {
            color: var(--hijau-400);
            transform: translateX(5px);
        }

        .footer-bottom {
            border-top: 1px solid var(--glass-border);
            padding-top: 2rem;
            text-align: center;
        }

        .footer-bottom p {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.9rem;
        }

        .footer-bottom .heart {
            color: #ef4444;
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .features-grid { grid-template-columns: 1fr; max-width: 500px; margin: 0 auto; }
            .steps-grid { grid-template-columns: repeat(2, 1fr); gap: 3rem; }
            .steps-grid::before { display: none; }
            .footer-grid { grid-template-columns: 1fr; text-align: center; gap: 2.5rem; }
            .footer-description { margin: 0 auto; }
        }

        @media (max-width: 768px) {
            .navbar-menu { display: none; }
            .mobile-toggle { display: block; }
            .stats-grid { grid-template-columns: 1fr; }
            .steps-grid { grid-template-columns: 1fr; }
            .cta-card { padding: 3rem 2rem; }
            .btn { width: 100%; }
        }

        /* Mobile Menu */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 15, 8, 0.95);
            backdrop-filter: blur(20px);
            z-index: 999;
            padding: 6rem 2rem 2rem;
        }

        .mobile-menu.active { display: block; }

        .mobile-menu-close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.75rem;
            border-radius: var(--radius-md);
        }

        .mobile-menu-links {
            list-style: none;
        }

        .mobile-menu-links li { margin-bottom: 0.5rem; }

        .mobile-menu-links a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 1.25rem;
            text-decoration: none;
            color: white;
            font-size: 1.1rem;
            font-weight: 500;
            border-radius: var(--radius-lg);
            border: 1px solid transparent;
            transition: all 0.3s;
        }

        .mobile-menu-links a:hover {
            background: var(--glass-bg);
            border-color: var(--glass-border);
        }
        /* ==================== SCROLL ZOOM EFFECT ==================== */
        .hero {
            transform-origin: center top;
            will-change: transform, opacity;
        }

        .hero.zoomed-out {
            transform: scale(0.9);
            opacity: 0.8;
        }

        /* ==================== SCROLL IMAGE SECTIONS ==================== */
        .scroll-section {
            padding: 6rem 0;
            position: relative;
        }

        .scroll-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .scroll-text {
            text-align: center;
            margin-bottom: 3rem;
        }

        .scroll-text h2 {
            font-size: clamp(1.25rem, 2.5vw, 1.5rem);
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
            margin-bottom: 0.75rem;
        }

        .scroll-text h3 {
            font-size: clamp(2rem, 4.5vw, 3.5rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .scroll-text h3 .highlight {
            background: linear-gradient(135deg, var(--hijau-400), var(--hijau-300));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Card dengan efek 3D */
        .scroll-card {
            position: relative;
            border-radius: var(--radius-xl);
            overflow: hidden;
            transform-style: preserve-3d;
            perspective: 1000px;
            will-change: transform;
            transition: transform 0.1s linear;
        }

        .scroll-card-frame {
            background: linear-gradient(145deg, rgba(30, 30, 30, 0.9), rgba(15, 15, 15, 0.95));
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-xl);
            padding: 0.5rem;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .scroll-card-inner {
            border-radius: calc(var(--radius-xl) - 0.35rem);
            overflow: hidden;
            position: relative;
        }

        .scroll-card-inner img {
            width: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            display: block;
            transition: transform 0.6s ease;
        }

        .scroll-card:hover .scroll-card-inner img {
            transform: scale(1.30);
        }

        /* Floating info cards */
        .floating-info {
            position: absolute;
            background: var(--glass-bg-strong);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius-lg);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0;
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 10;
        }

        .floating-info.visible {
            opacity: 1;
        }

        .floating-info.left {
            left: -20px;
            top: 25%;
            transform: translateX(-30px);
        }

        .floating-info.right {
            right: -20px;
            bottom: 25%;
            transform: translateX(30px);
        }

        .floating-info.visible.left {
            transform: translateX(0);
        }

        .floating-info.visible.right {
            transform: translateX(0);
        }

        .floating-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .floating-icon.hijau {
            background: rgba(34, 197, 94, 0.2);
            color: var(--hijau-400);
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .floating-icon.kuning {
            background: rgba(250, 204, 21, 0.2);
            color: var(--kuning-400);
            border: 1px solid rgba(250, 204, 21, 0.3);
        }

        .floating-text h6 {
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .floating-text p {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Scroll card animation states */
        .scroll-card {
            transform: perspective(1000px) rotateX(12deg) scale(0.92);
            opacity: 0.7;
        }

        .scroll-card.in-view {
            transform: perspective(1000px) rotateX(0deg) scale(1);
            opacity: 1;
        }

        @media (max-width: 1024px) {
            .floating-info {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .scroll-card-inner img {
                aspect-ratio: 4 / 3;
            }
        }
    </style>
</head>
<body>
    <!-- Video Background -->
    <div class="video-background">
        <video autoplay muted loop playsinline>
            <source src="images/viedo.mp4" type="video/mp4">
        </video>
    </div>
    <div class="video-overlay"></div>

    <!-- Partikel Hijau -->
    <div class="particles" id="particles"></div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-inner">
                <a href="#" class="navbar-brand">
                    <div class="navbar-brand-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    Diagnosa Jagung
                </a>
                <ul class="navbar-menu">
                    <li><a href="#" class="navbar-link"><i class="fas fa-home"></i> Beranda</a></li>
                    <li><a href="./info-penyakit/infopenyakit.php" class="navbar-link"><i class="fas fa-book-medical"></i> Info Penyakit</a></li>
                    <li><a href="./tentang/tentang.php" class="navbar-link"><i class="fas fa-info-circle"></i> Tentang</a></li>
                    <li><a href="./diagnosis/input_gejala.php" class="navbar-link navbar-cta"><i class="fas fa-stethoscope"></i> Mulai Diagnosa</a></li>
                </ul>
                <button class="mobile-toggle" onclick="bukaMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <button class="mobile-menu-close" onclick="tutupMobileMenu()">
            <i class="fas fa-times"></i>
        </button>
        <ul class="mobile-menu-links">
            <li><a href="#" onclick="tutupMobileMenu()"><i class="fas fa-home"></i> Beranda</a></li>
            <li><a href="./info-penyakit/infopenyakit.php"><i class="fas fa-book-medical"></i> Info Penyakit</a></li>
            <li><a href="./tentang/tentang.php"><i class="fas fa-info-circle"></i> Tentang</a></li>
            <li><a href="./diagnosis/input_gejala.php"><i class="fas fa-stethoscope"></i> Mulai Diagnosa</a></li>
        </ul>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-leaf"></i>
                    Sistem Pakar Berbasis Certainty Factor
                </div>
                <h1 class="hero-title">
                    Deteksi Penyakit <span class="highlight">Jagung</span><br>
                    dengan <span class="serif-italic">Cepat & Akurat</span>
                </h1>
                <p class="hero-description">
                    Lindungi hasil panen Anda dengan sistem pakar canggih. Identifikasi penyakit tanaman jagung secara dini untuk penanganan yang tepat dan efektif.
                </p>
                <div class="hero-buttons">
                    <a href="./diagnosis/input_gejala.php" class="btn btn-primary">
                        <i class="fas fa-stethoscope"></i> Mulai Diagnosa
                    </a>
                    <a href="./info-penyakit/infopenyakit.php" class="btn btn-glass">
                        <i class="fas fa-book-open"></i> Pelajari Lebih
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Scroll Image Section 1 -->
<section class="scroll-section">
    <div class="scroll-container">
        <div class="scroll-text">
            <h2>Teknologi Cerdas untuk Pertanian</h2>
            <h3 class="font-serif">Identifikasi Penyakit dalam <span class="highlight">Hitungan Detik</span></h3>
        </div>
        <div class="scroll-card" data-scroll-card>
            <div class="scroll-card-frame">
                <div class="scroll-card-inner">
                    <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?q=80&w=1400&h=788&auto=format&fit=crop" alt="Petani dengan Tanaman Jagung" draggable="false">
                </div>
            </div>
            <div class="floating-info left">
                <div class="floating-icon hijau">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="floating-text">
                    <h6>Akurasi 95%</h6>
                    <p>Tingkat keberhasilan tinggi</p>
                </div>
            </div>
            <div class="floating-info right">
                <div class="floating-icon kuning">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="floating-text">
                    <h6>Hasil Instan</h6>
                    <p>Diagnosa dalam detik</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scroll Image Section 2 -->
<section class="scroll-section">
    <div class="scroll-container">
        <div class="scroll-text">
            <h2>Database Penyakit Lengkap</h2>
            <h3 class="font-serif"><span class="highlight">6+ Penyakit</span> & 18+ Gejala Teridentifikasi</h3>
        </div>
        <div class="scroll-card" data-scroll-card>
            <div class="scroll-card-frame">
                <div class="scroll-card-inner">
                    <img src="images/pexels-lina-13054496.jpg" alt="Ladang Jagung" draggable="false">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scroll Image Section 3 -->
<section class="scroll-section">
    <div class="scroll-container">
        <div class="scroll-text">
            <h2>Solusi & Rekomendasi</h2>
            <h3 class="font-serif">Panduan Penanganan yang <span class="highlight">Tepat & Efektif</span></h3>
        </div>
        <div class="scroll-card" data-scroll-card>
            <div class="scroll-card-frame">
                <div class="scroll-card-inner">
                    <img src="images/cewek-jagung.png" alt="Hasil Panen Jagung" draggable="false">
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Stats Section -->
    <section class="section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">6+</div>
                    <div class="stat-label">Jenis Penyakit</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">18+</div>
                    <div class="stat-label">Gejala Teridentifikasi</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Tingkat Akurasi</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">40+</div>
                    <div class="stat-label">Konsultasi</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-star"></i> Keunggulan Kami
                </div>
                <h2 class="section-title font-serif">Mengapa Memilih Sistem Kami?</h2>
                <p class="section-description">
                    Kami menyediakan solusi terbaik untuk membantu petani Indonesia dalam mendeteksi dan menangani penyakit tanaman jagung
                </p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon hijau">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>Cepat & Akurat</h4>
                    <p>Diagnosa penyakit jagung secara cepat dengan tingkat akurasi tinggi menggunakan metode Certainty Factor yang telah teruji.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon kuning">
                        <i class="fas fa-book"></i>
                    </div>
                    <h4>Informasi Lengkap</h4>
                    <p>Dapatkan informasi detail tentang berbagai jenis penyakit jagung beserta solusi penanganan dan pencegahannya.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon biru">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4>Mudah Diakses</h4>
                    <p>Akses sistem kapan saja dan di mana saja melalui perangkat mobile maupun desktop dengan tampilan yang responsif.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-cogs"></i> Cara Kerja
                </div>
                <h2 class="section-title font-serif">Bagaimana Sistemnya Bekerja?</h2>
                <p class="section-description">
                    Proses diagnosa yang sederhana dan mudah dipahami dalam 4 langkah
                </p>
            </div>

            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h4>Pilih Gejala</h4>
                    <p>Identifikasi dan pilih gejala yang terlihat pada tanaman jagung Anda</p>
                </div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h4>Analisis Sistem</h4>
                    <p>Sistem akan menganalisis gejala menggunakan metode Certainty Factor</p>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h4>Hasil Diagnosa</h4>
                    <p>Dapatkan hasil diagnosa penyakit beserta tingkat kepastiannya</p>
                </div>
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h4>Solusi & Penanganan</h4>
                    <p>Terima rekomendasi penanganan dan pencegahan yang tepat</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section">
        <div class="container">
            <div class="cta-card">
                <div class="cta-content">
                    <h2 class="cta-title font-serif">Siap Melindungi Tanaman Jagung Anda?</h2>
                    <p class="cta-description">
                        Mulai diagnosa sekarang dan dapatkan rekomendasi penanganan yang tepat untuk tanaman Anda.
                    </p>
                    <a href="./diagnosis/input_gejala.php" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Diagnosa Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="footer-brand">
                        <i class="fas fa-seedling"></i> Diagnosa Jagung
                    </div>
                    <p class="footer-description">
                        Sistem pakar untuk membantu petani Indonesia dalam mendeteksi dan menangani penyakit tanaman jagung dengan cepat dan akurat.
                    </p>
                </div>
                <div class="footer-col">
                    <h5 class="footer-title">Menu Navigasi</h5>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Beranda</a></li>
                        <li><a href="./diagnosis/input_gejala.php"><i class="fas fa-chevron-right"></i> Mulai Diagnosa</a></li>
                        <li><a href="./info-penyakit/infopenyakit.php"><i class="fas fa-chevron-right"></i> Info Penyakit</a></li>
                        <li><a href="./tentang/tentang.php"><i class="fas fa-chevron-right"></i> Tentang</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5 class="footer-title">Kontak Kami</h5>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-envelope"></i> info@diagnosajagung.id</a></li>
                        <li><a href="#"><i class="fas fa-phone"></i> +62 812 3456 7890</a></li>
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i> Indonesia</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Sistem Pakar Diagnosa Penyakit Jagung. Dibuat dengan <i class="fas fa-heart heart"></i> untuk Petani Indonesia</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Buat partikel hijau
        function buatPartikel() {
            const container = document.getElementById('particles');
            const jumlahPartikel = 100;

            for (let i = 0; i < jumlahPartikel; i++) {
                const partikel = document.createElement('div');
                partikel.className = 'particle';
                partikel.style.left = Math.random() * 100 + '%';
                partikel.style.animationDuration = (1 + Math.random() * 20) + 's';
                partikel.style.animationDelay = Math.random() * 20 + 's';
                partikel.style.width = (2 + Math.random() * 4) + 'px';
                partikel.style.height = partikel.style.width;
                container.appendChild(partikel);
            }
        }

        buatPartikel();

        // Mobile menu
        function bukaMobileMenu() {
            document.getElementById('mobileMenu').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function tutupMobileMenu() {
            document.getElementById('mobileMenu').classList.remove('active');
            document.body.style.overflow = '';
        }

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Animasi saat scroll
        const observerAnimasi = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.stat-card, .feature-card, .step-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observerAnimasi.observe(el);
        });
        // ==================== HERO ZOOM OUT EFFECT ====================
        const heroSection = document.querySelector('.hero');

        function updateHeroZoom() {
            const scrollY = window.scrollY;
            const windowHeight = window.innerHeight;
            
            // Hitung progress scroll (0 sampai 1)
            const scrollProgress = Math.min(scrollY / (windowHeight * 0.5), 1);
            
            // Scale: 1 -> 0.85, Opacity: 1 -> 0.6
            const scale = 1.15 - (scrollProgress * 0.35);
            const opacity = 1 - (scrollProgress * 0.5);
            const translateY = scrollProgress * -50;
            
            heroSection.style.transform = `scale(${scale}) translateY(${translateY}px)`;
            heroSection.style.opacity = opacity;
        }

        // ==================== SCROLL CARD 3D EFFECT ====================
        const scrollCards = document.querySelectorAll('[data-scroll-card]');

        function updateScrollCards() {
            scrollCards.forEach(card => {
                const rect = card.getBoundingClientRect();
                const windowHeight = window.innerHeight;
                const cardCenter = rect.top + rect.height / 2;
                const viewportCenter = windowHeight / 2;
                
                // Hitung jarak dari tengah viewport
                const distanceFromCenter = cardCenter - viewportCenter;
                const maxDistance = windowHeight * 200;
                
                // Normalize progress: 1 = di tengah, 0 = jauh
                let progress = 1 - Math.abs(distanceFromCenter) / maxDistance;
                progress = Math.max(0, Math.min(1, progress));
                
                // Cek apakah card terlihat
                const isInView = rect.top < windowHeight && rect.bottom > 0;
                
                if (isInView) {
                    // rotateX: 12deg -> 0deg
                    const rotateX = 50 * (1 - progress);
                    // scale: 0.92 -> 1
                    const scale = 0.92 + (0.08 * progress);
                    // opacity: 0.7 -> 1
                    const opacity = 0.7 + (0.3 * progress);
                    // translateY: bergerak sedikit
                    const translateY = 2 * (1 - progress);
                    
                    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) scale(${scale}) translateY(${translateY}px)`;
                    card.style.opacity = opacity;
                    
                    // Tampilkan floating info cards
                    const floatingInfos = card.querySelectorAll('.floating-info');
                    floatingInfos.forEach(info => {
                        if (progress > 0.5) {
                            info.classList.add('visible');
                        } else {
                            info.classList.remove('visible');
                        }
                    });
                }
            });
        }

        // ==================== SCROLL EVENT LISTENER ====================
        let ticking = false;

        function onScroll() {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    updateHeroZoom();
                    updateScrollCards();
                    ticking = false;
                });
                ticking = true;
            }
        }

        window.addEventListener('scroll', onScroll, { passive: true });

        // Initial call
        updateHeroZoom();
        updateScrollCards();
    </script>
</body>
</html>