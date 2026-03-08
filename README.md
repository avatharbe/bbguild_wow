# bbGuild - World of Warcraft

Game plugin that adds World of Warcraft support to [bbGuild](https://github.com/avandenberghe/bbguild).

## Features

- **WoW Classes** - All 13 playable classes (Warrior, Paladin, Hunter, Rogue, Priest, Death Knight, Shaman, Mage, Warlock, Monk, Druid, Demon Hunter) with color codes and level ranges
- **WoW Races** - 15 playable races across Alliance and Horde factions, including Pandaren
- **WoW Factions** - Alliance and Horde with faction-based guild styling
- **Battle.net API** - Guild roster sync, character profiles, armory links, and portrait images
- **Localization** - Class and race names in English, French, German, and Italian

## Requirements

- phpBB >= 3.3.0
- PHP >= 7.4.0
- PHP cURL extension
- **bbGuild core** (`avathar/bbguild`) must be installed and enabled

## Installation

1. Ensure bbGuild core (`avathar/bbguild`) is installed and enabled.
2. Download the latest release of `bbguild_wow`.
3. Copy the `bbguild_wow` folder to `/ext/avathar/bbguild_wow/`.
4. Navigate in the ACP to `Customise -> Manage extensions`.
5. Look for `bbGuild - World of Warcraft` under Disabled Extensions and click `Enable`.
6. Go to ACP > bbGuild > Games and install the **World of Warcraft** game.

See [docs/INSTALL.md](docs/INSTALL.md) for detailed setup instructions including Battle.net API configuration.

## Uninstall

1. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
2. Find `bbGuild - World of Warcraft` under Enabled Extensions and click `Disable`.
3. To permanently uninstall, click `Delete Data` and then delete the `/ext/avathar/bbguild_wow` folder.

**Note:** Disabling the extension does not delete existing guild or player data from the database. Your roster and player records remain intact in bbGuild core. Only the WoW installer, API integration, and game-specific images become unavailable.

## Battle.net API

This extension integrates with the Blizzard Battle.net API for:
- Automatic guild member synchronization
- Character profile data (level, class, race, achievements)
- Character portraits and armory links
- Guild emblem generation

The API client uses OAuth 2.0 Client Credentials Grant with the modern `api.blizzard.com` endpoints. See [docs/BATTLENET_API.md](docs/BATTLENET_API.md) for details.

## Documentation

- [Installation Guide](docs/INSTALL.md) - Step-by-step setup
- [Battle.net API Reference](docs/BATTLENET_API.md) - API integration details and known issues
- [FAQ](docs/FAQ.md) - Frequently asked questions
- [Changelog](CHANGELOG.md) - Version history
- [Architecture](docs/ARCHITECTURE.md) - How the plugin system works

## For Developers

This extension serves as the reference implementation for bbGuild game plugins. If you want to create a plugin for another game, see [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) for the plugin contract and structure.

## License

[GNU General Public License v2](http://opensource.org/licenses/gpl-2.0.php)

## Links

- [bbGuild Core](https://github.com/avandenberghe/bbguild)
- [Support Forum](https://www.avathar.be/forum)
- [Issue Tracker](https://github.com/avandenberghe/bbguild/issues)
