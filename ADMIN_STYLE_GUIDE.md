# Motor Bazar :: Elite Admin Design System (v1.0)
---

This document serves as the official design reference for the Motor Bazar Administrative Suite. All future developments should adhere to these specifications to maintain brand elite identity and visual consistency.

## 1. Core Color Palette (The Map)
These variables are defined in `:root` inside `admin/layout.blade.php`.

| Token | Hex Code | Usage |
| :--- | :--- | :--- |
| `Primary Orange` | `#ff4605` | Accents, Primary Buttons, Active States, Logo Emphasis. |
| `Branded Navy` | `#031629` | Sidebars, Navigation, Heavy Text, Deep Backgrounds. |
| `Slate Background`| `#f8fafc` | General App Background, subtle contrast for white cards. |
| `Border Slate` | `#f1f5f9` | Gentle dividers, component borders, structural lines. |
| `Text Main` | `#111827` | Headings, Primary Body Copy, High-contrast data. |

## 2. Visual Architecture & Surface
We follow a **"Sharp Modern"** approach with selective glassmorphism.

*   **Border Radius (The Rounding):**
    *   `Standard Containers:` `rounded-2xl` (1.5rem) - Used for major layout sections.
    *   `Stats/Inner Cards:` `rounded-xl` (1rem) - For internal dashboard components.
    *   `Buttons & Inputs:` `rounded-lg` (0.5rem) - Sharp, professional edges for interactivity.
*   **Surface Effects:**
    *   `Glassmorphism:` White backgrounds with `bg-white/60` and `backdrop-blur-md` for high-end depth.
    *   `Elevations:` Subtle shadows (`shadow-sm` or `shadow-md`) to prevent visual clutter.

## 3. Component Standards

### Table Design (Tabulator Elite)
*   **Header Background:** Horizontal gradient `linear-gradient(135deg, #1e293b 0%, #334155 100%)`.
*   **Header Bottom Border:** `3px solid var(--primary-orange)`.
*   **Row Interactions:** Hover state `scale(1.002) translateX(5px)` with a very subtle glow.
*   **Status Badges:** Pill-style with high-contrast text and low-opacity backgrounds.

### Typography
*   **Primary Font:** `Plus Jakarta Sans` (Google Fonts).
*   **Weights:** 
    *   `Black (900):` Headings & Key Numbers.
    *   `Extrabold (800):` Sub-headings & Labels.
    *   `Semibold (600):` Table utility data.

## 4. UI Philosophies
*   **Extra Polish:** Use entry animations (`animate-in slide-in-from-bottom`) for all major page loads.
*   **Density:** Prefer professional high-density layouts over excessive whitespace.
*   **Real-time:** Always include a visual pulse indicator if data is live.

---
*Maintained by Antigravity AI Engine for Motor Bazar.*
