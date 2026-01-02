<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // === SALES INQUIRIES === //

        Contact::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@techcorp.id',
            'phone' => '+62-812-3456-7890',
            'category' => 'sales_inquiry',
            'message' => 'Selamat siang, saya dari PT TechCorp Indonesia. Kami sedang mencari supplier komputer untuk kantor baru kami di Jakarta. Kami membutuhkan 25 unit laptop Dell XPS 15 dan 10 unit monitor LG UltraFine. Apakah ada diskon untuk pembelian dalam jumlah besar? Mohon info harga dan waktu pengiriman. Terima kasih.',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Sarah Chen',
            'email' => 'sarah.chen@creativestudio.co.id',
            'phone' => '+62-811-9876-5432',
            'category' => 'sales_inquiry',
            'message' => 'Hi! We\'re a video production company looking to upgrade our editing workstations. Interested in the MacBook Pro 16" M3 Max and the Samsung Odyssey OLED G9 monitors. Do you offer business accounts with NET 30 payment terms? We\'d be ordering 8 MacBooks and 5 monitors. Also, is installation service available?',
            'status' => 'read',
        ]);

        Contact::create([
            'name' => 'Ahmad Rahman',
            'email' => 'ahmad.rahman@univ-jakarta.ac.id',
            'phone' => '+62-813-2468-1357',
            'category' => 'sales_inquiry',
            'message' => 'Assalamualaikum, saya koordinator lab komputer Universitas Jakarta. Kami berencana membeli 50 unit Acer Aspire 5 untuk lab mahasiswa. Apakah ada harga khusus untuk institusi pendidikan? Kami juga memerlukan extended warranty 3 tahun. Mohon kirimkan quotation lengkap beserta term of payment. Jazakallah.',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Jennifer Lopez',
            'email' => 'jlopez@digitalmedia.co',
            'phone' => null,
            'category' => 'sales_inquiry',
            'message' => 'Hello, I run a digital marketing agency and need to equip our new office. Looking for: 15x Lenovo ThinkPad X1 Carbon, 3x Synology NAS DS923+, 20x Dell P2723DE monitors, and various peripherals (keyboards, mice, headsets). Can you prepare a complete package quote? Budget is flexible for quality products. Need delivery by end of next month.',
            'status' => 'read',
            'admin_reply' => 'Hi Jennifer, thank you for your interest! We\'ve prepared a comprehensive quote for your office setup. Our sales team will contact you within 24 hours with detailed pricing, volume discounts (approximately 12-15% off), and delivery schedule. We can definitely meet your end-of-month deadline. For a purchase of this size, we also offer free installation and 1-year premium support. Best regards, Computer Store Sales Team',
        ]);

        Contact::create([
            'name' => 'Michael Wijaya',
            'email' => 'michael.w@gamingcafe.id',
            'phone' => '+62-815-7777-8888',
            'category' => 'sales_inquiry',
            'message' => 'Halo, saya mau buka gaming cafe di Bandung dengan 30 PC. Tertarik dengan Custom Gaming PC - RTX 4070 Ti Build. Apakah bisa custom spec? Saya mau upgrade ke RTX 4080 dan 64GB RAM. Berapa total cost untuk 30 unit? Apakah ada garansi dan after sales service? Butuh ready dalam 2 bulan. Tolong info detail ya. Thanks!',
            'status' => 'new',
        ]);

        // === TECHNICAL SUPPORT === //

        Contact::create([
            'name' => 'David Kurniawan',
            'email' => 'david.k@email.com',
            'phone' => '+62-813-5555-1234',
            'category' => 'tech_support',
            'message' => 'Saya beli ASUS ROG Strix G16 minggu lalu (invoice #RT-2024-1234). Laptop nya panas banget saat main game, temperature CPU sampai 95°C. Fan juga bunyi keras. Apakah ini normal? Saya khawatir ada masalah dengan cooling system. Mohon bantuan troubleshooting atau perlu dibawa ke service center? Terima kasih.',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Emily Rodriguez',
            'email' => 'emily.rodriguez@freelance.com',
            'phone' => '+62-815-7777-9999',
            'category' => 'tech_support',
            'message' => 'My Dell P2723DE monitor has several dead pixels near the center of the screen. I purchased it 2 months ago. Is this covered under warranty? How do I process a warranty claim? Do I need to ship it back or can someone pick it up? I need this monitor for work urgently, so hoping for a quick resolution or replacement.',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Rini Kusuma',
            'email' => 'rini.kusuma@company.co.id',
            'phone' => '+62-817-2222-3333',
            'category' => 'tech_support',
            'message' => 'MacBook Pro M3 Max saya tiba-tiba slow sejak update macOS kemarin. Applications sering crash dan battery life menurun drastis. Sudah coba restart berkali-kali tapi masih sama. Apakah ini known issue? Ada solusi tanpa harus factory reset? Data saya penting semua. Mohon advise segera karena mengganggu pekerjaan. Thanks.',
            'status' => 'read',
            'admin_reply' => 'Hi Rini, thank you for reaching out. The issues you\'re experiencing are typically related to macOS indexing processes after an update. Please try: 1) Reset SMC and NVRAM, 2) Check Activity Monitor for high CPU processes, 3) Disable and re-enable Spotlight indexing. If problems persist after 48 hours, please bring your MacBook to our service center for diagnostics (free under warranty). We can backup your data before any repairs. Contact: 021-xxx-xxxx.',
        ]);

        Contact::create([
            'name' => 'Alex Thompson',
            'email' => 'alex.t@startup.io',
            'phone' => '+62-818-3333-4444',
            'category' => 'tech_support',
            'message' => 'I\'m having connectivity issues with my ASUS RT-AX88U Pro router. WiFi keeps dropping every few hours and I have to restart it. Firmware is up to date (latest version). I have about 45 devices connected. Is this a hardware issue or configuration problem? Any troubleshooting steps I should try? Router is only 3 months old.',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.n@designer.id',
            'phone' => '+62-819-6666-7777',
            'category' => 'tech_support',
            'message' => 'Samsung Odyssey OLED G9 monitor saya mengalami burn-in di bagian taskbar Windows. Baru pakai 4 bulan. Ini masuk garansi kan? Katanya OLED ada 3 tahun OLED care warranty. Bagaimana prosedur klaim nya? Apakah perlu foto bukti? Saya sangat kecewa karena harga monitor ini mahal sekali. Mohon solusi cepat.',
            'status' => 'read',
        ]);

        Contact::create([
            'name' => 'Kevin Tan',
            'email' => 'kevin.tan@gamer.net',
            'phone' => '+62-821-9999-0000',
            'category' => 'tech_support',
            'message' => 'Razer DeathAdder V3 Pro mouse not charging. LED indicator doesn\'t light up when connected to USB-C cable. Tried different cables and ports but same issue. Battery was working fine yesterday, suddenly dead today. Is this a battery defect? Still under warranty (bought 5 months ago). Need replacement ASAP as I use this for competitive gaming.',
            'status' => 'new',
        ]);

        // === GENERAL INQUIRIES === //

        Contact::create([
            'name' => 'Lisa Anderson',
            'email' => 'lisa.anderson@techblog.com',
            'phone' => null,
            'category' => 'general',
            'message' => 'Hi! I\'m a tech blogger writing an article about "Best Laptops for Content Creators in 2024". Would love to feature some of your products (MacBook Pro M3, Dell XPS 15, MSI Raider). Can I use product images from your website? Will include links and proper attribution. Also, any chance of getting review units for hands-on testing? My blog gets 50K monthly visitors.',
            'status' => 'read',
            'admin_reply' => 'Hi Lisa, thank you for your interest in featuring our products! You\'re welcome to use our product images with proper attribution and links to our store. Regarding review units, please send us your media kit and previous work samples to marketing@store.com. We\'ll review your request and get back to you within 3-5 business days. Looking forward to potential collaboration!',
        ]);

        Contact::create([
            'name' => 'Robert Martinez',
            'email' => 'robert.martinez@itconsultant.biz',
            'phone' => '+62-812-6666-9999',
            'category' => 'general',
            'message' => 'Good day! I\'m an IT consultant serving 20+ corporate clients in Jakarta and Surabaya. Very interested in your product range. Do you have a reseller or partner program? Looking for competitive pricing, priority stock allocation, and technical support for my clients. Annual revenue potential: $500K+. Please share partnership terms and conditions.',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi.lestari@foundation.or.id',
            'phone' => '+62-814-3333-4444',
            'category' => 'general',
            'message' => 'Yayasan kami bergerak di bidang pendidikan untuk anak kurang mampu di Indonesia Timur. Kami ingin melengkapi 5 computer labs (total 150 komputer). Apakah Computer Store punya CSR program atau educational discount? Kami juga terbuka untuk partnership jangka panjang. Budget terbatas tapi komitmen jangka panjang tinggi. Mohon info lebih lanjut. Terima kasih banyak.',
            'status' => 'read',
            'admin_reply' => 'Dear Ibu Dewi, terima kasih atas kepercayaan kepada Computer Store. Kami sangat menghargai misi mulia yayasan Ibu. Computer Store memiliki program CSR untuk institusi pendidikan dengan diskon up to 25% dan payment terms yang fleksibel. Tim kami akan menghubungi Ibu untuk diskusi lebih detail mengenai kebutuhan dan solusi yang kami tawarkan. Mari kita bersama membangun pendidikan Indonesia. Salam, Computer Store CSR Team',
        ]);

        Contact::create([
            'name' => 'Marcus Johnson',
            'email' => 'marcus.j@youtuber.com',
            'phone' => '+62-816-1111-2222',
            'category' => 'general',
            'message' => 'Hey! I\'m a tech YouTuber with 250K subscribers focusing on gaming gear reviews. Want to collaborate on review videos for your gaming products - laptops, monitors, peripherals, etc. My videos average 100K views. Can provide detailed reviews, unboxing, performance testing. Interested in long-term partnership. Check out my channel: TechMarcusID. Let\'s talk!',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Putri Wulandari',
            'email' => 'putri.w@webagency.co.id',
            'phone' => '+62-822-4444-5555',
            'category' => 'general',
            'message' => 'Halo Computer Store! Kami digital agency yang sedang develop website untuk client kami. Bolehkah kami menggunakan beberapa product photos dari website Computer Store sebagai mockup/demo content? Tentu dengan watermark dan credit to Computer Store. Website tersebut untuk portfolio kami saja, non-commercial. Mohon izin dan approval. Thank you!',
            'status' => 'read',
            'admin_reply' => 'Hi Putri, terima kasih sudah menghubungi kami. Untuk penggunaan product photos sebagai mockup/demo content non-commercial, kami perbolehkan dengan syarat: 1) Include watermark/credit to Computer Store, 2) Include link to website kami, 3) Kirim preview ke marketing@store.com untuk approval. Senang bisa membantu project Anda. Good luck!',
        ]);

        Contact::create([
            'name' => 'William Chen',
            'email' => 'william.chen@enterprise.com',
            'phone' => '+62-823-7777-8888',
            'category' => 'general',
            'message' => 'We\'re a multinational company expanding to Indonesia. Need IT equipment procurement for 200+ employees across 3 offices (Jakarta, Surabaya, Bali). Looking for comprehensive solution: laptops, desktops, monitors, networking equipment, peripherals. Do you handle enterprise-level deployment, asset management, and on-site support? Need RFP response by next week.',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Andi Firmansyah',
            'email' => 'andi.f@esports.id',
            'phone' => '+62-824-9999-0000',
            'category' => 'general',
            'message' => 'Kami E-Sports organization dengan 5 professional teams. Butuh sponsor untuk gaming equipment: 30x gaming laptops (ASUS ROG/MSI), 30x gaming monitors (240Hz+), 30x gaming peripherals (keyboard, mouse, headset). Bisa discuss sponsorship package? We have 500K+ social media followers dan participate in national/international tournaments. Contact untuk meeting?',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Sophie Wang',
            'email' => 'sophie.wang@photographer.id',
            'phone' => null,
            'category' => 'general',
            'message' => 'Hello! I\'m a commercial photographer looking to shoot product photography for tech brands. Saw your product range and would love to collaborate. Can provide high-quality lifestyle shots, studio photography, and creative content for your website/social media. Portfolio: sophiewang.com. Let me know if you\'re interested in upgrading your product visuals. Competitive rates available!',
            'status' => 'read',
            'admin_reply' => 'Hi Sophie, thank you for reaching out! We\'re actually planning a product photography refresh for Q1 2024. Your portfolio looks impressive. Please send your rate card and available packages to marketing@store.com. Our marketing team will review and schedule a meeting to discuss potential collaboration. Looking forward to working together!',
        ]);

        Contact::create([
            'name' => 'Hendra Gunawan',
            'email' => 'hendra.g@distributor.co.id',
            'phone' => '+62-825-1111-2222',
            'category' => 'general',
            'message' => 'Selamat siang, saya distributor elektronik di Kalimantan dengan 15 retail stores. Tertarik menjadi authorized dealer Computer Store untuk wilayah Kalimantan. Punya pengalaman 10 tahun di industri IT retail. Mohon info mengenai dealer program, margin, MOQ, dan support yang diberikan. Bisa schedule meeting untuk discuss partnership? Terima kasih.',
            'status' => 'new',
        ]);
    }
}
