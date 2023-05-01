# vue-laravel-blog

Projede frontend tarafında vue, vite ve vuetify backend tarafında ise laravel mysql kullanılmıştır.

Demo ``https://blog.muhammetkoklu.com/``

# Projeyi Çalıştırma

# Frontend

Frontendi çalıştırmak için aşağıdaki üç kodu client klasörü içinde terminalde çalıştırmalısınız. vuetify'da bir sorun olduğu için npm install komutunda --force flag'ini kullanın.

```bash
npm install --force
npm run build
npm run preview
```

# Backend

Backendi çalıştırmak için öncelikle server klasörü içerisinde .env dosyası oluşturmamız gerekmektedir .env.example dosyasını yeniden adlandırarak kullanabilirsiniz. Ardından aşağıdaki üç kodu server klasörü içinde terminalde çalıştırmalısınız ve ayrıca default portunda mysql çalışıyor olmalıdır.

```bash
composer install
php artisan migrate
php artisan serve
```

Frontend ve backendi çalıştırdıktan sonra frontendin çalıştığı adrese giderek uygulamayı kullanabilirsiniz.

