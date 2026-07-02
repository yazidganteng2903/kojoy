CREATE DATABASE IF NOT EXISTS job_portal;
USE job_portal;

CREATE TABLE IF NOT EXISTS jobs (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    judul_posisi VARCHAR(100) NOT NULL,
    nama_perusahaan VARCHAR(100) NOT NULL,
    tipe_pekerjaan VARCHAR(50) NOT NULL,
    gaji VARCHAR(100) NOT NULL,
    deadline DATE NOT NULL,
    deskripsi TEXT NOT NULL,
    persyaratan TEXT NOT NULL,
    profil_perusahaan TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS applicants (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    job_id INT(11) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    cv VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO jobs (judul_posisi, nama_perusahaan, tipe_pekerjaan, gaji, deadline, deskripsi, persyaratan, profil_perusahaan) VALUES
('Driver Gojek', 'PT Gojek Indonesia', 'Freelance', 'Rp 2.000.000 - Rp 4.000.000', '2026-04-30',
 'Mengantarkan penumpang ke tujuan dengan aman dan nyaman.\nBekerja secara fleksibel dengan waktu yang dapat diatur sendiri.\nMendapatkan bonus berdasarkan performa dan jam kerja.\n',
 'Usia minimal 18 tahun.\nMemiliki SIM C yang masih berlaku.\nMemiliki kendaraan sendiri (motor).\nBerpenampilan rapi dan bertanggung jawab.\nJujur dan disiplin.\n',
 'Gojek adalah perusahaan teknologi terkemuka di Asia Tenggara yang menyediakan solusi transportasi, logistik, dan layanan digital lainnya. Dengan jutaan pengguna aktif, Gojek terus berinovasi untuk memberikan pengalaman terbaik.'),

('Driver Shopee', 'PT Shopee Indonesia', 'Freelance', 'Rp 5.000.000', '2026-05-05',
 'Mengantarkan pesanan dari merchant ke pelanggan tepat waktu.\nMenjaga kualitas layanan selama pengiriman.\nMelaporkan status pengiriman secara real-time.\n',
 'Usia minimal 18 tahun.\nMemiliki SIM C yang masih berlaku.\nMemiliki kendaraan sendiri.\nSopan dan ramah kepada pelanggan.\nMemiliki smartphone Android.\n',
 'Shopee adalah platform e-commerce terdepan di Asia Tenggara yang menghubungkan pembeli, penjual, dan mitra pengiriman untuk menciptakan pengalaman belanja online yang terbaik.'),

('Driver Grab', 'PT Grab Indonesia', 'Freelance', 'Rp 2.000.000 - Rp 4.000.000', '2026-05-31',
 'Memberikan layanan transportasi terbaik kepada pelanggan.\nMenjaga kebersihan dan kenyamanan kendaraan.\nMengikuti prosedur operasional Grab.\n',
 'Usia minimal 18 tahun.\nMemiliki SIM A atau C yang masih berlaku.\nKendaraan layak jalan tahun 2010 ke atas.\nBerpengalaman minimal 1 tahun.\nLulus tes psikologi dan kesehatan.\n',
 'Grab adalah perusahaan teknologi super-app terkemuka di Asia Tenggara yang menyediakan layanan transportasi, pesan-antar makanan, pembayaran digital, dan lainnya.'),
('Frontend Developer', 'PT Teknologi Maju', 'Full-time', 'Rp 8.000.000 - Rp 12.000.000', '2026-06-15',
 'Mengembangkan dan memelihara aplikasi web menggunakan HTML, CSS, JavaScript.\nBerkolaborasi dengan tim backend dan desainer UI/UX.\nMelakukan optimasi performa website.\n',
 'Pendidikan minimal S1 Teknik Informatika atau setara.\nPengalaman minimal 2 tahun sebagai Frontend Developer.\nMenguasai HTML, CSS, JavaScript, dan framework modern.\nMemahami responsive design.\n mampu bekerja dalam tim.\n',
 'PT Teknologi Maju adalah perusahaan software house yang berfokus pada pengembangan solusi digital inovatif untuk berbagai industri di Indonesia.');
