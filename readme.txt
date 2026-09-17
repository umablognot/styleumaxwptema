=== Styleumax ===
Contributors: styleumax
Requires at least: 6.5
Tested up to: 6.8
Requires PHP: 8.0
Stable tag: 1.1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tags: blog, news, two-columns, three-columns, four-columns, right-sidebar, grid-layout, custom-colors, custom-logo, custom-menu, featured-images, footer-widgets, sticky-post, threaded-comments, translation-ready, editor-style, wide-blocks

Styleumax — sıfırdan kodlanmış, modern ve güvenli dergi/haber teması. Stylebook'un ruhu, günümüz teknolojisiyle.

== Description ==

Styleumax, eski Stylebook dergi temasının tamamen sıfırdan, modern standartlarla yeniden yazılmış halidir. Eski temadaki tüm temel özellikler korunmuş, güvenli ve W3C uyumlu biçimde yeniden inşa edilmiştir.

= Özellikler =

* **6 arşiv düzeni**: Liste, 2/3/4 kolon ızgara, Dergi (büyük manşet), Slayt (carousel)
* **Kapsamlı tasarım sistemi v2**: Akışkan tipografi skalası (clamp), glass efektli yapışkan navigasyon, katmanlı gölgeler, marka renkli Bootstrap bileşenleri
* **Hero karşılama alanı** (anasayfada, Customizer'dan kapatılabilir)
* **Okuma ilerleme çubuğu** (tek yazıda, Customizer'dan kapatılabilir)
* **Kategori bazlı düzen seçimi**: Her kategoriye ayrı düzen atayın (term meta tabanlı)
* **PHP 8+**: strict_types, modern sözdizimi, null-safe kontroller
* **Bootstrap 5.3.8** (yerelde barındırılır, CDN bağımlılığı yok)
* **Font Awesome 7.3.1** (yerelde barındırılır)
* **Inter değişken font** (latin + latin-ext, Türkçe karakter desteği, yerelde barındırılır)
* **Koyu/Açık mod**: auto/light/dark + ziyaretçi düğmesi, localStorage ile hatırlanır
* **Customizer paneli**: Renkler, sosyal medya (11 ağ + RSS + e-posta adresi), düzenler, reklam alanları, alt bilgi
* **Reklam slotları**: header, sidebar üst/alt, arşiv üstü, yazı altı (Customizer veya template-parts/ads/*.php)
* **JSON-LD yapılandırılmış veri**: WebSite, NewsArticle, BreadcrumbList, Organization
* **OpenGraph + Twitter Card** meta (SEO eklentisi algılanırsa otomatik devre dışı kalır)
* **Alt başlık meta kutusu** (yazı düzenleme ekranında)
* **Breadcrumb navigasyonu**, okuma süresi, yazar kutusu, ilgili yazılar
* **Öne çıkan yazılar widget'ı** (kategori seçilebilir)
* **Yüzen kısayol çubuğu** (önceki/sonraki yazı, iletişim, anasayfa, arama)
* **Yukarı dön düğmesi**, sticky navigasyon
* **Güvenlik**: Tüm çıktılar esc_* ile kaçışlanır, tüm girişler sanitize edilir, nonce korumalı formlar, SVG yükleme yalnızca yöneticilere açık, isteğe bağlı XML-RPC/sürüm gizleme
* **Erişilebilirlik**: Skip link, aria etiketleri, semantik HTML5, odak halkaları, prefers-reduced-motion
* **Çeviri hazır**: styleumax text domain + styleumax.pot şablonu (arayüz metinleri Türkçe kaynaklıdır; başka diller için .po/.mo üretmeniz gerekir — aşağıdaki SSS'ye bakın)
* **W3C uyumlu HTML5** çıktısı

= Performans =

* Tüm varlıklar yerelde barındırılır (fontlar, CSS, JS) — GDPR dostu, harici istek yok
* filemtime tabanlı sürümleme (cache otomatik bozulur)
* Defer'li JS yüklemesi, lazy-load görseller
* Önceden kırpılmış 5 özel görsel boyutu

== Installation ==

1. Bu klasörü ZIP olarak paketleyin (klasör adı `styleumax` kalmalı).
2. WordPress yönetim panelinden **Görünüm > Temalar > Yeni ekle > Tema yükle** adımını izleyin.
3. ZIP dosyasını seçip yükleyin ve temayı etkinleştirin.
4. **Görünüm > Özelleştir** bölümünden renkleri, sosyal medya adreslerini, düzenleri ve reklam alanlarını ayarlayın.
5. **Görünüm > Menüler** bölümünden menü oluşturup "Ana menü" konumuna atayın (menü atanmasa bile site sorunsuz çalışır; navigasyonda otomatik "Anasayfa" bağlantısı gösterilir).
6. Kategori düzenleri için: **Yazılar > Kategoriler > Düzenle > Arşiv düzeni** alanını kullanın.

= Kurulum (Türkçe özet) =

Tema kurulduktan sonra hiçbir zorunlu ayar yoktur; varsayılan değerlerle çalışır. Kaydırıcı (slider) öne çıkan yazılardan beslenir: yazıları "öne çıkan" olarak işaretleyin veya hiç öne çıkan yazı yokken otomatik olarak en yeni yazıları kullanır.

== Frequently Asked Questions ==

= Reklam kodu nereye eklenir? =

İki yol: (1) Görünüm > Özelleştir > Styleumax Ayarları > Reklam alanları, (2) doğrudan `template-parts/ads/` klasöründeki boş dosyaları düzenleyin. Customizer değeri her zaman önceliklidir.

= Koyu mod nasıl çalışır? =

Özelleştirici > Genel > Renk modu'ndan "Açık", "Koyu" veya "Ziyaretçi tercihine göre" seçebilirsiniz. Tema düğmesiyle değiştirilen tercih, ziyaretçinin tarayıcısında saklanır.

= Neden Font Awesome 7'de bazı eski ikonlar farklı? =

Font Awesome 7, v6 sınıf adlarıyla geriye dönük uyumludur (fa-solid, fa-brands vb.). Tema içindeki tüm ikonlar zaten v7 uyumlu yazılmıştır.

= Neden "Bu web sitesinde kritik bir hata oluştu" hatası alıyorum? =

İki yaygın neden vardır:

1. **PHP sürümü:** Styleumax PHP 8.0 veya üstünü gerektirir ve 7.x sürümünde "kritik hata" üretir. 1.0.1'den itibaren bu durumda beyaz ekran yerine net bir uyarı ekranı ve yönetim paneli bildirimi gösterilir. Çözüm: barındırma panelinizden PHP sürümünü 8.0+ yapın.
2. **1.0.2 ve öncesinde menü atanmamışsa:** Ana menü konumuna hiç menü atanmamışsa tema 1.0.2 ve altı sürümlerde fatal TypeError üretirdi (fallback imzası WordPress çekirdeğinin geçtiği args dizisiyle uyumsuzdu). 1.1.0'da kalıcı olarak düzeltildi; menü atanmamış siteler artık sorunsuz çalışır. Bu hatayı yaşayanlar temayı 1.1.0'a güncellemelidir.

Hata devam ederse `wp-config.php` içinde `define( 'WP_DEBUG', true );` ve `define( 'WP_DEBUG_LOG', true );` tanımlayıp `wp-content/debug.log` dosyasındaki ilk hatayı kontrol edin.

= Temayı başka bir dilde (ör. İngilizce) kullanabilir miyim? =

Temanın kaynak metinleri Türkçe yazılmıştır ve kutudan çıktığı gibi yalnızca Türkçe çalışır; pakette hiçbir derlenmiş .po/.mo çevirisi bulunmaz. Başka bir dile çevirmek için `languages/styleumax.pot` şablonunu temel alarak Poedit veya Loco Translate eklentisiyle çevirinizi üretin ve `wp-content/languages/themes/styleumax-<yerel>.mo` (ör. `styleumax-en_US.mo`) olarak yükleyin. Paket yenilendiğinde çeviriniz korunur.

= Google Fonts kullanıyor mu? =

Hayır. Inter fontu tema içinde yerelde barındırılır (woff2, latin + latin-ext). Hem gizlilik hem hız için harici istek yapılmaz.

== Credits ==

* Bootstrap 5.3.8 — MIT License, (c) Bootstrap Authors
* Font Awesome Free 7.3.1 — Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License, (c) Fonticons Inc.
* Inter font — SIL Open Font License 1.1, (c) The Inter Project Authors

== Additional notes ==

* Şablon kapsamı: kategori/etiket/taksonomi/yazar/tarih arşivleri `archive.php`, tekil içerikler `single.php` / `page.php`, arama `search.php`, 404 `404.php` üzerinden sunulur. `singular.php` ve `style-rtl.css` (RTL) bilinçli olarak dahil değildir; ihtiyaç halinde child theme ile eklenebilir.
* Geliştirme: `functions.php` 8 modülü yükler (`inc/` klasörü). Child theme için tüm işlevler `styleumax_` önekiyle yazılmıştır ve `styleumax_*` filtreleri mevcuttur.
* Ad kodları Customizer'da yalnızca `edit_theme_options` yetkisi olan kullanıcılar tarafından kaydedilebilir.
