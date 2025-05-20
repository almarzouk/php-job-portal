# 🌟 Jobportal – Karriere leicht gemacht

## 📘 Projektbeschreibung

Diese Plattform ist eine moderne Jobbörse, die Arbeitgeber und Arbeitssuchende zusammenbringt. Sie ermöglicht es Unternehmen, Stellenangebote zu veröffentlichen, während Bewerber sich registrieren, nach Jobs suchen, Bewerbungen einreichen und den Fortschritt verfolgen können – alles über ein intuitives Interface.

---

## 🔧 Technologien

| Bereich         | Technologie                        |
|----------------|------------------------------------|
| Frontend       | HTML, CSS, Bootstrap, jQuery       |
| Backend        | PHP (OOP + MVC)                    |
| Datenbank      | MySQL                              |
| AJAX           | Für asynchrone Kommunikation       |
| Features       | Emoji Picker, TinyMCE, Notifications |

---

## 📁 Projektstruktur

```
job-portal/
├── app/
│   ├── controllers/
│   ├── core/
│   ├── models/
│   └── views/
│       ├── admin/
│       ├── user/
│       └── public/
├── public/
│   ├── css/
│   ├── js/
│   ├── uploads/
│   └── index.php
├── config/
├── .htaccess
├── README.md
└── database.sql
```

---

## 👥 Benutzerrollen

| Rolle      | Beschreibung                                                             |
|------------|--------------------------------------------------------------------------|
| Bewerber   | Registrierung, Bewerbung, Favoriten speichern, Nachrichten, Benachrichtigungen |
| Administrator | Verwaltung von Jobs, Nutzern, Bewerbungen, Nachrichten, Systemsteuerung   |

---

## 🛠️ Hauptfunktionen

### ✅ Authentifizierung
- Login/Registrierung mit Session-Sicherheit

### 🧑‍💼 Benutzerbereich
- Profil aktualisieren + Lebenslauf hochladen
- Bewerbungen einsehen
- Nachrichten mit Admin (in Echtzeit)

### 🧑‍💻 Admin-Bereich
- Stellenanzeigen erstellen/bearbeiten/löschen
- Nutzer verwalten (inkl. Beförderung zu Admin)
- Bewerbungen einsehen und Status ändern
- Nachrichten mit Bewerbern (inkl. "Gelesen"-Status)

### 📄 Bewerbungen
- Bewerber lädt Lebenslauf hoch
- Admin akzeptiert/ablehnt
- Automatische Benachrichtigung über Status

### 💬 Nachrichten
- Echtzeit-Kommunikation via AJAX
- Lesestatus und Emoji-Support
- "Nachricht gelesen"-Funktion

### 🔔 Benachrichtigungen
- Bewerbungsstatus
- Echtzeit-Updates
- Möglichkeit "alle als gelesen markieren"

---

## 🔗 Wichtige Links

| Route | Beschreibung |
|-------|--------------|
| `/public/` | Startseite |
| `/public/auth/login` | Login |
| `/public/auth/register` | Registrierung |
| `/public/userprofile` | Nutzerprofil |
| `/public/admin/dashboard` | Admin Dashboard |
| `/public/messages` | Nachrichtenmodul |

---

## 🚀 Projekt auf GitHub veröffentlichen

```bash
git init
git remote add origin https://github.com/DEIN-NUTZERNAME/job-portal.git
git add .
git commit -m "Initialer Commit"
git push -u origin main
```

---

## 📌 Hinweise für Entwickler

- MVC-Struktur
- Routing über `core/Router.php`
- `config/database.php` enthält Datenbankverbindung
- `.htaccess` aktiv für URL-Rewriting

---

## 📅 Erstellt am

20.05.2025
