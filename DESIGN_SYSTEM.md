# Apple Standard Design System

## Version 1.0 - Enforcement Constitution

This is not a style guide. This is a constraint system.

Violations of this document are regressions, not creative decisions.

**Related Documents:**

- [Enforcement & Automation](DESIGN_SYSTEM.md#appendix-b-enforcement--automation) - Pre-commit validation
- [Future Evolution](DESIGN_SYSTEM_ROADMAP.md) - Token-only mode, CI hardening (non-urgent)
- [Agent Rules](AGENTS.md) - AI agent operating constraints

---

## 1. Philosophy

- **Reduction over addition**: Remove elements until the UI breaks, then add one back.
- **Neutrality over expression**: Gray is the default state. Color is an exception requiring justification.
- **Function over decoration**: If it does not communicate information or enable action, remove it.
- **Restraint over creativity**: Expressiveness is discouraged. Consistency is mandatory.
- **Silence over noise**: Empty space is preferable to filled space. Visual weight must be earned.

**Core principle**: If a design decision makes the UI feel "designed", it is wrong.

---

## 2. Color System (Hard Constraints)

### 2.0 Design Tokens (MANDATORY)

**All colors MUST be referenced through design tokens.**

Hardcoded color values are **PERMANENTLY BANNED** in UI code.

#### Forbidden Patterns

```tsx
// ❌ FORBIDDEN - Hardcoded hex values
className = 'bg-[#0071e3] text-[#ffffff]';

// ❌ FORBIDDEN - Raw Tailwind semantic colors
className = 'bg-blue-500 text-green-600';

// ❌ FORBIDDEN - Direct gray utilities (use tokens)
className = 'bg-gray-50 text-gray-600';
```

#### Required Pattern

```tsx
// ✅ CORRECT - Design tokens only
className = 'bg-accent text-text-primary';
className = 'bg-surface-muted text-text-secondary';
className = 'border-border hover:bg-surface-hover';
```

#### Available Design Tokens

**Accent (Apple Blue)**:

- `accent` - Primary accent color
- `accent-hover` - Hover state

**Surface**:

- `surface` - White background
- `surface-muted` - Light gray background (gray-50)
- `surface-hover` - Hover state (gray-100/60)

**Border**:

- `border` - Standard border (gray-200)
- `border-muted` - Subtle border (gray-200/60)

**Text**:

- `text-primary` - Primary text (gray-900)
- `text-secondary` - Secondary text (gray-700)
- `text-tertiary` - Tertiary text (gray-600)
- `text-muted` - Muted text (gray-400)

**Danger (Destructive/Error)**:

- `danger` - Danger color (red-500)
- `danger-hover` - Hover state (red-600)
- `danger-bg` - Error background (red-50)
- `danger-border` - Error border (red-200)
- `danger-text` - Error text (red-600)

#### Enforcement Rule

If a required color does not exist in the token system:

1. **STOP** - Do not implement
2. **REPORT** - Request new token
3. **DOCUMENT** - Update this section
4. **IMPLEMENT** - Only after token is added

**Rationale**: Hardcoded colors break consistency, accessibility, enforcement, and agent reliability. Tokens prevent "just this once" exceptions that compound into chaos.

### 2.1 Accent Color

**Apple Blue**: `#0071e3`

This is the **ONLY** accent color in the system.

### 2.2 Allowed Usage

Apple Blue may **ONLY** appear in:

1. **Primary action buttons**
    - `bg-[#0071e3]`
    - Hover: `bg-[#0077ED]`
2. **Focus rings**
    - `ring-[#0071e3]`
    - No other ring colors permitted

**That is the complete list.**

### 2.3 Forbidden Usage

Apple Blue is **FORBIDDEN** in:

- Background sections
- Text emphasis
- Badges
- Status indicators
- Icon colors
- Borders (except focus rings)
- Hover states (except primary buttons)
- Table rows
- Card headers
- Sidebar highlights
- Progress bars (use gray)
- Links in body text (use gray with underline)

### 2.4 Error Color

**Red**: `#ef4444` (Tailwind `red-500` equivalent)

Red may **ONLY** appear in:

1. **Destructive action buttons**
    - Delete actions
    - Irreversible modifications
    - `bg-red-600`, `hover:bg-red-700`

2. **Error states**
    - Form validation errors: `text-red-500` or `text-red-600`
    - Error containers: `bg-red-50`, `border-red-200`
    - Error text: `text-red-600` or `text-red-700`

3. **Inline validation feedback**
    - Field-level error messages only

**Constraint**: Red must never be used for:

- Warnings
- Recommendations
- Tips
- Emphasis
- Status labels
- Section backgrounds

### 2.5 Neutral Color Palette

Allowed grays (Tailwind scale):

- `gray-50`: `#f9fafb`
- `gray-100`: `#f3f4f6`
- `gray-200`: `#e5e7eb`
- `gray-400`: `#9ca3af`
- `gray-500`: `#6b7280`
- `gray-600`: `#4b5563`
- `gray-700`: `#374151`
- `gray-800`: `#1f2937`
- `gray-900`: `#111827`
- White: `#ffffff`
- Black: `#000000`

**All other colors are forbidden.**

### 2.6 Forbidden Colors

The following color families are **PERMANENTLY BANNED** from this system:

#### Semantic Colors (Forbidden)

- `blue-50` through `blue-900` (except `#0071e3`)
- `green-*` (all shades)
- `yellow-*` (all shades)
- `orange-*` (all shades)
- `purple-*` (all shades)
- `pink-*` (all shades)
- `indigo-*` (all shades)
- `teal-*` (all shades)
- `cyan-*` (all shades)

#### Specifically Forbidden Patterns

- Success green backgrounds
- Warning yellow backgrounds
- Info blue backgrounds
- Any "semantic color" system
- Colored section headers
- Colored badges with backgrounds
- Gradient backgrounds
- Colored hover states (except primary button, destructive actions)

**If a color is not explicitly allowed in section 2.3–2.5, it is forbidden.**

---

## 3. Background Constraints

### 3.1 Allowed Backgrounds

Backgrounds may **ONLY** use:

1. **White** (`bg-white`, `#ffffff`)
    - Primary surface color
    - Card backgrounds
    - Form inputs
    - Modals

2. **Gray-50** (`bg-gray-50`)
    - Sidebar
    - Table headers (optional)
    - Collapsed section headers
    - Page backgrounds (when not white)

3. **Gray-100** with opacity (`bg-gray-100/40` to `bg-gray-100/60`)
    - Hover states on secondary buttons
    - Hover states on table rows (if needed)
    - Subtle hover feedback

4. **Red-50** (`bg-red-50`)
    - Error containers ONLY
    - Must include `border-red-200`

**Opacity constraint**: When using opacity, range is `40` to `60` only. No other values.

### 3.2 Forbidden Backgrounds

The following backgrounds are **PERMANENTLY BANNED**:

- `bg-blue-50` (common violation)
- `bg-green-50` (common violation)
- `bg-yellow-50` (common violation)
- `bg-orange-50`
- `bg-purple-50`
- Any semantic color with `-50` suffix
- Colored section backgrounds
- Gradient backgrounds
- Image backgrounds in structural UI
- Transparent backgrounds with colored overlays

**Test**: If a section has a colored background to "organize" or "highlight" it, remove it. Use typography hierarchy instead.

---

## 4. Typography Lockdown

### 4.1 Font Family

**System font stack** (mandatory):

```css
-apple-system, BlinkMacSystemFont, 'SF Pro', system-ui, sans-serif
```

**No exceptions.** No custom fonts. No Google Fonts. No brand fonts.

### 4.2 Allowed Font Weights

Only **3 weights** are permitted:

1. **Normal** (`font-normal`, `400`)
    - Default weight
    - Body text
    - Labels
2. **Medium** (`font-medium`, `500`)
    - Emphasis within text
    - Subheadings
3. **Semibold** (`font-semibold`, `600`)
    - Headings
    - Section titles
    - Button text (if needed)

**Forbidden weights**:

- `font-light` (300)
- `font-bold` (700)
- `font-extrabold` (800)
- `font-black` (900)

### 4.3 Allowed Text Colors

Text may **ONLY** use these colors:

1. **Black** (`text-black`, `text-gray-900`)
    - Primary headings
2. **Gray-700** (`text-gray-700`)
    - Body text
    - Primary labels
3. **Gray-600** (`text-gray-600`)
    - Secondary text
    - Descriptions
    - Captions
4. **Gray-500** (`text-gray-500`)
    - Tertiary text
    - Placeholders
5. **Gray-400** (`text-gray-400`)
    - Disabled text
    - De-emphasized labels
    - Asterisk indicators
6. **Red-500/600** (`text-red-500`, `text-red-600`)
    - Error messages ONLY

**Forbidden text colors**:

- `text-blue-600` (except Apple Blue in buttons)
- `text-green-600` (common violation)
- `text-yellow-600`
- `text-orange-600` (common violation)
- `text-purple-600`
- Any semantic color for emphasis

**Anti-pattern**: Colored text to indicate importance or category.

**Correct pattern**: Use font weight and size hierarchy instead.

### 4.4 Text Decoration

Allowed:

- `underline` (for links)
- `line-through` (for strikethrough prices)

Forbidden:

- Colored underlines
- Background highlights on text
- Text shadows
- Gradient text

---

## 5. Component Constraints

### 5.1 Button Component

**Location**: `resources/js/components/ui/Button.tsx`

#### Allowed Variants

**Only 3 variants exist**:

1. **Primary** (`variant="primary"`)
    - Background: `bg-[#0071e3]`
    - Text: `text-white`
    - Hover: `bg-[#0077ED]`
    - Shadow: `shadow-sm` (minimal)
    - Shape: `rounded-full` (pill-shaped)
2. **Secondary** (`variant="secondary"`)
    - Background: transparent
    - Text: `text-gray-700`
    - Border: `border-gray-200/0` → `border-gray-200/60` on hover
    - Hover: `bg-gray-100/60`
    - Shape: `rounded-full`
3. **Ghost** (`variant="ghost"`)
    - Background: transparent
    - Text: `text-gray-600`
    - Hover: `bg-gray-100/40`
    - No border
    - Shape: `rounded-full`

**Destructive actions** (delete, remove):

- Use inline button with `bg-red-600`, `hover:bg-red-700`
- Shape: `rounded-full` or `rounded` (context-dependent)
- Text: `text-white`

#### Forbidden Variants

The following button variants are **FORBIDDEN**:

- `variant="success"` (green)
- `variant="warning"` (yellow)
- `variant="info"` (blue, lighter than accent)
- `variant="outline-blue"`
- `variant="outline-green"`
- Any variant with colored backgrounds except primary and destructive
- Gradient buttons
- Buttons with icons in accent colors
- Buttons with colored borders

#### Intent

Buttons are functional, not expressive. If a button "draws the eye", it should be primary. If it shouldn't draw the eye, it should be secondary or ghost.

**No intermediate states.**

### 5.2 Badge Component

**Location**: `resources/js/components/ui/Badge.tsx`

#### Allowed Variant

**Only 1 variant exists**:

1. **Default** (`variant="default"`)
    - Text: `text-gray-600`
    - Background: none
    - Border: none
    - Size: `text-xs`
    - Weight: `font-normal`

#### Forbidden Variants

The following badge patterns are **FORBIDDEN**:

- `variant="success"` with green background
- `variant="warning"` with yellow background
- `variant="error"` with red background
- `variant="info"` with blue background
- Any badge with colored backgrounds
- Any badge with colored text (except gray)
- Badges with borders
- Badges with icons
- Pill-shaped badges with background colors

#### Intent

Badges communicate categorical information through text, not color. If color is needed to distinguish meaning, the text is insufficient.

**Anti-pattern**: Color-coded status badges.

**Correct pattern**: Text-only labels with clear wording.

### 5.3 BackLink Component

**Location**: `resources/js/components/ui/BackLink.tsx`

#### Required Pattern (Mandatory for Detail Pages)

BackLink is **MANDATORY** for all detail views (Show, Edit pages).

**Usage**:

```tsx
<BackLink href="/admin/contacts">Contacts</BackLink>
```

**Visual Specification**:

- Text: `text-sm text-gray-500 hover:text-gray-700`
- Arrow: `←` (included automatically)
- Spacing: `inline-flex items-center gap-1 mb-2`
- Position: Directly above page title, left-aligned

#### Placement Rule

Back navigation must appear in this **EXACT** structure:

```tsx
<div>
    <BackLink href="/admin/contacts">Contacts</BackLink>
    <h1 className="text-3xl font-bold">Contact Submission</h1>
    <p className="text-gray-600">Optional subtitle</p>
</div>
```

**Not this** (forbidden):

```tsx
<div className="flex items-center gap-4">
    <Link href="/admin/contacts">
        <Button variant="secondary" size="sm">
            ← Back
        </Button>
    </Link>
    <h1 className="text-3xl font-bold">Contact Submission</h1>
</div>
```

#### Label Rules

Labels must be **contextual destination names**, never generic verbs:

**Correct**:

- `<BackLink href="/admin/contacts">Contacts</BackLink>`
- `<BackLink href="/admin/products">Products</BackLink>`
- `<BackLink href="/admin/users">Users</BackLink>`

**Forbidden**:

- `← Back` (generic, assumes browser history)
- `← Return` (vague)
- `← Go Back` (redundant)

#### Intent

Back navigation is **contextual wayfinding**, not a browser action.

It must:

- Be visually subordinate to the page title
- Indicate destination, not action
- Work independently of browser history (bookmarks, refresh)

**Relationship to title**: Back is navigation context for the title, not a peer element. The hierarchy is: navigation → title → content.

### 5.4 Card Component

**Location**: `resources/js/components/ui/Card.tsx`

#### Allowed Pattern

Cards may **ONLY** use:

- Background: `bg-white`
- Border: `border-gray-200/60` (subtle, with opacity)
- Radius: `rounded-xl`
- Shadow: none (border provides definition)

#### Forbidden Patterns

The following card patterns are **FORBIDDEN**:

- Drop shadows (`shadow-md`, `shadow-lg`, etc.)
- Colored borders
- Colored backgrounds
- Gradient backgrounds
- Hover shadows
- Elevation changes
- Colored headers
- Accent color strips on edges

#### Intent

Cards are containers, not visual elements. They should recede, not attract attention.

### 5.4 Table Component

**Location**: `resources/js/components/ui/Table.tsx`

#### Allowed Pattern

Tables may **ONLY** use:

- Header background: `bg-gray-50` (optional) or transparent
- Row separator: `border-b border-gray-200` (minimal)
- Header text: `text-gray-700`, `font-medium`
- Body text: `text-gray-700`
- Hover: `hover:bg-gray-50` (subtle, optional)

#### Forbidden Patterns

The following table patterns are **FORBIDDEN**:

- Colored row backgrounds (except hover)
- Colored column backgrounds
- Striped rows with color
- Colored borders
- Shadows on rows
- Colored header backgrounds (except gray-50)
- Status indicators with colored backgrounds
- Hover states with accent colors

#### Intent

Tables display data. Color does not improve scanability in data tables. Alignment, spacing, and typography do.

### 5.5 Modal Component

**Location**: `resources/js/components/ui/Modal.tsx`

#### Allowed Pattern

Modals may **ONLY** use:

- Background: `bg-white`
- Backdrop: `bg-black/40` to `bg-black/60`
- Border: `border-gray-200` (optional)
- Radius: `rounded-lg`
- Shadow: minimal or none

#### Forbidden Patterns

The following modal patterns are **FORBIDDEN**:

- Colored headers
- Accent color strips
- Gradient backgrounds
- Heavy shadows
- Backdrop blur (excessive)
- Colored backdrops
- Animated entrances beyond simple fade

#### Intent

Modals interrupt. They should not also distract.

### 5.6 Toast Notification

**Location**: `resources/js/components/ui/Toast.tsx`

#### Allowed Pattern

Toasts may **ONLY** use:

- Success: `bg-white`, `border-gray-200`, `text-gray-700`
- Error: `bg-red-50`, `border-red-200`, `text-red-700`
- Progress bar (if needed): `bg-gray-300`, progress `bg-gray-600`

#### Forbidden Patterns

The following toast patterns are **FORBIDDEN**:

- Green success toasts
- Yellow warning toasts
- Blue info toasts
- Colored icons in toasts
- Gradient backgrounds
- Heavy shadows
- Animated icons

#### Intent

Toasts confirm actions. They do not celebrate. Success is gray. Only errors use red.

### 5.7 Sidebar Navigation

**Location**: `resources/js/pages/Admin/Layouts/AdminLayout.tsx`

#### Allowed Pattern

Sidebar may **ONLY** use:

- Background: `bg-gray-50/95` (Finder-style, light)
- Text: `text-gray-700`
- Hover: `bg-gray-100/60`
- Active state: `text-gray-900`, `bg-gray-100`
- Border: `border-gray-200` (separator)

#### Forbidden Patterns

The following sidebar patterns are **FORBIDDEN**:

- Dark sidebar (`bg-gray-900`)
- Colored active states (blue, green, etc.)
- Colored icons
- Accent color highlights
- Colored section dividers
- Badges with colored backgrounds
- Notification dots in accent colors

#### Intent

Sidebars are wayfinding, not branding. They should disappear into the periphery.

### 5.8 Select/Dropdown Component

**Location**: `resources/js/components/ui/Select.tsx`

#### Required Pattern (Mandatory)

Select dropdowns must **ALWAYS** use:

**Visual Specification**:

- Background: `bg-white`
- Border: `border-gray-300`
- Text: `text-gray-700` (selected value must be clearly visible)
- Height: `h-11`, `min-h-[44px]`
- Padding: `px-3 py-2 pr-10` (right padding for icon)
- Border radius: `rounded-lg`
- Native styling: `appearance-none` (remove browser defaults)

**Dropdown Indicator**:

- Icon: ChevronDown from lucide-react
- Size: `h-4 w-4`
- Color: `text-gray-400` (muted)
- Position: Absolute right, vertically centered
- Non-interactive: `pointer-events-none`

**Focus State**:

- Ring: `focus:ring-2 focus:ring-[#0071e3]`
- Border: `focus:border-transparent`
- Outline: `focus:outline-none`

**Structure**:

```tsx
<div className="relative">
    <select className="...appearance-none pr-10 text-gray-700">
        <option value="option1">Label 1</option>
    </select>
    <ChevronDown className="pointer-events-none absolute top-1/2 right-3 h-4 w-4 -translate-y-1/2 text-gray-400" />
</div>
```

#### Forbidden Patterns

The following select patterns are **FORBIDDEN**:

- Native browser styling (missing `appearance-none`)
- Colored dropdown indicators (blue, green, etc.)
- Multiple colored states based on selection
- Custom colored borders
- Animated dropdown indicators
- Icon-only selects without visible selected value
- Gradient backgrounds
- Heavy shadows

#### Intent

Dropdowns are functional inputs, not decorative elements. The selected value must be immediately visible without interaction. The dropdown indicator is subordinate visual feedback, not a design feature.

**Critical Rule**: If the user cannot see what is currently selected without clicking, the dropdown is broken.

---

## 6. Interaction Standards

### 6.1 Focus Rings

**Mandatory pattern**:

```
focus:outline-none focus:ring-2 focus:ring-[#0071e3] focus:ring-offset-1
```

**Constraints**:

- Color: `ring-[#0071e3]` (Apple Blue) ONLY
- Width: `ring-2` ONLY
- Offset: `ring-offset-1` or `ring-offset-2`
- No other ring colors permitted
- No colored ring-offset backgrounds

**Forbidden**:

- `ring-blue-500` (use hex value)
- `ring-green-500`
- `ring-red-500` (even for errors)
- Colored focus states
- Glow effects

### 6.2 Hover States

**Allowed patterns**:

1. **Button hover** (secondary/ghost):
    - `hover:bg-gray-100/40` to `hover:bg-gray-100/60`
2. **Link hover**:
    - `hover:text-gray-900` (darken)
    - `hover:underline`
3. **Card hover** (if interactive):
    - `hover:border-gray-300` (subtle border darken)
4. **Table row hover**:
    - `hover:bg-gray-50`

**Forbidden patterns**:

- Colored hover backgrounds
- Accent color hovers (except primary button)
- Shadow changes on hover
- Scale transforms
- Colored border changes
- Glow effects

### 6.3 Active States

**Allowed patterns**:

1. **Button active**:
    - Slightly darken background (1 shade)
    - No animation
2. **Input active**:
    - Focus ring only (see 6.1)

**Forbidden patterns**:

- Animated active states
- Colored active backgrounds
- Scale animations
- Ripple effects
- Colored presses

### 6.4 Loading States

**Allowed patterns**:

1. **Button loading**:
    - Spinner in `text-current` (inherits button text color)
    - Disable button, reduce opacity to `opacity-40`
2. **Page loading**:
    - Gray skeleton screens
    - Gray progress bars

**Forbidden patterns**:

- Colored spinners
- Colored progress bars
- Animated gradient loaders
- Pulsing colors

---

## 7. Iconography Rules

### 7.1 Allowed Icon Usage

Icons may **ONLY** be used:

1. **In buttons** (optional, not required)
    - Size: `h-4 w-4` or `h-5 w-5`
    - Color: inherits from button text
2. **In navigation**
    - Size: `h-5 w-5`
    - Color: `text-gray-400` or `text-gray-500`
3. **As status indicators** (rare)
    - Size: `h-4 w-4`
    - Color: `text-gray-400`

### 7.2 Icon Color Constraints

Icons must **ONLY** use:

- `text-gray-400` (default, de-emphasized)
- `text-gray-500` (slightly emphasized)
- `text-gray-600` (emphasized)
- `text-current` (inherits from parent)

**Forbidden icon colors**:

- `text-blue-500`
- `text-green-500`
- `text-yellow-500`
- `text-red-500` (even for error icons)
- Any semantic color

**Exception**: Icons within error messages may inherit `text-red-600` from parent.

### 7.3 Icon Size Constraints

Allowed sizes:

- `h-3 w-3` (rare, inline)
- `h-4 w-4` (default)
- `h-5 w-5` (navigation, headers)
- `h-6 w-6` (large, rare)

**Forbidden**:

- Icons larger than `h-6 w-6`
- Decorative large icons
- Icon-only pages

### 7.4 Emoji Ban

**Emojis are PERMANENTLY BANNED from structural UI.**

Emojis may **NOT** appear in:

- Section headers
- Button labels
- Form field labels
- Navigation items
- Table headers
- Card titles
- Status indicators
- Error messages
- Success messages
- Badges
- Tooltips
- Any UI component

**Allowed exception**: User-generated content display only (e.g., showing a product description written by a user that contains emoji).

**Test**: If the emoji is part of the interface design, it is forbidden.

---

## 8. Forbidden Patterns (Anti-Pattern List)

The following patterns are **EXPLICITLY FORBIDDEN** and constitute design violations:

### 8.1 Color-Coded Sections

**Forbidden**:

```tsx
<div className="border-blue-200 bg-blue-50">
    <h3>💼 Business Details</h3>
</div>
```

**Reason**: Color does not organize information. Hierarchy does.

**Correct**: Use white background with typography hierarchy.

### 8.2 Emoji Headers

**Forbidden**:

```tsx
<h3>⚙️ Hardware Specifications</h3>
<h3>💰 Pricing & Discount</h3>
<h3>🎯 Additional Details</h3>
```

**Reason**: Emojis are not iconography. They are decoration.

**Correct**: Use text only.

### 8.3 Colored Status Badges

**Forbidden**:

```tsx
<Badge variant="success">Active</Badge>
<Badge variant="warning">Pending</Badge>
<Badge variant="error">Inactive</Badge>
```

**Reason**: Status should be readable without color.

**Correct**: Use text-only badges with clear wording.

### 8.4 Semantic Background Colors

**Forbidden**:

```tsx
<div className="border-green-200 bg-green-50">
    <p className="text-green-700">✓ Success message</p>
</div>
```

**Reason**: Success does not need celebration. Confirmation is sufficient.

**Correct**: Use gray background or white, with gray text.

### 8.5 Colored Text Emphasis

**Forbidden**:

```tsx
<p className="text-blue-600 font-bold">Important information</p>
<span className="text-green-600">Discount: 20% OFF</span>
<span className="text-orange-600">⚠️ Warning</span>
```

**Reason**: Importance is communicated through hierarchy, not color.

**Correct**: Use font weight and size.

### 8.6 Expressive Warning Boxes

**Forbidden**:

```tsx
<div className="border-yellow-200 bg-yellow-50">
    <p className="text-yellow-800">⚠️ Recommendation: Add processor</p>
</div>
```

**Reason**: Recommendations are not warnings. Yellow implies urgency where none exists.

**Correct**: Use gray background with gray text.

### 8.7 Colored Hover States

**Forbidden**:

```tsx
<button className="hover:bg-blue-100 hover:text-blue-700">
```

**Reason**: Hover feedback should be subtle, not expressive.

**Correct**: Use `hover:bg-gray-100/40`.

### 8.8 Multiple Accent Colors

**Forbidden**:

```tsx
<Button className="bg-blue-600">Primary</Button>
<Button className="bg-green-600">Success</Button>
<Button className="bg-purple-600">Special</Button>
```

**Reason**: Multiple accent colors create visual chaos and hierarchy collapse.

**Correct**: Use single accent color with variant differentiation.

### 8.9 Decorative Icons

**Forbidden**:

```tsx
<h2>
    <Sparkles className="text-yellow-500" /> Premium Features
</h2>
```

**Reason**: Icons are for function, not decoration.

**Correct**: Remove icon or use monochrome.

### 8.10 Gradient Backgrounds

**Forbidden**:

```tsx
<div className="bg-gradient-to-r from-blue-500 to-purple-600">
```

**Reason**: Gradients are expressiveness for its own sake.

**Correct**: Use solid white or gray.

### 8.11 Shadow Elevation System

**Forbidden**:

```tsx
<Card className="shadow-lg hover:shadow-xl transition-shadow">
```

**Reason**: Shadows add visual weight without communicating information.

**Correct**: Use border for definition.

### 8.12 Colored Form Labels

**Forbidden**:

```tsx
<label className="text-blue-600">Email Address *</label>
```

**Reason**: Form labels are wayfinding, not branding.

**Correct**: Use `text-gray-700`.

### 8.13 Required Field Indicators (Verbose)

**Forbidden**:

```tsx
<label>
    Name * <span className="text-red-500">(Required)</span>
</label>
```

**Reason**: Redundant and noisy.

**Correct**: Use asterisk only with `text-gray-400`.

### 8.14 Colored Progress Bars

**Forbidden**:

```tsx
<div className="bg-green-500" style="width: 70%"></div>
```

**Reason**: Progress is neutral information.

**Correct**: Use gray progress bars.

### 8.15 Animated Notifications

**Forbidden**:

```tsx
<Toast className="animate-bounce bg-green-500">Success! 🎉</Toast>
```

**Reason**: Confirmation does not require celebration.

**Correct**: Gray toast with simple fade-in.

### 8.16 Colored Link Underlines

**Forbidden**:

```tsx
<a className="text-blue-600 underline decoration-blue-600">
```

**Reason**: Links should be readable, not branded.

**Correct**: `text-gray-700 underline hover:text-gray-900`.

### 8.17 Colored Table Rows

**Forbidden**:

```tsx
<tr className="bg-green-50">
    <td>Active Order</td>
</tr>
```

**Reason**: Status is communicated through text, not row color.

**Correct**: Use consistent white/transparent rows.

### 8.18 Icon Buttons with Colored Backgrounds

**Forbidden**:

```tsx
<button className="rounded-full bg-blue-100 p-2 text-blue-700">
    <Icon />
</button>
```

**Reason**: Icon buttons should recede, not attract.

**Correct**: Use ghost button variant.

### 8.19 Colored Section Dividers

**Forbidden**:

```tsx
<div className="border-t-4 border-blue-500"></div>
```

**Reason**: Dividers are structure, not decoration.

**Correct**: `border-t border-gray-200`.

### 8.20 Tooltip with Colored Backgrounds

**Forbidden**:

```tsx
<Tooltip className="bg-blue-600 text-white">
```

**Reason**: Tooltips provide information, not branding.

**Correct**: `bg-gray-900 text-white` (high contrast) or `bg-white border-gray-200`.

### 8.21 Colored Placeholder Text

**Forbidden**:

```tsx
<input placeholder="Enter email" className="placeholder:text-blue-400" />
```

**Reason**: Placeholders should be de-emphasized, not highlighted.

**Correct**: `placeholder:text-gray-400` or `placeholder:text-gray-500`.

### 8.22 "Helper" Hint Boxes

**Forbidden**:

```tsx
<div className="border-blue-200 bg-blue-50 p-3">
    <p className="text-blue-700">💡 Pro Tip: You can...</p>
</div>
```

**Reason**: Tips and hints add noise. Good UI doesn't need them.

**Correct**: Remove or use gray if essential.

---

## 9. Enforcement Checklist

This checklist must be reviewed before merging any UI changes:

### 9.1 Color Audit

- [ ] No `bg-blue-50`, `bg-green-50`, `bg-yellow-50`, or similar
- [ ] No `text-blue-600`, `text-green-600`, `text-orange-600`, or similar
- [ ] No colored borders except `border-gray-*` and error `border-red-200`
- [ ] No gradients
- [ ] Apple Blue (`#0071e3`) used ONLY in primary buttons and focus rings
- [ ] Red used ONLY in destructive actions and error states

### 9.2 Background Audit

- [ ] All section backgrounds are white or gray-50
- [ ] No colored section headers
- [ ] No colored hover backgrounds except gray
- [ ] No transparent backgrounds with colored overlays

### 9.3 Typography Audit

- [ ] System font stack used exclusively
- [ ] Font weights limited to normal (400), medium (500), semibold (600)
- [ ] Text colors limited to black, gray-700, gray-600, gray-500, gray-400, or red-500/600
- [ ] No colored text for emphasis

### 9.4 Component Audit

- [ ] Buttons use ONLY primary, secondary, ghost, or destructive red
- [ ] Badges use ONLY text-gray-600 with no background
- [ ] Cards use white background with gray border, no shadow
- [ ] Tables use minimal gray borders, no colored rows
- [ ] Modals use white background with gray border
- [ ] Toasts use gray for success, red-50 for errors only

### 9.5 Interaction Audit

- [ ] Focus rings use ONLY `ring-[#0071e3]`
- [ ] Hover states use ONLY gray backgrounds
- [ ] No colored active states
- [ ] No animated flourishes
- [ ] Loading states use gray spinners/progress bars

### 9.6 Icon Audit

- [ ] Icons use ONLY gray colors (gray-400, gray-500, gray-600)
- [ ] Icon sizes limited to h-3 through h-6
- [ ] No decorative icons
- [ ] **ZERO EMOJIS in structural UI**

### 9.7 Anti-Pattern Check

- [ ] No color-coded sections
- [ ] No emoji headers
- [ ] No colored status badges
- [ ] No semantic background colors
- [ ] No expressive warning boxes
- [ ] No multiple accent colors
- [ ] No shadow elevation systems
- [ ] No gradient backgrounds
- [ ] No "Pro Tip" or "Helper" boxes with colored backgrounds

### 9.8 Accessibility Check

- [ ] Focus rings visible on all interactive elements
- [ ] Color is not the sole means of conveying information
- [ ] Error messages include text, not just color
- [ ] Contrast ratios meet WCAG AA standards (4.5:1 for text)

---

## 10. Recommended Enforcement Mechanisms

While this document is the authoritative source, the following tools can help detect violations:

### 10.1 ESLint Rules

Configure ESLint to warn on:

- String literals containing `bg-blue-`, `bg-green-`, `bg-yellow-`, `bg-orange-`, `bg-purple-`
- String literals containing `text-blue-`, `text-green-`, `text-orange-`, `text-yellow-`, `text-purple-`
- Emoji unicode characters in JSX

### 10.2 Stylelint Rules

Configure Stylelint (if using CSS modules) to forbid:

- Color values outside the allowed palette
- Gradient functions
- Box-shadow properties

### 10.3 Git Pre-Commit Hooks

Add a pre-commit hook that runs:

```bash
# Check for forbidden color classes
git diff --cached --name-only | grep -E '\.(tsx|jsx)$' | xargs grep -n 'bg-\(blue\|green\|yellow\|orange\|purple\)-[0-9]' && echo "❌ Forbidden color detected" && exit 1

# Check for emojis in UI files
git diff --cached --name-only | grep -E 'components/.*\.(tsx|jsx)$' | xargs grep -P '[\x{1F600}-\x{1F64F}]' && echo "❌ Emoji detected in component" && exit 1
```

### 10.4 Pull Request Checklist

Include this in your PR template:

```markdown
## Design System Compliance

- [ ] I have reviewed the DESIGN_SYSTEM.md enforcement checklist
- [ ] No forbidden colors were introduced
- [ ] No emojis were added to structural UI
- [ ] All new components use approved variants only
- [ ] Focus rings use Apple Blue only
```

### 10.5 Code Review Guidelines

Reviewers must reject PRs that:

- Introduce colored backgrounds for sections
- Add new button variants
- Use emojis in interface elements
- Use colored text for emphasis
- Add shadow elevation

**No exceptions without updating this document first.**

---

## 11. Document Maintenance

### 11.1 Amendment Process

This document may only be amended when:

1. A legitimate use case arises that cannot be solved within current constraints
2. The amendment is discussed and approved by the team
3. The amendment is added with the same level of specificity as existing rules

**Amendments must be restrictive, not permissive.**

If you are considering loosening a rule, the default answer is no.

### 11.2 Version History

- **v1.0** (2026-01-01): Initial enforcement constitution

---

## 12. Final Test

Before merging any UI changes, ask:

1. **Does this component use color to convey information?**
    - If yes: Can it use text or hierarchy instead?
2. **Does this component "feel designed"?**
    - If yes: Reduce it.
3. **Would an AI agent misinterpret this as permission to add color elsewhere?**
    - If yes: It's not specific enough.
4. **If I removed all color except gray, would the UI still function?**
    - If no: The design is broken.

**When in doubt, use gray.**

---

## Appendix B: Enforcement & Automation

### Pre-Commit Validation

This design system is enforced via automated pre-commit checks.

**Script Location**: `scripts/validate-design-system.sh`

The validator runs automatically before every commit and blocks violations.

#### Forbidden Patterns (Auto-Detected)

The following patterns will fail commits:

**Colors:**

- `bg-blue-*` (except Button.tsx primary)
- `bg-green-*`, `bg-yellow-*`, `bg-orange-*`
- `text-blue-*` (except Button.tsx)
- `text-green-*`, `text-yellow-*`, `text-orange-*`
- `text-slate-*`, `bg-slate-*` (use gray instead)
- `ring-blue-500` (use `ring-[#0071e3]`)
- Hardcoded hex colors (except `#0071e3`)

**Shadows:**

- `shadow-md`, `shadow-lg`, `shadow-xl`, `shadow-2xl`
- `hover:shadow-*` (all hover shadows)

**Other:**

- `style=` inline attributes (rare exceptions documented in validator)
- Emojis in JSX/TSX source code

#### Running Manual Audits

Monthly audit command:

```bash
npm run audit:design-system
```

This runs a full scan of all TypeScript React files.

#### Bypass (Not Recommended)

To bypass the pre-commit check:

```bash
git commit --no-verify
```

**Warning**: Bypassed violations will be caught in CI/code review.

#### Adding Exceptions

If a technical requirement demands an exception (e.g., SVG blend modes):

1. Document the reason in `scripts/validate-design-system.sh`
2. Add the file to the exception list
3. Update this documentation

**Undocumented exceptions will be removed.**

---

## Appendix A: Quick Reference

### Colors (Complete List)

**Allowed**:

- Apple Blue: `#0071e3` (buttons, focus rings ONLY)
- Red: `#ef4444` (destructive actions, errors ONLY)
- Gray scale: `gray-50` through `gray-900`
- White: `#ffffff`
- Black: `#000000`

**Forbidden**: Everything else.

### Components (Approved Variants)

- **Button**: primary, secondary, ghost, (destructive red inline)
- **Badge**: default (text-only)
- **Card**: white with gray border
- **Table**: gray borders, white/gray-50 rows
- **Modal**: white with gray border
- **Toast**: gray (success), red-50 (error)
- **Sidebar**: gray-50 background

### Typography

- **Font**: System font stack only
- **Weights**: 400, 500, 600
- **Colors**: black, gray-700, gray-600, gray-500, gray-400, (red-500/600 for errors)

### Backgrounds

- **Allowed**: white, gray-50, gray-100/40-60, (red-50 for errors)
- **Forbidden**: All semantic colors, gradients, images

### Icons

- **Colors**: gray-400, gray-500, gray-600, text-current
- **Sizes**: h-3 to h-6
- **Emojis**: BANNED

---

**End of Document**

This is the law. Violations are regressions. Entropy is the enemy. Gray is the path.
