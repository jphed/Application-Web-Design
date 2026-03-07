# Sistema de Gestión de Torneos de Fútbol

Este proyecto implementa un sistema básico de gestión de torneos de fútbol utilizando Laravel 11 y MySQL.

## Estructura de la Base de Datos

### Tablas Creadas

#### 1. `teams`
- **id**: Identificador único
- **name**: Nombre del equipo
- **city**: Ciudad del equipo
- **founded_year**: Año de fundación
- **timestamps**: Campos created_at y updated_at

#### 2. `players`
- **id**: Identificador único
- **team_id**: Llave foránea a teams (con cascade delete)
- **name**: Nombre del jugador
- **position**: Posición en el campo (Goalkeeper, Defender, Midfielder, Forward)
- **jersey_number**: Número de camiseta (único por equipo)
- **age**: Edad del jugador
- **timestamps**: Campos created_at y updated_at

#### 3. `matches`
- **id**: Identificador único
- **home_team_id**: Llave foránea a teams (equipo local)
- **away_team_id**: Llave foránea a teams (equipo visitante)
- **home_score**: Goles del equipo local
- **away_score**: Goles del equipo visitante
- **match_date**: Fecha y hora del partido
- **timestamps**: Campos created_at y updated_at

## Decisiones de Diseño

### 1. Llaves Foráneas y Relaciones
- **home_team_id** y **away_team_id** referencian la misma tabla `teams` sin duplicar información
- Se implementó una restricción CHECK a nivel de base de datos para evitar que un equipo juegue contra sí mismo
- Todas las relaciones tienen `onDelete('cascade')` para mantener la integridad referencial

### 2. Modelo FootballMatch
- Se utilizó el nombre `FootballMatch` en lugar de `Match` porque `match` es una palabra reservada en PHP
- Se especificó explícitamente el nombre de la tabla `matches` en el modelo

### 3. Números de Camiseta
- Los números de camiseta son únicos por equipo (no globalmente)
- Esta lógica se maneja en el seeder para evitar conflictos durante la generación de datos

### 4. Generación de Datos
- **Equipos**: 8 equipos con nombres realistas de fútbol
- **Jugadores**: 15-22 jugadores por equipo (rango realista)
- **Partidos**: 20 partidos con marcadores entre 0-5 goles
- **Fechas**: Partidos distribuidos en un rango de ±6 meses desde la fecha actual

## Modelos y Relaciones Eloquent

### Team
```php
- hasMany(Player::class)           // Un equipo tiene muchos jugadores
- hasMany(FootballMatch::class, 'home_team_id')  // Partidos como local
- hasMany(FootballMatch::class, 'away_team_id')  // Partidos como visitante
```

### Player
```php
- belongsTo(Team::class)           // Un jugador pertenece a un equipo
```

### FootballMatch
```php
- belongsTo(Team::class, 'home_team_id')  // Equipo local
- belongsTo(Team::class, 'away_team_id')  // Equipo visitante
```

## Factories Implementadas

### TeamFactory
- Genera nombres de equipos realistas
- Ciudades correspondientes a los equipos
- Años de fundación entre 1900-2010

### PlayerFactory
- Nombres realistas combinando first y last names
- Posiciones: Goalkeeper, Defender, Midfielder, Forward
- Edades entre 18-38 años (rango profesional)

### FootballMatchFactory
- Evita que un equipo juegue contra sí mismo
- Marcadores realistas entre 0-5 goles
- Fechas coherentes en un rango de ±6 meses

## Instalación y Uso

### Prerrequisitos
- PHP 8.2+
- MySQL 8.0+
- Composer
- XAMPP (o similar) para servidor MySQL

### Configuración
1. Asegúrate de que XAMPP esté ejecutando MySQL
2. Crea la base de datos `futbol` en MySQL
3. El archivo `.env` ya está configurado para usar:
   - Base de datos: `futbol`
   - Usuario: `root`
   - Contraseña: (vacía)

### Ejecutar el Sistema
```bash
# Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# Verificar los datos generados
php artisan tinker
```

### Verificación en Tinker
```php
# Ver equipos
Team::count()  // Debe mostrar 8
Team::all()

# Ver jugadores
Player::count()  // Debe mostrar entre 120-176
Player::with('team')->get()

# Ver partidos
FootballMatch::count()  // Debe mostrar 20
FootballMatch::with(['homeTeam', 'awayTeam'])->get()
```

## Características de Seguridad y Validación

1. **Restricción CHECK**: Evita que un equipo juegue contra sí mismo a nivel de base de datos
2. **Llaves Foráneas**: Mantiene la integridad referencial con cascade delete
3. **Validación en Factory**: La lógica de negocio evita partidos inválidos durante la generación

## Datos Generados

El sistema genera automáticamente:
- **8 equipos** con nombres reconocidos mundialmente
- **141 jugadores** (promedio ~17.6 por equipo)
- **20 partidos** con resultados realistas

Todos los datos mantienen coherencia relacional y cumplen con las restricciones definidas.
