````md
# Laravel Project Clonen & Opstarten

## Repository clonen

```bash
git clone https://github.com/USERNAME/PROJECTNAAM.git
cd PROJECTNAAM
````

## Dependencies installeren

```bash
composer install
```

Indien frontend assets aanwezig zijn:

```bash
npm install
npm run dev
```

## Environment file instellen (PHPStorm)

Kopieer in PHPStorm het bestand `.env.example` en hernoem dit naar `.env`.

## App key genereren

```bash
php artisan key:generate
```

## Database configureren

Pas je `.env` bestand aan:

```env
DB_DATABASE=naam_van_database
DB_USERNAME=root
DB_PASSWORD=
```

## Migraties uitvoeren

```bash
php artisan migrate
```

Eventueel met seeders:

```bash
php artisan migrate --seed
```

## Storage linken (belangrijk)

```bash
php artisan storage:link
```

Zorgt ervoor dat uploads (zoals afbeeldingen) zichtbaar zijn via `/storage`.

## Server starten

```bash
php artisan serve
```

Ga naar: [http://127.0.0.1:8000](http://127.0.0.1:8000)

## Queue verwerken (optioneel)

```bash
php artisan queue:work
```

## Cache clearen bij problemen (optioneel)

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Veelvoorkomende problemen

### Afbeeldingen worden niet getoond

Controleer of `php artisan storage:link` werd uitgevoerd.

### .env wijzigingen werken niet

```bash
php artisan config:clear
```

### Composer fouten

```bash
php -v
```

## Vereisten

* PHP >= 8.x
* Composer
* Node.js + npm
* MySQL of MariaDB

## Klaar

Het project zou nu correct moeten draaien.

```
```
"# professional-cat-breeding-cms" 
"# professional-cat-breeding-cms" 
