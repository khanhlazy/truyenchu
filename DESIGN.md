---
name: Premium Digital Library
colors:
  background: '#FEF7FF'
  surface: '#FFFFFF'
  surface-variant: '#F3EBF9'
  surface-elevated: '#EDE5F3'
  primary-text: '#1D1A24'
  secondary-text: '#4A4455'
  muted-text: '#7B7486'
  primary-action: '#5300B7'
  secondary-action: '#B4136D'
  highlight-gradient: '#5300B7 → #FD56A7'
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

This design system is created to deliver a premium digital reading experience that prioritizes clarity, comfort, and emotional immersion. The product combines Modern Minimalism with Editorial Elegance, creating an interface that feels calm, intelligent, and production-ready.

Unlike traditional content-heavy reading platforms, this experience focuses on content hierarchy, reading flow, and interaction simplicity. Every design decision should reduce friction and help users stay engaged with the content for extended reading sessions.

The emotional response should feel like:

Focused
Calm
Premium
Intellectual
Immersive

Users should feel as though they are entering a curated digital library designed for serious reading.

## Colors

The color system is built around a deep purple identity that communicates intelligence, creativity, and premium quality, paired with a rose accent for interaction feedback and emotional warmth.

### Background & Surface

A soft neutral background is used to reduce visual fatigue and create an elegant reading environment.

Background: #FEF7FF
Surface: #FFFFFF
Surface Variant: #F3EBF9
Surface Elevated: #EDE5F3

These colors create subtle depth without relying on aggressive shadows or harsh contrast.

### Typography Colors

Text hierarchy uses strong contrast for content readability.

Primary Text: #1D1A24
Secondary Text: #4A4455
Muted Text: #7B7486

Reading content should always maintain high contrast while avoiding pure black to reduce eye strain.

### Accent Colors

Accent colors are used sparingly to preserve visual balance.

Primary Action: #5300B7
Secondary Action: #B4136D
Highlight Gradient: #5300B7 → #FD56A7

Accent colors should only appear in:

Primary buttons
Progress indicators
Interactive states
Premium membership indicators

## Typography

The typography system uses a dual-font strategy to balance interface clarity with long-form reading comfort.

### Interface Font

Be Vietnam Pro

Used for:

Navigation
Buttons
Labels
Dashboard elements
Form fields

This font ensures modern readability across all screen sizes.

### Reading Font

Merriweather

Used exclusively for:

Articles
Books
Chapters
Editorial content

Its serif construction improves eye tracking and supports extended reading sessions.

### Typography Hierarchy

#### Heading 1

Used for page titles and hero sections.

Font Size: 32px
Weight: 700
Line Height: 1.2
Letter Spacing: -0.02em

#### Heading 2

Used for content sections.

Font Size: 24px
Weight: 600
Line Height: 1.3

#### Heading 3

Used for content cards.

Font Size: 20px
Weight: 600
Line Height: 1.4

#### Reading Body

Used for long-form content.

Font Size: 18px
Weight: 400
Line Height: 1.8
Letter Spacing: 0.01em
Max Width: 700px

This width ensures optimal readability on desktop screens.

## Layout & Spacing

The layout system follows an 8px spacing scale to create consistency across components and screen sizes.

### Grid System

#### Desktop
12 Columns
Max Width: 1280px
Gutter: 24px

#### Tablet
8 Columns

#### Mobile
4 Columns
Padding: 16px

### Spacing Tokens

XS: 4px
SM: 8px
MD: 16px
LG: 24px
XL: 32px
2XL: 48px
3XL: 64px

Horizontal spacing should generally use 16px, while major vertical sections should use 48px.

## Elevation & Depth

The visual hierarchy uses tonal surfaces and soft ambient shadows.

### Level 0 — Background
#FEF7FF

Used for application background.

### Level 1 — Cards & Containers
#FFFFFF

Shadow:

0 4px 6px -1px rgb(0 0 0 / 0.05),
0 2px 4px -2px rgb(0 0 0 / 0.05)

Used for:

Book cards
Content sections
Navigation containers

### Level 2 — Overlays & Floating Elements

Used for:

Dropdown menus
Modals
Reading settings
Search suggestions

These elements should have stronger elevation and background blur.

## Shapes

The design language uses rounded geometry to feel modern and approachable.

### Radius System

Small: 4px
Default: 8px
Medium: 12px
Large: 16px
Extra Large: 24px
Full: 9999px

Usage:

Buttons: 8px
Inputs: 8px
Cards: 16px
Tags: Full radius

## Components

### Buttons

#### Primary Button

Used for primary actions.

Background: #5300B7
Text: #FFFFFF
Radius: 8px

Hover state should slightly increase elevation.

#### Secondary Button

Used for secondary interactions.

Border: 1px solid #B4136D
Text: #B4136D
Background: Transparent

### Cards

Cards should follow a vertical content structure.

Structure:

Thumbnail
Title
Metadata
Action

Hover behavior:

Lift 2px
Increase shadow
Smooth transition

### Input Fields

Structure:

Border: 1px solid #E5E7EB
Radius: 8px
Padding: 16px

Focus state:

Border: 2px solid #5300B7

### Progress Indicators

Reading progress should use subtle thickness.

Height: 4px
Color: #FD56A7
Radius: Full

### Navigation

Desktop navigation should remain fixed with blur support.

Mobile navigation should use:

Fixed bottom positioning
Glassmorphism background
Safe thumb interaction zones