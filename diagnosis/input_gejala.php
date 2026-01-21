<?php
include('../connection.php');
$query = "SELECT * FROM gejala";
$result = mysqli_query($connection, $query);
$total_gejala = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Gejala - Sistem Pakar Penyakit Jagung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/input_gejala.css">
</head>
<body>
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
                        <i class="fas fa-home me-1"></i> <span class="d-none d-sm-inline">Beranda</span>
                    </a>
                    <a class="nav-link" href="../info-penyakit/infopenyakit.php">
                        <i class="fas fa-book-medical me-1"></i> <span class="d-none d-sm-inline">Info</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-badge">
                <i class="fas fa-stethoscope"></i> Langkah 1 dari 2
            </div>
            <h1 class="page-title">Pilih <span>Gejala</span> Tanaman</h1>
            <p class="page-description">
                Pilih tingkat keyakinan untuk setiap gejala yang tampak pada tanaman jagung Anda
            </p>
        </div>

        <!-- Progress Section -->
        <div class="progress-section">
            <div class="progress-header">
                <span class="progress-title">
                    <i class="fas fa-tasks"></i> Progress Pengisian
                </span>
                <span class="progress-count">
                    <span id="selectedCount">0</span> / <?php echo $total_gejala; ?> gejala dipilih
                </span>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill" id="progressBar"></div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-header">
                <h2><i class="fas fa-clipboard-list me-2"></i>Daftar Gejala</h2>
                <p>Pilih tingkat keyakinan: Yakin, Ragu-ragu, Mungkin, atau Tidak</p>
            </div>

            <form action="proses_diagnosa.php" method="POST" id="diagnosisForm">
                <div class="form-body">
                    <!-- Info Box -->
                    <div class="info-box">
                        <div class="info-box-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div class="info-box-content">
                            <h4>Tips Pengisian</h4>
                            <p>Amati tanaman jagung Anda dengan seksama. Pilih "Yakin" jika gejala terlihat jelas, "Ragu-ragu" jika kurang yakin, "Mungkin" jika samar-samar terlihat, dan "Tidak" jika tidak ada gejala.</p>
                        </div>
                    </div>

                    <!-- Symptom List -->
                    <div class="symptom-list">
                        <?php 
                        $index = 1;
                        mysqli_data_seek($result, 0);
                        while ($row = mysqli_fetch_assoc($result)) { 
                        ?>
                        <div class="symptom-item" style="animation-delay: <?php echo ($index * 0.05); ?>s;">
                            <div class="symptom-left">
                                <span class="symptom-number"><?php echo $index; ?></span>
                                <label class="symptom-label" for="gejala_<?php echo $row['id_gejala']; ?>">
                                    <?php echo htmlspecialchars($row['nama_gejala']); ?>
                                </label>
                            </div>
                            <div class="select-wrapper">
                                <select 
                                    class="custom-select" 
                                    name="gejala[<?php echo $row['id_gejala']; ?>]"
                                    id="gejala_<?php echo $row['id_gejala']; ?>"
                                    data-index="<?php echo $index; ?>"
                                >
                                    <option value="0.0" selected>Tidak</option>
                                    <option value="0.2">Tidak tahu</option>
                                    <option value="0.4">Sedikit yakin</option>
                                    <option value="0.6">cukup yakin</option>
                                    <option value="0.8">yakin</option>
                                    <option value="1.0">sangat yakin</option>
                                </select>
                            </div>
                        </div>
                        <?php 
                        $index++;
                        } 
                        ?>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-submit">
                        <span>Proses Diagnosa</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Back Link -->
        <a href="../index.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Beranda
        </a>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selects = document.querySelectorAll('.custom-select');
            const totalGejala = <?php echo $total_gejala; ?>;
            const progressBar = document.getElementById('progressBar');
            const selectedCount = document.getElementById('selectedCount');

            function updateProgress() {
                let count = 0;
                selects.forEach(select => {
                    if (parseFloat(select.value) > 0) {
                        count++;
                    }
                });
                
                const percentage = (count / totalGejala) * 100;
                progressBar.style.width = percentage + '%';
                selectedCount.textContent = count;
            }

            function updateSelectStyle(select) {
                const value = parseFloat(select.value);
                const symptomItem = select.closest('.symptom-item');
                
                // Remove all value classes
                select.classList.remove('value-yakin', 'value-ragu', 'value-mungkin');
                symptomItem.classList.remove('selected', 'just-selected');
                
                // Add appropriate class based on value
                if (value === 0.8) {
                    select.classList.add('value-yakin');
                    symptomItem.classList.add('selected', 'just-selected');
                } else if (value === 0.6) {
                    select.classList.add('value-ragu');
                    symptomItem.classList.add('selected', 'just-selected');
                } else if (value === 0.4) {
                    select.classList.add('value-mungkin');
                    symptomItem.classList.add('selected', 'just-selected');
                }

                // Remove animation class after animation completes
                setTimeout(() => {
                    symptomItem.classList.remove('just-selected');
                }, 500);
            }

            selects.forEach(select => {
                select.addEventListener('change', function() {
                    updateSelectStyle(this);
                    updateProgress();
                });
            });

            // Form submission animation
            document.getElementById('diagnosisForm').addEventListener('submit', function(e) {
                const btn = this.querySelector('.btn-submit');
                btn.innerHTML = '<span>Memproses...</span> <i class="fas fa-spinner fa-spin"></i>';
                btn.disabled = true;
            });

            // Initialize progress
            updateProgress();
        });
    </script>
</body>
</html>


<!-- 

sedikit yakin
yakin
tidak tahu
tidak
sangat yakin
cukup yakin 
-->