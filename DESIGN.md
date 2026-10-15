UI DESIGN.md
Heri Agus Prasetyo - Personal Portfolio

CURRENT DIRECTION (7 Okt 2026)

Arah iPortfolio di bawah ini TIDAK lagi berlaku: frontend publik faktual
mengikuti design mockup `UI/ui-cv.jpg` (landing satu halaman, tema dark ungu).
Implementasi aktif:

- Stack: Blade + Tailwind CSS 4 + Vite (resources/css/frontend.css,
  resources/js/frontend.js, vanilla JS tanpa vendor). Bootstrap/iPortfolio di
  public/assets/ tidak dirender oleh view mana pun.
- Tema: background #08081a (abyss) / #0c0c22 (night), kartu #141432, aksen
  gradient ungu #7c3aed ke #8b5cf6 (tombol, tile statistik), font Plus Jakarta
  Sans + JetBrains Mono.
- Struktur: navbar (tanpa topbar) + hero (badge, foto lingkaran, kartu kode,
  ikon teknologi berwarna brand via helper techBrandColor) + about (grid
  statistik) + skills (progress bar) + portfolio (filter + expand) + resume +
  contact (CTA, testimonial beravatar inisial, follow me, form kontak) +
  footer slim bar (copyright, link legal, "Made with heart").
- Konten tetap dikelola dari Admin Panel (Settings group landing, tabel
  project/skill/timeline/testimonial); section tanpa data tidak ditampilkan.
- Detail project halaman terpisah di /projects/{slug}; halaman legal memakai
  page-title + card legal-copy.

CATATAN LAMA (tidak berlaku, disimpan sebagai arsip):

REVISED DIRECTION (26 Sep 2026)

Arah desain berikut ini yang berlaku sekarang. Dokumen di bawahnya adalah catatan
lama (tema dark portfolio) dan bagian tampilannya sudah tidak dipakai.

Frontend publik mengikuti referensi template iPortfolio (BootstrapMade) yang
disalin ke public/assets/ (css/main.css, js/main.js, vendor, img/hero-bg.jpg).
Aturan yang berlaku:

- Bahasa: seluruh teks publik berbahasa Indonesia.
- Struktur: satu halaman landing (hero, tentang, statistik, keahlian, perjalanan,
  portfolio, layanan, testimoni, kontak) dengan id section anchor. Detail project
  adalah halaman terpisah di /projects/{slug}.
- Layout: sidebar tetap 300px (header#header, navmenu) + main, mobile memakai
  header-toggle. Section berselang dark (hero, statistik, perjalanan, layanan,
  kontak) dan light (tentang, keahlian, portfolio, testimoni).
- Aset: font Google Poppins/Raleway/Roboto, Bootstrap 5.3, bootstrap-icons, AOS,
  typed.js, purecounter, waypoints, GLightbox, Isotope, Swiper. Tidak ada Vite di
  frontend (Vite dipakai admin saja).
- Konten: seluruh teks section, typed roles, statistik, keahlian, layanan,
  testimoni, dan project dikelola dari Admin Panel lewat Settings (group landing)
  dan tabel masing-masing. Section tanpa data otomatis tidak ditampilkan.
- Fallback: bila foto profil/about/kosong, tampilkan monogram (profile-fallback,
  photo-fallback) agar tidak ada gambar rusak.
- Halaman legal (kebijakan privasi, syarat ketentuan) memakai page-title dark
  background + section biasa.

Guardrail tetap berlaku: tanpa em dash, tanpa emoji/glyph aneh, tanpa gradient
ungu, tanpa copy template AI, tanpa klaim palsu. Kredit referensi: footer
menyebut "Desain mengikuti iPortfolio oleh BootstrapMade" sesuai lisensi.

Admin panel tetap memakai Tailwind + Vite (resources/css/admin.css,
resources/js/app.js, resources/js/admin.js).

1. Project Overview
Website Name

Heri Agus Prasetyo

Personal Branding

Web Developer & Software Engineer

Education

Program Studi Sistem Informasi
Universitas Mercu Buana Yogyakarta

Website Purpose

Website ini merupakan personal portfolio yang digunakan untuk menampilkan:

Profil pribadi
Pendidikan
Keahlian teknis
Pengalaman pengembangan website
Project yang telah dikerjakan
Layanan yang dapat ditawarkan
Teknologi yang digunakan
Kontak dan social media

Website harus lebih menonjolkan hasil karya nyata daripada sekadar informasi biodata.

2. Main Design Concept
Concept

Modern Digital Portfolio â€” Developer, Builder & Problem Solver

Visual website harus memberikan kesan:

Professional
Modern
Clean
Technical
Creative
Elegant
Trustworthy
Developer-oriented

Website jangan terlihat seperti:

CV digital biasa
Template portfolio mahasiswa
Landing page perusahaan
Website agency

Website harus terasa sebagai personal brand seorang developer yang memiliki pengalaman membangun berbagai website dan sistem untuk kebutuhan nyata.

3. Main Positioning

Gunakan positioning utama:

I build modern websites and digital solutions for businesses.

Versi Indonesia:

Saya membangun website modern dan solusi digital untuk membantu bisnis berkembang.

Secondary positioning:

Web Developer & Software Engineer with a background in Information Systems.

4. Design Style

Gunakan visual:

Dark
Minimal
Modern
Editorial
Developer
Premium

Inspirasi visual:

Modern SaaS
Developer portfolio
Creative agency
Product showcase
Editorial website

Jangan menggunakan desain yang terlalu corporate.

5. Color System
Primary Theme

Gunakan dark mode sebagai default.

Background:
#080B12

Background Secondary:
#0D111A

Surface:
#111827

Surface Hover:
#172033

Primary:
#3B82F6

Primary Hover:
#2563EB

Accent:
#60A5FA

Text:
#F8FAFC

Text Secondary:
#94A3B8

Muted:
#64748B

Border:
#1E293B

Success:
#22C55E
Visual Accent

Gunakan warna biru hanya sebagai aksen.

Contoh:

White Text
      +
Dark Background
      +
Blue Accent

Jangan menggunakan gradient warna-warni.

6. Typography
Primary Font
Inter

Alternative:

Manrope

Technical / code:

JetBrains Mono
Typography
Hero H1:
64px â€“ 80px
Weight: 700â€“800

Section H2:
42px â€“ 52px
Weight: 700

Project Title:
24px â€“ 30px

Body:
16px â€“ 18px

Small:
14px

Code:
13px â€“ 14px

Mobile:

Hero:
40px â€“ 48px

Section:
32px â€“ 38px

Body:
15px â€“ 16px
7. Global Layout

Maximum width:

1200px

Large desktop:

max-width: 1280px

Mobile:

padding: 20px

Desktop:

padding: 32px

Section spacing:

Desktop:
100px â€“ 140px

Mobile:
70px â€“ 90px
8. Navbar

Navbar:

â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚ HA     About   Skills   Projects   Experience   Contactâ”‚
â”‚                                             Let's Talk  â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
Logo

Gunakan:

HA

dengan tulisan kecil:

Heri Agus Prasetyo

atau:

HA.
Navigation
Home
About
Skills
Projects
Experience
Contact
CTA
Let's Talk

Navbar:

Sticky
Backdrop blur
Transparent ketika di bagian atas
Dark surface ketika scrolling
Border bottom subtle
9. HERO SECTION

Hero harus menjadi identitas utama website.

Layout

Desktop:

â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚                                                     â”‚
â”‚  HELLO, I'M HERI                                    â”‚
â”‚                                                     â”‚
â”‚  Heri Agus Prasetyo                                 â”‚
â”‚                                                     â”‚
â”‚  WEB DEVELOPER                                      â”‚
â”‚  & SOFTWARE ENGINEER                                â”‚
â”‚                                                     â”‚
â”‚  I build modern websites and digital solutions      â”‚
â”‚  for businesses and organizations.                  â”‚
â”‚                                                     â”‚
â”‚  [ View Projects ]  [ Let's Talk ]                  â”‚
â”‚                                                     â”‚
â”‚                                      â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”‚
â”‚                                      â”‚           â”‚  â”‚
â”‚                                      â”‚   PHOTO   â”‚  â”‚
â”‚                                      â”‚           â”‚  â”‚
â”‚                                      â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â”‚
â”‚                                                     â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
10. Hero Content
Eyebrow
HELLO, I'M HERI
Heading
Heri Agus Prasetyo
Main Title
Web Developer
&
Software Engineer
Description
Saya mengembangkan website, aplikasi berbasis web,
dan solusi digital yang modern, responsif,
serta disesuaikan dengan kebutuhan bisnis.
Education
Sistem Informasi
Universitas Mercu Buana Yogyakarta
CTA

Primary:

View My Projects â†’

Secondary:

Let's Talk
11. Hero Visual

Jangan menggunakan ilustrasi developer generic.

Prioritaskan:

Foto personal + visual code/interface.

Konsep:

                    â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
                    â”‚                 â”‚
                    â”‚   HERI PHOTO    â”‚
                    â”‚                 â”‚
                    â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
                         â”‚
                  â”Œâ”€â”€â”€â”€â”€â”€â”´â”€â”€â”€â”€â”€â”€â”
                  â”‚ <code />    â”‚
                  â”‚ Laravel     â”‚
                  â”‚ PHP         â”‚
                  â”‚ JavaScript  â”‚
                  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜

Foto:

Professional
Natural
Background clean
Dark/neutral tone
Tidak terlalu formal
12. Hero Stats

Tambahkan statistik singkat.

Contoh:

10+
Projects

5+
Web Solutions

3+
Years Learning & Building

âˆž
Ideas to Build

Namun angka hanya boleh digunakan apabila benar-benar sesuai dengan data aktual.

Jika tidak ada data pasti, gunakan kategori:

Websites
Applications
Business Solutions
Information Systems
13. About Section
Heading
About Me

Subheading:

Building digital solutions with technology.

Isi:

Saya adalah Heri Agus Prasetyo, seorang Web Developer
dan Software Engineer dengan latar belakang Sistem Informasi
dari Universitas Mercu Buana Yogyakarta.

Saya memiliki ketertarikan dalam membangun website,
aplikasi berbasis web, dan sistem informasi untuk
kebutuhan bisnis maupun organisasi.

Fokus saya adalah menghasilkan solusi yang tidak hanya
berfungsi dengan baik, tetapi juga memiliki tampilan
modern, responsif, terstruktur, dan mudah digunakan.
14. About Visual

Gunakan dua kolom:

â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚                  â”‚
â”‚     PHOTO        â”‚
â”‚                  â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜

                 ABOUT HERI
                 â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

                 Information Systems
                 Web Development
                 Software Engineering

                 [ Download CV ]
15. Career Focus

Buat tiga focus card.

01
WEB DEVELOPMENT

Building responsive and modern
web applications.

02
SOFTWARE ENGINEERING

Developing structured systems
for real-world needs.

03
DIGITAL SOLUTIONS

Turning business requirements
into usable digital products.
16. Skills Section
Heading
Skills & Technologies

Subtitle:

Technologies I use to turn ideas into working products.

Kelompokkan berdasarkan fungsi.

Frontend
HTML
CSS
JavaScript
Bootstrap
Responsive Design
Backend
PHP
Laravel
CodeIgniter
MySQL
REST API
Tools
Git
GitHub
VS Code
XAMPP
Figma
Canva
17. Tech Stack Visual

Buat horizontal scrolling technology list.

PHP
Laravel
CodeIgniter
JavaScript
Bootstrap
MySQL
Git
GitHub

Visual:

â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â” â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â” â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚  PHP   â”‚ â”‚ Laravel  â”‚ â”‚ JavaScript â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”˜ â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜ â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜

â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â” â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â” â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚ Bootstrap â”‚ â”‚ MySQL  â”‚ â”‚   Git    â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜ â””â”€â”€â”€â”€â”€â”€â”€â”€â”˜ â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
18. PROJECTS â€” MAIN SECTION

Ini adalah section terpenting kedua setelah Hero.

Gunakan project nyata dari portofolio ini.

Website ini saat ini menampilkan berbagai project seperti DUYUFUNA, RDR Interior Aluminium, Horeca Furniture, InnovaTeak, Bukit Jati, Mury Furniture, SMOPF, dan Juneo Phone.

Heading
Selected Projects

Subtitle:

A selection of websites and digital systems I've built.
19. Project Categories

Gunakan filter:

All
Company Profile
E-Commerce / Catalog
Information System
Management System
Landing Page

Project grid harus dapat difilter secara interaktif.

20. Project Card

Struktur:

â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚                                     â”‚
â”‚          PROJECT SCREENSHOT         â”‚
â”‚                                     â”‚
â”‚                                     â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚ COMPANY PROFILE                     â”‚
â”‚                                     â”‚
â”‚ DUYUFUNA                            â”‚
â”‚                                     â”‚
â”‚ Website company profile dan         â”‚
â”‚ katalog layanan Umrah & Haji.      â”‚
â”‚                                     â”‚
â”‚ Laravel  PHP  MySQL                 â”‚
â”‚                                     â”‚
â”‚ View Project â†’                      â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
21. Featured Project

Project pertama dibuat lebih besar.

â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚                                                     â”‚
â”‚              LARGE PROJECT IMAGE                    â”‚
â”‚                                                     â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚ DUYUFUNA               â”‚ Company Profile            â”‚
â”‚                        â”‚                            â”‚
â”‚ Umrah & Haji Digital   â”‚ Website untuk menampilkan â”‚
â”‚ Services               â”‚ berbagai layanan.          â”‚
â”‚                        â”‚                            â”‚
â”‚ Laravel â€¢ PHP â€¢ MySQL  â”‚ [View Project â†’]          â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”´â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
22. Project List
Project 01
DUYUFUNA

Category:

Company Profile

Description:

Website company profile dan katalog digital
untuk layanan pendukung perjalanan Umrah dan Haji.

Technology:

Laravel
PHP
MySQL
JavaScript

Fitur yang dapat ditonjolkan:

Service catalog
Service detail
WhatsApp integration
Admin panel
Gallery
Testimonial
FAQ
Blog
SEO
Responsive design

Informasi fitur tersebut tercantum pada portofolio ini.

23. Project 02
RDR Interior Aluminium

Category:

Company Profile
Product Catalog

Description:

Company profile dan katalog digital untuk bisnis
interior aluminium custom.

Highlight:

Kitchen Set
Kabinet
Pintu Aluminium
Jendela
Partisi
Cermin

Technology:

Laravel
PHP
MySQL
JavaScript
24. Project 03
Horeca Furniture

Category:

Company Profile
Product Catalog

Description:

Website katalog digital untuk menampilkan
produk furniture kayu jati untuk kebutuhan
Hotel, Restaurant, dan Cafe.

Highlight:

Product Catalog
Product Detail
WhatsApp Integration
Responsive Design
25. Project 04
InnovaTeak

Category:

Company Profile
Furniture

Description:

Website company profile dan katalog digital
untuk produk furniture kayu jati outdoor.

Highlight:

Outdoor Furniture
Product Categories
Product Detail
Legal Wood Certification
Payment Terms
Contact
26. Project 05
Bukit Jati

Category:

Furniture
Product Catalog

Description:

Company profile dan katalog digital furniture
kayu jati indoor dan outdoor.

Highlight:

Product Catalog
Product Detail
Gallery
Request Catalog
WhatsApp
27. Project 06
Mury Furniture

Category:

Furniture
Company Profile

Description:

Website company profile dan katalog digital
untuk furniture klasik dan custom wood finishes.

Highlight:

Product Catalog
Production Documentation
FAQ
Blog
Multi-language
WhatsApp
28. Project 07
SMOPF

Full Name:

Sistem Manajemen Order & Produksi Furniture

Category:

Management System
Information System

Description:

Sistem manajemen digital untuk mengelola
order, produksi, pekerja, QC, laporan,
hingga stok siap kirim.

Highlight:

Dashboard
Buyer Management
Worker Management
Product Management
Order Management
Production Tracking
QC
Rework
Reports
PDF Export
Excel Export
Role Management

Project ini sangat penting untuk menunjukkan kemampuan membangun sistem informasi yang kompleks, bukan hanya company profile. Fitur tersebut tercantum pada portofolio ini.

29. Project 08
Juneo Phone

Category:

Management System
Service Management

Description:

Aplikasi manajemen service HP untuk membantu
pemilik usaha mengelola pelanggan, antrian,
service, biaya, dan laporan.

Highlight:

Dashboard
Service Management
Queue
Financial Report
Daily Expenses
Employee Fee
30. Project Detail Page

Setiap project sebaiknya memiliki halaman detail.

URL:

/projects/{slug}

Contoh:

/projects/duyufuna
/projects/rdr-interior-aluminium
/projects/smopf

Struktur:

Project Title

Category

Project Overview

[ LARGE SCREENSHOT ]

The Challenge

The Solution

Key Features

Technology Stack

[ SCREENSHOT ]
[ SCREENSHOT ]
[ SCREENSHOT ]

My Role

Project Outcome

[ Live Website ]
31. Project Detail â€” My Role

Bagian ini penting untuk portfolio personal.

Jangan hanya mengatakan:

Website ini dibuat menggunakan Laravel.

Gunakan:

My Role

Planning
UI Development
Frontend Development
Backend Development
Database Design
CMS / Admin Panel
Responsive Development
Deployment
Maintenance

Tampilkan hanya tanggung jawab yang benar-benar dikerjakan.

32. Project Screenshot

Screenshot harus menjadi elemen visual utama.

Gunakan:

Desktop Mockup
Browser Mockup
Mobile Mockup

Contoh:

             â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
             â”‚ â -  â -  â -                       â”‚
             â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
             â”‚                           â”‚
             â”‚      WEBSITE SCREEN       â”‚
             â”‚                           â”‚
             â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜

Jangan menggunakan screenshot kecil yang sulit dibaca.

33. Experience Section

Karena portfolio berisi project nyata, gunakan judul:

My Journey

bukan hanya:

Work Experience

Timeline:

Education
     â†“
Learning
     â†“
Building Projects
     â†“
Client / Business Projects
     â†“
Software Engineering

Jika memiliki pengalaman profesional tertentu, masukkan berdasarkan data yang benar.

34. Education

Card:

Universitas Mercu Buana Yogyakarta

Program Studi Sistem Informasi

Information Systems

Tambahkan deskripsi:

Mempelajari sistem informasi, pengembangan aplikasi,
database, analisis sistem, dan teknologi informasi.
35. Services

Karena project yang dimiliki menunjukkan kemampuan membuat solusi untuk berbagai bisnis, bagian layanan dapat menggunakan:

What I Build
01
Business Website

Website company profile profesional
untuk bisnis dan organisasi.
02
Product Catalog

Katalog produk digital dengan
halaman detail dan kategori.
03
Information System

Sistem informasi berbasis web
sesuai kebutuhan organisasi.
04
Management System

Aplikasi untuk membantu bisnis
mengelola operasional.
05
Custom Website

Website dengan fitur yang
disesuaikan kebutuhan.
06
Landing Page

Landing page modern untuk
produk, bisnis, dan campaign.
36. Process Section

Gunakan:

How I Build
01 â€” Discover
Memahami kebutuhan dan masalah
yang ingin diselesaikan.
02 â€” Plan
Menyusun struktur, fitur,
database, dan user flow.
03 â€” Design
Membuat interface yang
modern dan mudah digunakan.
04 â€” Develop
Mengembangkan frontend,
backend, database, dan fitur.
05 â€” Test
Melakukan testing dan
perbaikan sebelum deployment.
06 â€” Improve
Maintenance dan pengembangan
fitur sesuai kebutuhan.
37. Contact Section
Heading
Have a project in mind?
Let's build it.

Indonesia:

Punya ide atau project?
Mari kita bangun bersama.

Description:

Jika Anda membutuhkan website, sistem informasi,
atau solusi digital untuk bisnis, saya terbuka
untuk berdiskusi mengenai kebutuhan project Anda.

CTA:

Let's Talk â†’
38. Contact Information

Gunakan:

Email
WhatsApp
GitHub
LinkedIn
Instagram

Hanya tampilkan akun yang benar-benar tersedia.

39. Footer
HA.

Heri Agus Prasetyo
Web Developer & Software Engineer

Building modern digital solutions.

--------------------------------

Home
About
Skills
Projects
Services
Contact

--------------------------------

Â© 2026 Heri Agus Prasetyo
All Rights Reserved.
40. Micro Interaction

Gunakan animasi ringan.

Navbar
Active menu
underline / blue indicator
Project
Image zoom 1.03
Card translateY(-4px)
Border accent
Button
translateY(-2px)
Arrow
â†’

bergeser sedikit ketika hover.

41. Page Transition

Gunakan:

fade

Durasi:

200ms â€“ 400ms

Jangan menggunakan animasi halaman yang terlalu berat.

42. Responsive Design
Desktop
â‰¥ 1200px

Hero:

2 columns

Project:

3 columns
Tablet
768px â€“ 1199px

Project:

2 columns
Mobile
< 768px

Project:

1 column

Hero:

1 column
43. Mobile Navigation

Mobile:

â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚ HA.                     â˜°   â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜

Menu:

Home
About
Skills
Projects
Experience
Contact

[ Let's Talk ]
44. Accessibility

Wajib:

Semantic HTML
ARIA jika diperlukan
Alt text
Keyboard navigation
Focus state
Readable contrast
Accessible forms
45. SEO
Title
Heri Agus Prasetyo â€” Web Developer & Software Engineer
Description
Portfolio pribadi Heri Agus Prasetyo,
Web Developer dan Software Engineer dengan
latar belakang Sistem Informasi Universitas
Mercu Buana Yogyakarta.
Open Graph

Gunakan:

og:title
og:description
og:image
og:url
46. Performance

Target:

Fast Loading
Optimized Images
Lazy Loading
WebP / AVIF
Minimal JavaScript
CSS Optimization

Project screenshot harus dioptimalkan sebelum ditampilkan.

47. Recommended Website Structure
/
â”‚
â”œâ”€â”€ Home
â”‚
â”œâ”€â”€ About
â”‚
â”œâ”€â”€ Skills
â”‚
â”œâ”€â”€ Projects
â”‚   â”œâ”€â”€ DUYUFUNA
â”‚   â”œâ”€â”€ RDR Interior Aluminium
â”‚   â”œâ”€â”€ Horeca Furniture
â”‚   â”œâ”€â”€ InnovaTeak
â”‚   â”œâ”€â”€ Bukit Jati
â”‚   â”œâ”€â”€ Mury Furniture
â”‚   â”œâ”€â”€ SMOPF
â”‚   â””â”€â”€ Juneo Phone
â”‚
â”œâ”€â”€ Experience
â”‚
â”œâ”€â”€ Services
â”‚
â””â”€â”€ Contact
48. Recommended Homepage Flow
NAVBAR
   â†“
HERO
   â†“
SHORT INTRO
   â†“
SELECTED PROJECTS
   â†“
ABOUT ME
   â†“
SKILLS & TECHNOLOGY
   â†“
MY JOURNEY
   â†“
WHAT I BUILD
   â†“
CONTACT
   â†“
FOOTER

Project harus ditempatkan lebih awal, karena karya nyata merupakan salah satu bukti utama kemampuan.

49. Homepage Visual Structure
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚ HA.       About Skills Projects ...    Let's Talkâ”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚                                                  â”‚
â”‚ HELLO, I'M HERI                                  â”‚
â”‚                                                  â”‚
â”‚ Heri Agus Prasetyo                               â”‚
â”‚ Web Developer & Software Engineer                â”‚
â”‚                                                  â”‚
â”‚ I build modern websites and digital solutions.   â”‚
â”‚                                                  â”‚
â”‚ [View Projects] [Let's Talk]                     â”‚
â”‚                                                  â”‚
â”‚                         [PERSONAL PHOTO]         â”‚
â”‚                                                  â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚                                                  â”‚
â”‚ SELECTED PROJECTS                                â”‚
â”‚                                                  â”‚
â”‚ â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â” â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â” â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”    â”‚
â”‚ â”‚ DUYUFUNA   â”‚ â”‚ RDR        â”‚ â”‚ SMOPF      â”‚    â”‚
â”‚ â”‚ Screenshot â”‚ â”‚ Screenshot â”‚ â”‚ Screenshot â”‚    â”‚
â”‚ â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜ â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜ â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜    â”‚
â”‚                                                  â”‚
â”‚              View All Projects â†’                 â”‚
â”‚                                                  â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚                                                  â”‚
â”‚ ABOUT ME                                         â”‚
â”‚                                                  â”‚
â”‚ [PHOTO]       Heri Agus Prasetyo                â”‚
â”‚               Information Systems                â”‚
â”‚               Web Developer                      â”‚
â”‚               Software Engineer                  â”‚
â”‚                                                  â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚                                                  â”‚
â”‚ SKILLS & TECHNOLOGIES                            â”‚
â”‚                                                  â”‚
â”‚ PHP  Laravel  JavaScript  Bootstrap  MySQL      â”‚
â”‚ Git  GitHub  CodeIgniter                         â”‚
â”‚                                                  â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚                                                  â”‚
â”‚ MY JOURNEY                                       â”‚
â”‚                                                  â”‚
â”‚ Education â†’ Learning â†’ Projects â†’ Development   â”‚
â”‚                                                  â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚                                                  â”‚
â”‚ WHAT I BUILD                                     â”‚
â”‚                                                  â”‚
â”‚ Business Website   Product Catalog               â”‚
â”‚ Information System Management System             â”‚
â”‚                                                  â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚                                                  â”‚
â”‚ HAVE A PROJECT IN MIND?                          â”‚
â”‚ LET'S BUILD IT.                                  â”‚
â”‚                                                  â”‚
â”‚             [ Let's Talk â†’ ]                     â”‚
â”‚                                                  â”‚
â”œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¤
â”‚ HA.                                              â”‚
â”‚ Heri Agus Prasetyo                               â”‚
â”‚ Web Developer & Software Engineer                â”‚
â”‚                                                  â”‚
â”‚ Â© 2026 Heri Agus Prasetyo                        â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
50. Core Design Principle

Portfolio ini adalah website personal, bukan website brand.

Website brand adalah website bisnis yang dikelola pihak lain.

Portfolio Heri harus menjawab:

"Siapa Heri, apa yang Heri bisa bangun, dan project apa yang sudah pernah Heri kerjakan?"

Maka hierarchy utamanya:

HERI
 â†“
SKILLS
 â†“
REAL PROJECTS
 â†“
EXPERIENCE
 â†“
SERVICES
 â†“
CONTACT

Bukan:

Pricing
 â†“
Services
 â†“
Business
 â†“
Promotion
51. Final Brand Statement

Gunakan sebagai statement utama portfolio:

I build modern websites and digital solutions that turn ideas into real products.

Alternatif Indonesia:

Saya membangun website modern dan solusi digital yang mengubah ide menjadi produk nyata.

Secondary:

Web Developer & Software Engineer â€” Information Systems, Universitas Mercu Buana Yogyakarta.

52. Design Keywords
Modern
Dark
Clean
Developer
Professional
Minimal
Elegant
Technical
Creative
Personal
Responsive
Fast
Project-focused

END