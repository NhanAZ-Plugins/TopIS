# TopIS

TopIS is a legacy PocketMine-MP island level plugin snapshot. It records player experience and island levels in plugin data files and provides `/is` and `/topis` commands. The manifest declares the prerelease plugin API `3.0.0-ALPHA6`, credits kuraLLsz as author and notes an edit by Ghast Noob.

The source looks up an external plugin named `PureChat` and calls its prefix API, but `plugin.yml` does not declare that dependency. It also uses legacy PocketMine-MP classes and calls player methods on command senders without a player check. Existing level and experience files would require a backed-up migration and regression tests before a replacement is deployed.

This snapshot has not been ported or tested on Axolotl-PM 5.x. The repository has no current Axolotl-PM 5 build, runtime test or stable release. It also has no LICENSE file or documented grant to redistribute a modified build. The original source, author credits and historical translations remain unchanged.

See [plugin.yml](plugin.yml) and [the historical source](src/kuraLLsz/Main.php) for the exact implementation. A port or release needs verified rights, a validated PureChat integration and compatibility and data migration tests.
