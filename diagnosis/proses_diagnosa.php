<?php
include('../connection.php');

$gejala_user = $_POST['gejala'] ?? [];

if (!is_array($gejala_user)) {
    $gejala_user = [];
}

$query = "SELECT * FROM aturan";
$result = mysqli_query($connection, $query);

$diagnosa = [];
while ($row = mysqli_fetch_assoc($result)) {
    $id_penyakit = $row['id_penyakit'];
    $id_gejala = $row['id_gejala'];

    $query_gejala = "SELECT nilai_pakar FROM gejala WHERE id_gejala='$id_gejala'";
    $result_gejala = mysqli_query($connection, $query_gejala);
    $data_gejala = mysqli_fetch_assoc($result_gejala);

    if ($data_gejala !== null) {
        $nilai_user = $gejala_user[$id_gejala] ?? 0;
        $cf_gejala = $nilai_user * $data_gejala['nilai_pakar'];
        if (!isset($diagnosa[$id_penyakit])) {
            $diagnosa[$id_penyakit] = [];
        }
        $diagnosa[$id_penyakit][$id_gejala] = [
            'cf' => $cf_gejala,
            'nilai_user' => $nilai_user,
            'nilai_pakar' => $data_gejala['nilai_pakar']
        ];
    }
}

$hasil_diagnosa = [];
foreach ($diagnosa as $penyakit => $cf_values) {
    $cf_combine = 0;
    $cf_process = [];
    
    // Hitung jumlah gejala yang dipilih user
    $matched_count = 0;
    $total_rules = count($cf_values);
    
    foreach ($cf_values as $id_gejala => $cf_data) {
        if ($cf_data['nilai_user'] > 0) {
            $matched_count++;
        }
        $cf_new = $cf_data['cf'];
        $cf_combine = $cf_combine + ($cf_new * (1 - $cf_combine));
        $cf_process[] = "CF Baru = {$cf_combine} + ({$cf_new} * (1 - {$cf_combine}))";
    }
    
    // Hitung coverage (proporsi gejala terpenuhi)
    $coverage = $total_rules > 0 ? $matched_count / $total_rules : 0;
    
    // CF akhir = CF combine × coverage
    $cf_final = $cf_combine * $coverage;
    
    $hasil_diagnosa[$penyakit] = [
        'cf' => $cf_final,
        'cf_combine' => $cf_combine,
        'coverage' => $coverage,
        'matched_count' => $matched_count,
        'total_rules' => $total_rules,
        'gejala' => $cf_values,
        'proses' => $cf_process
    ];
}

uasort($hasil_diagnosa, function($a, $b) {
    // Prioritas 1: CF akhir tertinggi
    if (abs($b['cf'] - $a['cf']) > 0.0001) {
        return ($b['cf'] > $a['cf']) ? 1 : -1;
    }
    // Prioritas 2: Coverage tertinggi
    return ($b['coverage'] > $a['coverage']) ? 1 : -1;
});

$daftar_penyakit = [];
$penyakit_tertinggi = null;
foreach ($hasil_diagnosa as $penyakit_id => $data) {
    if ($data['cf'] > 0) {
        $query_penyakit = "SELECT nama_penyakit, solusi FROM penyakit WHERE id_penyakit='$penyakit_id'";
        $result_penyakit = mysqli_query($connection, $query_penyakit);
        $data_penyakit = mysqli_fetch_assoc($result_penyakit);

        if ($data_penyakit) {
            $penyakit_info = [
                'id' => $penyakit_id,
                'nama' => htmlspecialchars($data_penyakit['nama_penyakit']),
                'cf' => $data['cf'] * 100,
                'solusi' => htmlspecialchars($data_penyakit['solusi']),
                'gejala' => $data['gejala'],
                'proses' => $data['proses']
            ];

            $daftar_penyakit[] = $penyakit_info;
            if (!$penyakit_tertinggi) {
                $penyakit_tertinggi = $penyakit_info;
            }
        }
    }
}

// Get current date
$tanggal_diagnosa = date('d F Y, H:i');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Diagnosa - Sistem Pakar Penyakit Jagung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- html2pdf Library for PDF Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        :root {
            --primary: #16a34a;
            --primary-dark: #15803d;
            --primary-light: #22c55e;
            --secondary: #f59e0b;
            --secondary-light: #fbbf24;
            --danger: #ef4444;
            --danger-light: #fca5a5;
            --info: #3b82f6;
            --info-light: #93c5fd;
            --dark: #1a1a2e;
            --light: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 25px 50px -12px rgb(0 0 0 / 0.25);
            --radius: 16px;
            --radius-lg: 24px;
            --radius-full: 9999px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #d1fae5 100%);
            position: relative;
            overflow-x: hidden;
        }

        /* ========== ANIMATED BACKGROUND ========== */
        .bg-decoration {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .bg-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.5;
            animation: float 20s infinite ease-in-out;
        }

        .bg-circle:nth-child(1) {
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, rgba(22, 163, 74, 0.12), rgba(34, 197, 94, 0.08));
            top: -150px;
            right: -150px;
        }

        .bg-circle:nth-child(2) {
            width: 350px;
            height: 350px;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(251, 191, 36, 0.06));
            bottom: -100px;
            left: -100px;
            animation-delay: -7s;
        }

        .bg-circle:nth-child(3) {
            width: 250px;
            height: 250px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(147, 197, 253, 0.05));
            top: 40%;
            left: 5%;
            animation-delay: -14s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
            25% { transform: translate(30px, -30px) rotate(5deg) scale(1.02); }
            50% { transform: translate(-20px, 20px) rotate(-5deg) scale(0.98); }
            75% { transform: translate(-30px, -15px) rotate(3deg) scale(1.01); }
        }

        /* ========== NAVBAR ========== */
        .navbar {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: var(--shadow-md);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--primary) !important;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-brand i {
            color: var(--secondary);
        }

        .nav-link {
            font-weight: 500;
            color: var(--gray-600) !important;
            padding: 0.5rem 1rem !important;
            border-radius: var(--radius-full);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .nav-link:hover {
            color: var(--primary) !important;
            background: rgba(22, 163, 74, 0.1);
        }

        /* ========== MAIN CONTAINER ========== */
        .main-container {
            position: relative;
            z-index: 1;
            padding: 2rem 1rem 4rem;
            max-width: 1000px;
            margin: 0 auto;
        }

        /* ========== PAGE HEADER ========== */
        .page-header {
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s ease-out;
        }

        .page-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(22, 163, 74, 0.1);
            padding: 0.5rem 1.25rem;
            border-radius: var(--radius-full);
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .page-title {
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .page-title span {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-date {
            color: var(--gray-500);
            font-size: 0.95rem;
        }

        /* ========== RESULT HERO CARD ========== */
        .result-hero {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: var(--radius-lg);
            padding: 2.5rem;
            color: white;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            animation: scaleIn 0.6s ease-out 0.2s both;
        }

        .result-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: pulse-slow 4s ease-in-out infinite;
        }

        .result-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: pulse-slow 4s ease-in-out infinite 2s;
        }

        @keyframes pulse-slow {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .result-hero-content {
            position: relative;
            z-index: 2;
        }

        .result-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            animation: bounce-in 0.8s ease-out 0.5s both;
        }

        @keyframes bounce-in {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .result-disease-name {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .result-percentage {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-full);
            font-size: 1.25rem;
            font-weight: 700;
        }

        .percentage-bar {
            width: 100%;
            max-width: 300px;
            height: 12px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-full);
            margin: 1.5rem auto 0;
            overflow: hidden;
        }

        .percentage-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--secondary), var(--secondary-light));
            border-radius: var(--radius-full);
            width: 0%;
            animation: fillBar 1.5s ease-out 0.8s forwards;
        }

        @keyframes fillBar {
            to { width: var(--percentage); }
        }

        /* ========== SECTION CARDS ========== */
        .section-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.6s ease-out both;
        }

        .section-card:nth-child(1) { animation-delay: 0.3s; }
        .section-card:nth-child(2) { animation-delay: 0.4s; }
        .section-card:nth-child(3) { animation-delay: 0.5s; }
        .section-card:nth-child(4) { animation-delay: 0.6s; }
        .section-card:nth-child(5) { animation-delay: 0.7s; }

        .section-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .section-header:hover {
            background: var(--gray-100);
        }

        .section-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .section-icon.green { background: rgba(22, 163, 74, 0.1); color: var(--primary); }
        .section-icon.orange { background: rgba(245, 158, 11, 0.1); color: var(--secondary); }
        .section-icon.blue { background: rgba(59, 130, 246, 0.1); color: var(--info); }
        .section-icon.red { background: rgba(239, 68, 68, 0.1); color: var(--danger); }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .section-subtitle {
            font-size: 0.85rem;
            color: var(--gray-500);
            margin: 0;
        }

        .section-toggle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-500);
            transition: all 0.3s ease;
        }

        .section-header.collapsed .section-toggle {
            transform: rotate(-180deg);
        }

        .section-body {
            padding: 1.5rem;
            display: block;
        }

        .section-body.collapsed {
            display: none;
        }

        /* ========== SOLUTION BOX ========== */
        .solution-box {
            background: linear-gradient(135deg, rgba(22, 163, 74, 0.08), rgba(34, 197, 94, 0.04));
            border: 1px solid rgba(22, 163, 74, 0.2);
            border-radius: var(--radius);
            padding: 1.5rem;
        }

        .solution-box h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .solution-box p {
            color: var(--gray-600);
            line-height: 1.7;
            margin: 0;
        }

        /* ========== DISEASE LIST ========== */
        .disease-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .disease-item {
            background: var(--gray-100);
            border-radius: var(--radius);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateX(-20px);
            animation: slideInLeft 0.5s ease-out forwards;
        }

        .disease-item:nth-child(1) { animation-delay: 0.1s; }
        .disease-item:nth-child(2) { animation-delay: 0.2s; }
        .disease-item:nth-child(3) { animation-delay: 0.3s; }
        .disease-item:nth-child(4) { animation-delay: 0.4s; }
        .disease-item:nth-child(5) { animation-delay: 0.5s; }

        .disease-item:hover {
            background: white;
            box-shadow: var(--shadow-md);
            transform: translateX(5px);
        }

        .disease-item.top {
            background: linear-gradient(135deg, rgba(22, 163, 74, 0.1), rgba(34, 197, 94, 0.05));
            border: 2px solid var(--primary);
        }

        .disease-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .disease-rank {
            width: 32px;
            height: 32px;
            background: var(--gray-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--gray-600);
        }

        .disease-item.top .disease-rank {
            background: var(--primary);
            color: white;
        }

        .disease-name {
            font-weight: 600;
            color: var(--dark);
        }

        .disease-cf {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }

        /* ========== SYMPTOM ITEMS ========== */
        .symptom-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .symptom-card {
            background: var(--gray-100);
            border-radius: var(--radius);
            padding: 1.25rem;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .symptom-card:hover {
            background: white;
            box-shadow: var(--shadow-md);
            transform: translateY(-3px);
        }

        .symptom-card.selected {
            border-left: 4px solid var(--primary);
        }

        .symptom-card.unselected {
            border-left: 4px solid var(--gray-300);
            opacity: 0.8;
        }

        .symptom-name {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .symptom-values {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .symptom-tag {
            padding: 0.35rem 0.75rem;
            border-radius: var(--radius-full);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .symptom-tag.user {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .symptom-tag.expert {
            background: rgba(245, 158, 11, 0.1);
            color: #b45309;
        }

        .symptom-tag.cf {
            background: rgba(22, 163, 74, 0.1);
            color: var(--primary-dark);
        }

        /* ========== CALCULATION STEPS ========== */
        .calc-steps {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .calc-step {
            background: var(--gray-100);
            border-radius: var(--radius);
            padding: 1.25rem;
            position: relative;
            opacity: 0;
            transform: translateX(-20px);
            animation: slideInLeft 0.5s ease-out forwards;
        }

        .calc-step::before {
            content: '';
            position: absolute;
            left: 1.25rem;
            top: 100%;
            width: 2px;
            height: 1rem;
            background: var(--gray-300);
        }

        .calc-step:last-child::before {
            display: none;
        }

        .calc-step-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0.75rem;
        }

        .calc-step-number {
            width: 28px;
            height: 28px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .calc-step-title {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.95rem;
        }

        .calc-formula {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: var(--gray-600);
            overflow-x: auto;
        }

        .calc-result {
            margin-top: 0.75rem;
            padding: 0.75rem 1rem;
            background: rgba(22, 163, 74, 0.1);
            border-radius: 8px;
            font-weight: 700;
            color: var(--primary-dark);
        }

        /* ========== FINAL RESULT BOX ========== */
        .final-result {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: var(--radius);
            padding: 1.5rem;
            text-align: center;
            color: white;
            margin-top: 1.5rem;
        }

        .final-result h4 {
            font-size: 1rem;
            font-weight: 600;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .final-result .value {
            font-size: 2.5rem;
            font-weight: 800;
        }

        /* ========== ACTION BUTTONS ========== */
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2rem;
            animation: fadeInUp 0.6s ease-out 0.8s both;
        }

        .btn-action {
            flex: 1;
            min-width: 150px;
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 15px rgba(22, 163, 74, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(22, 163, 74, 0.4);
            color: white;
        }

        .btn-secondary {
            background: white;
            color: var(--gray-600);
            border: 2px solid var(--gray-200);
        }

        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-3px);
        }

        .btn-print {
            background: linear-gradient(135deg, var(--info), #2563eb);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-print:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
            color: white;
        }

        /* ========== NO RESULT STATE ========== */
        .no-result {
            text-align: center;
            padding: 4rem 2rem;
            animation: fadeInUp 0.6s ease-out;
        }

        .no-result-icon {
            width: 120px;
            height: 120px;
            background: var(--gray-100);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 3rem;
            color: var(--gray-400);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .no-result h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.75rem;
        }

        .no-result p {
            color: var(--gray-500);
            max-width: 400px;
            margin: 0 auto;
        }

        /* ========== ANIMATIONS ========== */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* ========== PRINT STYLES ========== */
        @media print {
            .bg-decoration, .navbar, .action-buttons, .section-toggle {
                display: none !important;
            }

            body {
                background: white !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .main-container {
                padding: 0;
                max-width: 100%;
            }

            .section-card {
                break-inside: avoid;
                box-shadow: none;
                border: 1px solid var(--gray-200);
            }

            .section-body.collapsed {
                display: block !important;
            }

            .result-hero {
                background: var(--primary) !important;
            }
        }

        /* ========== PDF EXPORT SPECIFIC ========== */
        .pdf-header {
            display: none;
            text-align: center;
            padding: 1.5rem;
            border-bottom: 2px solid var(--primary);
            margin-bottom: 1.5rem;
        }

        .pdf-header h1 {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }

        .pdf-header p {
            color: var(--gray-500);
            font-size: 0.9rem;
        }

        .generating-pdf .pdf-header {
            display: block;
        }

        .generating-pdf .bg-decoration,
        .generating-pdf .navbar,
        .generating-pdf .action-buttons {
            display: none !important;
        }

        .generating-pdf .section-body.collapsed {
            display: block !important;
        }

        .generating-pdf * {
            animation: none !important;
            opacity: 1 !important;
            transform: none !important;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .main-container {
                padding: 1.5rem 1rem 3rem;
            }

            .result-hero {
                padding: 2rem 1.5rem;
            }

            .result-disease-name {
                font-size: 1.35rem;
            }

            .section-header {
                padding: 1rem 1.25rem;
            }

            .section-body {
                padding: 1.25rem;
            }

            .symptom-grid {
                grid-template-columns: 1fr;
            }

            .disease-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .disease-cf {
                align-self: flex-end;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.5rem;
            }

            .result-icon {
                width: 60px;
                height: 60px;
                font-size: 1.75rem;
            }

            .section-title {
                font-size: 1rem;
            }

            .calc-formula {
                font-size: 0.75rem;
            }
        }

        /* ========== LOADING OVERLAY ========== */
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.95);
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1.5rem;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid var(--gray-200);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-text {
            font-weight: 600;
            color: var(--gray-600);
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Generating PDF...</div>
    </div>

    <!-- Animated Background -->
    <div class="bg-decoration">
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a class="navbar-brand" href="../index.php">
                    <i class="fas fa-seedling"></i> Diagnosa Jagung
                </a>
                <div class="d-flex gap-2">
                    <a class="nav-link" href="../index.php">
                        <i class="fas fa-home"></i> <span class="d-none d-sm-inline">Beranda</span>
                    </a>
                    <a class="nav-link" href="../info-penyakit/infopenyakit.php">
                        <i class="fas fa-book-medical"></i> <span class="d-none d-sm-inline">Info</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-container" id="printArea">
        <!-- PDF Header (Hidden by default) -->
        <div class="pdf-header">
            <h1><i class="fas fa-seedling"></i> Sistem Pakar Diagnosa Penyakit Jagung</h1>
            <p>Laporan Hasil Diagnosa - <?php echo $tanggal_diagnosa; ?></p>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-badge">
                <i class="fas fa-clipboard-check"></i> Hasil Analisis
            </div>
            <h1 class="page-title">Hasil <span>Diagnosa</span></h1>
            <p class="page-date"><i class="far fa-calendar-alt me-1"></i> <?php echo $tanggal_diagnosa; ?></p>
        </div>

        <?php if ($penyakit_tertinggi) { ?>
            <!-- Result Hero Card -->
            <div class="result-hero">
                <div class="result-hero-content">
                    <div class="result-icon">
                        <i class="fas fa-virus"></i>
                    </div>
                    <h2 class="result-disease-name"><?php echo $penyakit_tertinggi['nama']; ?></h2>
                    <div class="result-percentage">
                        <i class="fas fa-chart-pie"></i>
                        <span>Tingkat Keyakinan: <?php echo number_format($penyakit_tertinggi['cf'], 2); ?>%</span>
                    </div>
                    <div class="percentage-bar">
                        <div class="percentage-fill" style="--percentage: <?php echo min($penyakit_tertinggi['cf'], 100); ?>%"></div>
                    </div>
                </div>
            </div>

            <!-- Solution Section -->
            <div class="section-card">
                <div class="section-header" onclick="toggleSection(this)">
                    <div class="section-header-left">
                        <div class="section-icon green">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Solusi & Penanganan</h3>
                            <p class="section-subtitle">Rekomendasi untuk mengatasi penyakit</p>
                        </div>
                    </div>
                    <div class="section-toggle">
                        <i class="fas fa-chevron-up"></i>
                    </div>
                </div>
                <div class="section-body">
                    <div class="solution-box">
                        <h4><i class="fas fa-check-circle"></i> Rekomendasi Penanganan</h4>
                        <p><?php echo $penyakit_tertinggi['solusi']; ?></p>
                    </div>
                </div>
            </div>

            <!-- All Related Diseases -->
            <div class="section-card">
                <div class="section-header" onclick="toggleSection(this)">
                    <div class="section-header-left">
                        <div class="section-icon orange">
                            <i class="fas fa-list-ol"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Semua Penyakit Terkait</h3>
                            <p class="section-subtitle"><?php echo count($daftar_penyakit); ?> penyakit terdeteksi</p>
                        </div>
                    </div>
                    <div class="section-toggle">
                        <i class="fas fa-chevron-up"></i>
                    </div>
                </div>
                <div class="section-body">
                    <div class="disease-list">
                        <?php 
                        $rank = 1;
                        foreach ($daftar_penyakit as $penyakit) { 
                        ?>
                            <div class="disease-item <?php echo $rank === 1 ? 'top' : ''; ?>">
                                <div class="disease-info">
                                    <span class="disease-rank"><?php echo $rank; ?></span>
                                    <span class="disease-name"><?php echo $penyakit['nama']; ?></span>
                                </div>
                                <span class="disease-cf"><?php echo number_format($penyakit['cf'], 2); ?>%</span>
                            </div>
                        <?php 
                        $rank++;
                        } 
                        ?>
                    </div>
                </div>
            </div>

            <!-- Selected Symptoms -->
            <div class="section-card">
                <div class="section-header" onclick="toggleSection(this)">
                    <div class="section-header-left">
                        <div class="section-icon blue">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Gejala yang Dipilih</h3>
                            <p class="section-subtitle">Gejala yang Anda laporkan</p>
                        </div>
                    </div>
                    <div class="section-toggle">
                        <i class="fas fa-chevron-up"></i>
                    </div>
                </div>
                <div class="section-body">
                    <div class="symptom-grid">
                        <?php 
                        $delay = 0;
                        foreach ($penyakit_tertinggi['gejala'] as $id_gejala => $data) { 
                            if ($data['nilai_user'] > 0) { 
                                $query_nama_gejala = "SELECT nama_gejala FROM gejala WHERE id_gejala='$id_gejala'";
                                $result_nama_gejala = mysqli_query($connection, $query_nama_gejala);
                                $nama_gejala = mysqli_fetch_assoc($result_nama_gejala)['nama_gejala'] ?? 'Gejala Tidak Ditemukan';
                        ?>
                            <div class="symptom-card selected" style="animation-delay: <?php echo $delay * 0.1; ?>s">
                                <div class="symptom-name"><?php echo htmlspecialchars($nama_gejala); ?></div>
                                <div class="symptom-values">
                                    <span class="symptom-tag user">User: <?php echo $data['nilai_user']; ?></span>
                                    <span class="symptom-tag expert">Pakar: <?php echo $data['nilai_pakar']; ?></span>
                                    <span class="symptom-tag cf">CF: <?php echo number_format($data['cf'], 2); ?></span>
                                </div>
                            </div>
                        <?php 
                                $delay++;
                            } 
                        } 
                        ?>
                    </div>
                </div>
            </div>

            <!-- Unselected Related Symptoms -->
            <?php
            $ada_gejala_tidak_dipilih = false;
            foreach ($penyakit_tertinggi['gejala'] as $id_gejala => $data) {
                if ($data['nilai_user'] == 0) {
                    $ada_gejala_tidak_dipilih = true;
                    break;
                }
            }
            
            if ($ada_gejala_tidak_dipilih) {
            ?>
            <div class="section-card">
                <div class="section-header collapsed" onclick="toggleSection(this)">
                    <div class="section-header-left">
                        <div class="section-icon red">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Gejala Terkait (Tidak Dipilih)</h3>
                            <p class="section-subtitle">Gejala lain yang berhubungan dengan penyakit</p>
                        </div>
                    </div>
                    <div class="section-toggle">
                        <i class="fas fa-chevron-up"></i>
                    </div>
                </div>
                <div class="section-body collapsed">
                    <div class="symptom-grid">
                        <?php 
                        foreach ($penyakit_tertinggi['gejala'] as $id_gejala => $data) {
                            if ($data['nilai_user'] == 0) {
                                $query_nama_gejala = "SELECT nama_gejala FROM gejala WHERE id_gejala='$id_gejala'";
                                $result_nama_gejala = mysqli_query($connection, $query_nama_gejala);
                                $nama_gejala = mysqli_fetch_assoc($result_nama_gejala)['nama_gejala'] ?? 'Gejala Tidak Ditemukan';
                        ?>
                            <div class="symptom-card unselected">
                                <div class="symptom-name"><?php echo htmlspecialchars($nama_gejala); ?></div>
                                <div class="symptom-values">
                                    <span class="symptom-tag expert">Nilai Pakar: <?php echo $data['nilai_pakar']; ?></span>
                                </div>
                            </div>
                        <?php 
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!-- Calculation Process -->
            <div class="section-card">
                <div class="section-header collapsed" onclick="toggleSection(this)">
                    <div class="section-header-left">
                        <div class="section-icon green">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Proses Perhitungan CF</h3>
                            <p class="section-subtitle">Detail perhitungan Certainty Factor</p>
                        </div>
                    </div>
                    <div class="section-toggle">
                        <i class="fas fa-chevron-up"></i>
                    </div>
                </div>
                <div class="section-body collapsed">
                    <div class="calc-steps">
                        <?php
                        $cf_combine = 0;
                        $counter = 0;
                        foreach ($penyakit_tertinggi['gejala'] as $id_gejala => $data) {
                            if ($data['nilai_user'] > 0) {
                                $counter++;
                                $cf_old = $cf_combine;
                                $cf_new = $data['cf'];
                                $cf_combine = $cf_combine + ($cf_new * (1 - $cf_combine));
                                
                                $query_nama_gejala = "SELECT nama_gejala FROM gejala WHERE id_gejala='$id_gejala'";
                                $result_nama_gejala = mysqli_query($connection, $query_nama_gejala);
                                $nama_gejala = mysqli_fetch_assoc($result_nama_gejala)['nama_gejala'] ?? 'Gejala Tidak Ditemukan';
                        ?>
                            <div class="calc-step" style="animation-delay: <?php echo $counter * 0.15; ?>s">
                                <div class="calc-step-header">
                                    <span class="calc-step-number"><?php echo $counter; ?></span>
                                    <span class="calc-step-title"><?php echo htmlspecialchars($nama_gejala); ?></span>
                                </div>
                                <div class="calc-formula">
                                    <strong>CF<sub>combine</sub> = CF<sub>old</sub> + CF<sub>gejala</sub> × (1 - CF<sub>old</sub>)</strong><br><br>
                                    <strong>CF<sub>combine <?php echo $counter; ?></sub> = CF<sub>old <?php echo $counter - 1; ?></sub> + CF<sub>gejala <?php echo $counter; ?></sub> × (1 - CF<sub>old <?php echo $counter - 1; ?></sub>)</strong><br>
                                    = <?php echo number_format($cf_old, 4); ?> + <?php echo number_format($cf_new, 4); ?> × (1 - <?php echo number_format($cf_old, 4); ?>)<br>
                                    = <?php echo number_format($cf_old, 4); ?> + <?php echo number_format($cf_new, 4); ?> × <?php echo number_format(1 - $cf_old, 4); ?><br>
                                    = <?php echo number_format($cf_old, 4); ?> + <?php echo number_format($cf_new * (1 - $cf_old), 4); ?><br><br>
                                    <strong style="color: var(--primary);">CF<sub>old <?php echo $counter; ?></sub> = <?php echo number_format($cf_combine, 4); ?></strong>
                                </div>
                                        <div class="calc-result">
                                            <i class="fas fa-arrow-right me-2"></i>Hasil: <?php echo number_format($cf_combine, 4); ?> (<?php echo number_format($cf_combine * 100, 2); ?>%)
                                        </div>
                            </div>
                        <?php
                            }
                        }
                        ?>
                    </div>

                    <?php
                    $matched = 0;
                    $total = count($penyakit_tertinggi['gejala']);
                    foreach ($penyakit_tertinggi['gejala'] as $g) {
                        if ($g['nilai_user'] > 0) $matched++;
                    }
                    $coverage = $total > 0 ? $matched / $total : 0;
                    $cf_akhir = $cf_combine * $coverage;
                    ?>
                    <div class="final-result">
                        <h4>Hasil Akhir CF</h4>
                        <div class="value"><?php echo number_format($cf_akhir * 100, 5); ?>%</div>
                        <small style="opacity: 0.8; display: block; margin-top: 0.5rem;">
                            CF Combine: <?php echo number_format($cf_combine * 100, 2); ?>% × 
                            Coverage: <?php echo $matched; ?>/<?php echo $total; ?> (<?php echo number_format($coverage * 100, 0); ?>%)
                        </small>
                    </div>
                </div>
            </div>

        <?php } else { ?>
            <!-- No Result State -->
            <div class="section-card">
                <div class="no-result">
                    <div class="no-result-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h2>Tidak Ada Penyakit Terdeteksi</h2>
                    <p>Berdasarkan gejala yang Anda pilih, tidak ditemukan penyakit yang cocok. Silakan pilih gejala yang lebih spesifik atau konsultasikan dengan ahli.</p>
                </div>
            </div>
        <?php } ?>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button onclick="exportToPDF()" class="btn-action btn-print">
                <i class="fas fa-file-pdf"></i> Download PDF
            </button>
            <a href="input_gejala.php" class="btn-action btn-primary">
                <i class="fas fa-redo"></i> Diagnosa Ulang
            </a>
            <a href="../index.php" class="btn-action btn-secondary">
                <i class="fas fa-home"></i> Beranda
            </a>
        </div>
    </main>

    <script>
        // Toggle Section
        function toggleSection(header) {
            header.classList.toggle('collapsed');
            const body = header.nextElementSibling;
            body.classList.toggle('collapsed');
        }

        // Export to PDF
        function exportToPDF() {
            const element = document.getElementById('printArea');
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            // Show loading
            loadingOverlay.classList.add('active');
            
            // Add generating-pdf class
            document.body.classList.add('generating-pdf');
            
            // Expand all sections
            document.querySelectorAll('.section-body.collapsed').forEach(body => {
                body.classList.remove('collapsed');
            });
            document.querySelectorAll('.section-header.collapsed').forEach(header => {
                header.classList.remove('collapsed');
            });

            const opt = {
                margin: [10, 10, 10, 10],
                filename: 'Hasil_Diagnosa_Jagung_<?php echo date("Y-m-d_H-i"); ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { 
                    scale: 2,
                    useCORS: true,
                    letterRendering: true
                },
                jsPDF: { 
                    unit: 'mm', 
                    format: 'a4', 
                    orientation: 'portrait' 
                },
                pagebreak: { mode: 'avoid-all' }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                // Hide loading and remove class
                loadingOverlay.classList.remove('active');
                document.body.classList.remove('generating-pdf');
            });
        }

        // Animate elements on scroll
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.section-card, .disease-item, .symptom-card, .calc-step').forEach(el => {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });

        // Number counter animation for percentage
        function animateValue(element, start, end, duration) {
            const range = end - start;
            const increment = end > start ? 1 : -1;
            const stepTime = Math.abs(Math.floor(duration / range));
            let current = start;
            
            const timer = setInterval(() => {
                current += increment;
                element.textContent = current.toFixed(2) + '%';
                if (current >= end) {
                    clearInterval(timer);
                    element.textContent = end.toFixed(2) + '%';
                }
            }, stepTime);
        }
    </script>
</body>
</html>