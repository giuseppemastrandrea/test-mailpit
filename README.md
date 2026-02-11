# Test Mailpit - Ambiente di Sviluppo PHP

## Cos'è Mailpit?

**Mailpit** è uno strumento per testare l'invio di email in ambiente di sviluppo locale. Funziona come un "server email finto" che:
- **Cattura** tutte le email inviate dalla tua applicazione
- **Non invia** realmente le email ai destinatari
- Fornisce un'**interfaccia web** per visualizzare e ispezionare le email catturate

È perfetto per lo sviluppo perché evita l'invio accidentale di email di test a indirizzi reali.

## Come funziona in questo progetto

Questo repository utilizza Docker Compose per orchestrare tre container:

### 1. **Container PHP con Apache** (`app`)
- Esegue l'applicazione PHP su `http://localhost:8090`
- Configurato per inviare email tramite **msmtp** (client SMTP leggero)
- Le email vengono automaticamente dirottate verso Mailpit
- Include l'estensione MySQL per la connessione al database

### 2. **Container Mailpit** (`mailpit`)
- Cattura tutte le email inviate dall'applicazione PHP
- **Interfaccia web**: [http://localhost:8025](http://localhost:8025)
- **SMTP interno**: porta 1025 (accessibile solo tra container)

### 3. **Container MySQL** (`mysql`)
- Database MySQL 8.0 disponibile sulla porta `3307`
- Configurato tramite variabili d'ambiente nel file `.env`

## Configurazione

### File `.env` richiesto

Crea un file `.env` nella root del progetto con le seguenti variabili:

```env
# Database
DB_HOST=mysql
DB_NAME=test_db
DB_USER=user
DB_PASSWORD=password
DB_ROOT_PASSWORD=root_password

# SMTP (opzionale, già configurato via msmtp)
SMTP_HOST=mailpit
SMTP_PORT=1025
```

### Configurazione PHP

- **php.ini**: Configura il sendmail_path per usare msmtp
- **Dockerfile**: Installa msmtp e lo configura per puntare a Mailpit sulla porta 1025

## Come usare

### 1. Avviare l'ambiente

```bash
docker compose up --build -d
```

### 2. Testare l'applicazione

Visita [http://localhost:8090](http://localhost:8090) per:
- Verificare la configurazione PHP
- Testare la connessione al database MySQL
- Inviare una email di test

### 3. Visualizzare le email

Apri [http://localhost:8025](http://localhost:8025) per vedere l'interfaccia di Mailpit dove appariranno tutte le email catturate.

### 4. Fermare l'ambiente

```bash
docker compose down
```

Per rimuovere anche i volumi (database):

```bash
docker compose down -v
```

## Porte esposte

| Servizio | Porta Host | Porta Container | Descrizione |
|----------|------------|-----------------|-------------|
| PHP/Apache | 8090 | 80 | Applicazione web |
| Mailpit Web | 8025 | 8025 | Interfaccia web Mailpit |
| Mailpit SMTP | 1025 | 1025 | Server SMTP (interno) |
| MySQL | 3307 | 3306 | Database MySQL |

## File del progetto

- **docker-compose.yml**: Definisce i tre servizi (PHP, Mailpit, MySQL)
- **Dockerfile**: Costruisce l'immagine PHP con Apache, msmtp e mysqli
- **index.php**: Script di test per verificare configurazione e invio email
- **php.ini**: Configurazione PHP personalizzata
- **.env**: Variabili d'ambiente (non tracciato da Git)

## Vantaggi

✅ Ambiente isolato con Docker  
✅ Nessun rischio di inviare email reali durante i test  
✅ Interfaccia visuale per ispezionare email (header, body, allegati)  
✅ Configurazione pronta all'uso con MySQL  
✅ Facile da avviare e fermare
