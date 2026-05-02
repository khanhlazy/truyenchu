---
name: Premium Digital Library
colors:
  surface: '#fef7ff'
  surface-dim: '#dfd7e5'
  surface-bright: '#fef7ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f9f1ff'
  surface-container: '#f3ebf9'
  surface-container-high: '#ede5f3'
  surface-container-highest: '#e8e0ee'
  on-surface: '#1d1a24'
  on-surface-variant: '#4a4455'
  inverse-surface: '#332f39'
  inverse-on-surface: '#f6eefc'
  outline: '#7b7486'
  outline-variant: '#ccc3d7'
  surface-tint: '#7331df'
  primary: '#5300b7'
  on-primary: '#ffffff'
  primary-container: '#6d28d9'
  on-primary-container: '#dac5ff'
  inverse-primary: '#d3bbff'
  secondary: '#b4136d'
  on-secondary: '#ffffff'
  secondary-container: '#fd56a7'
  on-secondary-container: '#600037'
  tertiary: '#6b3000'
  on-tertiary: '#ffffff'
  tertiary-container: '#8f4200'
  on-tertiary-container: '#ffc19e'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ebddff'
  primary-fixed-dim: '#d3bbff'
  on-primary-fixed: '#250059'
  on-primary-fixed-variant: '#5b00c5'
  secondary-fixed: '#ffd9e4'
  secondary-fixed-dim: '#ffb0cd'
  on-secondary-fixed: '#3e0022'
  on-secondary-fixed-variant: '#8c0053'
  tertiary-fixed: '#ffdbc8'
  tertiary-fixed-dim: '#ffb68b'
  on-tertiary-fixed: '#321300'
  on-tertiary-fixed-variant: '#743400'
  background: '#fef7ff'
  on-background: '#1d1a24'
  surface-variant: '#e8e0ee'
typography:
  h1:
    fontFamily: Be Vietnam Pro
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  h2:
    fontFamily: Be Vietnam Pro
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.3'
    letterSpacing: -0.01em
  h3:
    fontFamily: Be Vietnam Pro
    fontSize: 20px
    fontWeight: '600'
    lineHeight: '1.4'
    letterSpacing: '0'
  body-reading:
    fontFamily: Merriweather
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.8'
    letterSpacing: 0.01em
  body-ui:
    fontFamily: Be Vietnam Pro
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.5'
    letterSpacing: '0'
  label-md:
    fontFamily: Be Vietnam Pro
    fontSize: 14px
    fontWeight: '500'
    lineHeight: '1.2'
    letterSpacing: 0.02em
  caption:
    fontFamily: Be Vietnam Pro
    fontSize: 12px
    fontWeight: '400'
    lineHeight: '1.2'
    letterSpacing: '0'
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 4px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  2xl: 48px
  3xl: 64px
  container-max: 1280px
  gutter: 24px
---

## Brand & Style

This design system is built to provide an immersive, distraction-free reading environment while maintaining a sophisticated, high-end digital library feel. The style merges **Modern Minimalism** with **Corporate Precision**, prioritizing content legibility and ease of navigation. 

The aesthetic is characterized by expansive white space, subtle depth through ambient shadows, and a refined use of vibrant accent colors (Purple and Pink) to guide user actions. The target audience values a premium reading experience that feels contemporary and "production-ready," moving away from the cluttered layouts often found in traditional reading platforms. The emotional response should be one of calm focus and intellectual curiosity.

## Colors

The color palette is anchored by a deep Purple primary, symbolizing wisdom and premium quality, paired with a vibrant Pink secondary for call-to-actions and interactive highlights. 

- **Background & Surface:** We use a very light slate background (#F8FAFC) to provide a soft contrast against the pure white (#FFFFFF) surfaces of cards and containers, reducing eye strain during long reading sessions.
- **Typography:** Text hierarchy is established through a high-contrast primary gray (#111827) and a softer secondary gray (#6B7280) for metadata and auxiliary information.
- **Accents:** Use gradients between Purple and Pink sparingly—only for high-impact areas like "Start Reading" buttons or premium member badges.

## Typography

The typography system follows a dual-font strategy to balance functional interface needs with literary comfort.

1.  **Interface (Be Vietnam Pro):** Used for all navigational elements, buttons, titles, and system feedback. It provides a contemporary, clean look that ensures high legibility at small sizes.
2.  **Reading (Merriweather):** Reserved exclusively for the actual story content. This serif typeface is optimized for screen reading, with a generous line height (1.8) and slight letter spacing to prevent character crowding.

**Hierarchy Rules:**
- Titles use tight line-heights and negative letter spacing for a punchy, modern look.
- Reading text must maintain a maximum line width of 700px to ensure optimal eye tracking across the screen.

## Layout & Spacing

This design system utilizes an **8px linear scale** for all spacing and layout decisions. The layout philosophy is a **Fluid Grid** that transitions into a fixed-width container for desktop displays to maintain the premium editorial feel.

- **Grid:** A 12-column grid is used for the main dashboard, while a single-column centered layout is enforced for the reading mode.
- **Margins:** Mobile screens utilize a minimum 16px side margin, while tablet and desktop increase this to 24px or 48px to allow the content "room to breathe."
- **Consistency:** Horizontal spacing between elements in a row should typically be 'md' (16px), while vertical section spacing should be '2xl' (48px) to clearly define content blocks.

## Elevation & Depth

Visual hierarchy is communicated through **Tonal Layering** and **Ambient Shadows**.

- **Level 0 (Background):** #F8FAFC. The lowest layer.
- **Level 1 (Cards/Surfaces):** #FFFFFF. Used for book cards, chapter lists, and navigation bars. These surfaces feature a very soft, diffused shadow: `0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05)`.
- **Level 2 (Dropdowns/Modals):** Floating elements that sit above Level 1. These use a more pronounced shadow to indicate interactivity and focus.
- **Outlines:** A subtle 1px border (#E5E7EB) is used on all Level 1 surfaces to maintain structural definition on white backgrounds, ensuring the UI feels "crisp" rather than "blurry."

## Shapes

The shape language is consistently **Rounded**, reflecting a modern and approachable brand personality. 

- **Standard Elements:** Buttons, input fields, and small cards use a 0.5rem (8px) radius.
- **Large Containers:** Content blocks and featured book sections use a 1rem (16px) radius to emphasize their importance.
- **Book Covers:** Should always feature a subtle 4px radius and a soft "inner shadow" to mimic the physical edge of a book.
- **Pills:** Category tags and status indicators use a full-round (999px) radius to distinguish them from structural elements.

## Components

Components in this design system must prioritize clarity and premium interaction.

- **Buttons:** 
  - *Primary:* Solid Purple (#6D28D9) with white text. High-emphasis.
  - *Secondary:* Ghost style with Pink (#EC4899) text and border. Used for "Add to Library" or "Share."
- **Cards (Book Display):** Vertical orientation. The book cover is the hero, followed by the title (H3) and author (Secondary Text). On hover, cards should lift slightly via an increased shadow.
- **Input Fields:** Soft gray borders (#E5E7EB) that transition to a 2px Purple border on focus. Use Be Vietnam Pro for all input text.
- **Chapter Navigation:** Fixed bottom bar on mobile with a "Glassmorphism" blur effect (backdrop-filter: blur(8px)) to show the text passing underneath while maintaining focus.
- **Progress Bars:** Thin 4px height bars using the Secondary Pink to show reading completion.
- **Reading Controls:** A floating or retractable menu for adjusting font size, background color (Themes), and line height.