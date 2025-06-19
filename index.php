<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@200,300,400,500,600,700&display=swap"
        rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="output.css" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/logos/favicon.png" />

    <title>SILADUMA</title>
</head>

<body>
    <div id="main" class="w-full h-full min-h-screen overflow-hidden mx-auto">

        <!-- Navigation Bar -->
        <nav id="navbar" class="fixed top-0 w-full backdrop-blur-md z-50">
            <div class="max-w-[1280px] mx-auto w-full flex justify-between items-center px-[75px] py-[10px]">
                <div style="width: 50px !important; height: 50px !important"
                    class="shrink-0 overflow-hidden flex items-center justify-center">
                    <img src="assets/images/logos/logodinasbjm.png" style="
                width: 50px !important;
                height: 50px !important;
                max-width: 50px !important;
                max-height: 50px !important;" class="object-contain" alt="logo" />
                </div>
                <ul class="flex items-center gap-[30px]">
                    <li>
                        <a href="#" class="font-medium">Home</a>
                    </li>
                    <li>
                        <a href="#" class="font-medium">Tentang Kami</a>
                    </li>
                    <li>
                        <a href="#" class="font-medium flex gap-1">
                            Layanan
                            <img src="assets/images/icons/arrow-down.svg" width="18" height="18" alt="icon" />
                        </a>
                    </li>
                    <li>
                        <a href="#" class="font-medium">Berita</a>
                    </li>
                    <li>
                        <a href="#" class="font-medium">Kontak</a>
                    </li>
                </ul> <!-- Tombol Login -->
                <div class="flex gap-[10px] items-center">
                    <a href="#" onclick="showLoginModal(); return false;" id="openLoginModal"
                        class="bg-gradient-green rounded-full px-[30px] py-[10px] text-white font-semibold">
                        Login
                    </a>
                </div>
            </div>
        </nav>

        <div class="flex flex-col gap-[100px] pt-[80px]">

            <!-- Hero Section -->
            <section id="hero-section" class="max-w-[1280px] w-full overflow-hidden mx-auto">
                <div class="px-[75px] pt-[80px] flex justify-between items-center">
                    <div class="flex flex-col gap-[40px]">
                        <div class="!w-fit bg-white rounded-full px-4 py-2">
                            <div class="flex gap-[6px] items-center">
                                <img src="assets/images/icons/global.svg" class="w-5 h-5" alt="icons" />
                                <p class="font-semibold">SILADUMA - DKP3 Banjarmasin</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-[2px]">
                            <h1 class="efekwavelineteks font-clash-display text-[70px] leading-[100%] font-semibold min-h-[100px] mb-1">
                                <span class="line">Dari Rumah</span><br />
                                <span class="line"><span class="text-gradient-green">Semua Mudah</span></span>
                            </h1>
                            <p class="font-medium text-weserve-grey">
                                Sistem Informasi Terpadu kami mempermudah perizinan,
                                bantuan,<br />dan pengaduan masyarakat dengan cepat dan
                                Efektif.
                            </p>
                        </div>

                        <!-- JavaScript for Wave Effect -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const waveLines = document.querySelectorAll('.efekwavelineteks .line'); // Pilih semua elemen dengan kelas line

                                waveLines.forEach(line => {
                                    const originalHTML = line.innerHTML; // Ambil HTML asli dari line
                                    line.innerHTML = ''; // Kosongkan konten dalam line

                                    // Proses HTML asli dan pisahkan tag dengan teks
                                    const tempDiv = document.createElement('div');
                                    tempDiv.innerHTML = originalHTML;

                                    // Loop untuk setiap node dalam konten (termasuk teks dan elemen HTML)
                                    tempDiv.childNodes.forEach((node, index) => {
                                        if (node.nodeType === Node.TEXT_NODE) {
                                            node.textContent.split('').forEach((char, charIndex) => {
                                                const span = document.createElement('span');
                                                span.textContent = char === ' ' ? '\u00A0' : char; // Ganti spasi dengan nbsp
                                                span.style.transitionDelay = `${(index + charIndex) * 0.1}s`;
                                                line.appendChild(span);
                                            });
                                        } else if (node.nodeType === Node.ELEMENT_NODE) {
                                            const element = node.cloneNode(true);
                                            element.style.transitionDelay = `${index * 0.1}s`; // Tetap tambahkan delay untuk animasi
                                            line.appendChild(element);
                                        }
                                    });
                                });

                                function startWaveAnimation() {
                                    waveLines.forEach(line => {
                                        const spans = line.querySelectorAll('span');
                                        spans.forEach((span, index) => {
                                            // Pastikan bahwa setiap span memiliki transisi yang diterapkan
                                            span.style.transition = 'transform 2s, opacity 2s';

                                            // Gerakkan ke kiri dan atur opacity ke 0
                                            setTimeout(() => {
                                                span.style.opacity = '0';
                                                span.style.transform = 'translateX(-20px)';
                                            }, index * 100);

                                            // Reset posisi dan opacity setelah animasi selesai
                                            setTimeout(() => {
                                                span.style.opacity = '1';
                                                span.style.transform = 'translateX(0)';
                                            }, 2000 + index * 100);
                                        });
                                    });
                                }

                                // Mulai animasi dan ulangi setiap 10 detik
                                startWaveAnimation(); // Mulai animasi segera
                                setInterval(startWaveAnimation, 10000); // Ulangi setiap 10 detik
                            });
                        </script>
                        <div class="flex gap-5 items-center">
                            <a href="#" onclick="showChatModal(); return false;" id="openChatModal"
                                class="bg-gradient-green rounded-full px-10 py-4 font-bold text-[18px] text-white">Tanya
                                Chatbot</a>
                        </div>
                    </div>
                    <div class="w-[600px] h-[550px] shrink-0 overflow-hidden">
                        <img src="assets/images/thumbnails/thumbnail.png"
                            class="w-full h-full object-cover animate-fade-zoom" alt="thumbnail" />
                    </div>
                </div>
            </section>

            <!-- FAQ -->
            <section id="faq-section" class="max-w-[1280px] w-full overflow-hidden mx-auto">
                <div class="flex flex-col gap-[30px] px-[75px]">
                    <h2 class="font-clash-display font-semibold text-[46px] text-center">
                        Pertanyaan Umum tentang SILADUMA
                    </h2>
                    <div class="w-full flex gap-[30px] flex-wrap">
                        <div class="flex flex-col gap-[20px] w-full md:w-1/2">
                            <!-- FAQ 1 -->
                            <div class="bg-white rounded-[16px] p-5" x-data="{ isOpen: false }">
                                <div class="flex justify-between cursor-pointer items-center" @click="isOpen = !isOpen">
                                    <h4 class="text-[20px] font-bold">Apa itu SILADUMA?</h4>
                                    <div :class="{'bg-weserve-navy rounded-full w-[30px] h-[30px] flex items-center justify-center': isOpen}"
                                        class="transition-all duration-300">
                                        <img :src="isOpen ? 'assets/images/icons/arrow-up-navybg.svg' : 'assets/images/icons/arrow-down-whitebg.svg'"
                                            alt="icon" />
                                    </div>
                                </div>
                                <div class="flex flex-col gap-5 pt-[20px] w-full" x-show="isOpen" x-transition>
                                    <p class="text-weserve-grey font-medium leading-[32px]">
                                        SILADUMA adalah Sistem Informasi Layanan Terpadu milik
                                        DKP3 Kota Banjarmasin yang memudahkan masyarakat dalam
                                        mengurus perizinan, bantuan, hingga pengaduan secara
                                        digital dari rumah.
                                    </p>
                                </div>
                            </div>

                            <!-- FAQ 2 -->
                            <div class="bg-white rounded-[16px] p-5" x-data="{ isOpen: false }">
                                <div class="flex justify-between cursor-pointer items-center" @click="isOpen = !isOpen">
                                    <h4 class="text-[20px] font-bold">
                                        Apakah semua layanan bisa dilakukan secara online?
                                    </h4>
                                    <div :class="{'bg-weserve-navy rounded-full w-[30px] h-[30px] flex items-center justify-center': isOpen}"
                                        class="transition-all duration-300">
                                        <img :src="isOpen ? 'assets/images/icons/arrow-up-navybg.svg' : 'assets/images/icons/arrow-down-whitebg.svg'"
                                            alt="icon" />
                                    </div>
                                </div>
                                <div class="flex flex-col gap-5 pt-[20px] w-full" x-show="isOpen" x-transition>
                                    <p class="text-weserve-grey font-medium leading-[32px]">
                                        Ya, sebagian besar layanan bisa diakses online. Namun
                                        untuk beberapa proses verifikasi atau pengambilan dokumen,
                                        Anda mungkin perlu datang ke kantor DKP3.
                                    </p>
                                </div>
                            </div>

                            <!-- FAQ 3 -->
                            <div class="bg-white rounded-[16px] p-5" x-data="{ isOpen: false }">
                                <div class="flex justify-between cursor-pointer items-center" @click="isOpen = !isOpen">
                                    <h4 class="text-[20px] font-bold">
                                        Bagaimana mengakses layanan SILADUMA?
                                    </h4>
                                    <div :class="{'bg-weserve-navy rounded-full w-[30px] h-[30px] flex items-center justify-center': isOpen}"
                                        class="transition-all duration-300">
                                        <img :src="isOpen ? 'assets/images/icons/arrow-up-navybg.svg' : 'assets/images/icons/arrow-down-whitebg.svg'"
                                            alt="icon" />
                                    </div>
                                </div>
                                <div class="flex flex-col gap-5 pt-[20px] w-full" x-show="isOpen" x-transition>
                                    <p class="text-weserve-grey font-medium leading-[32px]">
                                        Anda bisa mengaksesnya melalui website resmi DKP3
                                        Banjarmasin atau klik tombol
                                        <strong>Tanya Chatbot</strong> pada halaman utama untuk
                                        bantuan otomatis.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-[20px] w-full md:w-1/2">
                            <!-- FAQ 4 -->
                            <div class="bg-white rounded-[16px] p-5" x-data="{ isOpen: false }">
                                <div class="flex justify-between cursor-pointer items-center" @click="isOpen = !isOpen">
                                    <h4 class="text-[20px] font-bold">
                                        Layanan apa saja yang tersedia di SILADUMA?
                                    </h4>
                                    <div :class="{'bg-weserve-navy rounded-full w-[30px] h-[30px] flex items-center justify-center': isOpen}"
                                        class="transition-all duration-300">
                                        <img :src="isOpen ? 'assets/images/icons/arrow-up-navybg.svg' : 'assets/images/icons/arrow-down-whitebg.svg'"
                                            alt="icon" />
                                    </div>
                                </div>
                                <div class="flex flex-col gap-5 pt-[20px] w-full" x-show="isOpen" x-transition>
                                    <p class="text-weserve-grey font-medium leading-[32px]">
                                        SILADUMA mencakup layanan perizinan usaha tani, pengajuan
                                        bantuan alat pertanian/perikanan, pengaduan lingkungan,
                                        hingga pelaporan kegiatan DKP3 lainnya.
                                    </p>
                                </div>
                            </div>

                            <!-- FAQ 5 -->
                            <div class="bg-white rounded-[16px] p-5" x-data="{ isOpen: false }">
                                <div class="flex justify-between cursor-pointer items-center" @click="isOpen = !isOpen">
                                    <h4 class="text-[20px] font-bold">
                                        Bagaimana jika saya mengalami kendala teknis?
                                    </h4>
                                    <div :class="{'bg-weserve-navy rounded-full w-[30px] h-[30px] flex items-center justify-center': isOpen}"
                                        class="transition-all duration-300">
                                        <img :src="isOpen ? 'assets/images/icons/arrow-up-navybg.svg' : 'assets/images/icons/arrow-down-whitebg.svg'"
                                            alt="icon" />
                                    </div>
                                </div>
                                <div class="flex flex-col gap-5 pt-[20px] w-full" x-show="isOpen" x-transition>
                                    <p class="text-weserve-grey font-medium leading-[32px]">
                                        Anda bisa menghubungi tim admin melalui fitur live chat,
                                        chatbot, atau kirim email langsung ke DKP3 melalui tombol
                                        di bawah ini.
                                    </p>
                                </div>
                            </div>

                            <!-- CTA -->
                            <div class="bg-weserve-green rounded-[16px] p-5">
                                <div class="flex justify-between cursor-pointer items-center">
                                    <div class="flex items-center gap-[6px]">
                                        <img src="assets/images/icons/device-message-white.svg" class="w-6 h-6"
                                            alt="icons" />
                                        <h4 class="text-[20px] font-semibold text-white">
                                            WhatsApp untuk pertanyaan lainnya
                                        </h4>
                                    </div>
                                    <a href="mailto:dkp3@banjarmasinkota.go.id">
                                        <img src="assets/images/icons/arrow-right-whitebg.svg" class="w-[30px] h-[30px]"
                                            alt="icons" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Service Provide -->
            <section id="services-provide"
                class="relative z-20 max-w-[1280px] w-full overflow-hidden mx-auto px-[75px] -mb-[250px]">
                <div class="bg-white rounded-[40px]">
                    <div class="flex gap-[60px] items-center p-[50px]">
                        <!-- Bagian Kiri -->
                        <div class="flex flex-col gap-[40px] w-1/2">
                            <div class="!w-fit bg-white rounded-full px-4 py-2">
                                <div class="flex gap-[6px] items-center">
                                    <img src="assets/images/icons/global.svg" class="w-6 h-6" alt="icons" />
                                    <p class="font-semibold">
                                        Kami sudah membantu lebih 1000+ permintaan
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col gap-[10px] text-gradient-green">
                                <h2 class="font-clash-display font-semibold text-[46px]">
                                    SILADUMA
                                </h2>
                                <p class="leading-[32px]">
                                    Memudahkan masyarakat menyampaikan laporan, memantau
                                    progres, dan mendapatkan solusi secara cepat.
                                </p>
                            </div>
                            <div class="flex gap-5 items-center">
                                <a href="#" onclick="showChatModal(); return false;" id="openChatModal"
                                    class="bg-gradient-green text-white rounded-full px-10 py-4 font-bold text-[18px]">Tanya Chatbot
                                </a>
                            </div>
                        </div>

                        <!-- Bagian Kanan -->
                        <div class="flex justify-center w-1/2">
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); grid-template-rows: repeat(2, 1fr); gap: 24px; width: 100%; max-width: 320px;">
                                <!-- Baris 1, Kolom 1: Efektif -->
                                <div class="bg-white rounded-tl-[20px] p-3 flex flex-col items-center justify-center h-full min-h-[120px]">
                                    <div class="w-[45px] h-[45px] flex items-center justify-center flex-shrink-0"> <img src="assets/images/icons/efektif.png" alt="Efektif Icon" class="w-full h-full object-contain" /> </div>
                                    <h3 class="font-bold text-[12px] text-center mt-2">Efektif</h3>
                                </div> <!-- Baris 1, Kolom 2: Mudah -->
                                <div class="bg-white rounded-tr-[20px] p-3 flex flex-col items-center justify-center h-full min-h-[120px]">
                                    <div class="w-[45px] h-[45px] flex items-center justify-center flex-shrink-0"> <img src="assets/images/icons/mudah.png" alt="Mudah Icon" class="w-full h-full object-contain" /> </div>
                                    <h3 class="font-bold text-[12px] text-center mt-2">Mudah</h3>
                                </div> <!-- Baris 2, Kolom 1: Responsif -->
                                <div class="bg-white rounded-bl-[20px] p-3 flex flex-col items-center justify-center h-full min-h-[120px]">
                                    <div class="w-[45px] h-[45px] flex items-center justify-center flex-shrink-0"> <img src="assets/images/icons/responsif.png" alt="Responsif Icon" class="w-full h-full object-contain" /> </div>
                                    <h3 class="font-bold text-[12px] text-center mt-2">Responsif</h3>
                                </div> <!-- Baris 2, Kolom 2: Solutif -->
                                <div class="bg-white rounded-br-[20px] p-3 flex flex-col items-center justify-center h-full min-h-[120px]">
                                    <div class="w-[45px] h-[45px] flex items-center justify-center flex-shrink-0"> <img src="assets/images/icons/solutif.png" alt="Solutif Icon" class="w-full h-full object-contain" /> </div>
                                    <h3 class="font-bold text-[12px] text-center mt-2">Solutif</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="relative z-10 w-full overflow-hidden mx-auto bg-gradient-green -mt-[150px] pt-[150px]">
                <div class="flex flex-col gap-[50px] px-[75px] pb-[50px] pt-[200px] max-w-[1280px] mx-auto">
                    <div class="flex gap-[100px] items-center">
                        <div class="flex flex-col gap-[30px] items-center">
                            <div style="
                    width: 75px !important;
                    height: 75px !important;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                  ">
                                <img src="assets/images/logos/logodinasbjm.png" style="
                      max-width: 100% !important;
                      max-height: 100% !important;
                      object-fit: contain;
                    " alt="logo" />
                            </div>
                            <p class="font-medium text-white leading-[28px] text-center">
                                Bantu Masyarakat Dari Rumah <br />
                                Lebih Cepat Selesai dan Mudah
                            </p>
                            <div class="flex gap-[14px] items-center">
                                <a href="#"
                                    class="bg-white rounded-full p-[15px] flex items-center justify-center zoom-in"
                                    style="width: 54px; height: 54px; box-sizing: border-box">
                                    <i class="bi bi-whatsapp"
                                        style="color: #6aac51; font-size: 24px; line-height: 1"></i>
                                </a>
                                <a href="#"
                                    class="bg-white rounded-full p-[15px] flex items-center justify-center zoom-in"
                                    style="width: 54px; height: 54px; box-sizing: border-box">
                                    <i class="bi bi-instagram"
                                        style="color: #6aac51; font-size: 24px; line-height: 1"></i>
                                </a>
                                <a href="#"
                                    class="bg-white rounded-full p-[15px] flex items-center justify-center zoom-in"
                                    style="width: 54px; height: 54px; box-sizing: border-box">
                                    <i class="bi bi-facebook"
                                        style="color: #6aac51; font-size: 24px; line-height: 1"></i>
                                </a>
                                <a href="#"
                                    class="bg-white rounded-full p-[15px] flex items-center justify-center zoom-in"
                                    style="width: 54px; height: 54px; box-sizing: border-box">
                                    <i class="bi bi-youtube"
                                        style="color: #6aac51; font-size: 24px; line-height: 1"></i>
                                </a>
                                <a href="#"
                                    class="bg-white rounded-full p-[15px] flex items-center justify-center zoom-in"
                                    style="width: 54px; height: 54px; box-sizing: border-box">
                                    <i class="bi bi-twitter-x"
                                        style="color: #6aac51; font-size: 24px; line-height: 1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-[70px] content-center justify-end">
                            <div class="flex flex-col gap-[30px]">
                                <h4 class="text-white font-bold text-[18px]">Products</h4>
                                <div class="flex flex-col gap-4">
                                    <a href="#" class="text-white font-medium">Powerful Reports</a>
                                    <a href="#" class="text-white font-medium">Blockchain</a>
                                    <a href="#" class="text-white font-medium">Auto-Backup</a>
                                    <a href="#" class="text-white font-medium">Data Science</a>
                                    <a href="#" class="text-white font-medium">Auto-Scaling Up</a>
                                </div>
                            </div>
                            <div class="flex flex-col gap-[30px]">
                                <h4 class="text-white font-bold text-[18px]">Resouces</h4>
                                <div class="flex flex-col gap-4">
                                    <a href="#" class="text-white font-medium">Support 24/7</a>
                                    <a href="#" class="text-white font-medium">Help Center</a>
                                    <a href="#" class="text-white font-medium">How-to Instructions</a>
                                    <a href="#" class="text-white font-medium">Blog & Tips</a>
                                    <a href="#" class="text-white font-medium">About Us</a>
                                </div>
                            </div>
                            <div class="flex flex-col gap-[30px]">
                                <h4 class="text-white font-bold text-[18px]">Company</h4>
                                <div class="flex flex-col gap-4">
                                    <a href="#" class="text-white font-medium">Privacy & Policy</a>
                                    <a href="#" class="text-white font-medium">Terms and Conditions</a>
                                    <a href="#" class="text-white font-medium">Investor Relations</a>
                                    <a href="#" class="text-white font-medium">Join With Us</a>
                                    <a href="#" class="text-white font-medium">Our Statistics</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-center text-white font-medium">
                        All Rights Reserved • Copyright SILADUMA by Hilda Nurfadilah 2025
                        in Banjarmasin
                    </p>
                </div>
            </footer>

        </div>

        <!-- Login Modal -->
        <div class="modal-login__overlay hidden" id="loginModal">
            <div class="modal-login__container">
                <button class="modal-login__close" type="button">×</button>
                <h2 class="modal-login__title">Selamat Datang</h2>
                <form action="login_aksi.php" method="post">
                    <div class="modal-login__form-group">
                        <label class="modal-login__form-label">Username</label>
                        <input type="text" name="username" class="modal-login__form-input" placeholder="Masukkan Username" required>
                    </div>

                    <div class="modal-login__form-group">
                        <label class="modal-login__form-label">Password</label>
                        <input type="password" name="password" class="modal-login__form-input" placeholder="Masukkan Password" required>
                    </div>

                    <div class="modal-login__form-group">
                        <label class="modal-login__form-label">Pilih Role</label>
                        <select name="level" class="modal-login__form-select" required>
                            <option value="">Pilih Role</option>
                            <option value="warga">Warga</option>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="kepala_dinas">Kepala Dinas</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-gradient-green rounded-full px-[30px] py-[10px] text-white font-semibold flex items-center justify-center w-[200px] mx-auto" style="width: 100%; max-width: 200px; padding: 10px; border: none; cursor: pointer;">
                        Masuk
                    </button>

                    <p class="text-center">
                        Belum punya akun? <a href="#" id="createAccountLink" class="text-weserve-green">Buat disini</a>
                    </p>
                </form>
            </div>
        </div>

        <!-- Modal Register -->
        <div id="registerModal" class="modal-register__overlay hidden">
            <div class="modal-register__container">
                <button id="closeRegisterModal" class="modal-register__close" type="button">×</button>
                <h2 class="modal-register__title">Daftar Akun</h2>

                <!-- Form Register -->
                <form action="register_aksi.php" method="POST" class="modal-register__form">
                    <div class="modal-register__form-group">
                        <label class="modal-register__form-label" for="reg_username">Username</label>
                        <input type="text" id="reg_username" name="username" placeholder="Masukkan Username" class="modal-register__form-input" maxlength="15" required>
                    </div>

                    <div class="modal-register__form-group">
                        <label class="modal-register__form-label" for="reg_password">Password</label>
                        <input type="password" id="reg_password" name="password" placeholder="Masukkan Password" class="modal-register__form-input" required>
                    </div>

                    <div class="modal-register__form-group">
                        <label class="modal-register__form-label" for="reg_nama">Nama Lengkap</label>
                        <input type="text" id="reg_nama" name="nama" class="modal-register__form-input" placeholder="Sesuai KTP/KK" required>
                    </div>

                    <div class="modal-register__form-group">
                        <label class="modal-register__form-label" for="reg_email">Email</label>
                        <input type="email" id="reg_email" name="email" class="modal-register__form-input" placeholder="nama@gmail.com" required>
                    </div>

                    <div class="modal-register__form-group">
                        <label class="modal-register__form-label" for="reg_tlp">No. Telepon</label>
                        <input type="text" id="reg_tlp" name="tlp" placeholder="Nomor Telepon" class="modal-register__form-input" required>
                    </div>

                    <div class="modal-register__form-group">
                        <label class="modal-register__form-label" for="reg_level">Role</label>
                        <select id="reg_level" name="level" class="modal-register__form-select" required>
                            <option value="">Pilih Role</option>
                            <option value="warga">Warga</option>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="kepala_dinas">Kepala Dinas</option>
                        </select>
                    </div>

                    <div class="modal-register__form-group full-width">
                        <label class="modal-register__form-label" for="reg_alamat">Alamat</label>
                        <textarea id="reg_alamat" name="alamat" class="modal-register__form-input" placeholder="Sesuai KTP/KK" rows="3" required></textarea>
                    </div>

                    <div id="nipField" class="modal-register__form-group full-width hidden">
                        <label class="modal-register__form-label" for="reg_nip">NIP</label>
                        <input type="text" id="reg_nip" name="nip" class="modal-register__form-input" minlength="18" maxlength="18" placeholder="18 digit NIP">
                    </div>

                    <div id="kodeField" class="modal-register__form-group full-width hidden">
                        <label class="modal-register__form-label" for="reg_kode">Kode</label>
                        <input type="text" id="reg_kode" name="kode" class="modal-register__form-input" pattern="[F-J0-9]{7,7}" placeholder="7 karakter (F-J dan 0-9)">
                    </div>

                    <div class="modal-register__btn-group">
                        <button type="submit" class="bg-gradient-green rounded-full px-[30px] py-[10px] text-white font-semibold flex items-center justify-center w-[200px] mx-auto">
                            Daftar
                        </button>
                        <button type="reset" class="bg-gradient-red rounded-full px-[30px] py-[10px] text-white font-semibold flex items-center justify-center w-[200px] mx-auto">
                            Reset
                        </button>
                    </div>

                    <div class="modal-register__footer">
                        <p>Sudah punya akun? <a href="#" id="backToLogin" class="text-weserve-green">Login disini</a></p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Chat Bot -->
        <div id="chatModal" class="modal-chat__overlay">
            <div class="modal-chat__container">
                <div class="modal-chat__header">
                    <h3 class="modal-chat__title">Chat dengan Bot SILADUMA</h3>
                    <button onclick="hideChatModal()" class="modal-chat__close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-chat__messages" id="chatMessages">
                    <div class="chat-message chat-message--bot">
                        <div class="chat-message__content">
                            Halo! Saya bot SILADUMA. Ada yang bisa saya bantu?
                        </div>
                    </div>
                </div>
                <div class="modal-chat__input-container">
                    <input type="text" class="modal-chat__input" id="chatInput"
                        placeholder="Ketik pesan Anda di sini..."
                        onkeypress="if(event.key === 'Enter') sendMessage()">
                    <button onclick="sendMessage()" class="modal-chat__send-btn">
                        <i class="bi bi-send"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil elemen modal
            const loginModal = document.getElementById('loginModal');
            const registerModal = document.getElementById('registerModal');
            const openLoginBtn = document.getElementById('openLoginModal');
            const createAccountLink = document.getElementById('createAccountLink');
            const backToLoginBtn = document.getElementById('backToLogin');
            const closeLoginBtn = document.querySelector('#loginModal .modal-login__close');
            const closeRegisterBtn = document.getElementById('closeRegisterModal');
            const regLevelSelect = document.getElementById('reg_level');
            const nipField = document.getElementById('nipField');
            const kodeField = document.getElementById('kodeField');
            const nipInput = document.getElementById('reg_nip');
            const kodeInput = document.getElementById('reg_kode');

            // Fungsi untuk mengelola modal
            const modalManager = {
                showModal: function(modal) {
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('active');
                        console.log('Showing modal:', modal.id); // Debug
                    }
                },
                hideModal: function(modal) {
                    if (modal) {
                        modal.classList.remove('active');
                        modal.classList.add('hidden');
                        console.log('Hiding modal:', modal.id); // Debug
                    }
                },
                switchModals: function(hideThis, showThis) {
                    this.hideModal(hideThis);
                    // Menambahkan setTimeout untuk memastikan transisi yang mulus
                    setTimeout(() => {
                        this.showModal(showThis);
                    }, 100);
                }
            };

            // Event Listeners untuk tombol-tombol
            if (openLoginBtn) {
                openLoginBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    modalManager.showModal(loginModal);
                });
            }

            if (createAccountLink) {
                createAccountLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    console.log('Switching to register modal'); // Debug
                    modalManager.switchModals(loginModal, registerModal);
                });
            }

            if (backToLoginBtn) {
                backToLoginBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    console.log('Switching to login modal'); // Debug
                    modalManager.switchModals(registerModal, loginModal);
                });
            }

            // Event untuk tombol close
            if (closeLoginBtn) {
                closeLoginBtn.addEventListener('click', () => modalManager.hideModal(loginModal));
            }

            if (closeRegisterBtn) {
                closeRegisterBtn.addEventListener('click', () => modalManager.hideModal(registerModal));
            }

            // Menutup modal ketika klik di luar area modal
            window.addEventListener('click', (e) => {
                if (e.target === loginModal) modalManager.hideModal(loginModal);
                if (e.target === registerModal) modalManager.hideModal(registerModal);
            });

            // Handle perubahan level pada form register
            if (regLevelSelect) {
                regLevelSelect.addEventListener('change', function() {
                    const selectedLevel = this.value;

                    // Reset semua field
                    nipField?.classList.add('hidden');
                    kodeField?.classList.add('hidden');
                    nipInput?.removeAttribute('required');
                    kodeInput?.removeAttribute('required');

                    // Show field berdasarkan level
                    if (selectedLevel === 'petugas') {
                        nipField?.classList.remove('hidden');
                        nipInput?.setAttribute('required', '');
                    } else if (selectedLevel === 'admin') {
                        kodeField?.classList.remove('hidden');
                        kodeInput?.setAttribute('required', '');
                    }
                });
            }

            // Validasi form register
            const registerForm = document.querySelector('form[action="register_aksi.php"]');
            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    const level = regLevelSelect?.value;
                    const nip = nipInput?.value;
                    const kode = kodeInput?.value;

                    // Validasi berdasarkan level
                    if (level === 'petugas' && (!nip || nip.trim() === '')) {
                        e.preventDefault();
                        alert('NIP harus diisi untuk level Petugas');
                        return;
                    }

                    if (level === 'admin' && (!kode || kode.trim() === '')) {
                        e.preventDefault();
                        alert('Kode Admin harus diisi untuk level Admin');
                        return;
                    }

                    // Validasi format NIP (18 digit)
                    if (level === 'petugas' && nip && nip.length !== 18) {
                        e.preventDefault();
                        alert('NIP harus 18 digit');
                        return;
                    }

                    // Validasi format kode (7 karakter F-J dan 0-9)
                    if (level === 'admin' && kode && !/^[F-J0-9]{7}$/.test(kode)) {
                        e.preventDefault();
                        alert('Kode harus 7 karakter (F-J dan 0-9)');
                        return;
                    }
                });
            }

            // Cek error login dari URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('alert') === 'gagal') {
                const errorType = urlParams.get('error');
                const errorMessages = {
                    username: 'Username tidak ditemukan!',
                    password: 'Password salah!',
                    role: 'Role/Level tidak sesuai!'
                };

                const errorMessage = errorMessages[errorType] || 'Login gagal!';

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal',
                        text: errorMessage,
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert(errorMessage);
                }

                modalManager.showModal(loginModal);
            }

            // Buat fungsi global untuk onclick handler di HTML
            window.showLoginModal = () => modalManager.showModal(loginModal);
            window.closeLoginModal = () => modalManager.hideModal(loginModal);
            window.showRegisterModal = () => modalManager.showModal(registerModal);
            window.closeRegisterModal = () => modalManager.hideModal(registerModal);

            // Chat Bot Modal Functions
            window.showChatModal = function() {
                document.getElementById('chatModal').classList.add('active');
                document.getElementById('chatInput').focus();
            }

            window.hideChatModal = function() {
                document.getElementById('chatModal').classList.remove('active');
            }

            window.sendMessage = function() {
                const input = document.getElementById('chatInput');
                const messagesContainer = document.getElementById('chatMessages');
                const message = input.value.trim();

                if (message) {
                    // Add user message
                    messagesContainer.innerHTML += `
                        <div class="chat-message chat-message--user">
                            <div class="chat-message__content">
                                ${message}
                            </div>
                        </div>
                    `;

                    // Clear input
                    input.value = '';

                    // Auto scroll to bottom
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;

                    // Simulate bot response (you can replace this with actual API call)
                    setTimeout(() => {
                        messagesContainer.innerHTML += `
                            <div class="chat-message chat-message--bot">
                                <div class="chat-message__content">
                                    Terima kasih atas pertanyaan Anda. Saya akan membantu menjawabnya.
                                </div>
                            </div>
                        `;
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }, 1000);
                }
            }
        });
    </script>

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Wave Text Animation -->

    <script src="js/script.js"></script>
    <script src="js/swiper.js"></script>

</body>

</html>