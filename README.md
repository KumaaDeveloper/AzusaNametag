## General
AzusaNametag is a Pocketmine plug-in that works to remove the nametags of all players on the server

## Features
- Can remove player nametags when online, and vice virsa
- Can delete the nametags of newly joined players, and vice versa
- Custom message in config

## Command
Commands | Default | Permission
--- | --- | ---
`hidenametag` | Op | azusanametag.command.hidenametag
`shownametag` | Op | azusanametag.command.shownametag
  
## Configuration
```yaml
# AzusaNametag Configuration

messages:
# Message when show/hide is successful
  hide_nametag_success: "§aSuccessfully hidden all players nametags."
  remove_nametag_success: "§aSuccessfully restored all players nametags."

# Message when show/hide has been used before
  already_hidden_nametag: "§cAll players nametags are already hidden."
  already_visible_nametag: "§cAll players nametags are already visible."

# Broadcast message to all players
broadcast:
  hide_nametag: "§eAll players §fnametags have been hidden by an admin."
  show_nametag: "§eAll players §fnametags have been restored by an admin."
```
