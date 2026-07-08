/* ============================================================
   POST — shared application script
   Handles: theme/logo customization, header + footer injection,
   product data + rendering, cart, and all UI interactions.
   ============================================================ */

/* =================================================================
   ⚙️  CUSTOMIZE HERE  — brand identity, colours, navigation, footer
   Change these values and every page updates automatically.
   ================================================================= */
const SITE = {
    brand: "POST",
    tagline: "Premium Origin Stories & Thoughts",
    currency: "$",

    // Rose-gold palette — drives the logo sheen and every accent.
    colors: {
        rose: "#BC8472",
        roseDeep: "#9E5F4D",
        roseLight: "#E2BEAF",
        roseSoft: "#F1DED4",
        goldHi: "#F4DACD",
        ink: "#2A2521",
        cream: "#FAF6F1"
    },

    // Top navigation (label → page file)
    nav: [
        { label: "Women", href: window.navLinks.women },
        { label: "Children", href: window.navLinks.kids },
        { label: "Beauty", href: window.navLinks.beauty },
        { label: "Accessories", href: window.navLinks.accessories },
        { label: "Our Story", href: window.navLinks.about },
        { label: "Account", href: window.navLinks.login }
    ],

    announce: "Complimentary shipping on orders over $120 · Easy 30-day returns",

    footer: {
        blurb: "Considered pieces for women and children — clothing, beauty and accessories, each carried for the story it begins.",
        columns: [
            {
                title: "Shop", links: [
                    { label: "Women", href: window.navLinks.women },
                    { label: "Children", href: window.navLinks.kids },
                    { label: "Beauty", href: window.navLinks.beauty },
                    { label: "Accessories", href: window.navLinks.accessories },
                    { label: "New Arrivals", href: window.navLinks.women + "?filter=new" },
                ]
            },

            {
                title: "House", links: [
                    { label: "Our Story", href: window.navLinks.about },
                    { label: "Journal", href: window.navLinks.about + "?section=journal" },
                    { label: "Stockists", href: window.navLinks.contact },
                    { label: "Careers", href: window.navLinks.contact }
                ]
            },
            {
                title: "Care", links: [
                    { label: "Contact", route: "{{ route('contact') }}" },
                    { label: "Shipping", route: "{{ route('contact') }}" },
                    { label: "Returns", route: "{{ route('contact') }}" },
                    { label: "Size Guide", route: "{{ route('contact') }}" },
                    { label: "Gift Cards", route: "{{ route('contact') }}" }
                ]
            }
        ]
    },

    contact: {
        email: "hello@poststudio.com",
        phone: "+1 (212) 555-0147",
        address: "84 Mercer Street, SoHo · New York, NY 10012",
        hours: "Mon–Sat 10–7 · Sun 12–6"
    }
};

/* =================================================================
   Colour blocks used as product imagery (tonal "lookbook" look)
   ================================================================= */
const PALETTES = {
    blush: { bg: "linear-gradient(150deg,#F5E1D8,#E9C7BA)", ink: "#A8634F" },
    rose: { bg: "linear-gradient(150deg,#F3D9CF,#E0AE9C)", ink: "#93513D" },
    sand: { bg: "linear-gradient(150deg,#F1E8D9,#E2CCB0)", ink: "#9C7B53" },
    mauve: { bg: "linear-gradient(150deg,#ECDEDE,#D9C0C2)", ink: "#8E5F63" },
    pearl: { bg: "linear-gradient(150deg,#F7F1EB,#E9DCCE)", ink: "#9E7E68" },
    taupe: { bg: "linear-gradient(150deg,#EEE6DB,#D7C7B5)", ink: "#7E6E5C" },
    clay: { bg: "linear-gradient(150deg,#F0DAD0,#DDB39F)", ink: "#8C4E38" }
};

/* =================================================================
   Minimal line illustrations (inner SVG paths) per product type
   ================================================================= */
const ILLUS = {
    dress: `<path d="M40 22 L33 31 L38 35 Q35 56 30 78 L70 78 Q65 56 62 35 L67 31 L60 22 Q50 28 40 22 Z"/><path d="M44 23 Q50 27 56 23"/>`,
    blouse: `<path d="M38 28 L30 36 L35 41 L36 70 L64 70 L65 41 L70 36 L62 28 Q50 34 38 28 Z"/><path d="M44 29 Q50 33 56 29"/><line x1="36" y1="55" x2="64" y2="55"/>`,
    coat: `<path d="M38 26 L30 34 L35 40 L35 78 L65 78 L65 40 L70 34 L62 26 L50 30 L38 26 Z"/><line x1="50" y1="30" x2="50" y2="78"/><path d="M38 26 L50 30 L44 40"/><path d="M62 26 L50 30 L56 40"/><circle cx="50" cy="50" r="1.3"/><circle cx="50" cy="60" r="1.3"/><circle cx="50" cy="70" r="1.3"/>`,
    skirt: `<path d="M36 34 L34 40 L28 74 L72 74 L66 40 L64 34 Z"/><line x1="34" y1="40" x2="66" y2="40"/><line x1="42" y1="42" x2="38" y2="72"/><line x1="50" y1="42" x2="50" y2="72"/><line x1="58" y1="42" x2="62" y2="72"/>`,
    knit: `<path d="M36 30 L28 38 L34 44 L34 72 L66 72 L66 44 L72 38 L64 30 Q50 36 36 30 Z"/><path d="M44 31 Q50 35 56 31"/><line x1="34" y1="66" x2="66" y2="66"/>`,
    trousers: `<path d="M38 26 H62 L60 50 L58 78 H52 L50 54 L48 78 H42 L40 50 Z"/><line x1="38" y1="34" x2="62" y2="34"/>`,
    romper: `<path d="M40 30 L34 36 L38 41 L38 58 L36 72 H46 L48 58 L52 58 L54 72 H64 L62 58 L62 41 L66 36 L60 30 Q50 35 40 30 Z"/><path d="M44 31 Q50 34 56 31"/>`,
    kidstee: `<path d="M38 34 L31 40 L36 45 L37 64 L63 64 L64 45 L69 40 L62 34 Q50 39 38 34 Z"/><path d="M50 57 L46 52 Q44 49 47 48.5 Q49 48.5 50 51 Q51 48.5 53 48.5 Q56 49 54 52 Z"/>`,
    lipstick: `<rect x="42" y="48" width="16" height="30" rx="3"/><rect x="44" y="40" width="12" height="8" rx="1.5"/><path d="M44 40 L44 26 Q44 22 50 22 L56 30 L56 40 Z"/>`,
    palette: `<rect x="26" y="30" width="48" height="40" rx="6"/><circle cx="40" cy="45" r="6"/><circle cx="60" cy="45" r="6"/><circle cx="40" cy="60" r="6"/><circle cx="60" cy="60" r="6"/>`,
    perfume: `<rect x="36" y="40" width="28" height="38" rx="6"/><rect x="44" y="27" width="12" height="13" rx="2"/><rect x="42" y="19" width="16" height="8" rx="2"/><line x1="36" y1="56" x2="64" y2="56"/>`,
    serum: `<rect x="40" y="42" width="20" height="36" rx="5"/><rect x="44" y="30" width="12" height="12" rx="2"/><path d="M44 30 V24 H56 V30"/><line x1="50" y1="24" x2="50" y2="16"/>`,
    bag: `<path d="M32 44 L36 76 L64 76 L68 44 Z"/><path d="M40 44 Q40 30 50 30 Q60 30 60 44"/><line x1="32" y1="53" x2="68" y2="53"/>`,
    earrings: `<circle cx="38" cy="28" r="2.6"/><line x1="38" y1="31" x2="38" y2="44"/><circle cx="38" cy="51" r="7"/><circle cx="62" cy="28" r="2.6"/><line x1="62" y1="31" x2="62" y2="40"/><path d="M62 40 L56 56 L68 56 Z"/>`,
    scarf: `<path d="M40 24 C30 40 30 60 40 76"/><path d="M60 24 C70 40 70 60 60 76"/><path d="M40 24 Q50 20 60 24"/><line x1="44" y1="76" x2="44" y2="84"/><line x1="50" y1="78" x2="50" y2="86"/><line x1="56" y1="76" x2="56" y2="84"/>`,
    sunglasses: `<path d="M22 44 H78"/><rect x="24" y="44" width="22" height="16" rx="7"/><rect x="54" y="44" width="22" height="16" rx="7"/><path d="M46 50 H54"/>`,
    hat: `<ellipse cx="50" cy="60" rx="30" ry="9"/><path d="M34 60 Q34 34 50 34 Q66 34 66 60"/><path d="M36 56 Q50 60 64 56"/>`,
    watch: `<rect x="38" y="40" width="24" height="24" rx="6"/><path d="M44 40 L46 26 H54 L56 40"/><path d="M44 64 L46 78 H54 L56 64"/><line x1="50" y1="52" x2="50" y2="46"/><line x1="50" y1="52" x2="55" y2="52"/>`
};
function illus(type) {
    return `<svg class="illus" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">${ILLUS[type] || ILLUS.dress}</svg>`;
}
function mediaStyle(p) { const pal = PALETTES[p.palette] || PALETTES.blush; return `background:${pal.bg};color:${pal.ink}`; }

/* =================================================================
   UI icons (24×24 stroke icons)
   ================================================================= */
const UI = {
    search: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.2-3.2"/></svg>`,
    bag: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8h12l-1 12H7L6 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>`,
    heart: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-7-4.3-7-9.3A3.7 3.7 0 0 1 12 8a3.7 3.7 0 0 1 7 2.7c0 5-7 9.3-7 9.3Z"/></svg>`,
    heartFill: `<svg viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 20s-7-4.3-7-9.3A3.7 3.7 0 0 1 12 8a3.7 3.7 0 0 1 7 2.7c0 5-7 9.3-7 9.3Z"/></svg>`,
    user: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><circle cx="12" cy="8" r="3.4"/><path d="M5.5 20a6.5 6.5 0 0 1 13 0"/></svg>`,
    menu: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>`,
    close: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>`,
    arrow: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>`,
    check: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5 10 17 19 7"/></svg>`,
    star: `<svg viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 3l2.6 5.4 5.9.8-4.3 4.1 1 5.9L12 16.9 6.8 19.2l1-5.9L3.5 9.2l5.9-.8L12 3Z"/></svg>`,
    mail: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>`,
    phone: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h3l2 5-2.5 1.5a12 12 0 0 0 5 5L17 13l5 2v3a2 2 0 0 1-2.2 2A17 17 0 0 1 4 5.2 2 2 0 0 1 6 3Z"/></svg>`,
    pin: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6 7-11a7 7 0 1 0-14 0c0 5 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>`,
    clock: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8"/><path d="M12 8v4l3 2"/></svg>`,
    truck: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h11v8H3zM14 10h4l3 3v2h-7z"/><circle cx="7" cy="18" r="1.6"/><circle cx="17" cy="18" r="1.6"/></svg>`,
    shield: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v5c0 5-3.5 8-7 10-3.5-2-7-5-7-10V6l7-3Z"/><path d="m9 12 2 2 4-4"/></svg>`,
    refresh: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11a8 8 0 0 1 13.6-4.6L20 8"/><path d="M20 4v4h-4"/><path d="M20 13a8 8 0 0 1-13.6 4.6L4 16"/><path d="M4 20v-4h4"/></svg>`,
    leaf: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 19C5 11 11 5 19 5c0 8-6 14-14 14Z"/><path d="M5 19C9 15 13 11 17 9"/></svg>`,
    gift: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="9" width="16" height="11" rx="1"/><path d="M4 13h16M12 9v11"/><path d="M12 9S10 4 7.5 5 9 9 12 9Zm0 0s2-5 4.5-4S15 9 12 9Z"/></svg>`,
    sparkle: `<svg viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2c.6 4.4 2.6 6.4 7 7-4.4.6-6.4 2.6-7 7-.6-4.4-2.6-6.4-7-7 4.4-.6 6.4-2.6 7-7Z"/></svg>`,
    instagram: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17" cy="7" r="1" fill="currentColor" stroke="none"/></svg>`,
    pinterest: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7c-2.2 0-3.8 1.5-3.8 3.6 0 1 .5 2 1.4 2.4.2 0 .2-.1.2-.3l-.2-.8c0-.1 0-.2.1-.3.4-1.6.9-2.6 2.3-2.6 1.2 0 2 .9 2 2.2 0 1.6-.7 2.9-1.7 2.9-.6 0-1-.5-.9-1.1l.5-2c.2-.7-.6-1.1-1-.4-.6 1.1.2 3-.6 4.9"/></svg>`,
    facebook: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 8h2.5V5H14a3 3 0 0 0-3 3v2H9v3h2v7h3v-7h2.2l.5-3H14V8Z"/></svg>`,
    tiktok: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 4v9.5a3.5 3.5 0 1 1-3-3.5"/><path d="M14 7a4.5 4.5 0 0 0 4 3"/></svg>`
};

/* =================================================================
   Product catalogue
   ================================================================= */
const PRODUCTS = [
    // ---- Women ----
    { id: "w1", name: "Marlowe Silk Slip Dress", page: "women", cat: "Dresses", price: 248, type: "dress", palette: "clay", tag: "New", featured: true, rating: 4.8, reviews: 64, swatches: ["#C08B73", "#2A2521", "#C9B7A6"], desc: "A bias-cut slip in washed silk that moves like water and holds its line.", origin: "Cut from a single bolt of mulberry silk sourced through a third-generation mill in Como, finished by a small atelier we have worked with since our first season." },
    { id: "w2", name: "Lenore Wool Overcoat", page: "women", cat: "Outerwear", price: 420, was: 520, type: "coat", palette: "taupe", tag: "-19%", featured: true, rating: 4.9, reviews: 38, swatches: ["#7E6E5C", "#2A2521"], desc: "A double-faced wool coat with a quiet drape and an unlined, hand-finished interior.", origin: "The cloth is a recycled-wool blend woven in Yorkshire; the buttons are turned from corozo nut rather than plastic." },
    { id: "w3", name: "Halden Pleated Midi Skirt", page: "women", cat: "Skirts", price: 165, type: "skirt", palette: "sand", rating: 4.6, reviews: 51, swatches: ["#9C7B53", "#8E5F63", "#2A2521"], desc: "Knife pleats set into a soft satin-back crepe that swings with every step.", origin: "Pleated by heat-set on a vintage press in a family workshop outside Kyoto." },
    { id: "w4", name: "Adair Cashmere Knit", page: "women", cat: "Knitwear", price: 198, type: "knit", palette: "pearl", featured: true, rating: 4.9, reviews: 88, swatches: ["#9E7E68", "#E2BEAF", "#7E6E5C"], desc: "A relaxed boatneck in grade-A cashmere, light enough for spring evenings.", origin: "Spun from fibre traced to a single herding cooperative in Inner Mongolia." },
    { id: "w5", name: "June Poplin Blouse", page: "women", cat: "Tops", price: 128, type: "blouse", palette: "blush", tag: "New", rating: 4.5, reviews: 29, swatches: ["#A8634F", "#FFFFFF", "#C9B7A6"], desc: "Crisp organic-cotton poplin with a softly gathered shoulder.", origin: "Woven from GOTS-certified organic cotton, dyed in low-water vats." },
    { id: "w6", name: "Solène Tailored Trousers", page: "women", cat: "Trousers", price: 176, type: "trousers", palette: "mauve", rating: 4.7, reviews: 42, swatches: ["#8E5F63", "#2A2521", "#7E6E5C"], desc: "A high-waisted, wide-leg trouser with a pressed crease and a clean fall.", origin: "Tailored in a workshop in Porto run entirely on renewable energy." },
    { id: "w7", name: "Wren Linen Wrap Dress", page: "women", cat: "Dresses", price: 189, type: "dress", palette: "sand", rating: 4.6, reviews: 47, swatches: ["#9C7B53", "#C08B73"], desc: "A breathable linen wrap that ties softly and packs without complaint.", origin: "European flax, spun and woven within 200km of where it was grown." },
    { id: "w8", name: "Cosima Quilted Jacket", page: "women", cat: "Outerwear", price: 235, type: "coat", palette: "clay", rating: 4.8, reviews: 33, swatches: ["#8C4E38", "#2A2521"], desc: "A lightly quilted jacket with a rounded collar and patch pockets.", origin: "Filled with a recycled-down alternative; shell made from regenerated nylon." },

    // ---- Children ----
    { id: "k1", name: "Poppy Pinafore Dress", page: "kids", cat: "Dresses", price: 64, type: "dress", palette: "rose", tag: "New", featured: true, rating: 4.9, reviews: 73, swatches: ["#E0AE9C", "#9C7B53"], desc: "A swingy pinafore in soft brushed cotton, built for cartwheels.", origin: "Made from organic cotton offcuts rescued from our womenswear cutting floor." },
    { id: "k2", name: "Bramble Knit Cardigan", page: "kids", cat: "Knitwear", price: 58, type: "knit", palette: "pearl", rating: 4.8, reviews: 41, swatches: ["#9E7E68", "#E2BEAF"], desc: "A cosy little cardigan with wooden buttons and roomy sleeves.", origin: "Knitted from a soft merino blend, gentle on small skin." },
    { id: "k3", name: "Hazel Cotton Romper", page: "kids", cat: "Rompers", price: 48, type: "romper", palette: "blush", featured: true, rating: 4.9, reviews: 96, swatches: ["#A8634F", "#C9B7A6"], desc: "An easy snap-button romper that survives the wash and the playground.", origin: "GOTS-certified cotton, dyed with low-impact colour." },
    { id: "k4", name: "Tilly Heart Tee", page: "kids", cat: "Tops", price: 32, type: "kidstee", palette: "rose", tag: "New", rating: 4.7, reviews: 58, swatches: ["#E0AE9C", "#FFFFFF"], desc: "A soft everyday tee with a tiny embroidered heart at the chest.", origin: "Hand-embroidered detail added by a women-led cooperative we partner with." },
    { id: "k5", name: "Otto Cord Trousers", page: "kids", cat: "Trousers", price: 52, type: "trousers", palette: "sand", rating: 4.6, reviews: 27, swatches: ["#9C7B53", "#7E6E5C"], desc: "Fine-wale corduroy with an elastic back and reinforced knees.", origin: "Woven from organic cotton corduroy in a small Portuguese mill." },
    { id: "k6", name: "Maple Quilted Coat", page: "kids", cat: "Outerwear", price: 78, type: "coat", palette: "clay", rating: 4.8, reviews: 34, swatches: ["#8C4E38", "#C9B7A6"], desc: "A warm little coat with a soft-lined hood for cold mornings.", origin: "Filled with recycled insulation; shell made from regenerated fibres." },
    { id: "k7", name: "Wisp Pleated Skirt", page: "kids", cat: "Skirts", price: 44, type: "skirt", palette: "mauve", rating: 4.7, reviews: 22, swatches: ["#8E5F63", "#E2BEAF"], desc: "A twirl-tested pleated skirt with a comfy elastic waist.", origin: "Pleated from the same satin-back crepe as our womenswear, scaled down." },
    { id: "k8", name: "Birch Linen Blouse", page: "kids", cat: "Tops", price: 46, type: "blouse", palette: "pearl", rating: 4.6, reviews: 19, swatches: ["#9E7E68", "#FFFFFF"], desc: "A featherlight linen blouse with a scalloped collar.", origin: "European flax, softened through a gentle stone-wash." },

    // ---- Beauty ----
    { id: "b1", name: "Origin Satin Lipstick", page: "beauty", cat: "Lips", price: 34, type: "lipstick", palette: "clay", tag: "New", featured: true, rating: 4.8, reviews: 212, swatches: ["#9E3B2E", "#A8634F", "#8C4E38", "#C0584A"], desc: "A creamy satin-finish lipstick in six story-led shades.", origin: "Formulated without parabens or fragrance, in refillable brass cases." },
    { id: "b2", name: "Dusk Eyeshadow Quartet", page: "beauty", cat: "Eyes", price: 48, type: "palette", palette: "mauve", featured: true, rating: 4.7, reviews: 144, swatches: ["#8E5F63", "#C9B7A6", "#7E6E5C", "#E2BEAF"], desc: "Four blendable shades — matte to soft shimmer — in one slim compact.", origin: "Pressed with mineral pigments and finished in a recyclable case." },
    { id: "b3", name: "Halo Glow Serum", page: "beauty", cat: "Skin", price: 62, type: "serum", palette: "pearl", tag: "New", rating: 4.9, reviews: 308, swatches: ["#E2BEAF"], desc: "A lightweight hydrating serum with niacinamide and squalane.", origin: "Made in small batches; bottled in recycled, refill-ready glass." },
    { id: "b4", name: "Première Eau de Parfum", page: "beauty", cat: "Fragrance", price: 96, type: "perfume", palette: "sand", featured: true, rating: 4.8, reviews: 97, swatches: ["#9C7B53"], desc: "A warm, woody-floral signature scent with notes of rose and amber.", origin: "Blended by an independent perfumer in Grasse, the heart of fine fragrance." },
    { id: "b5", name: "Veil Tinted Balm", page: "beauty", cat: "Lips", price: 26, type: "lipstick", palette: "rose", rating: 4.6, reviews: 176, swatches: ["#E0AE9C", "#C0584A", "#A8634F"], desc: "A sheer, buildable balm that conditions while it tints.", origin: "Built on a base of shea and jojoba; cruelty-free and refillable." },
    { id: "b6", name: "Glow Cheek Compact", page: "beauty", cat: "Cheeks", price: 38, type: "palette", palette: "blush", rating: 4.7, reviews: 121, swatches: ["#E0AE9C", "#C08B73", "#A8634F"], desc: "A duo blush-and-highlight compact for an easy lit-from-within flush.", origin: "Mineral pigments pressed in a refillable magnetic case." },
    { id: "b7", name: "Quiet Hour Night Oil", page: "beauty", cat: "Skin", price: 58, type: "serum", palette: "taupe", rating: 4.8, reviews: 88, swatches: ["#9C7B53"], desc: "A nourishing facial oil with rosehip and bakuchiol for overnight repair.", origin: "Cold-pressed botanicals, bottled with a glass dropper." },
    { id: "b8", name: "Soir Travel Perfume", page: "beauty", cat: "Fragrance", price: 46, type: "perfume", palette: "mauve", rating: 4.6, reviews: 54, swatches: ["#8E5F63"], desc: "A purse-sized refillable spray of our signature evening scent.", origin: "Refillable atomiser designed to be kept, not discarded." },

    // ---- Accessories ----
    { id: "a1", name: "Edie Leather Tote", page: "accessories", cat: "Bags", price: 265, type: "bag", palette: "clay", tag: "New", featured: true, rating: 4.9, reviews: 67, swatches: ["#8C4E38", "#2A2521", "#9C7B53"], desc: "A structured everyday tote in vegetable-tanned leather with a soft handle.", origin: "Cut and stitched by a family leatherworks in Florence, tanned without chrome." },
    { id: "a2", name: "Lune Drop Earrings", page: "accessories", cat: "Jewellery", price: 88, type: "earrings", palette: "sand", featured: true, rating: 4.8, reviews: 103, swatches: ["#C9B7A6", "#9C7B53"], desc: "Sculptural drop earrings in 14k gold-plated recycled brass.", origin: "Cast from recycled brass and plated in a workshop in Jaipur." },
    { id: "a3", name: "Faye Silk Scarf", page: "accessories", cat: "Scarves", price: 74, type: "scarf", palette: "mauve", rating: 4.7, reviews: 49, swatches: ["#8E5F63", "#E0AE9C", "#9C7B53"], desc: "A hand-rolled silk twill scarf printed with an original house motif.", origin: "Printed and hand-rolled by an artisan studio in Lyon." },
    { id: "a4", name: "Margaux Sunglasses", page: "accessories", cat: "Eyewear", price: 135, type: "sunglasses", palette: "taupe", tag: "New", featured: true, rating: 4.8, reviews: 71, swatches: ["#7E6E5C", "#2A2521"], desc: "Rounded acetate frames with polarised lenses and a timeless line.", origin: "Hand-polished from bio-acetate in a small Italian eyewear atelier." },
    { id: "a5", name: "Sol Wide-Brim Hat", page: "accessories", cat: "Hats", price: 92, type: "hat", palette: "sand", rating: 4.6, reviews: 38, swatches: ["#9C7B53", "#2A2521"], desc: "A packable wide-brim hat woven from natural straw with a grosgrain band.", origin: "Hand-woven from sustainably harvested raffia by a cooperative in Madagascar." },
    { id: "a6", name: "Ines Minimal Watch", page: "accessories", cat: "Jewellery", price: 178, type: "watch", palette: "pearl", rating: 4.7, reviews: 44, swatches: ["#9E7E68", "#2A2521"], desc: "A pared-back watch with a clean dial and an interchangeable strap.", origin: "Assembled with a Swiss movement and a vegetable-tanned leather strap." },
    { id: "a7", name: "Vela Quilted Crossbody", page: "accessories", cat: "Bags", price: 185, type: "bag", palette: "mauve", rating: 4.8, reviews: 52, swatches: ["#8E5F63", "#2A2521"], desc: "A compact quilted crossbody with a gold-tone chain and roomy interior.", origin: "Made from regenerated leather offcuts, stitched in Florence." },
    { id: "a8", name: "Romy Pearl Studs", page: "accessories", cat: "Jewellery", price: 64, type: "earrings", palette: "pearl", rating: 4.9, reviews: 118, swatches: ["#E2BEAF", "#9E7E68"], desc: "Freshwater pearl studs set on recycled gold-plated posts.", origin: "Ethically sourced freshwater pearls, hand-set in a small studio." }
];
window.PRODUCTS = PRODUCTS;

/* =================================================================
   Helpers
   ================================================================= */
const $ = (s, r = document) => r.querySelector(s);
const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));
const money = n => SITE.currency + Number(n).toFixed(0);
const byId = id => PRODUCTS.find(p => p.id === id);
function stars(r) {
    const full = Math.round(r);
    return Array.from({ length: 5 }, (_, i) => `<span style="color:${i < full ? 'var(--rose)' : 'var(--line-2)'}">${UI.star}</span>`).join("");
}

/* ---------- Theme (apply customizable colours) ---------- */
function applyTheme() {
    const c = SITE.colors, root = document.documentElement.style;
    root.setProperty("--rose", c.rose);
    root.setProperty("--rose-deep", c.roseDeep);
    root.setProperty("--rose-light", c.roseLight);
    root.setProperty("--rose-soft", c.roseSoft);
    root.setProperty("--gold-hi", c.goldHi);
    root.setProperty("--ink", c.ink);
    root.setProperty("--cream", c.cream);
    root.setProperty("--metal",
        `linear-gradient(118deg, ${c.goldHi} 0%, ${c.roseLight} 20%, ${c.rose} 40%, ${c.roseDeep} 50%, ${c.rose} 60%, ${c.roseLight} 80%, ${c.goldHi} 100%)`);
}

/* ---------- Logo (customizable SVG-style wordmark) ---------- */
function logoHTML(cls = "") {
    return `<a class="logo ${cls}" href="index.html" aria-label="${SITE.brand} — home">
    <span class="logo__mark" data-text="${SITE.brand}">${SITE.brand}</span>
    <span class="logo__tag">${SITE.tagline}</span>
  </a>`;
}

/* ---------- Header ---------- */
function renderHeader() {
    const host = $("#site-header"); if (!host) return;
    const current = document.body.dataset.page || "";
    const links = SITE.nav.map(n =>
        `<a href="${n.href}" ${n.href.startsWith(current) && current ? 'aria-current="page"' : ""}>${n.label}</a>`).join("");
    host.innerHTML = `
    <div class="announce">${SITE.announce.replace(/\$\d+/g, m => `<span>${m}</span>`)}</div>
    <header class="site-header" id="hdr">
      <div class="container">
        <nav class="nav">
          <div class="nav__links">${links}</div>
          <div class="nav__brand">${logoHTML("logo--sm")}</div>
          <div class="nav__actions">
            <button class="icon-btn js-search" aria-label="Search">${UI.search}</button>
            <a class="icon-btn" href="account.html" aria-label="Account">${UI.user}</a>
            <a class="icon-btn" href="cart.html" aria-label="Shopping bag">${UI.bag}<span class="bag-count">0</span></a>
            <button class="icon-btn nav__toggle js-menu-open" aria-label="Menu">${UI.menu}</button>
          </div>

        </nav>
      </div>
    </header>`;
}

/* ---------- Mobile menu ---------- */
function renderMobileMenu() {
    const links = SITE.nav.map(n => `<a href="${n.href}">${n.label}</a>`).join("");
    const el = document.createElement("div");
    el.className = "mobile-menu"; el.id = "mobileMenu";
    el.innerHTML = `
    <div class="mobile-menu__head">
      ${logoHTML("logo--sm")}
      <button class="icon-btn js-menu-close" aria-label="Close">${UI.close}</button>
    </div>
    <nav>${links}</nav>
    <div class="mobile-menu__foot">
      <a class="btn btn--ghost btn--sm" href="cart.html">Bag</a>
      <a class="btn btn--rose btn--sm" href="contact.html">Contact</a>
    </div>`;
    document.body.appendChild(el);
}

/* ---------- Footer ---------- */
function renderFooter() {
    const host = $("#site-footer"); if (!host) return;
    const cols = SITE.footer.columns.map(c => `
    <div><h4>${c.title}</h4><ul>${c.links.map(l => `<li><a href="${l.href}">${l.label}</a></li>`).join("")}</ul></div>`).join("");
    host.innerHTML = `
    <footer class="site-footer">
      <div class="container">
        <div class="footer__top">
          <div class="footer__brand">
            ${logoHTML("logo--light")}
            <p>${SITE.footer.blurb}</p>
            <div class="footer__socials">
              <a href="#" aria-label="Instagram">${UI.instagram}</a>
              <a href="#" aria-label="Pinterest">${UI.pinterest}</a>
              <a href="#" aria-label="Facebook">${UI.facebook}</a>
              <a href="#" aria-label="TikTok">${UI.tiktok}</a>
            </div>
          </div>
          ${cols}
        </div>
        <div class="footer__bottom">
          <span>© ${new Date().getFullYear()} ${SITE.brand}. All rights reserved.</span>
          <span class="pay" aria-label="Accepted payments"><i></i><i></i><i></i><i></i></span>
        </div>
      </div>
    </footer>`;
}

/* ---------- Product card ---------- */
function productCard(p) {
    const tagClass = (p.tag && p.tag.startsWith("-")) ? "prod__tag--ink" : "";
    const tag = p.tag ? `<span class="prod__tag ${tagClass}">${p.tag}</span>` : "";
    const sw = (p.swatches || []).slice(0, 4).map(c => `<i style="background:${c}"></i>`).join("");
    const price = p.was
        ? `<span class="prod__price"><s>${money(p.was)}</s>${money(p.price)}</span>`
        : `<span class="prod__price">${money(p.price)}</span>`;
    return `<article class="prod">
    <div class="prod__media" style="${mediaStyle(p)}">
      ${tag}
      <button class="prod__fav js-fav" data-id="${p.id}" aria-label="Save ${p.name}">${UI.heart}</button>
      <a href="product.html?id=${p.id}" aria-label="${p.name}">${illus(p.type)}</a>
      <button class="prod__add js-add" data-id="${p.id}">Add to bag</button>
    </div>
    <a class="prod__name" href="product.html?id=${p.id}">${p.name}</a>
    <div class="prod__meta"><span class="prod__cat">${p.cat}</span>${price}</div>
    <div class="prod__swatches">${sw}</div>
  </article>`;
}

/* ---------- Mount product grids declared via data-products ---------- */
function mountGrids() {
    $$("[data-products]").forEach(grid => {
        const f = grid.dataset.filter || "all";
        let list = PRODUCTS.slice();
        if (f === "featured") list = list.filter(p => p.featured);
        else if (f === "new") list = list.filter(p => p.tag === "New");
        else if (["women", "kids", "beauty", "accessories"].includes(f)) list = list.filter(p => p.page === f);
        const limit = parseInt(grid.dataset.limit || "0", 10);
        if (limit) list = list.slice(0, limit);
        grid.innerHTML = list.map(productCard).join("");
        const counter = document.querySelector(grid.dataset.count || "___none___");
        if (counter) counter.textContent = `${list.length} pieces`;
    });
}

/* =================================================================
   Cart (in-memory for this session)
   ================================================================= */
let CART = [];
function bagCount() { return CART.reduce((n, i) => n + i.qty, 0); }
function updateBag() { $$(".bag-count").forEach(el => { el.textContent = bagCount(); el.style.display = bagCount() ? "grid" : "none"; }); }
function addToCart(id, { size = "One Size", color = null, qty = 1 } = {}) {
    const p = byId(id); if (!p) return;
    color = color || (p.swatches && p.swatches[0]) || "#BC8472";
    const key = id + "|" + size + "|" + color;
    const ex = CART.find(i => i.key === key);
    if (ex) ex.qty += qty; else CART.push({ key, id, size, color, qty });
    updateBag(); renderCart();
    toast(`Added to bag — ${p.name}`);
}

function renderCart() {
    const root = $("#cart-root"); if (!root) return;
    if (CART.length === 0) {
        root.innerHTML = `
      <div class="cart-empty reveal in">
        <div class="ic">${UI.bag}</div>
        <h2 class="h-section">Your bag is empty</h2>
        <p class="muted" style="margin:.8rem 0 1.8rem">Once you add something, it will live here, ready when you are.</p>
        <a class="btn btn--rose" href="women.html">Start with Women ${UI.arrow}</a>
      </div>`;
        return;
    }
    const lines = CART.map(item => {
        const p = byId(item.id);
        return `<div class="cart-line" data-key="${item.key}">
      <div class="cart-line__media" style="${mediaStyle(p)}">${illus(p.type)}</div>
      <div>
        <h3>${p.name}</h3>
        <div class="cart-line__sub">${p.cat} · Size ${item.size} · <span style="display:inline-block;width:11px;height:11px;border-radius:50%;background:${item.color};vertical-align:middle;border:1px solid rgba(0,0,0,.12)"></span></div>
        <div class="qty" style="margin-top:.8rem">
          <button class="js-qty" data-key="${item.key}" data-dir="-1" aria-label="Decrease">–</button>
          <span>${item.qty}</span>
          <button class="js-qty" data-key="${item.key}" data-dir="1" aria-label="Increase">+</button>
        </div>
      </div>
      <div>
        <div class="cart-line__price">${money(p.price * item.qty)}</div>
        <button class="cart-line__remove js-remove" data-key="${item.key}">Remove</button>
      </div>
    </div>`;
    }).join("");
    const sub = CART.reduce((s, i) => s + byId(i.id).price * i.qty, 0);
    const ship = sub >= 120 || sub === 0 ? 0 : 9;
    const total = sub + ship;
    root.innerHTML = `
    <div class="cart-grid">
      <div>
        <div class="toolbar" style="margin-bottom:.5rem">
          <span class="result-count">${bagCount()} item${bagCount() > 1 ? "s" : ""} in your bag</span>
          <button class="link-underline js-clear">Clear all</button>
        </div>
        ${lines}
      </div>
      <aside class="summary">
        <h3>Order Summary</h3>
        <div class="promo">
          <input type="text" placeholder="Promo code" aria-label="Promo code">
          <button class="btn btn--ghost btn--sm js-promo">Apply</button>
        </div>
        <div class="summary__row"><span>Subtotal</span><span>${money(sub)}</span></div>
        <div class="summary__row"><span>Shipping</span><span>${ship === 0 ? "Complimentary" : money(ship)}</span></div>
        <div class="summary__row"><span>Estimated tax</span><span>Calculated at checkout</span></div>
        <div class="summary__row total"><span>Total</span><span>${money(total)}</span></div>
        <button class="btn btn--rose btn--block js-checkout" style="margin-top:1.4rem">Proceed to checkout ${UI.arrow}</button>
        <p class="muted" style="font-size:.74rem;text-align:center;margin-top:1rem">Secure checkout · ${ship === 0 ? "You've unlocked free shipping" : `Add ${money(120 - sub)} for free shipping`}</p>
      </aside>
    </div>`;
}

/* seed a couple of items so the cart page is demonstrable */
function seedCart() {
    if ($("#cart-root") && CART.length === 0) {
        addToCartSilent("w4", { size: "M", color: "#9E7E68", qty: 1 });
        addToCartSilent("a2", { size: "One Size", color: "#C9B7A6", qty: 1 });
        renderCart(); updateBag();
    }
}
function addToCartSilent(id, opts) { const before = toast; window.__silent = true; addToCart(id, opts); window.__silent = false; }

/* =================================================================
   Product detail page
   ================================================================= */
function renderPDP() {
    const root = $("#pdp-root"); if (!root) return;
    const id = new URLSearchParams(location.search).get("id") || "w1";
    const p = byId(id) || PRODUCTS[0];
    document.title = `${p.name} — ${SITE.brand}`;
    const sizes = p.page === "beauty" ? ["Standard"] :
        p.page === "accessories" ? ["One Size"] :
            p.page === "kids" ? ["2–3y", "4–5y", "6–7y", "8–9y"] : ["XS", "S", "M", "L", "XL"];
    const swatches = p.swatches || ["#BC8472"];
    const save = p.was ? Math.round((1 - p.price / p.was) * 100) : 0;

    root.innerHTML = `
    <div class="crumb reveal in">
      <a href="index.html">Home</a><span>/</span>
      <a href="${p.page}.html">${p.page.charAt(0).toUpperCase() + p.page.slice(1)}</a><span>/</span>
      ${p.cat}
    </div>
    <div class="pdp">
      <div class="pdp__gallery reveal" data-d="1">
        <div class="pdp__main" id="pdpMain" style="${mediaStyle(p)}">${illus(p.type)}</div>
        <div class="pdp__thumbs">
          ${[0, 1, 2, 3].map(i => `<div class="pdp__thumb ${i === 0 ? 'active' : ''}" style="${mediaStyle(p)}">${illus(p.type)}</div>`).join("")}
        </div>
      </div>
      <div class="pdp__info reveal" data-d="2">
        <span class="eyebrow">${p.cat}</span>
        <h1>${p.name}</h1>
        <div class="pdp__rating">${stars(p.rating)} <span class="muted">${p.rating} · ${p.reviews} reviews</span></div>
        <div class="pdp__price">${money(p.price)} ${p.was ? `<s>${money(p.was)}</s><span class="save">Save ${save}%</span>` : ""}</div>
        <p class="lead" style="font-size:1.05rem">${p.desc}</p>

        <div class="opt">
          <div class="opt__label"><span>Colour</span><span id="colorName" style="text-transform:none;letter-spacing:0;color:var(--ink-2)"></span></div>
          <div class="swatch-row" id="swatches">
            ${swatches.map((c, i) => `<button class="swatch ${i === 0 ? 'active' : ''}" data-color="${c}" style="background:${c}" aria-label="Colour ${i + 1}"></button>`).join("")}
          </div>
        </div>

        <div class="opt">
          <div class="opt__label"><span>${p.page === "beauty" || p.page === "accessories" ? "Option" : "Size"}</span><a href="contact.html" class="link-underline" style="font-size:.66rem">Size guide</a></div>
          <div class="size-row" id="sizes">
            ${sizes.map((s, i) => `<button class="size ${i === Math.min(2, sizes.length - 1) ? 'active' : ''}">${s}</button>`).join("")}
          </div>
        </div>

        <div class="pdp__buy">
          <div class="qty">
            <button class="js-pdp-qty" data-dir="-1" aria-label="Decrease">–</button>
            <span id="pdpQty">1</span>
            <button class="js-pdp-qty" data-dir="1" aria-label="Increase">+</button>
          </div>
          <button class="btn btn--rose js-pdp-add" data-id="${p.id}">Add to bag · ${money(p.price)}</button>
        </div>

        <div class="usp-row">
          <span class="usp">${UI.truck} Free shipping over $120</span>
          <span class="usp">${UI.refresh} 30-day returns</span>
          <span class="usp">${UI.leaf} Responsibly made</span>
        </div>

        <div class="accordion">
          <div class="acc open">
            <button class="acc__head js-acc">The Origin Story <span class="pm">+</span></button>
            <div class="acc__body"><p>${p.origin}</p></div>
          </div>
          <div class="acc">
            <button class="acc__head js-acc">Details &amp; Materials <span class="pm">+</span></button>
            <div class="acc__body"><p>Thoughtfully constructed for longevity. Each piece arrives in recycled, plastic-free packaging with a card telling you where it began. ${p.page === "beauty" ? "Dermatologist-tested and cruelty-free." : "Care: follow the label to keep it at its best for years."}</p></div>
          </div>
          <div class="acc">
            <button class="acc__head js-acc">Shipping &amp; Returns <span class="pm">+</span></button>
            <div class="acc__body"><p>Complimentary carbon-neutral shipping on orders over $120. Returns are free within 30 days — we'll send a prepaid label and re-home or recycle anything that comes back.</p></div>
          </div>
        </div>
      </div>
    </div>

    <section class="section--tight" style="margin-top:2rem">
      <div class="sec-head"><div><span class="eyebrow">You may also like</span><h2 class="h-section sec-head__title" style="margin-top:.6rem">Pieces with a kindred spirit</h2></div></div>
      <div class="prod-grid" id="relatedGrid"></div>
    </section>`;

    // related
    const related = PRODUCTS.filter(x => x.page === p.page && x.id !== p.id).slice(0, 4);
    $("#relatedGrid").innerHTML = related.map(productCard).join("");

    // colour name label
    const names = ["Rosewood", "Ink", "Oat", "Clay", "Blush", "Sand", "Mauve", "Pearl"];
    const setColorName = i => { $("#colorName").textContent = names[i % names.length]; };
    setColorName(0);

    // interactions specific to PDP
    let qty = 1;
    $$(".js-pdp-qty").forEach(b => b.addEventListener("click", () => {
        qty = Math.max(1, qty + parseInt(b.dataset.dir, 10));
        $("#pdpQty").textContent = qty;
    }));
    $$("#swatches .swatch").forEach((b, i) => b.addEventListener("click", () => {
        $$("#swatches .swatch").forEach(s => s.classList.remove("active"));
        b.classList.add("active"); setColorName(i);
    }));
    $$("#sizes .size").forEach(b => b.addEventListener("click", () => {
        $$("#sizes .size").forEach(s => s.classList.remove("active")); b.classList.add("active");
    }));
    $(".js-pdp-add").addEventListener("click", () => {
        const color = $("#swatches .swatch.active")?.dataset.color;
        const size = $("#sizes .size.active")?.textContent || "One Size";
        addToCart(p.id, { size, color, qty });
    });
}

/* =================================================================
   Generic interactions
   ================================================================= */
function wireInteractions() {
    // sticky header shadow
    const hdr = $("#hdr");
    const onScroll = () => hdr && hdr.classList.toggle("scrolled", window.scrollY > 16);
    onScroll(); window.addEventListener("scroll", onScroll, { passive: true });

    // mobile menu
    document.addEventListener("click", e => {
        if (e.target.closest(".js-menu-open")) $("#mobileMenu")?.classList.add("open");
        if (e.target.closest(".js-menu-close")) $("#mobileMenu")?.classList.remove("open");
    });

    // delegated buttons
    document.addEventListener("click", e => {
        const add = e.target.closest(".js-add");
        if (add) { addToCart(add.dataset.id); return; }

        const fav = e.target.closest(".js-fav");
        if (fav) {
            fav.classList.toggle("active");
            fav.innerHTML = fav.classList.contains("active") ? UI.heartFill : UI.heart;
            return;
        }

        const acc = e.target.closest(".js-acc");
        if (acc) {
            const item = acc.closest(".acc");
            const body = item.querySelector(".acc__body");
            const open = item.classList.toggle("open");
            body.style.maxHeight = open ? body.scrollHeight + "px" : "0";
            return;
        }

        const q = e.target.closest(".js-qty");
        if (q) {
            const it = CART.find(i => i.key === q.dataset.key);
            if (it) { it.qty = Math.max(1, it.qty + parseInt(q.dataset.dir, 10)); updateBag(); renderCart(); }
            return;
        }
        const rm = e.target.closest(".js-remove");
        if (rm) { CART = CART.filter(i => i.key !== rm.dataset.key); updateBag(); renderCart(); toast("Removed from bag"); return; }
        if (e.target.closest(".js-clear")) { CART = []; updateBag(); renderCart(); return; }
        if (e.target.closest(".js-checkout")) { e.preventDefault(); toast("Checkout is a demo — no payment taken ✦"); return; }
        if (e.target.closest(".js-promo")) { e.preventDefault(); toast("Promo codes are disabled in this demo"); return; }
        if (e.target.closest(".js-search")) { toast("Search is a demo in this preview"); return; }

        // pill filters (decorative active state)
        const pill = e.target.closest(".pill");
        if (pill && pill.parentElement.classList.contains("pills")) {
            pill.parentElement.querySelectorAll(".pill").forEach(x => x.classList.remove("active"));
            pill.classList.add("active");
            return;
        }
        // pagination (decorative)
        const pg = e.target.closest(".pagination button");
        if (pg) { pg.parentElement.querySelectorAll("button").forEach(x => x.classList.remove("active")); pg.classList.add("active"); }
    });

    // open first accordion bodies on load (set max-height)
    $$(".acc.open .acc__body").forEach(b => b.style.maxHeight = b.scrollHeight + "px");

    // forms (newsletter / contact)
    $$(".js-form").forEach(f => f.addEventListener("submit", e => {
        e.preventDefault();
        f.reset?.();
        toast(f.dataset.toast || "Thank you — we'll be in touch ✦");
    }));
}

/* ---------- Reveal on scroll ---------- */
function wireReveals() {
    const els = $$(".reveal:not(.in)");
    if (!("IntersectionObserver" in window)) { els.forEach(el => el.classList.add("in")); return; }
    const io = new IntersectionObserver((entries) => {
        entries.forEach(en => { if (en.isIntersecting) { en.target.classList.add("in"); io.unobserve(en.target); } });
    }, { threshold: .12, rootMargin: "0px 0px -8% 0px" });
    els.forEach(el => io.observe(el));
}

/* ---------- Toast ---------- */
function toast(msg) {
    if (window.__silent) return;
    let wrap = $(".toast-wrap");
    if (!wrap) { wrap = document.createElement("div"); wrap.className = "toast-wrap"; document.body.appendChild(wrap); }
    const t = document.createElement("div");
    t.className = "toast";
    t.innerHTML = `<span class="ic">${UI.check}</span><span>${msg}</span>`;
    wrap.appendChild(t);
    requestAnimationFrame(() => t.classList.add("show"));
    setTimeout(() => { t.classList.remove("show"); setTimeout(() => t.remove(), 500); }, 2600);
}

/* =================================================================
   Boot
   ================================================================= */
document.addEventListener("DOMContentLoaded", () => {
    applyTheme();
    renderHeader();
    renderMobileMenu();
    renderFooter();
    mountGrids();
    renderPDP();
    seedCart();
    renderCart();
    updateBag();
    wireInteractions();
    wireReveals();
});

window.POST = { SITE, PRODUCTS, addToCart, toast };

app.use(express.static('public'));

