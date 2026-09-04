# GeneDecode — Website (refined design)

Static front-end for the new owned GeneDecode platform (dark/cosmic refined system).
This is the design/markup layer the OgreLogic team edits and builds the Laravel backend behind.

## Structure
- `index.html`, `*.html` — one file per page (Home, Deep Dives, Watch, Join Us, Account, Community, Live, etc.)
- `styles.css` — the single shared stylesheet (all design tokens + components)
- `app.js` — injects the shared header/nav + footer into every page (edit nav/footer here once)
- `catalog.js` — sample video data + card renderer for the catalog/player pages
- `assets/` — logo, hero, membership images, social icons, and `assets/vid/` thumbnails

## Editing
Edit the HTML/CSS/JS directly. Header and footer are shared via `app.js`, so change them once there.
Push to `main` and the site auto-deploys (see below).

## Deploy (Cloudflare Pages connected to this repo)
Connect this repo once, then every push to `main` deploys automatically:
Cloudflare dashboard -> Workers & Pages -> Create -> Pages -> Connect to Git ->
select `ogrelogictech/genedecode-web`. Build command: leave empty. Output directory: `/`.
Live URL is provided by Cloudflare (e.g. genedecode-design.pages.dev).

## Notes
- Nav shows the logged-out state; member pages are linked for review. Production swaps to the logged-in nav.
- About bio and legal pages use placeholder copy pending the client's real text.
