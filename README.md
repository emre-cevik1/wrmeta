# Wild Rift Meta Tracker

League of Legends: Wild Rift için güncel meta, şampiyon istatistikleri ve counter (karşı eşleşme) durumlarını sunan tam teşekküllü web uygulaması.

## 🌟 Özellikler
- **Tier List**: S+ ile D arası tüm şampiyonların güncel yama seviyeleri.
- **Detaylı İstatistikler**: Kazanma (Win), Seçilme (Pick) ve Yasaklanma (Ban) oranları.
- **Counter Sistemi**: Her şampiyon için "Güçlü" ve "Zayıf" olduğu eşleşmeler.
- **Dinamik Arama ve Filtreleme**: Role (Baron, Orman vb.) ve isme göre anında filtreleme.
- **Premium Dark Mode**: Cam efekti (glassmorphism), neon ışımalar ve pürüzsüz animasyonlarla hazırlanmış modern tasarım.

## 🛠️ Teknoloji Yığını
- **Frontend**: HTML5, CSS3, Vanilla JS (Hiçbir framework kullanılmadı)
- **Backend API**: PHP 7.3+ / 8.0+ (MVC mimarisi)
- **Veritabanı**: MySQL (PDO)
- **Web Scraping**: cURL, DOMDocument & XPath

## 🚀 Kurulum

### 1. Gereksinimler
- **PHP** (7.3 veya üzeri)
- **MySQL Server** (XAMPP, WAMP, MAMP veya bağımsız kurulum)

### 2. Veritabanı Kurulumu
1. phpMyAdmin veya MySQL komut satırı üzerinden projeye bağlanın.
2. `database/schema.sql` dosyasını içe aktarın (Bu adım `wildrift_meta` veritabanını ve gerekli tabloları oluşturur).
3. Test verileriyle hemen başlamak için `database/seed.sql` dosyasını içe aktarın.

*(Eğer MySQL kullanıcı adı `root` ve parolanız boş değilse, `backend/config/Database.php` içerisindeki veritabanı ayarlarını güncelleyin).*

### 3. Çalıştırma
Windows ortamında projeyi ayağa kaldırmak için ana dizindeki **`start.bat`** dosyasına çift tıklayın. Bu script şunları yapacaktır:
- Backend API sunucusunu **8000** portunda başlatır.
- Frontend web sunucusunu **3000** portunda başlatır.
- Tarayıcınızı otomatik olarak `http://localhost:3000` adresinde açar.

Manuel başlatmak isterseniz:
```bash
# Terminal 1 - Backend
cd backend
php -S localhost:8000 index.php

# Terminal 2 - Frontend
cd frontend
php -S localhost:3000
```

## 🤖 Web Scraping Botu Kullanımı
Canlı verileri çekip veritabanını güncellemek için terminalde aşağıdaki komutları kullanabilirsiniz:

```bash
# Sadece test etmek için (Veritabanına yazmaz)
php scraper/ScrapeRunner.php --dry-run

# Tüm verileri çek ve veritabanını güncelle
php scraper/ScrapeRunner.php
```
