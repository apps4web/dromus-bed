# Deployen via Git

Eenmalige setup op de server, daarna is elke deploy één commando.

## Eenmalige setup op de server

### 1. Controleer git en composer

```bash
ssh GEBRUIKER@SERVER
git --version        # moet aanwezig zijn
composer --version   # zo niet: vraag je host, of installeer composer.phar lokaal
php -v               # moet >= 8.2 zijn
```

### 2. Geef de server (read-only) toegang tot de GitHub-repo

Maak op de server een SSH-key en voeg die als **deploy key** toe aan de repo:

```bash
ssh-keygen -t ed25519 -C "deploy dromus-bed" -f ~/.ssh/id_ed25519 -N ""
cat ~/.ssh/id_ed25519.pub
```

Plak de public key in GitHub: repo → Settings → Deploy keys → *Add deploy key*
(schrijfrechten zijn niet nodig).

### 3. Clone de repo naast de huidige FTP-site

Overschrijf de bestaande site niet meteen; clone ernaast en wissel daarna om:

```bash
cd ~
git clone git@github.com:apps4web/dromus-bed.git dromus-bed-git
cd dromus-bed-git
composer install --no-dev --optimize-autoloader
```

### 4. Neem de servereigen bestanden over uit de oude FTP-site

Deze staan bewust niet in git (`.gitignore`) en moeten je van de oude site kopiëren:

- `config/app_local.php` (database-gegevens, security salt)
- `config/.env` (als je die gebruikt)
- Geüploade bestanden (bijv. in `webroot/`), als die er zijn

Maak daarna `logs/` en `tmp/` schrijfbaar:

```bash
chmod -R u+rwX logs tmp
```

### 5. Wissel de documentroot om

Op deze hosting (Combell) is de documentroot vast `~/www`. Vervang die map
door een symlink naar de `webroot/` van de git-clone:

```bash
cd ~
mv www www-oud                      # oude FTP-site bewaren als backup
ln -s dromus-bed-git/webroot www
```

Test de site. Werkt alles, dan kan `www-oud` na een paar dagen weg.

## Deployen (elke keer)

Vanaf je eigen machine:

```bash
ssh GEBRUIKER@SERVER '~/dromus-bed-git/bin/deploy.sh'
```

Het script doet: `git pull` (hard reset naar `origin/main`) → `composer install
--no-dev` → `bin/cake migrations migrate` → cache legen.

Tip: zet er lokaal een alias voor in je PowerShell-profiel:

```powershell
function deploy-dromus { ssh GEBRUIKER@SERVER '~/dromus-bed-git/bin/deploy.sh' }
```

## Let op

- **Upload nooit meer via FTP** naar de git-map: het script doet een
  `git reset --hard`, dus handmatige wijzigingen op de server gaan verloren.
- Wijzigingen in `config/app_local.php` doe je rechtstreeks op de server;
  dat bestand wordt door git met rust gelaten.
- Het script deployt standaard `main`; een andere branch kan met
  `bin/deploy.sh naam-van-branch`.
