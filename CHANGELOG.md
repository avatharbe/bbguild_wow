# Changelog

## 2.0.0-a1 02/03/2026
  - [NEW] Initial release as standalone phpBB extension
  - [NEW] Extracted from bbGuild core as part of the game plugin architecture
  - [NEW] Implements `game_provider_interface` — registers WoW with bbGuild via tagged services
  - [NEW] `wow_installer` extends `abstract_game_install` with clean array-based table names
  - [NEW] `wow_api` implements `game_api_interface` wrapping Battle.net SDK
  - [NEW] `wow_provider` supplies game metadata (regions, locales, URLs)
  - [NEW] Battle.net API classes copied to own namespace (`avathar\bbguild_wow\api`)
  - [FIX] `battlenet_resource` now properly extends `curl` base class
  - [CHG] All 6 Battle.net API classes migrated from `avathar\bbguild\model\api` namespace
  - [CHG] Installer uses `$this->table()` helper instead of direct property access
  - [CHG] `has_api_support()` returns true, so `armory_enabled` is set correctly on install
  - [NOTE] Battle.net API uses legacy endpoints (`api.battle.net`) with HMAC auth — OAuth 2.0 migration planned
  - [NOTE] Images remain in bbGuild core for now — will be moved in a future release
