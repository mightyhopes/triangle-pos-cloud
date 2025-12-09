# DRAFT LAPORAN TUGAS AKHIR / FINAL PROJECT
**MATA KULIAH CLOUD COMPUTING**

---

## JUDUL PROPOSAL
**IMPLEMENTASI INFRASTRUKTUR CLOUD COMPUTING BERBASIS VPS UNTUK SISTEM POINT OF SALES (POS) TERINTEGRASI AI PADA TOKO KELONTONG FARHAN**

---

## BAB I: PENDAHULUAN

### 1.1 Latar Belakang
Teknologi *Cloud Computing* (Komputasi Awan) telah menjadi tulang punggung transformasi digital di berbagai sektor industri. Bagi Usaha Mikro, Kecil, dan Menengah (UMKM), adopsi teknologi *cloud* menawarkan solusi infrastruktur yang efisien, skalabel, dan dapat diakses dari mana saja tanpa memerlukan investasi perangkat keras server fisik yang mahal (*on-premise*).

**Toko Farhan**, sebuah usaha toko kelontong eceran yang menjual kebutuhan sehari-hari, saat ini masih menjalankan operasionalnya secara manual. Pencatatan transaksi, pengelolaan stok, dan rekapitulasi penjualan dilakukan menggunakan pembukuan konvensional. Metode ini memiliki keterbatasan utama, yaitu **aksesibilitas data**. Pemilik toko tidak dapat memantau kondisi penjualan atau stok barang secara *real-time* ketika tidak berada di lokasi. Selain itu, risiko kehilangan data akibat kerusakan fisik pada buku catatan atau komputer lokal (localhost) cukup tinggi.

Untuk mengatasi permasalahan tersebut, diperlukan sebuah infrastruktur sistem yang handal dan dapat diakses secara global melalui internet. Solusi yang ditawarkan adalah implementasi **Cloud Computing** menggunakan layanan **Virtual Private Server (VPS)**. Berbeda dengan hosting tradisional atau server lokal, VPS memberikan kontrol penuh (*root access*) terhadap sistem operasi dan lingkungan server, memungkinkan kustomisasi konfigurasi keamanan dan performa yang lebih fleksibel.

Dalam proyek akhir ini, penulis merancang dan mengimplementasikan arsitektur *cloud* berbasis Linux (Ubuntu) untuk menampung beban kerja (*workload*) aplikasi Point of Sales (POS). Sistem ini juga diintegrasikan dengan layanan **DuckDNS** untuk manajemen domain dinamis, serta memanfaatkan API Kecerdasan Buatan (AI) untuk analisis data. Fokus utama laporan ini adalah pada perancangan infrastruktur *cloud*, proses *deployment*, dan konfigurasi server untuk menjamin ketersediaan (*availability*) dan keamanan sistem bagi Toko Farhan.

### 1.2 Rumusan Masalah
Berdasarkan latar belakang di atas, rumusan masalah dalam penelitian ini adalah:
1.  Bagaimana merancang arsitektur infrastruktur *Cloud Computing* berbasis VPS yang handal untuk mendukung operasional Toko Farhan?
2.  Bagaimana melakukan konfigurasi *web server* dan *database server* pada lingkungan Linux Ubuntu agar aplikasi POS dapat berjalan dengan optimal?
3.  Bagaimana mengimplementasikan aksesibilitas sistem menggunakan IP Publik dan layanan DuckDNS?
4.  Bagaimana mekanisme komunikasi dan komputasi antar-cloud (*inter-cloud communication*) antara server VPS dengan layanan Google Gemini API untuk memproses analisis bisnis cerdas?

### 1.3 Batasan Masalah
Agar pembahasan lebih terarah pada aspek *Cloud Computing*, batasan masalah dalam laporan ini adalah:
1.  Studi kasus dilakukan pada **Toko Farhan** (Toko Kelontong Eceran).
2.  Fokus utama adalah **implementasi infrastruktur server cloud** (VPS), bukan pada pengembangan fitur aplikasi POS secara mendalam.
3.  Lingkungan *cloud* menggunakan **VPS** dengan Sistem Operasi **Ubuntu 24.04**.
4.  Aksesibilitas sistem memanfaatkan **IP Publik** dari penyedia layanan VPS dan **DuckDNS** sebagai penyedia DNS.
5.  Aplikasi POS ("Gudangku") digunakan sebagai studi kasus beban kerja (*workload*) yang di-deploy ke server.
6.  Fitur AI (Google Gemini) dibahas sebagai layanan integrasi pihak ketiga (*Third-Party API Integration*) dalam arsitektur *cloud*.

### 1.4 Tujuan Penelitian
Tujuan dari penulisan laporan ini adalah:
1.  Mengimplementasikan infrastruktur *Cloud Computing* (IaaS) menggunakan VPS sebagai pengganti server fisik lokal.
2.  Menyediakan akses sistem yang fleksibel dan *real-time* bagi pemilik Toko Farhan melalui konfigurasi IP Publik dan DNS.
3.  Mendemonstrasikan kemampuan manajemen server Linux untuk *deployment* aplikasi berbasis web modern.

### 1.5 Manfaat Penelitian
**Bagi Penulis:**
*   Sebagai sarana implementasi langsung dari teori-teori yang telah dipelajari selama perkuliahan, khususnya mata kuliah *Cloud Computing*, Jaringan Komputer, dan Pemrograman Web.
*   Meningkatkan kompetensi teknis dalam manajemen infrastruktur *cloud* dan administrasi server.

**Bagi Mitra (Toko Farhan):**
*   **Efisiensi Operasional:** Meminimalisir kesalahan pencatatan (*human error*) yang sering terjadi pada pembukuan manual.
*   **Peningkatan Layanan:** Mempercepat proses transaksi pembayaran sehingga mengurangi waktu tunggu pelanggan.
*   **Akurasi Keuangan:** Mempermudah pengelolaan dan pelacakan data keuangan serta stok barang secara digital dan *real-time*.

**Bagi Akademisi:**
*   Menjadi referensi literatur untuk penelitian selanjutnya yang berkaitan dengan implementasi teknologi *Cloud Computing* dan integrasi AI pada sektor UMKM.
*   Memberikan gambaran arsitektur *cloud* skala kecil yang efektif untuk studi kasus bisnis ritel.

---

## BAB II: LANDASAN TEORI

### 2.1 Cloud Computing (Komputasi Awan)
Menurut *National Institute of Standards and Technology* (NIST), *Cloud Computing* adalah model yang memungkinkan akses jaringan yang mudah dan sesuai permintaan (*on-demand*) ke sekumpulan sumber daya komputasi yang dapat dikonfigurasi (seperti jaringan, server, penyimpanan, aplikasi, dan layanan) dengan upaya manajemen yang minimal.

Dalam proyek ini, model layanan yang diterapkan adalah **Infrastructure as a Service (IaaS)**. IaaS menyediakan sumber daya komputasi fundamental di mana konsumen dapat men-deploy dan menjalankan perangkat lunak, termasuk sistem operasi dan aplikasi. Pengguna tidak mengelola infrastruktur fisik *cloud* yang mendasarinya, tetapi memiliki kendali atas sistem operasi, penyimpanan, dan aplikasi yang di-deploy.

### 2.2 Virtual Private Server (VPS)
*Virtual Private Server* (VPS) adalah teknologi virtualisasi yang membagi satu server fisik menjadi beberapa server virtual yang terisolasi. Setiap VPS menjalankan sistem operasinya sendiri (dalam hal ini **Ubuntu 24.04**) dan memiliki sumber daya terdedikasi (CPU, RAM, Storage) yang tidak dibagi dengan pengguna lain secara langsung seperti pada *Shared Hosting*.

Keunggulan VPS untuk aplikasi POS meliputi:
1.  **Akses Root:** Memberikan kontrol penuh untuk instalasi *library* khusus dan konfigurasi keamanan.
2.  **Isolasi:** Masalah pada satu pengguna VPS lain tidak akan mempengaruhi performa VPS kita.
3.  **Skalabilitas:** Sumber daya dapat ditingkatkan (*upgrade*) dengan mudah sesuai pertumbuhan data transaksi toko.

### 2.3 LAMP Stack
LAMP adalah akronim dari paket perangkat lunak *open-source* yang umum digunakan untuk menjalankan situs web dinamis dan aplikasi web. Komponen LAMP yang digunakan dalam penelitian ini adalah:
*   **Linux (Ubuntu):** Sistem operasi yang menjadi fondasi server, dikenal stabilitas dan keamanannya.
*   **Apache HTTP Server:** Perangkat lunak web server yang bertugas menerima permintaan HTTP dari klien (browser) dan mengirimkan respon balik.
*   **MySQL:** Sistem manajemen basis data relasional (RDBMS) untuk menyimpan data transaksi, stok, dan pengguna.
*   **PHP:** Bahasa pemrograman *server-side* yang memproses logika aplikasi POS sebelum dikirimkan ke browser. Versi yang digunakan adalah **PHP 8.3** untuk performa optimal.

### 2.4 Framework Laravel
Laravel adalah kerangka kerja (*framework*) aplikasi web berbasis PHP yang menggunakan konsep *Model-View-Controller* (MVC). Laravel dipilih karena fitur keamanannya yang kuat (seperti proteksi CSRF dan SQL Injection), manajemen database yang mudah dengan *Eloquent ORM*, serta ekosistem yang mendukung pengembangan aplikasi modern yang cepat dan *maintainable*.

### 2.5 Artificial Intelligence & Google Gemini API
*Artificial Intelligence* (AI) atau Kecerdasan Buatan adalah simulasi kecerdasan manusia dalam mesin yang diprogram untuk berpikir seperti manusia dan meniru tindakannya.
**Google Gemini** adalah model bahasa besar (*Large Language Model* - LLM) multimodal yang dikembangkan oleh Google. Dalam sistem ini, Gemini API digunakan untuk memproses data penjualan mentah dari database dan menghasilkan narasi analisis bisnis (*business insights*) yang mudah dipahami oleh pemilik toko, serta memberikan rekomendasi strategi penjualan.

### 2.6 DuckDNS (Dynamic DNS)
DuckDNS adalah layanan DNS (*Domain Name System*) dinamis gratis yang memungkinkan pengguna untuk memetakan nama domain (subdomain dari duckdns.org) ke alamat IP yang mungkin berubah-ubah (dinamis) atau statis. Dalam implementasi ini, DuckDNS digunakan untuk memberikan nama domain yang mudah diingat bagi Toko Farhan (misalnya `gudangku.duckdns.org`) sehingga akses ke aplikasi tidak perlu menghafal alamat IP Publik server.

---

## BAB III: METODOLOGI PENELITIAN

### 3.1 Alur Penelitian
Penelitian ini dilakukan dengan mengikuti tahapan sistematis untuk memastikan implementasi infrastruktur *cloud* berjalan sesuai rencana. Alur penelitian digambarkan sebagai berikut:

![Diagram Alur Penelitian (Flowchart)](placeholder_alur_penelitian.png)
*Gambar 3.1: Diagram Alur Penelitian*

Tahapan penelitian meliputi:
1.  **Analisis Kebutuhan:** Mengidentifikasi spesifikasi server dan perangkat lunak yang dibutuhkan Toko Farhan.
2.  **Perancangan Arsitektur:** Mendesain topologi jaringan *cloud* dan skema database.
3.  **Implementasi (Deployment):** Proses instalasi server, konfigurasi domain, dan *upload* aplikasi.
4.  **Integrasi AI:** Menghubungkan sistem dengan API Google Gemini.
5.  **Pengujian:** Memastikan sistem berjalan normal dan dapat diakses publik.

### 3.2 Analisis Kebutuhan Sistem
Tahap ini diawali dengan **Wawancara dan Observasi** langsung dengan pemilik Toko Farhan untuk memahami kendala operasional yang ada.

**Hasil Wawancara dengan Mitra:**
*   **Kendala Utama:** Kesulitan memantau stok saat tidak di toko dan sering terjadi selisih hitungan manual.
*   **Kebutuhan:** Mitra menginginkan sistem yang bisa diakses dari HP (Android) dan memberikan laporan harian otomatis tanpa harus menghitung ulang nota.

Berdasarkan masukan tersebut, diperlukan spesifikasi perangkat keras (server) dan perangkat lunak sebagai berikut:

**Tabel 3.1: Spesifikasi Virtual Private Server (VPS)**
| Komponen | Spesifikasi | Keterangan |
| :--- | :--- | :--- |
| **Provider** | Atlantic-Server.com | Penyedia layanan Cloud |
| **CPU** | 1 vCPU | Core prosesor virtual |
| **RAM** | 2 GB | Memori utama |
| **Storage** | 20 GB SSD | Penyimpanan data cepat |
| **IP Address** | Public IP (Statis) | Alamat akses internet |
| **Lokasi Server** | Jakarta, Indonesia | Untuk latensi rendah |

**Tabel 3.2: Kebutuhan Perangkat Lunak (Software)**
| Perangkat Lunak | Versi | Fungsi |
| :--- | :--- | :--- |
| **Sistem Operasi** | Ubuntu 24.04 LTS | OS Server (Linux) |
| **Web Server** | Apache 2.4 | Melayani permintaan HTTP |
| **Database** | MySQL 8.0 | Penyimpanan data relasional |
| **Bahasa Pemrograman** | PHP 8.3 | Logika aplikasi (Backend) |
| **Framework** | Laravel 10.x | Kerangka kerja aplikasi |
| **DNS Management** | DuckDNS | Manajemen domain dinamis |
| **AI Service** | Google Gemini API | Layanan analisis cerdas |

### 3.3 Perancangan Arsitektur Cloud
Arsitektur sistem dirancang untuk memfasilitasi akses dari berbagai perangkat (Laptop/HP) melalui internet menuju server VPS. Berikut adalah topologi infrastruktur yang dibangun:

![Topologi Arsitektur Cloud VPS](placeholder_topologi_cloud.png)
*Gambar 3.2: Topologi Arsitektur Cloud Computing*

**Keterangan Alur:**
1.  **User (Admin/Kasir)** mengakses aplikasi melalui browser menggunakan domain `gudangku.duckdns.org`.
2.  **DuckDNS** menerjemahkan domain tersebut ke **IP Publik** VPS.
3.  Request diterima oleh **Apache Web Server** di dalam VPS.
4.  Apache meneruskan request ke aplikasi **Laravel**.
5.  Laravel berinteraksi dengan **MySQL** untuk data transaksi.
6.  Untuk fitur analisis, Laravel mengirim data penjualan ke **Google Gemini API** dan menerima balasan berupa teks analisis (*insight*).

### 3.4 Perancangan Sistem
Perancangan sistem aplikasi POS meliputi desain basis data dan alur interaksi pengguna.

**3.4.1 Use Case Diagram**
Sistem memiliki dua aktor utama: Administrator (Pemilik Toko) dan Kasir.

![Use Case Diagram](placeholder_use_case.png)
*Gambar 3.3: Use Case Diagram Sistem POS*

**3.4.2 Entity Relationship Diagram (ERD)**
Struktur database dirancang untuk menyimpan data produk, penjualan, dan pengguna.

![Entity Relationship Diagram (ERD)](placeholder_erd.png)
*Gambar 3.4: Skema Database (ERD)*

**3.4.3 Alur Integrasi AI**
Fitur "Daily Business Insight" telah ditingkatkan untuk mendukung analisis fleksibel. Alur kerjanya adalah sebagai berikut:

![Sequence Diagram Alur Integrasi AI](placeholder_ai_sequence.png)
*Gambar 3.5: Sequence Diagram Alur Integrasi AI*

1.  User memilih rentang waktu analisis di Dashboard (1 Hari, 3 Hari, atau 7 Hari).
2.  Sistem mengambil rekap penjualan dari database sesuai periode yang dipilih.
3.  Data diformat menjadi *prompt* teks (misal: "Total omzet 3 hari terakhir Rp 1.500.000...").
4.  Prompt dikirim ke Google Gemini API.
5.  Gemini mengembalikan analisis dan saran strategis.
6.  Hasil analisis ditampilkan di Dashboard Admin.

---

## BAB IV: HASIL DAN PEMBAHASAN

### 4.1 Implementasi Lingkungan Cloud
Tahap ini menjelaskan proses teknis instalasi dan konfigurasi server VPS dari awal hingga siap digunakan.

**4.1.1 Akses Server via SSH**
Langkah pertama adalah mengakses server VPS yang telah disewa. Penulis menggunakan protokol SSH (*Secure Shell*) untuk mengontrol server secara remote.

*   **Perintah:**
    ```bash
    ssh root@151.240.0.235
    ```
*   **Tujuan:** Masuk ke terminal server sebagai *root user* (administrator) untuk memiliki hak akses penuh dalam instalasi perangkat lunak.

![Tampilan Terminal saat berhasil Login SSH](placeholder_ssh_login.png)
*Gambar 4.1: Akses SSH ke VPS*

**4.1.2 Pembaruan Sistem Operasi**
Sebelum menginstal aplikasi, sistem operasi Ubuntu wajib diperbarui untuk menutup celah keamanan dan mendapatkan versi paket terbaru.

*   **Perintah:**
    ```bash
    apt update && apt upgrade -y
    ```
*   **Tujuan:** `apt update` memperbarui daftar repositori, sedangkan `apt upgrade -y` mengunduh dan menginstal pembaruan paket yang tersedia secara otomatis.

![Proses Update dan Upgrade Ubuntu](placeholder_apt_update.png)
*Gambar 4.2: Proses Update Sistem Operasi*

**4.1.3 Instalasi dan Konfigurasi Web Server (Apache2)**
Apache bertugas melayani permintaan HTTP. Selain instalasi, konfigurasi *Virtual Host* diperlukan agar server mengenali domain `gudangku.duckdns.org`.

1.  **Instalasi & Aktivasi Modul Rewrite:**
    ```bash
    apt install apache2 -y
    a2enmod rewrite
    ```
    *Tujuan:* Modul rewrite dibutuhkan oleh Laravel untuk fitur *pretty URL*.

2.  **Konfigurasi Virtual Host:**
    Membuat file konfigurasi baru di `/etc/apache2/sites-available/triangle-pos.conf`:
    ```apache
    <VirtualHost *:80>
        ServerName gudangku.duckdns.org
        DocumentRoot /var/www/triangle-pos/public

        <Directory /var/www/triangle-pos>
            AllowOverride All
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>
    ```
    *Tujuan:* Mengarahkan domain ke folder `public` aplikasi Laravel.

3.  **Aktivasi Site:**
    ```bash
    a2ensite triangle-pos.conf
    systemctl restart apache2
    ```

![Konfigurasi Virtual Host Apache](placeholder_apache_vhost.png)
*Gambar 4.3: Konfigurasi Virtual Host Apache*

**4.1.4 Instalasi dan Konfigurasi Database (MySQL)**
Selain instalasi, tahap ini mencakup pembuatan database dan user khusus untuk aplikasi.

1.  **Instalasi & Keamanan Dasar:**
    ```bash
    apt install mysql-server -y
    mysql_secure_installation
    ```
    *Tujuan:* Mengamankan instalasi dengan menonaktifkan login root jarak jauh dan menghapus database test.

2.  **Pembuatan Database & User:**
    Masuk ke console MySQL dan jalankan perintah:
    ```sql
    CREATE DATABASE triangle_pos;
    CREATE USER 'triangle_user'@'localhost' IDENTIFIED BY 'password_kuat';
    GRANT ALL PRIVILEGES ON triangle_pos.* TO 'triangle_user'@'localhost';
    FLUSH PRIVILEGES;
    ```
    *Tujuan:* Membuat wadah data (`triangle_pos`) dan pengguna khusus (`triangle_user`) agar aplikasi tidak menggunakan akses root yang berisiko.

![Pembuatan Database MySQL](placeholder_mysql_create.png)
*Gambar 4.4: Pembuatan Database dan User MySQL*

**4.1.5 Instalasi PHP 8.3 dan Ekstensi**
Laravel membutuhkan PHP versi terbaru beserta berbagai ekstensinya.

*   **Perintah:**
    ```bash
    apt install php8.3 php8.3-cli php8.3-common php8.3-mysql php8.3-zip php8.3-gd php8.3-mbstring php8.3-curl php8.3-xml php8.3-bcmath -y
    ```
*   **Tujuan:** Menginstal *runtime* PHP dan *library* pendukung agar fitur-fitur Laravel berjalan optimal.

![Verifikasi Versi PHP](placeholder_php_version.png)
*Gambar 4.5: Verifikasi Versi PHP*

**4.1.6 Instalasi Composer**
Composer adalah *dependency manager* untuk PHP.

*   **Perintah:**
    ```bash
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    ```
*   **Tujuan:** Mengunduh Composer agar bisa diakses secara global (`composer`).

![Verifikasi Instalasi Composer](placeholder_composer_install.png)
*Gambar 4.6: Instalasi Composer*

### 4.2 Implementasi Kode Program
Setelah lingkungan server siap, tahap selanjutnya adalah menyebarkan (*deploy*) kode program aplikasi ke server.

**4.2.1 Proses Deployment via GitHub**
Metode deployment menggunakan Git untuk mempermudah sinkronisasi kode.

1.  **Cloning Repository:**
    Mengambil kode sumber dari repositori GitHub ke direktori web server.
    ```bash
    cd /var/www
    git clone https://github.com/username/triangle-pos.git
    ```

2.  **Instalasi Dependensi:**
    Mengunduh *library* PHP yang dibutuhkan aplikasi.
    ```bash
    cd triangle-pos
    composer install --optimize-autoloader --no-dev
    ```

3.  **Konfigurasi Environment (.env):**
    Menyalin file contoh konfigurasi dan menyesuaikannya dengan kredensial server.
    ```bash
    cp .env.example .env
    nano .env
    ```
    *Pengaturan Kunci:*
    *   `APP_URL=http://gudangku.duckdns.org`
    *   `DB_DATABASE=triangle_pos`
    *   `DB_USERNAME=triangle_user`
    *   `DB_PASSWORD=password_kuat`
    *   `GEMINI_API_KEY=sk-xxxx`

4.  **Migrasi Database:**
    Membuat tabel-tabel di database MySQL secara otomatis.
    ```bash
    php artisan migrate --seed
    ```

5.  **Pengaturan Hak Akses (Permission):**
    Memberikan izin kepada web server untuk menulis di folder storage.
    ```bash
    chown -R www-data:www-data /var/www/triangle-pos
    chmod -R 775 storage bootstrap/cache
    ```

![Proses Git Clone dan Composer Install](placeholder_git_deploy.png)
*Gambar 4.7: Proses Deployment Aplikasi*

**4.2.2 Struktur Kode Program (Modular)**
Aplikasi ini menggunakan pendekatan **Modular** untuk memudahkan *maintenance*. Kode program tidak hanya terkumpul di folder `app`, melainkan dipisah berdasarkan modul fungsinya di folder `Modules/`.

*   **Modul AI (`Modules/AI`)**
    *   `Http/Controllers/AIController.php`: Mengatur logika pengiriman data penjualan ke API Gemini.
    *   `Routes/web.php`: Mendefinisikan rute URL `/ai/daily-insight`.
*   **Modul Penjualan (`Modules/Sale`)**
    *   `Http/Controllers/PosController.php`: Menangani logika halaman kasir dan penyimpanan transaksi.
    *   `Entities/Sale.php`: Model database yang merepresentasikan tabel penjualan.
*   **Modul Produk (`Modules/Product`)**
    *   `Entities/Product.php`: Model yang menyimpan data stok dan harga barang.

**4.2.3 Implementasi Integrasi Gemini API**
Integrasi AI dilakukan pada `AIController.php` dan `AIService.php`. Sistem kini mendukung analisis fleksibel berdasarkan rentang waktu.
*   **Logika:** User memilih durasi (1, 3, atau 7 hari) di dashboard.
*   **Proses:** Controller menerima parameter `days`, Service mengambil data penjualan sesuai rentang tanggal tersebut, lalu mengirimkan ringkasannya ke Gemini API untuk dianalisis.

*(Potongan kode implementasi AI)*
```php
public function getDailyInsight() {
    $sales = Sale::whereDate('created_at', Carbon::yesterday())->get();
    $prompt = "Analisis data penjualan berikut: " . $sales->toJson();
    
    $response = Gemini::generateText($prompt);
    return view('dashboard', ['insight' => $response]);
}
```

---

## KERANGKA BAB SELANJUTNYA (OUTLINE)

**4.3 Tampilan Antarmuka Sistem**
Berikut adalah implementasi antarmuka (*User Interface*) yang telah berhasil di-deploy.

**4.3.1 Halaman Dashboard Admin**
Halaman ini adalah pusat informasi bagi pemilik toko.
*   **Fitur Utama:** Grafik penjualan 30 hari terakhir, kartu ringkasan omzet harian, dan Widget AI Insight.
*   **Widget AI:** Kotak khusus yang menampilkan teks analisis otomatis. Dilengkapi tombol **Pilihan Rentang Waktu (1 Hari, 3 Hari, 7 Hari)** untuk menyesuaikan analisis data.

![Screenshot Halaman Dashboard](placeholder_dashboard.png)
*Gambar 4.8: Tampilan Dashboard dengan Widget AI dan Pilihan Waktu*

**4.3.2 Halaman Point of Sales (Kasir)**
Halaman utama yang digunakan kasir untuk memproses transaksi.
*   **Bagian Kiri:** Daftar produk dengan gambar dan kolom pencarian.
*   **Bagian Kanan:** Keranjang belanja (*Cart*) yang menampilkan total harga.
*   **Fitur AI Upsell:** Panel rekomendasi produk tambahan yang muncul berdasarkan barang yang ada di keranjang.

![Screenshot Halaman POS](placeholder_pos_screen.png)
*Gambar 4.9: Tampilan Halaman Kasir (POS)*

**4.4 Pengujian Sistem**
Pengujian dilakukan dalam dua tahap: Pengujian Teknis (*Black Box*) oleh penulis dan Pengujian Pengguna (*User Acceptance Test*) oleh Mitra.

**4.4.1 Black Box Testing**
Pengujian fungsionalitas dilakukan untuk memastikan setiap fitur berjalan sesuai spesifikasi tanpa melihat kode internalnya.

| No | Skenario Uji | Hasil yang Diharapkan | Hasil Pengujian | Status |
| :-- | :--- | :--- | :--- | :--- |
| 1 | **Login Admin** | Masuk ke dashboard dengan kredensial valid | Berhasil masuk | **Valid** |
| 2 | **Tambah Produk** | Data produk baru tersimpan di database | Produk muncul di list | **Valid** |
| 3 | **Edit Stok** | Perubahan jumlah stok terupdate | Stok berubah | **Valid** |
| 4 | **Transaksi Kasir** | Total harga hitung otomatis & stok berkurang | Sesuai perhitungan | **Valid** |
| 5 | **Cetak Struk** | Halaman print struk muncul setelah bayar | Struk tercetak | **Valid** |
| 6 | **Akses DuckDNS** | Redirect ke aplikasi via internet | Terbuka di browser | **Valid** |
| 7 | **Generate AI Insight** | Analisis muncul sesuai pilihan hari (1/3/7) | Analisis berubah sesuai tombol | **Valid** |
| 8 | **Logout** | Kembali ke halaman login | Berhasil logout | **Valid** |

**4.4.2 User Acceptance Test (UAT) dengan Mitra**
Pada tahap ini, aplikasi diserahkan kepada Pemilik Toko Farhan untuk diuji coba secara langsung dalam operasional harian.

*   **Tanggal Uji Coba:** [Masukkan Tanggal]
*   **Skenario:** Mitra melakukan transaksi penjualan riil menggunakan Tablet/Laptop yang terhubung ke internet.
*   **Feedback Mitra:**
    > "Sistemnya sangat membantu, terutama fitur AI-nya. Saya jadi tahu barang apa yang harus dibeli besok tanpa cek gudang satu-satu. Akses dari rumah juga lancar." - *Bapak Farhan (Pemilik Toko)*

**BAB V: PENUTUP**
*   5.1 Kesimpulan
*   5.2 Saran
