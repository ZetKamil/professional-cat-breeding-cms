
awesome-design-md
Colors
Typography
Components
Responsive
Buy
Design System Analysis of Lumora
Photography-first interface that turns marketing into a museum gallery. Edge-to-edge tiles alternate light and dark; one Action Blue carries every interactive moment.

Learn more
Buy
Product render with system shadow
01 — Color Palette
One blue. Three near-blacks. Two whites.
The single brand-level interactive color is Action Blue. Surfaces alternate White / Parchment / Near-Black tiles. No gradients.

Brand & Accent
primary (Action Blue)
#0066cc
Every interactive element. Links, pill CTAs.
primary-focus
#0071e3
Keyboard focus ring on buttons.
primary-on-dark
#2997ff
Inline links on dark tiles.
Surface
canvas
#ffffff
Default page surface.
canvas-parchment
#f5f5f7
Signature off-white. Footer, tiles.
surface-pearl
#fafafc
Pearl Button capsule fill.
surface-tile-1
#272729
Primary dark tile.
surface-tile-2
#2a2a2c
Micro-step lighter.
surface-tile-3
#252527
Micro-step darker.
surface-black
#000000
True void. Global nav, video.
Text & Hairlines
ink
#1d1d1f
All headlines, body, dark utility fill.
ink-muted-80
#333333
Body on Pearl Button.
ink-muted-48
#7a7a7a
Disabled, fine-print.
body-muted
#cccccc
Secondary copy on dark tiles.
hairline
#e0e0e0
1px borders on store cards.
divider-soft
#f0f0f0
Pearl Button soft ring.
02 — Typography Scale
SF Pro Display + SF Pro Text
Inter substituted here. Negative letter-spacing at display sizes is the signature; weight 500 is deliberately absent.

hero-display
56px / 600 / 1.07 / -0.28px
There's never been a better Pulse
display-lg
40px / 600 / 1.10 / 0
Pulse 17 Pro
display-md
34px / 600 / 1.47 / -0.374px
All-day battery. All-screen display.
lead
28px / 400 / 1.14 / 0.196px
Built for Lumora Intelligence.
lead-airy
24px / 300 / 1.5 / 0
Carbon neutral. From the materials we use to the way we make them.
tagline
21px / 600 / 1.19 / 0.231px
From $999
body-strong
17px / 600 / 1.24 / -0.374px
Pro. Beyond.
body
17px / 400 / 1.47 / -0.374px
Lumora runs body copy at 17px, not 16px. The extra pixel gives the page an unmistakable "reading, not scanning" pace.
button-large
18px / 300 / 1.0 / 0
Pre-order now
button-utility
14px / 400 / 1.29 / -0.224px
Sign In
nav-link
12px / 400 / 1.0 / -0.12px
Store · Studio · Slate · Pulse · Orbit
03 — Button Variants
Two grammars: pills and rectangles
Pills for actions; rectangles for utility. The pill IS the brand action signal.

button-primary
Buy
Action Blue / pill / 17px / 400
button-primary-focus
Learn more
2px Focus Blue outline ring
button-primary-active
Pressed
scale(0.95) — system-wide press
button-secondary-pill
Learn more
Transparent + 1px Action Blue border
button-dark-utility
Sign In
Ink fill / 8px radius / 14px
button-pearl-capsule
Compare
Pearl fill / 11px radius / soft 3px ring
button-store-hero
Pre-order now
18px / weight 300 — the rare airy weight
button-icon-circular
‹
×
44 × 44 / chip-translucent / full radius
text-link
Read environmental report ›
Action Blue inline body link
text-link-on-dark
Read environmental report ›
Sky Link Blue on dark tiles
04 — Product Tiles
Edge-to-edge alternation
Tiles touch edges with no gap. The color change IS the divider.

Pulse 17 Pro
Pro. Beyond.

Learn more
Buy
Orbit Ultra
Adventure awaits.

Learn more
Buy ›
Studio Pro
Mind-blowing. Head-turning.

Learn more
Buy
Echo Buds Pro 3
Personalized spatial audio.

Learn more
Buy ›
05 — Store Grid Cards
White cards at 18px radius
MagSafe Charger
$39
Buy ›
AirTag (4-pack)
$99
Buy ›
Stylus Pro
$129
Buy ›
USB-C Cable
$29
Buy ›
06 — Configurator + Search + Sticky Bar
Choose a finish

Natural Titanium

Black Titanium

White Titanium

Blue Titanium
Search
Search lumora.com
Floating sticky bar
From $999 or $41.62/mo. for 24 mo.
Add to Bag
07 — Navigation
Two-row nav stack
Store
Studio
Slate
Pulse
Orbit
Echo Buds
Lens
🔍
🛍
Pulse
Explore
Compare
Shop
Switch
Buy
08 — Form Elements
Search lumora.com
Try Pulse 17 Pro
Email address
you@icloud.com
09 — Spacing Scale
Base 8px. Universal rhythm constants: 17px and 21px.

xxs · 4px
xs · 8px
sm · 12px
md · 17px
lg · 24px
xl · 32px
xxl · 48px
section · 80px
10 — Border Radius Scale
0 (tiles) → 8 (utility) → 11 (Pearl) → 18 (cards) → 9999 (pills + circles).

0 · none
5 · xs
8 · sm
11 · md
18 · lg
pill
full
11 — Elevation & Depth
Exactly one drop-shadow in the entire system, applied to product imagery only.

Flat
Tiles, global nav, footer. No shadow.

Soft hairline
1px rgba(0,0,0,0.08) border. Utility cards.

Backdrop blur
Parchment 80% + blur(20px). Sub-nav, sticky bar.

Surface change
Light tile ↔ dark tile IS the elevation.

No card shadow
Cards never carry drop shadows.

12 — Responsive Behavior
Name	Width	Key Changes
Phone	≤ 640px	Single-column tiles; hero h1 → 34px.
Tablet	736–833px	Global nav collapses to hamburger.
Tablet landscape	834–1023px	Global nav fully expanded; 3-col → 2-col grids.
Desktop	1069–1440px	Full layout; 4–5 col store grids.
Wide	≥ 1441px	Content locks at 1440px.
375
mobile
640
phone
834
tablet
1068
laptop
1440
desktop
1441
wide
Touch Targets
Pill CTAs at ~44 × 100px.
Circular control chips exactly 44 × 44.
Global nav utility links at ~32 × 80 (precision desktop only).
Collapsing Strategy
Global nav: full row → Brand logo + hamburger + bag at 834px.
Product tiles: 2-col → 1-col at 834px.
Hero typography: 56px → 40px → 34px → 28px.