<?php 
include('conncection.php');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnosa Jagung - Sistem Pakar Penyakit Tanaman Jagung</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Organic Nature Palette */
            --forest-50: #f0f7f4;
            --forest-100: #dceee5;
            --forest-200: #bce0cf;
            --forest-300: #8cc9ad;
            --forest-400: #5aab86;
            --forest-500: #3a9068;
            --forest-600: #2a7453;
            --forest-700: #235d44;
            --forest-800: #1f4a38;
            --forest-900: #1b3d2f;
            --forest-950: #0d221a;
            
            --corn-50: #fffbeb;
            --corn-100: #fef3c7;
            --corn-200: #fde68a;
            --corn-300: #fcd34d;
            --corn-400: #fbbf24;
            --corn-500: #f59e0b;
            --corn-600: #d97706;
            --corn-700: #b45309;
            
            --cream-50: #fdfcf9;
            --cream-100: #faf7f0;
            --cream-200: #f3ede0;
            --cream-800: #4a4637;
            --cream-900: #2d2a20;
            
            /* Semantic */
            --background: #fdfcf9;
            --foreground: #1b3d2f;
            --card-bg: #ffffff;
            --muted: #6b7c74;
            
            /* Radius */
            --radius-sm: 8px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --radius-2xl: 48px;
            --radius-full: 9999px;
            
            /* Shadows */
            --shadow-soft: 0 4px 24px -4px rgba(27, 61, 47, 0.08);
            --shadow-medium: 0 8px 32px -8px rgba(27, 61, 47, 0.12);
            --shadow-strong: 0 24px 64px -16px rgba(27, 61, 47, 0.2);
            --shadow-glow: 0 0 80px -20px rgba(58, 144, 104, 0.4);
        }

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--background);
            color: var(--foreground);
            line-height: 1.6;
            overflow-x: hidden;
        }

        .font-serif {
            font-family: 'Playfair Display', Georgia, serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            line-height: 1.1;
        }

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Grain Texture Overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
            opacity: 0.025;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        /* ==================== NAVBAR ==================== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1.25rem 0;
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .navbar.scrolled {
            background: rgba(253, 252, 249, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 1px 0 rgba(27, 61, 47, 0.08);
            padding: 0.875rem 0;
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            transition: color 0.3s;
        }

        .navbar.scrolled .navbar-brand {
            color: var(--foreground);
        }

        .navbar-brand-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(145deg, var(--corn-400) 0%, var(--corn-500) 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.375rem;
            color: var(--forest-950);
            box-shadow: 0 4px 12px -2px rgba(251, 191, 36, 0.4);
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover .navbar-brand-icon {
            transform: rotate(-8deg) scale(1.05);
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            list-style: none;
        }

        .navbar-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.75rem 1.375rem;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            font-size: 0.925rem;
            border-radius: var(--radius-full);
            transition: all 0.3s;
            position: relative;
        }

        .navbar.scrolled .navbar-link {
            color: var(--foreground);
        }

        .navbar-link::after {
            content: '';
            position: absolute;
            bottom: 6px;
            left: 50%;
            width: 0;
            height: 2px;
            background: currentColor;
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .navbar-link:hover::after {
            width: calc(100% - 2.75rem);
        }

        .navbar-cta {
            background: white;
            color: var(--forest-700) !important;
            font-weight: 600;
            box-shadow: var(--shadow-soft);
        }

        .navbar-cta::after {
            display: none;
        }

        .navbar-cta:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .navbar.scrolled .navbar-cta {
            background: linear-gradient(145deg, var(--forest-600) 0%, var(--forest-700) 100%);
            color: white !important;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        .navbar.scrolled .mobile-toggle {
            color: var(--foreground);
        }

        /* ==================== HERO SECTION ==================== */
        .hero {
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
            background: linear-gradient(165deg, var(--forest-800) 0%, var(--forest-900) 40%, var(--forest-950) 100%);
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: 
                radial-gradient(ellipse 100% 70% at 10% 30%, rgba(58, 144, 104, 0.2) 0%, transparent 50%),
                radial-gradient(ellipse 70% 50% at 90% 70%, rgba(251, 191, 36, 0.12) 0%, transparent 50%),
                radial-gradient(ellipse 50% 50% at 50% 50%, rgba(58, 144, 104, 0.08) 0%, transparent 60%);
        }

        /* Organic pattern */
        .hero-pattern {
            position: absolute;
            inset: 0;
            opacity: 0.04;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M50 10C50 10 40 30 40 50C40 70 50 90 50 90C50 90 60 70 60 50C60 30 50 10 50 10Z' fill='%23ffffff' fill-opacity='0.6'/%3E%3Ccircle cx='25' cy='25' r='3' fill='%23ffffff' fill-opacity='0.3'/%3E%3Ccircle cx='75' cy='75' r='3' fill='%23ffffff' fill-opacity='0.3'/%3E%3C/svg%3E");
            background-size: 100px 100px;
        }

        /* Floating organic shapes */
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: organicFloat 25s ease-in-out infinite;
        }

        .floating-shape-1 {
            width: 500px;
            height: 500px;
            background: rgba(58, 144, 104, 0.25);
            top: -15%;
            right: -10%;
            animation-delay: 0s;
        }

        .floating-shape-2 {
            width: 400px;
            height: 400px;
            background: rgba(251, 191, 36, 0.15);
            bottom: 5%;
            left: -10%;
            animation-delay: -12s;
        }

        .floating-shape-3 {
            width: 300px;
            height: 300px;
            background: rgba(90, 171, 134, 0.2);
            top: 50%;
            left: 30%;
            animation-delay: -6s;
        }

        @keyframes organicFloat {
            0%, 100% { transform: translate(0, 0) scale(1) rotate(0deg); }
            25% { transform: translate(40px, -30px) scale(1.08) rotate(5deg); }
            50% { transform: translate(-20px, 40px) scale(0.95) rotate(-5deg); }
            75% { transform: translate(30px, 20px) scale(1.03) rotate(3deg); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 10rem 0 6rem;
            width: 100%;
        }

        .hero-text-center {
            text-align: center;
            max-width: 900px;
            margin: 0 auto 4rem;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-full);
            font-size: 0.9rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-badge i {
            color: var(--corn-400);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-title {
            font-size: clamp(2.75rem, 6vw, 5rem);
            color: white;
            margin-bottom: 1.75rem;
            letter-spacing: -0.03em;
            animation: fadeInUp 0.8s ease-out 0.1s both;
        }

        .hero-title .highlight {
            position: relative;
            display: inline-block;
            color: var(--corn-400);
        }

        .hero-title .highlight::after {
            content: '';
            position: absolute;
            bottom: 0.05em;
            left: -0.05em;
            right: -0.05em;
            height: 0.35em;
            background: linear-gradient(90deg, var(--corn-400), var(--corn-500));
            opacity: 0.25;
            border-radius: 4px;
            z-index: -1;
            transform: skewX(-3deg);
        }

        .hero-title .serif-italic {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-weight: 500;
        }

        .hero-description {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.75);
            max-width: 600px;
            margin: 0 auto 2.5rem;
            line-height: 1.8;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            animation: fadeInUp 0.8s ease-out 0.3s both;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 1.125rem 2.25rem;
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
            background: linear-gradient(145deg, var(--corn-400) 0%, var(--corn-500) 100%);
            color: var(--forest-950);
            box-shadow: 0 8px 32px -8px rgba(251, 191, 36, 0.5);
        }

        .btn-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px -8px rgba(251, 191, 36, 0.6);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-4px);
        }

        /* ==================== CONTAINER SCROLL SECTION ==================== */
        .scroll-section {
            padding: 0;
            position: relative;
        }

        .scroll-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 6rem 2rem;
        }

        .scroll-container.light-bg {
            background: var(--background);
        }

        .scroll-container.dark-bg {
            background: linear-gradient(180deg, var(--forest-900) 0%, var(--forest-950) 100%);
        }

        .scroll-title-wrapper {
            text-align: center;
            margin-bottom: 3rem;
            max-width: 800px;
            z-index: 10;
            will-change: transform;
            transition: transform 0.1s linear;
        }

        .scroll-title {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .scroll-container.light-bg .scroll-title {
            color: var(--foreground);
        }

        .scroll-container.dark-bg .scroll-title {
            color: white;
        }

        .scroll-title-large {
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 700;
            line-height: 1.05;
            letter-spacing: -0.03em;
        }

        .scroll-container.light-bg .scroll-title-large {
            background: linear-gradient(145deg, var(--forest-700) 0%, var(--forest-600) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .scroll-container.dark-bg .scroll-title-large {
            color: white;
        }

        .scroll-card-wrapper {
            width: 100%;
            max-width: 1000px;
            position: relative;
            transform-style: preserve-3d;
            will-change: transform;
            transition: transform 0.1s linear;
            transform: perspective(1000px) rotateX(15deg) scale(0.9);
        }

        .scroll-container.in-view .scroll-card-wrapper {
            transform: perspective(1000px) rotateX(0deg) scale(1);
        }

        .scroll-card {
            background: linear-gradient(145deg, #2a2a2a 0%, #1a1a1a 100%);
            border-radius: var(--radius-xl);
            padding: 0.75rem;
            box-shadow: var(--shadow-strong), 0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }

        .scroll-card-inner {
            border-radius: calc(var(--radius-xl) - 0.5rem);
            overflow: hidden;
            position: relative;
        }

        .scroll-card-image {
            width: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            object-position: left top;
            display: block;
            transition: transform 0.6s ease;
        }

        .scroll-card:hover .scroll-card-image {
            transform: scale(1.03);
        }

        /* Floating cards around scroll container */
        .scroll-float-card {
            position: absolute;
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow-strong);
            display: flex;
            align-items: center;
            gap: 14px;
            z-index: 20;
            opacity: 0;
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .scroll-container.in-view .scroll-float-card {
            opacity: 1;
        }

        .scroll-float-card-1 {
            top: 20%;
            left: 5%;
            transform: translateX(-30px);
            transition-delay: 0.4s;
        }

        .scroll-float-card-2 {
            bottom: 25%;
            right: 5%;
            transform: translateX(30px);
            transition-delay: 0.5s;
        }

        .scroll-container.in-view .scroll-float-card-1 {
            transform: translateX(0);
            animation: floatCard 5s ease-in-out infinite 0.6s;
        }

        .scroll-container.in-view .scroll-float-card-2 {
            transform: translateX(0);
            animation: floatCard 5s ease-in-out infinite 0.8s;
        }

        @keyframes floatCard {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .float-icon {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.375rem;
        }

        .float-icon.green {
            background: var(--forest-100);
            color: var(--forest-600);
        }

        .float-icon.gold {
            background: var(--corn-100);
            color: var(--corn-600);
        }

        .float-text h6 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--foreground);
            margin-bottom: 3px;
        }

        .float-text p {
            font-size: 0.8rem;
            color: var(--muted);
        }

        /* ==================== STATS SECTION ==================== */
        .stats-section {
            padding: 6rem 0;
            background: linear-gradient(180deg, var(--forest-950) 0%, var(--forest-900) 100%);
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(ellipse 60% 50% at 50% 100%, rgba(58, 144, 104, 0.15) 0%, transparent 70%);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 3rem;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            text-align: center;
            position: relative;
            padding: 2rem;
        }

        .stat-item::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 60px;
            width: 1px;
            background: linear-gradient(180deg, transparent, rgba(255,255,255,0.2), transparent);
        }

        .stat-item:last-child::after {
            display: none;
        }

        .stat-number {
            font-size: 4rem;
            font-weight: 800;
            font-family: 'Playfair Display', Georgia, serif;
            background: linear-gradient(145deg, var(--corn-400) 0%, var(--corn-300) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 0.75rem;
        }

        .stat-label {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        /* ==================== FEATURES SECTION ==================== */
        .features-section {
            padding: 8rem 0;
            background: var(--background);
            position: relative;
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 5rem;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--forest-100);
            padding: 0.625rem 1.5rem;
            border-radius: var(--radius-full);
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--forest-700);
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            color: var(--foreground);
            margin-bottom: 1.25rem;
            letter-spacing: -0.02em;
        }

        .section-description {
            color: var(--muted);
            font-size: 1.15rem;
            line-height: 1.8;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2.5rem;
        }

        .feature-card {
            background: white;
            border-radius: var(--radius-xl);
            padding: 3rem;
            border: 1px solid rgba(27, 61, 47, 0.08);
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--forest-500), var(--forest-400));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.5s ease;
        }

        .feature-card:hover {
            transform: translateY(-12px);
            box-shadow: var(--shadow-strong);
            border-color: transparent;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 2rem;
            transition: transform 0.4s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(-5deg);
        }

        .feature-icon.style-1 {
            background: linear-gradient(145deg, var(--forest-100) 0%, var(--forest-200) 100%);
            color: var(--forest-600);
        }

        .feature-icon.style-2 {
            background: linear-gradient(145deg, var(--corn-100) 0%, var(--corn-200) 100%);
            color: var(--corn-600);
        }

        .feature-icon.style-3 {
            background: linear-gradient(145deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0.2) 100%);
            color: #6366f1;
        }

        .feature-card h4 {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--foreground);
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.75;
        }

        /* ==================== HOW IT WORKS ==================== */
        .how-section {
            padding: 8rem 0;
            background: white;
        }

        .how-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2.5rem;
            position: relative;
        }

        .how-grid::before {
            content: '';
            position: absolute;
            top: 60px;
            left: 12%;
            right: 12%;
            height: 3px;
            background: linear-gradient(90deg, var(--forest-300), var(--corn-300), var(--forest-300));
            border-radius: 2px;
            z-index: 0;
        }

        .how-step {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-number {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            background: linear-gradient(145deg, var(--forest-500) 0%, var(--forest-600) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 800;
            font-family: 'Playfair Display', Georgia, serif;
            color: white;
            box-shadow: var(--shadow-glow);
            position: relative;
        }

        .step-number::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 2px dashed var(--forest-300);
            animation: spinSlow 30s linear infinite;
        }

        .step-number::after {
            content: '';
            position: absolute;
            inset: -16px;
            border-radius: 50%;
            border: 1px dashed var(--forest-200);
            animation: spinSlow 40s linear infinite reverse;
        }

        @keyframes spinSlow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .how-step h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--foreground);
            margin-bottom: 0.75rem;
        }

        .how-step p {
            font-size: 0.95rem;
            color: var(--muted);
            max-width: 220px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* ==================== CTA SECTION ==================== */
        .cta-section {
            padding: 8rem 0;
            background: var(--background);
        }

        .cta-card {
            background: linear-gradient(145deg, var(--forest-700) 0%, var(--forest-800) 50%, var(--forest-900) 100%);
            border-radius: var(--radius-2xl);
            padding: 5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-card::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -30%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(251, 191, 36, 0.15) 0%, transparent 70%);
            animation: ctaPulse 6s ease-in-out infinite;
        }

        .cta-card::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(58, 144, 104, 0.2) 0%, transparent 70%);
            animation: ctaPulse 6s ease-in-out infinite 3s;
        }

        @keyframes ctaPulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.15); opacity: 0.8; }
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        .cta-title {
            font-size: clamp(1.75rem, 3.5vw, 2.75rem);
            color: white;
            margin-bottom: 1.25rem;
        }

        .cta-description {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.2rem;
            max-width: 550px;
            margin: 0 auto 2.5rem;
            line-height: 1.8;
        }

        .btn-cta {
            background: white;
            color: var(--forest-700);
            padding: 1.25rem 3rem;
            font-weight: 700;
            box-shadow: var(--shadow-strong);
        }

        .btn-cta:hover {
            transform: translateY(-4px);
            box-shadow: 0 24px 60px -12px rgba(0, 0, 0, 0.3);
        }

        /* ==================== FOOTER ==================== */
        footer {
            background: var(--forest-950);
            color: white;
            padding: 5rem 0 2.5rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.75fr 1fr 1fr;
            gap: 5rem;
            margin-bottom: 4rem;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
        }

        .footer-brand i {
            color: var(--forest-400);
        }

        .footer-description {
            color: rgba(255, 255, 255, 0.6);
            font-size: 1rem;
            max-width: 320px;
            line-height: 1.85;
        }

        .footer-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1.75rem;
            color: white;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 1rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .footer-links a:hover {
            color: var(--corn-400);
            transform: translateX(6px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 2.5rem;
            text-align: center;
        }

        .footer-bottom p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.925rem;
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
        @media (max-width: 1200px) {
            .scroll-float-card {
                display: none;
            }
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stat-item:nth-child(2)::after {
                display: none;
            }

            .features-grid {
                grid-template-columns: 1fr;
                max-width: 500px;
                margin: 0 auto;
            }

            .how-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 4rem 2rem;
            }

            .how-grid::before {
                display: none;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 3rem;
            }

            .footer-description {
                margin: 0 auto;
            }
        }

        @media (max-width: 768px) {
            .navbar-menu {
                display: none;
            }

            .mobile-toggle {
                display: block;
            }

            .hero-content {
                padding: 8rem 0 4rem;
            }

            .scroll-container {
                min-height: auto;
                padding: 4rem 1rem;
            }

            .scroll-card-image {
                aspect-ratio: 4 / 3;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .stat-item::after {
                display: none !important;
            }

            .stat-item {
                padding: 1.5rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .stat-item:last-child {
                border-bottom: none;
            }

            .stat-number {
                font-size: 3rem;
            }

            .how-grid {
                grid-template-columns: 1fr;
            }

            .step-number {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }

            .cta-card {
                padding: 3rem 2rem;
            }

            .btn {
                width: 100%;
            }
        }

        /* Mobile menu */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--background);
            z-index: 999;
            padding: 7rem 2rem 2rem;
        }

        .mobile-menu.active {
            display: block;
        }

        .mobile-menu-close {
            position: absolute;
            top: 1.75rem;
            right: 1.75rem;
            background: none;
            border: none;
            font-size: 1.75rem;
            cursor: pointer;
            color: var(--foreground);
        }

        .mobile-menu-links {
            list-style: none;
        }

        .mobile-menu-links li {
            margin-bottom: 0.5rem;
        }

        .mobile-menu-links a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 1.25rem;
            text-decoration: none;
            color: var(--foreground);
            font-size: 1.2rem;
            font-weight: 500;
            border-radius: var(--radius-lg);
            transition: background 0.3s;
        }

        .mobile-menu-links a:hover {
            background: var(--forest-100);
            color: var(--forest-700);
        }
    </style>
</head>
<body>
    <!-- ==================== NAVBAR ==================== -->
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
                <button class="mobile-toggle" onclick="openMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <button class="mobile-menu-close" onclick="closeMobileMenu()">
            <i class="fas fa-times"></i>
        </button>
        <ul class="mobile-menu-links">
            <li><a href="#" onclick="closeMobileMenu()"><i class="fas fa-home"></i> Beranda</a></li>
            <li><a href="./info-penyakit/infopenyakit.php"><i class="fas fa-book-medical"></i> Info Penyakit</a></li>
            <li><a href="./tentang/tentang.php"><i class="fas fa-info-circle"></i> Tentang</a></li>
            <li><a href="./diagnosis/input_gejala.php"><i class="fas fa-stethoscope"></i> Mulai Diagnosa</a></li>
        </ul>
    </div>

    <!-- ==================== HERO SECTION ==================== -->
    <section class="hero">
        <div class="hero-pattern"></div>
        <div class="floating-shape floating-shape-1"></div>
        <div class="floating-shape floating-shape-2"></div>
        <div class="floating-shape floating-shape-3"></div>
        
        <div class="container hero-content">
            <div class="hero-text-center">
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
                    <a href="./info-penyakit/infopenyakit.php" class="btn btn-secondary">
                        <i class="fas fa-book-open"></i> Pelajari Lebih
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== SCROLL SECTION 1 ==================== -->
    <section class="scroll-section">
        <div class="scroll-container light-bg" data-scroll-container>
            <div class="scroll-title-wrapper">
                <h2 class="scroll-title">Teknologi Cerdas untuk Pertanian</h2>
                <span class="scroll-title-large font-serif">
                    Identifikasi Penyakit dalam Hitungan Detik
                </span>
            </div>
            <div class="scroll-card-wrapper">
                <div class="scroll-card">
                    <div class="scroll-card-inner">
                        <img 
                            src="../analisa-penyakit-jagung/images/cewek-jagung.png" 
                            alt="Tanaman Jagung Sehat"
                            class="scroll-card-image"
                            draggable="false"
                        >
                    </div>
                </div>
            </div>
            <div class="scroll-float-card scroll-float-card-1">
                <div class="float-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="float-text">
                    <h6>Akurasi 95%</h6>
                    <p>Tingkat keberhasilan tinggi</p>
                </div>
            </div>
            <div class="scroll-float-card scroll-float-card-2">
                <div class="float-icon gold">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="float-text">
                    <h6>Hasil Instan</h6>
                    <p>Diagnosa dalam hitungan detik</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== SCROLL SECTION 2 ==================== -->
    <section class="scroll-section">
        <div class="scroll-container dark-bg" data-scroll-container>
            <div class="scroll-title-wrapper">
                <h2 class="scroll-title">Database Penyakit Lengkap</h2>
                <span class="scroll-title-large font-serif">
                    6+ Jenis Penyakit & 18+ Gejala Teridentifikasi
                </span>
            </div>
            <div class="scroll-card-wrapper">
                <div class="scroll-card">
                    <div class="scroll-card-inner">
                        <img 
                            src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?q=80&w=1400&h=788&auto=format&fit=crop" 
                            alt="Analisis Penyakit Tanaman"
                            class="scroll-card-image"
                            draggable="false"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== SCROLL SECTION 3 ==================== -->
    <section class="scroll-section">
        <div class="scroll-container light-bg" data-scroll-container>
            <div class="scroll-title-wrapper">
                <h2 class="scroll-title">Solusi & Rekomendasi</h2>
                <span class="scroll-title-large font-serif">
                    Panduan Penanganan yang Tepat & Efektif
                </span>
            </div>
            <div class="scroll-card-wrapper">
                <div class="scroll-card">
                    <div class="scroll-card-inner">
                        <img 
                            src="https://images.unsplash.com/photo-1574943320219-553eb213f72d?q=80&w=1400&h=788&auto=format&fit=crop" 
                            alt="Petani Merawat Tanaman"
                            class="scroll-card-image"
                            draggable="false"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== STATS SECTION ==================== -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">6+</div>
                    <div class="stat-label">Jenis Penyakit</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">18+</div>
                    <div class="stat-label">Gejala Teridentifikasi</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Tingkat Akurasi</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">40+</div>
                    <div class="stat-label">Konsultasi</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FEATURES SECTION ==================== -->
    <section class="features-section">
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
                    <div class="feature-icon style-1">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>Cepat & Akurat</h4>
                    <p>Diagnosa penyakit jagung secara cepat dengan tingkat akurasi tinggi menggunakan metode Certainty Factor yang telah teruji.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon style-2">
                        <i class="fas fa-book"></i>
                    </div>
                    <h4>Informasi Lengkap</h4>
                    <p>Dapatkan informasi detail tentang berbagai jenis penyakit jagung beserta solusi penanganan dan pencegahannya.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon style-3">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4>Mudah Diakses</h4>
                    <p>Akses sistem kapan saja dan di mana saja melalui perangkat mobile maupun desktop dengan tampilan yang responsif.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== HOW IT WORKS ==================== -->
    <section class="how-section">
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

            <div class="how-grid">
                <div class="how-step">
                    <div class="step-number">1</div>
                    <h4>Pilih Gejala</h4>
                    <p>Identifikasi dan pilih gejala yang terlihat pada tanaman jagung Anda</p>
                </div>
                <div class="how-step">
                    <div class="step-number">2</div>
                    <h4>Analisis Sistem</h4>
                    <p>Sistem akan menganalisis gejala menggunakan metode Certainty Factor</p>
                </div>
                <div class="how-step">
                    <div class="step-number">3</div>
                    <h4>Hasil Diagnosa</h4>
                    <p>Dapatkan hasil diagnosa penyakit beserta tingkat kepastiannya</p>
                </div>
                <div class="how-step">
                    <div class="step-number">4</div>
                    <h4>Solusi & Penanganan</h4>
                    <p>Terima rekomendasi penanganan dan pencegahan yang tepat</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== CTA SECTION ==================== -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-card">
                <div class="cta-content">
                    <h2 class="cta-title font-serif">Siap Melindungi Tanaman Jagung Anda?</h2>
                    <p class="cta-description">
                        Mulai diagnosa sekarang dan dapatkan rekomendasi penanganan yang tepat untuk tanaman Anda.
                    </p>
                    <a href="./diagnosis/input_gejala.php" class="btn btn-cta">
                        <i class="fas fa-arrow-right"></i> Diagnosa Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FOOTER ==================== -->
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

        // Container Scroll Animation - Similar to React ContainerScroll
        const scrollContainers = document.querySelectorAll('[data-scroll-container]');
        
        function updateScrollAnimation() {
            scrollContainers.forEach(container => {
                const rect = container.getBoundingClientRect();
                const viewportHeight = window.innerHeight;
                const containerCenter = rect.top + rect.height / 2;
                const viewportCenter = viewportHeight / 2;
                
                // Calculate progress: 0 when entering from bottom, 1 when centered, back to lower when exiting top
                const distanceFromCenter = containerCenter - viewportCenter;
                const maxDistance = viewportHeight;
                
                // Normalize: 1 = centered, 0 = far away
                let progress = 1 - Math.abs(distanceFromCenter) / maxDistance;
                progress = Math.max(0, Math.min(1, progress));
                
                // Check if container is in viewport
                const isInView = rect.top < viewportHeight && rect.bottom > 0;
                
                if (isInView) {
                    container.classList.add('in-view');
                    
                    const cardWrapper = container.querySelector('.scroll-card-wrapper');
                    const titleWrapper = container.querySelector('.scroll-title-wrapper');
                    
                    if (cardWrapper) {
                        // rotateX: 15deg when progress=0, 0deg when progress=1
                        const rotateX = 15 * (1 - progress);
                        // scale: 0.9 when progress=0, 1 when progress=1
                        const scale = 0.9 + (0.1 * progress);
                        // translateY: moves up as you scroll
                        const translateY = 50 * (1 - progress);
                        
                        cardWrapper.style.transform = `perspective(1000px) rotateX(${rotateX}deg) scale(${scale}) translateY(${translateY}px)`;
                    }
                    
                    if (titleWrapper) {
                        // Title moves up and fades as card comes into full view
                        const titleTranslateY = -30 * progress;
                        titleWrapper.style.transform = `translateY(${titleTranslateY}px)`;
                    }
                } else {
                    container.classList.remove('in-view');
                }
            });
        }

        // Initial call
        updateScrollAnimation();
        
        // Throttled scroll listener for smooth animation
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    updateScrollAnimation();
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });

        // Mobile menu functions
        function openMobileMenu() {
            document.getElementById('mobileMenu').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            document.getElementById('mobileMenu').classList.remove('active');
            document.body.style.overflow = '';
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>