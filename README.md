# styleumaxwptema
styleumax wordpress tema

# Önizlema
<img src="https://raw.githubusercontent.com/umablognot/styleumaxwptema/refs/heads/main/screenshot.png" alt="img" class="responsive">

# Styleumax WordPress Teması

Styleumax, sıfırdan geliştirilmiş modern, dergi/haber odaklı bir WordPress temasıdır. çağdaş bir güvenlik, performans ve esneklik odaklı tasarlanmıştır. Tema; PHP 8+, Bootstrap 5.3, Font Awesome 7 ve Inter yazı tipi ailesi kullanılarak geliştirilmiştir ve WordPress 6.x ile tam uyumludur (en az 6.5 gerektirir, 6.8'e kadar test edilmiştir).

> **⚠️ Önemli Not**
>
> Bu, temanın **ilk sürümüdür**; hem insan emeği hem de yapay zeka araçlarıyla geliştirilmiştir. Üretim sitesinde kullanmadan önce temayı bir geliştirme ortamında veya [WordPress Playground](https://playground.wordpress.net/) üzerinden test etmeniz ve daha da geliştirmeniz önerilir. Tasarımcı/geliştirici, bu temanın kullanımından doğabilecek sorunlardan sorumlu tutulamaz. Sorumluluk kullanıcıya aittir.

---

## Özellikler

- **Duyarlı Dergi/Haber Düzeni** – Mobil uyumlu, ızgara tabanlı tasarım için Bootstrap 5.3 ile oluşturulmuştur.
- **6 Arşiv Düzeni** – Kategori arşivleri için liste, 2/3/4 sütunlu ızgara, dergi ve kaydırıcı düzenleri arasından seçim yapın.
- **Kategoriye Göre Düzen Seçimi** – Tek tek kategorilere farklı arşiv düzenleri atayın.
- **Özelleştirici Entegrasyonu** – WordPress Özelleştirici üzerinden sosyal medya bağlantıları, reklam alanları, renk seçenekleri ve karanlık mod geçişi.
- **Karanlık Mod Desteği** – Özelleştirici'den etkinleştirilebilen gelişmiş karanlık mod.
- **Hero Karşılama Alanı** – Öne çıkan içerikleri vurgulamak için özel bir hero bölümü.
- **Okuma İlerleme Çubuğu** – Kullanıcılar makalelerde kaydırdıkça bir ilerleme çubuğu görüntüler.
- **Breadcrumb Navigasyonu** – SEO dostu breadcrumb izleri.
- **JSON-LD Yapılandırılmış Veri** – Daha iyi arama motoru görünürlüğü için yerleşik şema işaretlemesi.
- **Çeviriye Hazır** – Bir `.pot` şablon dosyası (`languages/styleumax.pot`) içerir ve `styleumax` metin alan adını kullanır.
- **W3C Uyumlu HTML5 Çıktısı** – Temiz, standartlara uygun işaretleme.
- **Akışkan Tipografi Ölçeği** – Görüntü alanına uyum sağlayan duyarlı yazı tipi boyutları.
- **Cam Navigasyon** – Modern, yarı saydam bir gezinme çubuğu.
- **Katmanlı Gölgeler ve İyileştirilmiş Karanlık Mod** – Hem açık hem de karanlık temalarda gelişmiş görsel derinlik.
- **Tam Bootstrap Marka Rengi Köprüsü** – Özelleştirici renkleri Bootstrap yardımcı sınıflarına (`.btn-primary`, `.text-primary` vb.) uzanır.

---

## Gereksinimler

| Gereksinim | Minimum | Önerilen |
|-------------|---------|----------|
| WordPress   | 6.5     | 6.8+     |
| PHP         | 8.0     | 8.1+     |
| Tarayıcı    | Modern  | Modern   |

---

## Kurulum

1. **Temayı indirin** – Depoyu klonlayın veya ZIP dosyası olarak indirin.
2. **WordPress'e yükleyin** –
   - **Görünüm → Temalar → Yeni Ekle → Tema Yükle** bölümüne gidin.
   - ZIP dosyasını seçin ve **Şimdi Yükle**'ye tıklayın.
3. **Etkinleştirin** – Kurulumdan sonra **Etkinleştir**'e tıklayın.
4. **Yapılandırın** – Sosyal bağlantılar, renkler, düzenler ve diğer seçenekleri ayarlamak için **Görünüm → Özelleştir** bölümüne gidin.

> **Geliştirme İpucu:** Yerel geliştirme için, tam bir WordPress kurulumu olmadan temayı test etmek üzere [WordPress Playground](https://playground.wordpress.net/) kullanabilirsiniz.

---

## Kullanım

### Arşiv Düzenleri

Styleumax, genel olarak veya kategori başına atanabilen altı arşiv düzeni sunar:

- `archive-default.php` – Standart liste düzeni.
- `archive-grid.php` – Izgara düzeni (2, 3 veya 4 sütun).
- `archive-mag.php` – Dergi tarzı düzen.
- `archive-slider.php` – Kaydırıcı tabanlı düzen.

Düzen seçimi `inc/category-layouts.php` aracılığıyla yapılır ve Özelleştirici'de yapılandırılabilir.

### Özelleştirici Seçenekleri

Özelleştirici (`inc/customizer.php`) aşağıdaki ayarları sunar:

- **Sosyal Medya Bağlantıları** – Sosyal profilleriniz için URL'ler ekleyin.
- **Reklam Alanları** – Önceden tanımlanmış widget alanlarına reklam kodu ekleyin.
- **Renk Şeması** – Varsayılan ana rengi (`#c8102e`) ve diğer palet değişkenlerini geçersiz kılın.
- **Karanlık Mod** – Karanlık mod geçişini etkinleştirin veya devre dışı bırakın.
- **Hero Bölümü** – Hero karşılama alanını yapılandırın.

### Widget'lar

Tema, özel widget'lar (`inc/widgets.php`) içerir ve altbilgi widget alanlarını destekler. Widget'ları **Görünüm → Widget'lar** üzerinden ekleyebilirsiniz.

### Sayfa Şablonları

- `templates/fullwidth.php` – Kenar çubuğu olmayan tam genişlikte sayfa şablonu.

### Şablon Parçaları

Yeniden kullanılabilir şablon bileşenleri `template-parts/` dizininde bulunur:

- `content.php`, `content-mini.php`, `content-none.php`
- `slider.php`
- `archive-*.php` varyantları
- Reklam parçaları için `ads/` dizini

---

## Tema Yapısı

---

## Özelleştirme

### Alt Tema

Kapsamlı değişiklikler için bir alt tema oluşturmanız önerilir. Üst tema standart WordPress kancalarını ve şablon hiyerarşisini kullanır, bu da alt tema geliştirmeyi kolaylaştırır.

### CSS Değişkenleri

Tüm renkler CSS özel özellikleri tarafından yönetilir (`style.css` içinde `:root` altında tanımlanmıştır). Tüm paleti değiştirmek için bu değişkenleri bir alt temada veya Özelleştirici üzerinden geçersiz kılabilirsiniz.

### Çeviri

Temayı çevirmek için:

1. Sağlanan `languages/styleumax.pot` dosyasını kullanın.
2. Diliniz için bir `.po` dosyası oluşturun (ör. `tr_TR.po`).
3. `.mo` dosyasına derleyin ve `languages/` dizinine yerleştirin.
4. WordPress çeviriyi otomatik olarak yükleyecektir.

---

## Emeği Geçenler

- **Tema Yazarı:** Styleumax
- **Yazar URI:** https://github.com/styleumax
- **Bootstrap:** [Bootstrap 5.3](https://getbootstrap.com/)
- **Font Awesome:** [Font Awesome 7](https://fontawesome.com/)
- **Inter Font:** [Inter by Rasmus Andersson](https://rsms.me/inter/)
- **Simgeler ve Varlıklar:** Paketlenmiş kaynaklar için `assets/` dizinine bakın.

---

## Lisans

Bu tema **GNU Genel Kamu Lisansı v2 veya sonrası** altında lisanslanmıştır.

Tam lisans metni için [LICENSE](LICENSE) dosyasına bakın.

---

## Değişiklik Günlüğü

### 1.1.1
- İlk genel sürüm.
- 6 arşiv düzeni, kategoriye göre düzen seçimi, Özelleştirici seçenekleri, karanlık mod, hero bölümü, okuma ilerleme çubuğu, breadcrumb'lar, JSON-LD şeması ve çeviriye hazır dizeler eklendi.

---

## Katkıda Bulunma

Katkılar memnuniyetle karşılanır! Lütfen [GitHub deposunda](https://github.com/umablognot/styleumaxwptema) bir sorun açın veya bir çekme isteği gönderin.

---

**Styleumax** – Dergiler, haber siteleri ve bloglar için modern, esnek bir WordPress teması.

# Uyarı
bu tema insan ve yapay zeka araçlarıyla ilk sürümüdür.styleumax wordpress temasını geliştirme ortamında veya <a href="https://playground.wordpress.net/" target="_blank" title="WordPress Playground!">WordPress Playground!</a> test ediniz ve geliştiriniz. bu temdan kaynaklı sorunlardan tasarlayan sorumlu tutulamaz. sorumluluk kullanıcıya aittir.
