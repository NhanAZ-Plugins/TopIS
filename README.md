# TopIS

Island progression and leaderboard plugin for Axolotl-PM API 5; optional PureChat integration is unverified.

[![CI](https://github.com/NhanAZ-Plugins/TopIS/actions/workflows/build.yml/badge.svg)](https://github.com/NhanAZ-Plugins/TopIS/actions/workflows/build.yml)
[![Latest release](https://img.shields.io/github/v/release/NhanAZ-Plugins/TopIS)](https://github.com/NhanAZ-Plugins/TopIS/releases/latest)
[![Discord](https://img.shields.io/discord/986553214889517088?label=Discord&logo=discord&logoColor=white&color=5865F2)](https://discord.gg/j2X83ujT6c)


## Compatibility

- Minimum Axolotl-PM API: `5.0.0`.
- CI checks PHPStan at maximum level and builds against exact Axolotl-PM API source revisions for `5.0.0` and `5.49.2`.
- CI starts the built PHAR on Axolotl-PM `5.49.2`.
- These are the verified compatibility points; intermediate patch releases are not inferred from the matrix.

The `/is info` command shows the sender's level and experience. `/topis [page]` lists players ordered by island level. Each non-cancelled block placement adds one experience point. The initial threshold is 150 points and increases by 20 after each level-up.

## Player data

TopIS continues to use its historical `Exp.yml`, `Seviyeler.yml`, and `SeviyeAtlamaXp.yml` files. Existing values are read in place and are not renamed or automatically converted. On a player's next join, only missing player keys are initialized; existing records are preserved. The plugin saves progress on level-up and normal server shutdown.

`PureChat` integration is optional. When a loaded plugin exposes the `setPrefix` method, TopIS passes the player's island level as the prefix. The PureChat integration itself is not covered by the API 5 runtime smoke test; verify the installed chat plugin separately.

## Build and validation

GitHub Actions uses [DevTools](https://github.com/NhanAZ/DevTools) to run PHPStan at maximum level, build the plugin PHAR and validate its contents. Workflow dependencies and the server artifact are pinned to exact revisions or checksums for reproducibility.

The original manifest credits `kuraLLsz` and Ghast Noob. No `LICENSE` file is present; no general source reuse license is granted.
