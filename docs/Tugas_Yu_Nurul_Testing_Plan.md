**USER REQUIREMENT**  

**APLIKASI MEMBER KOMUNITAS (AMK)**



**DOKUMEN KEBUTUHAN PENGGUNA (USER REQUIREMENTS)**  

**Proyek:** Aplikasi Member Komunitas (AMK)



### **1\. Tujuan Sistem**



Sistem Aplikasi Member Komunitas (AMK) ini dibangun dengan tujuan untuk:



* Mengelola data keanggotaan asosiasi/komunitas secara terpusat.  

* Mengelola dan menerbitkan kartu anggota digital (menggunakan QR Code/Barcode).  

* Mengelola proses penagihan (invoice) dan konfirmasi pembayaran.  

* Menyediakan media komunikasi internal melalui fitur pertanyaan dan publikasi blog.  

* Menyediakan laporan operasional, keuangan, dan statistik aktivitas komunitas.



### **2\. Aktor Sistem**



Terdapat 5 (lima) peran (role) pengguna yang akan berinteraksi dengan sistem ini, yaitu:



1. **Member:** Anggota komunitas yang menggunakan layanan aplikasi.  

2. **Petugas:** Pengelola operasional harian, konten, dan layanan bantuan.  

3. **Bendahara:** Pengelola keuangan, tagihan, dan verifikasi pembayaran.  

4. **Ketua:** Pemimpin komunitas yang memantau laporan dan statistik.  

5. **Super Admin:** Pengelola kontrol akses dan pemantauan sistem secara menyeluruh.



### **3\. Kebutuhan Fungsional (Functional Requirements)**



**A. Member**



| Kode | Nama Kebutuhan | Deskripsi |

| ----- | ----- | ----- |

| FR-M-01 | Registrasi Akun | Member dapat membuat akun baru dengan mengisi nama, email, password, jenis kelamin, golongan darah, pendidikan terakhir, institusi, departemen, alamat, dan nomor telepon. |

| FR-M-02 | Login | Member dapat login menggunakan email dan password yang telah terdaftar. |

| FR-M-03 | Kelola Profil | Member dapat melengkapi profil serta menambahkan keahlian (expertise & proof). |

| FR-M-04 | Berlangganan Premium | Member dapat mendaftar membership premium, melihat tagihan/invoice dengan countdown, dan dapat membatalkan pesanan. |

| FR-M-05 | Mengunggah Bukti Pembayaran | Member dapat mengunggah bukti transfer sebagai konfirmasi pembayaran tagihan. |

| FR-M-06 | Lihat Status Pembayaran | Member dapat melihat status verifikasi pembayaran setelah diperiksa oleh keuangan. |

| FR-M-07 | Menerima Notifikasi Email | Member menerima notifikasi email saat invoice terbit, saat pembayaran diverifikasi, pengingat masa aktif premium (H-3), dan email mingguan ringkasan konten premium baru. |

| FR-M-08 | Generate & Unduh Kartu Anggota | Member dapat melihat nomor anggota dinamis, menampilkan QR Code/Barcode, dan mengunduh kartu anggota digital. |

| FR-M-09 | Lihat & Unduh CV Keanggotaan | Member dapat melihat dan mengunduh CV keanggotaan sebagai dokumen identitas atau profil anggota. |

| FR-M-10 | Lihat & Unduh Konten Benefit | Member dapat mengakses dan mengunduh konten benefit yang tersedia setelah keanggotaan aktif. |

| FR-M-11 | Konsultasi (Chat) | Member dapat mengirim pesan/pertanyaan melalui ruang obrolan (chat) langsung kepada petugas dan melihat balasan. |

| FR-M-12 | Hapus Akun | Member dapat mengajukan permohonan penghapusan akun dari sistem. |



**B. Petugas (Staff)**



| Kode | Nama Kebutuhan | Deskripsi |

| ----- | ----- | ----- |

| FR-P-01 | Login | Petugas dapat login ke dalam dashboard sistem. |

| FR-P-02 | Konsultasi (Chat) | Petugas dapat melihat chat/pertanyaan dari member dan membalas obrolan tersebut. |

| FR-P-03 | Kelola Blog | Petugas dapat membuat, mengedit, dan menghapus artikel blog. |

| FR-P-04 | Kelola Konten Benefit | Petugas dapat membuat, mengedit, dan menghapus Konten Benefit. |

| FR-P-05 | Kelola Profil | Petugas dapat mengubah dan memperbarui data profil akunnya sendiri. |



**C. Keuangan (Finance / Bendahara)**



| Kode | Nama Kebutuhan | Deskripsi |

| ----- | ----- | ----- |

| FR-F-01 | Login | Keuangan dapat login ke dashboard sistem. |

| FR-F-02 | Melihat Daftar Pembayaran | Keuangan dapat melihat daftar konfirmasi pembayaran dari member beserta status pembayarannya. |

| FR-F-03 | Verifikasi Pembayaran | Keuangan dapat memverifikasi bukti pembayaran dan mengubah statusnya menjadi diverifikasi atau ditolak. |

| FR-F-04 | Menerima Notifikasi Email | Keuangan menerima notifikasi email saat ada tagihan atau konfirmasi pembayaran baru dari member. |

| FR-F-05 | Kelola Profil | Keuangan dapat mengubah dan memperbarui data profil akunnya sendiri. |



**D. Ketua (Leader)**



| Kode | Nama Kebutuhan | Deskripsi |

| ----- | ----- | ----- |

| FR-K-01 | Login | Ketua dapat login ke dashboard pimpinan. |

| FR-K-02 | Statistik Dashboard | Ketua dapat memantau statistik ringkas terkait keanggotaan, keuangan, dan aktivitas sistem. |

| FR-K-03 | Detail Laporan & Export | Ketua dapat melihat rincian laporan dan mengekspor (export) datanya ke format file. |

| FR-K-04 | Kelola Profil | Ketua dapat mengubah dan memperbarui data profil akunnya sendiri. |



**E. Super Admin**



| Kode | Nama Kebutuhan | Deskripsi |

| ----- | ----- | ----- |

| FR-SA-01 | Login | Super Admin dapat login ke dashboard pengelolaan sistem. |

| FR-SA-02 | Manajemen Akun & Role | Super Admin dapat menambah, melihat, menghapus, serta mengaktifkan/menonaktifkan akun pengguna. |

| FR-SA-03 | Pengaturan Sistem & Tampilan | Super Admin dapat mengatur konfigurasi membership, rekening, identitas, kontak, serta pengaturan landing page dan kartu member. |

| FR-SA-04 | Live Web Editing | Super Admin dapat mengubah tampilan landing page secara real-time (live preview). |

| FR-SA-05 | Kelola Profil | Super Admin dapat mengubah dan memperbarui data profil akunnya sendiri. |



### **4\. Kebutuhan Non-Fungsional (Non-Functional Requirements)**



| Kode | Kategori | Deskripsi |

| ----- | ----- | ----- |

| NFR-01 | Security (Keamanan) | Kata sandi pengguna terenkripsi. Sistem menggunakan metode autentikasi session yang aman dan menerapkan Role-Based Access Control (RBAC). |

| NFR-02 | Integrasi Eksternal | Sistem terintegrasi dengan Google Sheets untuk pendataan transaksi, dan layanan SMTP untuk notifikasi email. |

| NFR-03 | Performance (Performa) | Sistem harus mampu menangani setidaknya 100 member aktif secara bersamaan dengan waktu respons maksimal 3 detik. |

| NFR-04 | Availability (Ketersediaan) | Sistem harus dapat diakses selama 24 jam sehari, 7 hari seminggu (24/7), serta memiliki mekanisme backup database secara berkala. |

| NFR-05 | Usability (Kebergunaan) | Antarmuka pengguna (UI) harus modern, mudah dipahami (user-friendly), dan bersifat responsif (mendukung tampilan mobile dan desktop). |



### 



### **5\. Batasan Sistem (System Boundaries)**



* Sistem dibangun dalam platform berbasis Web.  

* Pada rilis awal (MVP), pembayaran tidak terintegrasi langsung dengan Payment Gateway (verifikasi pembayaran dilakukan secara manual oleh Bendahara).  

* QR Code/Barcode pada kartu anggota hanya berfungsi sebagai identifikasi (visual ID), belum terintegrasi dengan perangkat pemindai akses fisik (gate/scanner fisik).








