<?php
include('../connection.php');
$query = mysqli_query($connection, "SELECT * FROM penyakit");
$hasil = mysqli_fetch_all($query, MYSQLI_ASSOC);
$total_penyakit = count($hasil);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Penyakit - Sistem Pakar Penyakit Jagung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/info-penyakit.css">
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-decoration">
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-seedling"></i> Diagnosa Jagung
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="infopenyakit.php">
                            <i class="fas fa-book-medical me-1"></i> Info Penyakit
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../diagnosis/input_gejala.php">
                            <i class="fas fa-stethoscope me-1"></i> Diagnosa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../tentang/tentang.php">
                            <i class="fas fa-info-circle me-1"></i> Tentang
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-badge">
                <i class="fas fa-book-medical"></i> Database Penyakit
            </div>
            <h1 class="page-title">Informasi <span>Penyakit</span> Jagung</h1>
            <p class="page-description">
                Pelajari berbagai jenis penyakit yang dapat menyerang tanaman jagung beserta deskripsi dan cara penanganannya
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-virus"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $total_penyakit; ?></h3>
                    <p>Total Penyakit</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-content">
                    <h3>18+</h3>
                    <p>Gejala Terdaftar</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $total_penyakit; ?></h3>
                    <p>Solusi Tersedia</p>
                </div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <div class="search-wrapper">
                <input type="text" class="search-input" id="searchInput" placeholder="Cari nama penyakit...">
                <i class="fas fa-search"></i>
            </div>
        </div>

        <!-- Disease Cards Grid -->
        <div class="disease-grid" id="diseaseGrid">
            <?php foreach($hasil as $index => $data_penyakit): ?>
                <div class="disease-card" data-name="<?php echo strtolower($data_penyakit['nama_penyakit']); ?>">
                    <div class="disease-card-header">
                        <div class="disease-code">
                            <i class="fas fa-tag"></i>
                            <?php echo $data_penyakit['id_penyakit']; ?>
                        </div>
                        <h3 class="disease-name"><?php echo $data_penyakit['nama_penyakit']; ?></h3>
                    </div>
                    <div class="disease-card-body">
                        <p class="disease-description"><?php echo $data_penyakit['deskripsi']; ?></p>
                        <div class="disease-card-footer">
                            <span class="disease-number">
                                <i class="fas fa-hashtag"></i>
                                Penyakit ke-<?php echo $index + 1; ?>
                            </span>
                            <button class="btn-detail" onclick="openModal('<?php echo $data_penyakit['id_penyakit']; ?>', '<?php echo addslashes($data_penyakit['nama_penyakit']); ?>', '<?php echo addslashes($data_penyakit['deskripsi']); ?>', '<?php echo addslashes($data_penyakit['solusi'] ?? 'Belum ada solusi yang terdaftar.'); ?>')">
                                Lihat Detail <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty State (Hidden by default) -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <div class="empty-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3>Penyakit Tidak Ditemukan</h3>
            <p>Coba gunakan kata kunci lain untuk mencari penyakit</p>
        </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <div class="cta-content">
                <h2 class="cta-title">Tanaman Jagung Anda Terlihat Sakit?</h2>
                <p class="cta-description">
                    Gunakan sistem diagnosa kami untuk mendeteksi penyakit berdasarkan gejala yang muncul
                </p>
                <a href="../diagnosis/input_gejala.php" class="cta-btn">
                    Mulai Diagnosa <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </main>

    <!-- Modal Detail -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-code" id="modalCode">
                    <i class="fas fa-tag"></i>
                    <span></span>
                </div>
                <h2 class="modal-title" id="modalTitle"></h2>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h4 class="modal-section-title">
                        <i class="fas fa-file-alt"></i> Deskripsi Penyakit
                    </h4>
                    <div class="modal-section-content" id="modalDescription"></div>
                </div>
                <div class="modal-section">
                    <h4 class="modal-section-title">
                        <i class="fas fa-lightbulb"></i> Solusi & Penanganan
                    </h4>
                    <div class="modal-section-content" id="modalSolution"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-modal-secondary" onclick="closeModal()">
                    <i class="fas fa-times"></i> Tutup
                </button>
                <a href="../diagnosis/input_gejala.php" class="btn-modal btn-modal-primary">
                    <i class="fas fa-stethoscope"></i> Diagnosa Sekarang
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const diseaseGrid = document.getElementById('diseaseGrid');
        const diseaseCards = document.querySelectorAll('.disease-card');
        const emptyState = document.getElementById('emptyState');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            diseaseCards.forEach(card => {
                const name = card.dataset.name;
                if (name.includes(searchTerm)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide empty state
            if (visibleCount === 0) {
                emptyState.style.display = 'block';
                diseaseGrid.style.display = 'none';
            } else {
                emptyState.style.display = 'none';
                diseaseGrid.style.display = 'grid';
            }
        });

        // Modal functionality
        const modalOverlay = document.getElementById('modalOverlay');
        const modalCode = document.getElementById('modalCode').querySelector('span');
        const modalTitle = document.getElementById('modalTitle');
        const modalDescription = document.getElementById('modalDescription');
        const modalSolution = document.getElementById('modalSolution');

        function openModal(code, name, description, solution) {
            modalCode.textContent = code;
            modalTitle.textContent = name;
            modalDescription.textContent = description;
            modalSolution.textContent = solution;
            
            modalOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(event) {
            if (event && event.target !== modalOverlay) return;
            
            modalOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
                closeModal();
            }
        });

        // Animate cards on scroll
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

        document.querySelectorAll('.disease-card, .stat-card').forEach(el => {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
    </script>
</body>
</html>