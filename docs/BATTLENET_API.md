# Battle.net API Reference

## Overview

This extension integrates with the Blizzard Battle.net API to provide:

- **Guild roster sync** — Automatically import guild members with class, race, level, and rank data
- **Character profiles** — Fetch individual character data including talents, titles, and achievements
- **Armory links** — Generate links to a character's Battle.net profile page
- **Character portraits** — Display character render images from Blizzard's CDN
- **Guild emblems** — Generate guild emblem images from API-provided emblem data
- **Realm status** — Query realm availability

## Current Implementation Status

### What Works (with valid API key)
- Guild member list retrieval
- Character profile retrieval
- Character portrait URLs
- Armory URL generation
- Guild emblem generation via GD library
- Response caching via phpBB cache

### What Needs Updating

The current API client was written for the **legacy Battle.net Community API** (circa 2016-2018). Blizzard has since made significant changes:

| Aspect | Current (Legacy) | Modern (Required) |
|--------|------------------|-------------------|
| **Base URL** | `https://{region}.api.battle.net/wow/` | `https://{region}.api.blizzard.com/` |
| **Auth method** | HMAC-SHA1 key signing | OAuth 2.0 Client Credentials |
| **Auth header** | `Authorization: BNET {key}:{signature}` | `Authorization: Bearer {access_token}` |
| **Key type** | Mashery API key + private key | OAuth Client ID + Client Secret |
| **Key portal** | dev.battle.net (discontinued) | [develop.battle.net](https://develop.battle.net/) |
| **Token endpoint** | N/A | `https://oauth.battle.net/token` |
| **Guild endpoint** | `/wow/guild/{realm}/{name}` | `/data/wow/guild/{realmSlug}/{guildNameSlug}` |
| **Character endpoint** | `/wow/character/{realm}/{name}` | `/profile/wow/character/{realmSlug}/{characterName}` |
| **Namespace header** | N/A | `Battlenet-Namespace: profile-{region}` or `static-{region}` |

### Migration Roadmap

The API modernization is planned but not yet implemented. The work involves:

1. **OAuth 2.0 token flow** — Implement client credentials grant to obtain a bearer token
2. **New base URLs** — Switch from `api.battle.net` to `api.blizzard.com`
3. **New endpoint paths** — Guild and character endpoints have changed
4. **Namespace headers** — Modern API requires `Battlenet-Namespace` header
5. **Slug-based lookups** — Realm and guild names must be lowercased and hyphenated
6. **Response format changes** — JSON response structure has changed

Until this migration is complete, the API integration may not function with Blizzard's current servers.

## API Architecture

### Class Hierarchy

```
curl (bbguild core)
  └── battlenet_resource (abstract base)
        ├── battlenet_guild
        ├── battlenet_character
        ├── battlenet_realm
        └── battlenet_achievement

battlenet (factory)
  └── creates guild/character/realm/achievement instances
```

### Request Flow

1. Caller creates `battlenet($type, $region, $apikey, $locale, $privkey, $ext_path, $cache)`
2. Factory instantiates the appropriate resource subclass
3. Resource's method (e.g. `getGuild()`) calls `consume($method, $params)`
4. `consume()` builds the request URL, checks cache, signs request, calls cURL
5. Response is JSON-decoded and cached for the configured TTL (default 3600s)

### Caching

All API responses are cached using phpBB's cache service:
- Cache key: base64-encoded full request URL
- Default TTL: 3600 seconds (1 hour)
- Cache is stored in phpBB's configured cache backend (file, APCu, Redis, etc.)

## API Endpoints Used

### Guild API

**Request:** `GET /wow/guild/{realm}/{name}?fields={fields}&locale={locale}&apikey={key}`

**Extra fields:** `members`, `achievements`, `news`

**Response structure (members):**
```json
{
  "name": "Guild Name",
  "realm": "Realm Name",
  "battlegroup": "Battlegroup",
  "level": 25,
  "side": 0,
  "achievementPoints": 1234,
  "emblem": {
    "icon": 126,
    "iconColor": "ff101517",
    "border": 0,
    "borderColor": "ff0f1415",
    "backgroundColor": "ffffffff"
  },
  "members": [
    {
      "character": {
        "name": "CharName",
        "realm": "Realm",
        "class": 1,
        "race": 1,
        "gender": 0,
        "level": 110,
        "achievementPoints": 5678,
        "thumbnail": "realm/12/34567890-avatar.jpg"
      },
      "rank": 0
    }
  ]
}
```

**Faction mapping:** `side: 0` = Alliance (bbGuild faction 1), `side: 1` = Horde (bbGuild faction 2)

### Character API

**Request:** `GET /wow/character/{realm}/{name}?fields={fields}&locale={locale}&apikey={key}`

**Extra fields:** `achievements`, `appearance`, `feed`, `guild`, `hunterPets`, `items`, `mounts`, `pets`, `petSlots`, `professions`, `progression`, `pvp`, `reputation`, `stats`, `talents`, `titles`

**Default fields requested by bbGuild:** `guild`, `titles`, `talents`

**Portrait URL format:** `http://{region}.battle.net/static-render/{region}/{thumbnail}`

**Armory URL format:** `http://{region}.battle.net/wow/en/character/{realm}/{name}/simple`

### Realm API

**Request:** `GET /wow/realm/status?realms={realm1,realm2}&locale={locale}&apikey={key}`

### Achievement API

**Request:** `GET /wow/achievement/{id}?locale={locale}&apikey={key}`

## Error Handling

The API returns standard HTTP status codes:

| Code | Meaning | bbGuild Behavior |
|------|---------|------------------|
| 200 | Success | Data processed normally |
| 403 | Forbidden | Marks armory as disabled, logs error |
| 404 | Not Found | Returns KO result |
| 500+ | Server Error | Returns KO result, logs error |

When an API call fails, bbGuild:
1. Sets `armoryresult = 'KO'` on the guild/player record
2. Logs the error to the bbGuild admin log
3. Continues operation without API data — manual management still works

## Configuration

### API Credentials

Set in ACP > bbGuild > Games > Edit World of Warcraft:

| Field | Description |
|-------|-------------|
| **API Key** | Your Blizzard API client ID |
| **Secret Key** | Your Blizzard API client secret (not currently used in HMAC signing but stored for future OAuth use) |
| **Locale** | Determines the language of API responses (e.g. `en_GB`, `de_DE`) |

### Region

Set per guild in ACP > bbGuild > Guilds > Edit:

| Region | API Base URL |
|--------|-------------|
| US | `https://us.api.battle.net/wow/` |
| EU | `https://eu.api.battle.net/wow/` |
| KR | `https://kr.api.battle.net/wow/` |
| TW | `https://tw.api.battle.net/wow/` |
| SEA | `https://us.api.battle.net/wow/` (routes to US) |

### Minimum Armory Level

Set per guild — only characters at or above this level will be imported during guild sync.

## Files

| File | Purpose |
|------|---------|
| `game/wow_api.php` | Implements `game_api_interface`, orchestrates API calls |
| `api/battlenet.php` | Factory — creates resource instances per API type |
| `api/battlenet_resource.php` | Abstract base — handles URL building, caching, signing, cURL |
| `api/battlenet_guild.php` | Guild resource — `getGuild()` |
| `api/battlenet_character.php` | Character resource — `getCharacter()` |
| `api/battlenet_realm.php` | Realm resource — `getRealmStatus()` |
| `api/battlenet_achievement.php` | Achievement resource — `getAchievementDetail()` |
