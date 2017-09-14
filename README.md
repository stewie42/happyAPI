# happyAPI

Diese API dient zu einer echtzeitbasierten Messung der affektiven Mitarbeiterzufriedenheit und wurde Rahmen einer Masterarbeit an der TU Dresden entwickelt.

## Getting Started

### Technische Voraussetzungen

Zur Ausführung der API wird das Containermanagement-Tool [Docker](https://www.docker.com) benötigt

### Installieren

Die Systemkomponenten bestehen aus einem nginx-Webserver, einem PHP-FPM-Server, sowie einer MySQL-Datenbank.
Um diese zu starten ist folgender Befehl im Hauptverzeichnis der API auszuführen:

```
docker-compose up -d
```

Der Aufruf der URL http://0.0.0.0:8084/api/v1 führt zu einer automatisch generierten interaktiven Dokumentation, welche einen Überblick über die implementierten Schnittstellen.

Hinweis: Wird der Host auf die Adresse http://0.0.0.0:8084/app_dev.php/api/v1 geändert wird die Dev-Umgebung aufgerufen.
Hierdurch werden Debugging-und Profiling-Möglichkeiten in die Oberfläche eingebunden.
Während der Entwicklung empfiehlt es sich diese URL zu nutzen, um keine Zwischenspeicherung zu verwenden. Änderungen Im Quellcode werden somit ohne Leeren des Caches sofort sichtbar.

### Zugriff auf die Datenbank

Über die URL http://0.0.0.0:3308 ist die Oberfläche von PhpMyAdmin erreichbar.
Mit folgenden Anmeldedaten erhält man Zugriff auf die Datenbank:
```
db: api_platform
name: api_platform
pw: api_platform
```

Während der Entwicklung empfiehlt es sich diese URL zu nutzen, um keine Zwischenspeicherung zu verwenden. Änderungen Im Quellcode werden somit ohne Leeren des Caches sofort sichtbar


### Anlegen eines Nutzers

Um einen Nutzer anzulegen ist die POST-Methode für die User-Ressource aufzurufen.
Im folgenden Beispiel wird dies mit dem Kommandozeilenprogramm cURL demonstriert.
Ebenso möglich ist die Verwendung der oben genannten Dokumentation oder von Programmen wie [Postman](https://www.getpostman.com)

```
curl -X POST \
  http://localhost:8084/api/v1/users \
  -H 'content-type: application/ld+json' \
  -d '{
    "email": "max.mustermann@tu-dresden.de",
    "plainPassword": "passwort",
    "familyName": "Mustermann",
    "username": "mmuster",
    "givenName": "Max",
    "gender": "m",
    "birthDate": null,
    "telephone": null,
    "jobTitle": "SE",
    "department": "/api/v1/departments/1"
}'

```

Das Passwort wird automatisch verschlüsselt bevor es in der Datenbank gespeichert wird.
Hinweis: Die Abteilung muss bereits in der Datenbank vorhanden sein, bevor ein Nutzer angelegt werden kann.
Da keine Abteilungen über die REST-Schnittstellen zu ändern sein sollen, hat das Hinzufügen über einen INSERT-Befehl auf der MySQL-Datenbank zu erfolgen (es empfiehlt sich die Verwendung der oben genannten PhpMyAdmin-Oberfläche).
### Authentifizierung des Nutzers

Zur authentifizierten Kommunikation wird ein Token benötigt.
Dies erhält der Nutzer durch die Übergabe seiner Anmeldeinformation an den Pfad /login_check:

```
curl -X POST \
  http://localhost:8084/login_check \
  -H 'content-type: application/x-www-form-urlencoded' \
  -d '_username=max.mustermann%40tu-dresden.de&_password=passwort'
```

Die Antwort sieht bei einer erfolgreichen Überprüfung wie folgt aus:
```
{
"token": "eyJhbGciOiJSUzI1NiJ9.eyJyb2xlcyI6WyJST0xFX1VTRVIiXSwidXNlcm5hbWUiOiJtbXVzdGVyIiwiaWF0IjoxNTA1NDIwMDg3LCJleHAiOjE1MDU0MjM2ODd9.j3kcVkjOn4eqlzisgf3vIr34DK0_JtjtsbuiJgZ6aa_IiWXEDnFWERNIMVl5269tLpdsFYRIpTqDgw3qIBtyEEWFwPq0l7CtT2r7LFx7Ev-uI9lR9JkObDvH4pRmv4c437PBVhXEXQbyIpVWPfJC-L-R8xTSv11KEQa0JmmWKOBihzcvNHK59Qfy_6ElG5G2gV_1cBwtEzrNET3KBnCYedK0wlvXC6VzwDtkkU2JRUpQFiEyNoAahp1T52XOEwYpxXfo2WeOVjq-vDUe3LVVeJmu6yMCGnkIUTMkhNNoO5y4Sev0eojwO_dmghtPWx5gxdoXOU9T5p8Ul8MjKCNYVYIVhVVK2kKbPc95fMtbOC9Ne4uBd8dwr3dq48NZSELmIY3_ojVNbGbBE15t1xHZaxnY8h3NO66ywaPt4Dn-Kb6AJovmo7HNY9aDrM6JOtWUadMww6xxYIvWhr4rFPrf1cRtldKc8WnGFAym3JgDUS2Fgm49EOJzz_XI_sBfLLRHVUOw1j3MHWtmgByHRz_Z1WhgDPOoYDozrX4FU_FEHcFmfm_DIegXgLKTvbfbiFH39R97Ot1aSBTdJOMa8Dy7goP6KUY7zV7WFKUa8qR5wOs2AAKXsi_KedYickrZtL-u4nYHEBTA9PHEMX_2cm9Hw7wqR5qJa5LeXeqWvIPw8qw",
    "data": {
        "userId": 1,
        "name": "mmuster"
    }
}
```

### Anlegen einer Bewertung

Das Anlegen einer Bewertung erfolgt mittels POSTauf dem Pfad /api/v1/moods.
In der jetzigen Version muss leider noch der Nutzer mit im Nachrichteninhalt übertragen werden.
Ziel ist es die Nutzer-ID aus dem übergebenen Token zu erhalten.

```
curl -X POST \
  'http://localhost:8084/api/v1/moods?XDEBUG_SESSION_START=PHPSTORM' \
  -d '{
  "mood": 4,
  "explanation": "Kickerturnier in der Küche",
  "user": "/api/v1/users/1"
}';
```

### Abrufen einer Bewertung mit Token

Zum Abrufen einer Bewertung muss der Token im Authorization-Header zusammen mit dem Schlüsselwort Bearer übergeben werden.

```
curl -X GET \
  http://localhost:8084/api/v1/moods/3 \
  -H 'authorization: Bearer eyJhbGciOiJSUzI1NiJ9.eyJyb2xlcyI6WyJST0xFX1VTRVIiXSwidXNlcm5hbWUiOiJtbXVzdGVyIiwiaWF0IjoxNTA1NDIyODI2LCJleHAiOjE1MDU0MjY0MjZ9.TxB1y8JtZho6KWCbftWxpTfaU1T1LJeuqkQN3hvYA0R1u2DPtPwxSUZHNioeYdNlaW9gUdffUWukAqboj__wBopPQdEBYo5aurt_pZ1-c-gvi-h39c8aX-c8bl3MXGCpzI573T0jMLwPzKB3l6sGB5qDMAxkkTB2PNbEq0E1_Qf9VqU-vyzM2YGfjTfKtffkDFSu04aFC1hqpcniPRz1J9vouJMuWYKuPq7ePLcsigK0LYjnT64_VT9Qa5czzC2c1Lpde-39dTwnAhDhn5gFOwE-wA5PwWsCoaVYRX1u5Oc5L5o3LTFOWkv-WnfzEAzWz_rwwydjNyKhGR1Mc5NRnSwtL6mLiN15CuraskfZErnSlCRy9oWYabCcm3ByXuhsE5wKRiesMjk5geXG23hU5tcuAjxd4zSZPci_ZYZIrs9Zh8HebcEYhSDzFQ7-ypv3Mzp0Ssb2Z2haxbYW-nyPPRBCNR5rd13Kw11_n3WoHONZaA5tp4bQEfVBwmRBJQDlUSawTdp9Sxim1D1R8cNVyO7InRxhno7T-YR1SS6801R7Ol_BSVicGPHG5W9z-E8RHW-k0T0hxt5FPzxgDSPL7JeKLD3fm7TrjbP6YOra3CcbExyfoHZDul1CYl0VHNYJMS5Rit9H37Lwc957d0LELYILnpRSL75Ud6Suubp_mAQ' \
  -H 'content-type: application/json';
```

## Javascript-Bibliothek & Webanwendung

Die in der Arbeit erwähnte Javascript-Bibliothek befindet sich im Ordner artefacts/client/js/moodAPI.js

Um den Client-Prototypen zu nutzen ist die Datei client.html im Order artefacts/client.html rauf einem (lokalen) Server zu öffnen.
Anschließend öffnet sich automatisch das Login-Fenster.
Zum Einloggen kann der oben angelegte Nutzer verwendet werden:

```
Name: max.mustermann@tu-dresden.de
Passwort: passwort
```

Getestet wurde mit Chrome (Version 60.0.3112.113). Beim Aufruf innerhalb eines lokalen Servers kam es zu Schwierigkeiten mit dem Access-Control-Allow-Origin-Header, sodass Cross Origin Resource Sharing (CORS) mittels Plugin aktiviert werden musste.

## Authors

* **Steven von Roden**
