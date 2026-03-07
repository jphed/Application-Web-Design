# API de Torneo de Fútbol

Esta documentación describe todos los endpoints disponibles para gestionar el torneo de fútbol.

## Base URL
```
http://localhost:8000/api
```

## Endpoints

### 🏆 EQUIPOS (TEAMS)

#### Obtener todos los equipos
```
GET /api/teams
```
**Respuesta:**
```json
[
  {
    "id": 1,
    "name": "Real Madrid",
    "city": "Madrid",
    "founded_year": 1902,
    "created_at": "2026-03-04T22:23:08.000000Z",
    "updated_at": "2026-03-04T22:23:08.000000Z",
    "players": [...]
  }
]
```

#### Obtener un equipo específico
```
GET /api/teams/{id}
```
**Respuesta:** Incluye jugadores y partidos del equipo

#### Obtener jugadores de un equipo
```
GET /api/teams/{id}/players
```

#### Obtener estadísticas de un equipo
```
GET /api/teams/{id}/statistics
```
**Respuesta:**
```json
{
  "team": {...},
  "total_matches": 5,
  "wins": 3,
  "losses": 1,
  "draws": 1,
  "goals_for": 8,
  "goals_against": 4,
  "goal_difference": 4,
  "points": 10,
  "players_count": 19
}
```

#### Crear un equipo
```
POST /api/teams
```
**Body:**
```json
{
  "name": "Nuevo Equipo",
  "city": "Ciudad",
  "founded_year": 2020
}
```

#### Actualizar un equipo
```
PUT /api/teams/{id}
```
**Body:** Mismo que para crear

#### Eliminar un equipo
```
DELETE /api/teams/{id}
```

---

### ⚽ JUGADORES (PLAYERS)

#### Obtener todos los jugadores
```
GET /api/players
```

#### Obtener un jugador específico
```
GET /api/players/{id}
```

#### Crear un jugador
```
POST /api/players
```
**Body:**
```json
{
  "team_id": 1,
  "name": "Juan Pérez",
  "position": "Forward",
  "jersey_number": 10,
  "age": 25
}
```
**Posiciones válidas:** `Goalkeeper`, `Defender`, `Midfielder`, `Forward`

#### Actualizar un jugador
```
PUT /api/players/{id}
```

#### Eliminar un jugador
```
DELETE /api/players/{id}
```

---

### 🥅 PARTIDOS (MATCHES)

#### Obtener todos los partidos
```
GET /api/matches
```
**Respuesta:** Ordenados por fecha descendente

#### Obtener un partido específico
```
GET /api/matches/{id}
```

#### Crear un partido
```
POST /api/matches
```
**Body:**
```json
{
  "home_team_id": 1,
  "away_team_id": 2,
  "home_score": 2,
  "away_score": 1,
  "match_date": "2026-03-05T20:00:00"
}
```
**Validaciones:**
- Los equipos deben ser diferentes
- La fecha debe ser hoy o futura
- Los goles deben ser >= 0

#### Actualizar un partido
```
PUT /api/matches/{id}
```

#### Eliminar un partido
```
DELETE /api/matches/{id}
```

---

### 📊 ESTADÍSTICAS (STATISTICS)

#### Obtener estadísticas generales del torneo
```
GET /api/statistics
```
**Respuesta:**
```json
{
  "total_teams": 8,
  "total_players": 141,
  "total_matches": 20,
  "teams_with_most_players": [...],
  "recent_matches": [...]
}
```

---

## Códigos de Respuesta

- `200` - OK
- `201` - Created
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Ejemplos de Uso

### 1. Listar todos los equipos con sus jugadores
```bash
curl http://localhost:8000/api/teams
```

### 2. Crear un nuevo jugador
```bash
curl -X POST http://localhost:8000/api/players \
  -H "Content-Type: application/json" \
  -d '{
    "team_id": 1,
    "name": "Carlos Rodríguez",
    "position": "Midfielder",
    "jersey_number": 8,
    "age": 24
  }'
```

### 3. Ver estadísticas de un equipo
```bash
curl http://localhost:8000/api/teams/1/statistics
```

### 4. Crear un partido
```bash
curl -X POST http://localhost:8000/api/matches \
  -H "Content-Type: application/json" \
  -d '{
    "home_team_id": 1,
    "away_team_id": 2,
    "home_score": 0,
    "away_score": 0,
    "match_date": "2026-03-10T19:00:00"
  }'
```

## Validaciones Importantes

### Equipos
- Nombre único en todo el sistema
- Año de fundación entre 1800 y año actual

### Jugadores
- Número de camiseta único por equipo
- Edad entre 16 y 50 años
- Posición debe ser una de las válidas

### Partidos
- Un equipo no puede jugar contra sí mismo
- Fecha debe ser hoy o futura
- Goles deben ser números no negativos

## Relaciones entre Modelos

- **Team** → hasMany **Players**
- **Team** → hasMany **homeMatches** (como local)
- **Team** → hasMany **awayMatches** (como visitante)
- **Player** → belongsTo **Team**
- **FootballMatch** → belongsTo **homeTeam**
- **FootballMatch** → belongsTo **awayTeam**
