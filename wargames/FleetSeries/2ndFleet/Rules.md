Units: SURF, SUB, AIR
Bases: Port, AF, Landing Strip, Port+AF
Dieroll: 0 to 9

# Sequence Of Play

- Turns: AM, PM, Night

- Strategic Cycle (AM Only)
  - Political Events (ADV, Except Turn 1)
  - Weather (ADV)
  - Reinfocement (ADV, Except Turn 1)
  - Submarine Mode (ADV, optional)
  - Strategic Air
    - Application (secretly assign)
    - Interception
    - Bounce
    - Mine (optional)
    - Detection
  - Invasion (ADV)
    - Soviet
    - Nato
    - Control
- Activity Cycle
  - CAP
  - Minesweeping (ADV, optional)
  - Replenishment (ADV, optional)
  - Local Detection
  - Action Phase 
    - choose one unit type (AIR, SUB or SURF) per segment, once chosen, cannot be chosen in subsequent segment(s)
    - Action 1 Segment
      - initiative: even Nato, odd Soviet
    - Action 2 Segment
    - Action 3 Segment
  - local detect remove
  - Land CAP
- Terminal Cycle (Night Only)
  - Fuel (ADV, optional)
  - Repair (INT, ADV, optional)
  - Strategic Air Mission Termination
  - Strategic Detection Removal

# 5 Movement

- no unit can leave the map
- drift ice ignored in Basic & Intermediate games
- units when activated together as a stack uses the lowest MP unit
- SURF cannot end active status in same hex as enemy unit or base (how about Amphibious Assaults ???)
- Norwegian SURF cannot move > 6 hex from Norwegian coast
- if full speed declared (cannot do in pack ice) for SUB, +1 MP, place Strategic Detect marker
- only SN and SB can enter pack ice, stop when enter, only 1 MP when starting move in pack ice
- only CV AIR can land on CV (must be of same nationality)
- AIR can move through any country without restriction
- AIR can never land on landing strip

# 6 Stacking

- max 12 combat SURF per hex
- max 4 INT/ATK units per AF
- TF / TG / solo units in a hex do NOT provide group benefit to each other
  - TG = 2-3 combat SURF
  - TF = 4+ combat SURF

# 7 Strategic AIR

- damaged units can perform
- nato units can perform together

- solitaire: roll to assign (odd nato, even soviet), after 4 rolls if a 9 is rolled stop, 
- missions
  - Interception = INT
    - rtb after interception & bounce
  - Recon = INT ATK RCN AEW
    - rtb after strategic detection phase
  - Tac-Coord = RCN
    - rtb after providing tac-coord for an attack
  - MINING = RCN
    - ?
- r all air units means return to base

- interception = INT vs INT
  - roll to see who is attaker
- bounce = winning side INT vs losing side all except INT
- mining TBD
- detection = see [9 Detection](9-Detection)

- strategic air termination
  - CV air returns to same CV (CV may have changed zone but its ok)
  - Land air returns on any eligible AF in the zone

# 8 CAP

- INT, AEW
- up to 3 units per CV or AF (if 2 CV are stacked 6, 3 CV stacked 9)
- soviet assign first followed by Nato
- CV CAP moves with CV
- CV can initiate CAP in fjord hexes
- CAP has range of 4 hexes, can choose to attack at any range except enemy units in CAP Hex
- A CAP Mission can make make 1 attack against a given enemy aircraft / stack in an action segment, it can attack other enemy units
  - it can attack unlimited different units/stacks, unless it suffers r result
- If Overlapping CAP missions can attack, each CAP mission attacks seperately
- Units participate as a whole.
- Friendly and Enemy CAP can overlap without affecting each other
- AF CAP use full value, CV CAP has modifications
- RTB: units suffer r from CAP
  - <= 1/2 MP used, return to originating AF
  - > 1/2 MP used, return to AF in range
- If units attacked by CAP do not contain INT units r result is ignored 
- CAP can return to damaged AF / CV
  - if AF destroyed move to friendly AF in same zone, subject to stacking restrictions, destroy if no AF available or overstacked
  - if CV destroyed destroy all CAP

# 4 Action Phase

# SURF Action Segment

- Can reorganize SURF in same hex at start of SURF action
  - forced reorganization: if TF has less than 4 combat SURF, flip to TG/remove depending on number of ships
- activate any or all SURF in the same hex
  - in a hex, may activate some first, then some next, then some more next...
- actions available
  - move and not attack
  - move and perform 1 or 2 attacks
  - perform 1 or 2 attacks and move
  - perform 1 or 2 attacks and not move
  - perform 1 attack, move, perform 2nd attack
  - attacks must be of different type (ASW, SSM)

# SUB Action Segment

- activated individually
- move then attack

# AIR Action Segment

- up to 4 Aircraft may be activated per AF / CV
  - if stacked, activate up to 4 each time from the stack, the 4 can be from different AF / CV
- move, attack, move
- begin and end on a friendly AF / CV of same nationality
- cannot activate CV AIR in fjord hex

# 9 Detection

- only applies to SUB and SURF
- each unit or stack can possess either local or strategic, not both

- Strategic
  - an AIR unit on Recon mission can
    - place marker on any SURF unit or stack (in a hex) in zone
    - attempt to place marker on a SUB in zone
      - cannot for SUB in pack ice
      - roll on sub detection table
      - INT / ATK / T16D / T95D units in Recon mission cannot detect sub
      - if has local detect, flip to strategic detect
    - once placed or attempted to place, the AIR unit rtb

- Local Detection Phase
  - if strategic detect, skip local detect
  - SURF have Limited & Extended Detection Zones 
  - SUB have Limited Detection Zone
  - place on SURF in Limited / Extended zone
  - place on SUB if combined ASW of units adjacent to SUB >= 6 

- Action Phase (local detection)
  - place after attack is resolved
    - SURF execute SSM or ASW attack in Limited zone
    - SUB execute SSM, ASW, or Torpedo attack in Limited zone
  - moment enemy SURF move from one Limited or Extended zone to another Limited or Extended zone of same detecting unit/stack
  - moment enemy SUB move from one Limited zone to another Limited zone of same detecting unit/stack with ASW >= 6
  - enter coastal hex of GB or NO (except spits bergen)
- if undetected SURF ends action with detected SURF - all are detected
- if detected SURF ends action with undetected SURF - all are detected
- all SURF units in a hex with detection can be attacked

- if SURF with local detect end in hex with strategic detect SURF - all become strategic detected
- if a detected SURF stack splits, the seperate unit/stackc are detected
- SURF and SUB units in base hexes need to be detected to be attacked

- Removal Exceptions
  - Strategic detect not removed from Soviet SUB within 3 hexes of SOSUS
  - Local detect not removed if
    - SURF unit / stacks in Limited / Extended zone
    - SUB in Limited zone of units with combined ASW >= 6

# 10 Combat

- Types:
  - Torpedo, SSM, Bombing, ASW = use CRT
- TacCoord can be applied if available
  - each aircraft contributes +1, and rtb if allocated

  - Air-to-Air (A2A) = use AA CRT
- SURF / SUB must be detected to be attacked
- Pack Ice: only SB/SN ASW attack against SB/SN in pack ice
- INT / ATK units detemine if doing INT or ATK
  - INT: bombing=0, SSM=0, AA=printed value
  - ATK: AA=1, bombing=printed value, SSM=printed value
- Max 2 Non-Norwegian INT/ATK can initiate Bombing or SSM when activaged from Norwegian AF

- Torpedo
  - SUB attacks adjacent detected SURF unit / stack
    - cannot if SURF in base hex, SUB in fjord hex
  - SUB only attacks 1 hex, can allocate up to 2 units to attack
  - defending SURF add ASW of up to 5 units for defense
  - defense roll
    - +2 if at least 1 targeted SURF in TF
    - -1 if no targeted SURF in TF
    - -3 if only SURF in hex
  - attack roll
    - +1 per TacCoord allocated
    - -3 if target in fjord hex

- SSM
  - AIR, SURF or SUB attacks detected SURF or AF/Port in SSM range
  - attacking SUB or SURF cannot be in fjord hex
  - attack 1 hex only
  - SURF defender position units in each attacked group first
  - allocate SSM attack points to target(s)
  - defense roll
    - add Area AA of all units in hex (except if in fjord hex)
    - add Close AA of all targets
    - add Close AA of unit stacked directly beneath targeted unit (unless the unit beneath is also a target)
    - add Area AA of hex(s) passed through with defending side surface units
    - +2 for each F14 CAP, +1 for each non-F14 CAP
    - +2 if at least 1 targeted SURF in TF
    - -1 if no targeted SURF in TF
    - -3 if only SURF in hex

  - attacking roll (per attacked target)
    - +1 per TacCoord allocated
    - -3 if target in fjord hex
    - -2 if no friendly unit adjacent to SURF in targeted hex
    - -4 if target is AF/Port

- BOMBING
  - AIR attacks detected SURF or AF/Port, AIR must be in same hex as target
  - attack 1 hex only
  - SURF defender position units in each attacked group first
  - allocate Bomb attack points to target(s)
  - defense roll
    - add Area AA of all units in hex (except if in fjord hex)
    - add Close AA of all targets
    - add Close AA of unit stacked directly beneath targeted unit (unless the unit beneath is also a target)
    - add Area AA of hex(s) passed through with defending side surface units
    - +2 if at least 1 targeted SURF in TF
    - -1 if no targeted SURF in TF
    - -3 if only SURF in hex
    - result = 0-4 (no effect), 5-8 (damage one unit), else (damage 2 units)
  - attack roll (per attacked target)
    - +1 per TacCoord allocated

- ASW
  - max 5 SURF or 4 AIR or 1 SUB attacks
    - e.g. if 12 SURF has 3 adjacent detected enemy SUB you can allocate units to attack, e.g. 5, 4, 3 ???
  - only 1 SUB attacked
    - attacked only once in AIR segment
    - attacked only once in SURF segment
    - attacked unlimited times in SUB segment
  - attacking SURF or SUB must be adjacent to SUB, cannot be in fjord hex
  - attacking AIR must be in same hex as SUB
  - Combine attack ASW value (SURF, AIR)
  - no defense roll

- Air-To-Air
  - combined A2A of attacked vs defender
  - round down to favour of defender

# 11 Damage

- destroyed if >= defense value
- damaged if >= half defense value (destroyed if already damaged)
- cannot be repaired

# 12 Special Units

- CV
  - max 2 units assign to CAP ??? contradict - 8 CAP
    - additional AEW unit can be assigned to the CAP ??? or is it part of 2 unit limit above 
  - max 1 unit assigned to strategic air
  - max 2 units active during air segment ??? contradict - 4 Action Phase
- EW
  - only 1 EW unit can be activated as part of a stack
    - reduce by 2, combined attacker AA value (before modification)
    - reduce 1 Area AA per unit in target hex (min 0)
    - reduce 1 Close AA of Port/AF

# 14 AF and Port

- Can be attacked unlimited times
- SURF units in AF/Port hex cannot contribute Area or Close AA
- Close AA
  - base can contribute Close AA to SURF targets in base hex (how?)
- In AF and Port hexes, they are attacked and damaged seperately, need to keep track
- effects of damage
  - cannot activate, initiate strategic AIR, perform CAP
  - cannot use port for in-port replenishment
- effects of destruction
  - AIR eliminated (include CAP at CAP landing)
  - Close AA no longer functional, no in-port replenishment
- Repair
  - remove Damage 1
  - flip Damage 2 to Damage 1

# 16 Time Of Year

- determine time of year: P1 = Jan-Feb, P2 = Mar-Apr or Nov-Dec, P3 = May-Jun or Sep-Oct, P4 = Jul-Aug
- Darkness
  - P1 - all 3 game turns are Dark (except Labrador Sea, North Atlantic, British Isles - AM = day)
  - P2 - only AM is Day
  - P3 - only Night is Dark
  - P4 - all 3 game turns are Day
  - Only US CV all-weather AIR can activate
  - Bombing: US all weather AIR can bomb at full value, other AIR is half value
  - Air-to-air: All Soviet INT / ATK have AA values reduced by 1 (min 1)
- Drift Ice
  - P1 - all drift ice hex are pack ice
  - P4 - all drift ice hex are sea
  - SURF must use 2MP to enter drift ice (unit with MP only 1 can move 1 hex)
  - SUB at full speed cannot enter, cannot activate full speed

# 17 Weather

TBD

# 18 Invasion of NATO Bases

TBD

Invasion Hexes
Amphibious Assault
Parachute Assault
Commando Assault

# 19 SOSUS

- only Soviet SUBS affected
- roll strategic detection each time SUB moves into a SOSUS Hex (do not roll if already strategically detected)
- do not remove during strategic detection removal phase if within 3 hexes of SOSUS Hex

# 20 Logistics (Optional)

TBD

# 21 Tactical Nuclear Warfare (Optional)

TBD

# 22 Deep Mode (Optional)

- during sub mode phase of Strategic cycle (AM Turn), first Soviet then Nato place Deep mode for subs with 2MP or more,
- cannot enter deep mode in coastal or pack ice hex 
- can only remove during next sub mode phase
- 1MP become only, cannot move at full speed or enter coastal / pack ice hex
- -1 to Attacker dieroll
- SUB can perform ASW normally, Torpedo at half value (round down)
- SUB cannot perform SSM combat
- +2 to strategic detection attempt
- +4 to strategic detection attempt if SUB in deep mode occupy sub-oceanic mounntain

# 23 Mines (Optional)

- place mines during strategic mining segment
  - each AIR unit on mining mission can place a single Mine marker on any coastal hex including fjords and ports
  - AIR unit rtb once placed
  - Soviet SUB can  lay mine but only if Logistics option is played, place on any adjacent coastal hex and check of 3 TORP boxes (must have at least 3 TORP boxes to place mine)
  - The 10 game markers are strict game limits, no more mines may be placed if all are placed. When mines are removed, they can be placed again
- max 4 mines per hex
- mines attack both Nato and Soviet SURF units, units are attacked when they leave a mined hex
  - if DR <= number of mine(s), number of SURF unit(s) damaged = number of mine - DR (minimum 1 damage)
  - if there are less SURF unit(s) than number of damage, the extra damage is ignored
  - undamaged units take mine damage first, if damage is inflicted on an already damaged unit, it is destroyed
  - 0-5 owning player decides damage, 6-9 enemy player decides damage
- minesweeping phase
  - either player roll die once for each hex containing mines, removed if
    - Soviet hex: 0,1,2
    - UK hex: 0,1
    - All others: 0
  - if mines are on Icelandic or Norwegian coast and US CV or AA (amphib assault) SURF unit is within 10 hexes of mined hex
    - can have +1 to DR (aerial minesweeping), applied once minesweeping phase

# 24 Optional Rules

TBD

- Cruise Missiles
- B1 Air Unit
- Alternate CV Air Wings
- Future Combatants
- Soviet TattleTales
- High Speeds
- Increased Movement For Air Units
- Variable SSM Targeting
- Close combat

