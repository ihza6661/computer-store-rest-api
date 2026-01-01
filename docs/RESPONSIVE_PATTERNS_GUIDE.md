# Mobile-Responsive UI Patterns Guide

**Version**: 1.0.0  
**Last Updated**: 2026-01-01  
**Purpose**: Practical guide to implementing mobile-responsive layouts that comply with the Apple Standard Design System

---

## Table of Contents

1. [Quick Reference](#1-quick-reference)
2. [Pattern Library](#2-pattern-library)
3. [Breakpoint Usage Guide](#3-breakpoint-usage-guide)
4. [Anti-Patterns Gallery](#4-anti-patterns-gallery)
5. [Testing Checklist](#5-testing-checklist)
6. [Troubleshooting](#6-troubleshooting)

---

## 1. Quick Reference

### Decision Tree: "Which Pattern Should I Use?"

```
START: I need to add action buttons to my UI

├─ Location: Page Header (top of page)
│  └─ Use: Page Header Actions Pattern (Section 2.1)
│     └─ Breakpoint: sm: (640px)
│
├─ Location: Card Header (inside CardHeader component)
│  └─ Use: Card Header Actions Pattern (Section 2.2)
│     └─ Breakpoint: sm: (640px)
│
├─ Location: Form Footer (save/cancel buttons)
│  └─ Use: Form Actions Pattern (Section 2.3)
│     └─ Breakpoint: sm: (640px)
│
├─ Location: Mobile Card (in responsive table-to-card layout)
│  └─ Use: Mobile Card Action Row Pattern (Section 2.4)
│     └─ Breakpoint: md: (768px for table/card switch)
│
├─ Location: Modal/Dialog Footer
│  └─ Use: Modal Actions Pattern (Section 2.5)
│     └─ Breakpoint: sm: (640px)
│
└─ Location: Pagination
   └─ Use: Pagination Pattern (Section 2.6)
      └─ Breakpoint: sm: (640px)
```

### Copy-Paste Patterns (Most Common)

#### Page Header Actions

```tsx
<div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <PageHeader title="..." description="..." />
    <Button variant="primary" size="md">
        Action
    </Button>
</div>
```

#### Card Header Actions

```tsx
<CardHeader>
    <div className="space-y-4">
        <div>
            <CardTitle>Title</CardTitle>
            <p className="mt-1 text-sm text-gray-600">Description</p>
        </div>
        <div className="flex flex-col gap-2 sm:flex-row sm:gap-3">
            <Button variant="primary" size="md" className="w-full sm:w-auto">
                Primary
            </Button>
            <Button variant="ghost" size="md" className="w-full sm:w-auto">
                Secondary
            </Button>
        </div>
    </div>
</CardHeader>
```

#### Form Actions

```tsx
<div className="flex flex-col gap-2 sm:flex-row sm:justify-end sm:gap-3">
    <Button variant="ghost" size="md" className="w-full sm:w-auto">
        Cancel
    </Button>
    <Button variant="primary" size="md" className="w-full sm:w-auto">
        Save
    </Button>
</div>
```

---

## 2. Pattern Library

### 2.1 Page Header Actions

**Use When**: Adding primary actions to page-level headers

**Real Example**: `resources/js/pages/Admin/Products/Index.tsx:212-228`

**Pattern**:

```tsx
<div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <PageHeader title="Products" description="Manage your product inventory" />
    <div className="flex gap-3">
        <Link href="/admin/products/import">
            <Button variant="secondary" size="md">
                <FileSpreadsheet size={18} />
                Import Products
            </Button>
        </Link>
        <Link href="/admin/products/create">
            <Button variant="primary" size="md">
                <Plus size={18} />
                Add Product
            </Button>
        </Link>
    </div>
</div>
```

**Behavior**:

- **Mobile (<640px)**: Vertical stack, buttons below description
- **Desktop (≥640px)**: Side-by-side, buttons top-right

**Key Classes**:

- Container: `flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between`
- Button group: `flex gap-3` (horizontal on all viewports)
- Individual buttons: `size="md"` (adequate touch targets)

**Why These Classes**:

- `flex-col` on mobile prevents horizontal overflow
- `gap-4` (16px) provides clear visual separation
- `sm:flex-row` transitions to horizontal at 640px
- `sm:justify-between` pushes actions to opposite end on desktop

---

### 2.2 Card Header Actions

**Use When**: Adding actions to card headers (preview, results, etc.)

**Real Example**: `resources/js/pages/Admin/Products/Import.tsx:276-299`

**Pattern**:

```tsx
<CardHeader>
    <div className="space-y-4">
        <div>
            <CardTitle>Preview Results</CardTitle>
            <p className="mt-1 text-sm text-gray-600">
                Total: {total} | Valid: {valid} | Errors: {errors}
            </p>
        </div>
        <div className="flex flex-col gap-2 sm:flex-row sm:gap-3">
            <Button variant="primary" size="md" onClick={handleConfirm} className="w-full sm:w-auto">
                <Upload size={18} />
                Confirm Import
            </Button>
            <Button variant="ghost" size="md" onClick={handleCancel} className="w-full sm:w-auto">
                Cancel
            </Button>
        </div>
    </div>
</CardHeader>
```

**Behavior**:

- **Mobile (<640px)**: Buttons stack vertically, full width
- **Desktop (≥640px)**: Buttons horizontal, auto width

**Key Classes**:

- Outer container: `space-y-4` (16px between title and buttons)
- Button container: `flex flex-col gap-2 sm:flex-row sm:gap-3`
- Individual buttons: `w-full sm:w-auto`

**Why These Classes**:

- `space-y-4` separates title from action area
- `gap-2` (8px) on mobile - tighter spacing for limited screen
- `sm:gap-3` (12px) on desktop - comfortable spacing
- `w-full` makes mobile buttons easy to tap (full width)
- `sm:w-auto` allows compact layout on desktop

**This Pattern Fixes The Exact Issue**: iPhone SE button overflow (see `docs/MOBILE_BUTTON_FIX.md`)

---

### 2.3 Form Actions

**Use When**: Save/Cancel buttons at bottom of forms

**Real Example**: Common pattern in Create/Edit pages

**Pattern**:

```tsx
<form onSubmit={handleSubmit}>
    {/* Form fields */}

    <div className="flex flex-col gap-2 sm:flex-row sm:justify-end sm:gap-3">
        <Button type="button" variant="ghost" size="md" onClick={handleCancel} className="w-full sm:w-auto">
            Cancel
        </Button>
        <Button type="submit" variant="primary" size="md" disabled={submitting} className="w-full sm:w-auto">
            {submitting ? 'Saving...' : 'Save Changes'}
        </Button>
    </div>
</form>
```

**Behavior**:

- **Mobile (<640px)**: Buttons stack vertically, full width, Cancel on top
- **Desktop (≥640px)**: Buttons horizontal right-aligned, Cancel left, Save right

**Key Classes**:

- Container: `flex flex-col gap-2 sm:flex-row sm:gap-3 sm:justify-end`
- Buttons: `w-full sm:w-auto`

**Button Order**:

- Mobile: Cancel first (top), then Primary (reduces accidental submissions)
- Desktop: Cancel left, Save right (standard pattern)

**Note**: On mobile, having Cancel on top is intentional - it prevents accidental form submission when scrolling/tapping.

---

### 2.4 Mobile Card Action Row

**Use When**: Edit/Delete actions in mobile card layouts (responsive table-to-card)

**Real Example**: `resources/js/pages/Admin/Products/Index.tsx:366-377`

**Pattern**:

```tsx
{
    /* Desktop: Table */
}
<div className="hidden overflow-x-auto md:block">
    <Table>{/* Table rows with inline actions */}</Table>
</div>;

{
    /* Mobile: Cards */
}
<div className="space-y-3 md:hidden">
    {items.map((item) => (
        <Card key={item.id} className="border border-gray-200">
            <CardContent className="p-4">
                <div className="space-y-3">
                    {/* Item details */}
                    <div>
                        <p className="font-semibold text-gray-900">{item.name}</p>
                        {/* More details */}
                    </div>

                    {/* Action Row */}
                    <div className="flex gap-2 border-t border-gray-200 pt-2">
                        <Link href={`/admin/items/${item.id}/edit`} className="flex-1">
                            <Button variant="secondary" size="sm" className="w-full">
                                <Edit2 size={16} />
                                Edit
                            </Button>
                        </Link>
                        <Button variant="ghost" size="sm" onClick={() => handleDelete(item.id)} className="flex-1">
                            <Trash2 size={16} />
                            Delete
                        </Button>
                    </div>
                </div>
            </CardContent>
        </Card>
    ))}
</div>;
```

**Behavior**:

- **Mobile (<768px)**: Cards with action buttons at bottom
- **Desktop (≥768px)**: Table with inline action buttons

**Key Classes for Action Row**:

- Container: `flex gap-2 border-t border-gray-200 pt-2`
- Button wrappers: `flex-1` (equal width distribution)
- Buttons: `w-full` (fill container), `size="sm"` (compact for mobile)

**Why These Classes**:

- `flex-1` on wrappers ensures equal button widths
- `gap-2` (8px) provides minimal but adequate spacing
- `border-t` visually separates actions from content
- `pt-2` (8px) adds breathing room above buttons

**Visual Structure**:

```
┌──────────────────────────────┐
│ Product Name                 │
│ Price: $1,000                │
│ Stock: 5 units               │
├──────────────────────────────┤ ← border-t
│  [Edit]     [Delete]         │ ← equal width
└──────────────────────────────┘
```

---

### 2.5 Modal/Dialog Actions

**Use When**: Action buttons in modal footers

**Pattern**:

```tsx
<Modal open={isOpen} onClose={handleClose}>
    <ModalHeader>
        <ModalTitle>Confirm Action</ModalTitle>
    </ModalHeader>

    <ModalContent>{/* Modal body content */}</ModalContent>

    <ModalFooter>
        <div className="flex flex-col gap-2 sm:flex-row sm:justify-end sm:gap-3">
            <Button variant="ghost" size="md" onClick={handleClose} className="w-full sm:w-auto">
                Cancel
            </Button>
            <Button variant="primary" size="md" onClick={handleConfirm} className="w-full sm:w-auto">
                Confirm
            </Button>
        </div>
    </ModalFooter>
</Modal>
```

**Behavior**: Same as Form Actions pattern

**Why Same Pattern**: Modals are constrained containers similar to forms, so button layout follows same principles.

---

### 2.6 Pagination

**Use When**: Navigating paginated lists

**Real Example**: `resources/js/pages/Admin/Products/Index.tsx:385-424`

**Pattern**:

```tsx
<div className="mt-6 flex flex-col items-center justify-center gap-2 sm:flex-row">
    <Button variant="secondary" size="sm" onClick={() => handlePageChange(currentPage - 1)} disabled={currentPage === 1}>
        Previous
    </Button>

    <div className="flex flex-wrap justify-center gap-2">
        {pageNumbers.map((page) => (
            <Button key={page} variant={currentPage === page ? 'primary' : 'secondary'} size="sm" onClick={() => handlePageChange(page)}>
                {page}
            </Button>
        ))}
    </div>

    <Button variant="secondary" size="sm" onClick={() => handlePageChange(currentPage + 1)} disabled={currentPage === lastPage}>
        Next
    </Button>
</div>
```

**Behavior**:

- **Mobile (<640px)**: Vertical stack - Previous, page numbers, Next
- **Desktop (≥640px)**: Horizontal layout - Previous | 1 2 3 4 5 | Next

**Key Classes**:

- Container: `flex flex-col items-center justify-center gap-2 sm:flex-row`
- Page numbers: `flex flex-wrap justify-center gap-2`

**Why These Classes**:

- `items-center justify-center` keeps pagination centered on all viewports
- `flex-wrap` allows page numbers to wrap gracefully if many pages
- `gap-2` maintains consistent 8px spacing

---

## 3. Breakpoint Usage Guide

### Tailwind Breakpoint Reference

| Prefix | Min Width | Typical Device            | Use Case                                  |
| ------ | --------- | ------------------------- | ----------------------------------------- |
| (none) | 0px       | Mobile                    | Default styles, mobile-first              |
| `sm:`  | 640px     | Large phone, small tablet | Layout shifts for slightly larger screens |
| `md:`  | 768px     | Tablet                    | Table/card switches, multi-column layouts |
| `lg:`  | 1024px    | Desktop                   | Wide layouts, additional columns          |
| `xl:`  | 1280px    | Large desktop             | (Rarely used in this project)             |

### Choosing The Right Breakpoint

#### Use `sm:` (640px) When:

✅ **Page header actions** - Adequate space for title + buttons side-by-side  
✅ **Card header actions** - Matches page header pattern for consistency  
✅ **Form actions (save/cancel)** - Standard pattern across all forms  
✅ **Modal actions** - Dialogs transition well at this size  
✅ **General button layout changes** - Most button patterns shift here

**Rationale**: 640px is when most phones in landscape mode and small tablets in portrait have enough horizontal space for side-by-side button layouts without text truncation.

#### Use `md:` (768px) When:

✅ **Table/card display switch** - Tables need more horizontal space to be usable  
✅ **Card metadata layouts** - Prevents cramming on small tablets  
✅ **Multi-column grids** - 2+ column layouts benefit from more width

**Rationale**: 768px is the standard tablet portrait width (iPad, etc.). Tables become unusable below this width, necessitating card-based mobile layouts.

#### Use `lg:` (1024px) When:

✅ **3+ column layouts** - Rare in admin interfaces  
✅ **Wide dashboard layouts** - Only when explicitly needed

**Rationale**: Most admin UI works well at `md:`, so `lg:` is reserved for genuinely wide layouts.

### Breakpoint Anti-Patterns

❌ **Don't use `md:` for simple button layouts**

```tsx
// WRONG - Unnecessarily delays mobile adaptation
<div className="flex flex-col md:flex-row">
    <Button>Action</Button>
</div>
```

✅ **Do use `sm:` for button layouts**

```tsx
// CORRECT - Adapts as soon as practical
<div className="flex flex-col gap-2 sm:flex-row sm:gap-3">
    <Button>Action</Button>
</div>
```

---

❌ **Don't mix breakpoints arbitrarily**

```tsx
// WRONG - Inconsistent breakpoint usage
<div className="flex flex-col sm:flex-row">
    <Button className="w-full md:w-auto">Action</Button>
</div>
```

✅ **Do use consistent breakpoints**

```tsx
// CORRECT - sm: for both container and buttons
<div className="flex flex-col gap-2 sm:flex-row sm:gap-3">
    <Button className="w-full sm:w-auto">Action</Button>
</div>
```

---

## 4. Anti-Patterns Gallery

### Anti-Pattern #1: No Mobile Adaptation

**Scenario**: Card header with action buttons that don't adapt to mobile

**Problem Code**:

```tsx
<CardHeader>
    <div className="flex items-center justify-between">
        <CardTitle>Preview Results</CardTitle>
        <div className="flex gap-3">
            <Button variant="primary">Confirm Import</Button>
            <Button variant="ghost">Cancel</Button>
        </div>
    </div>
</CardHeader>
```

**What Happens on iPhone SE (375px)**:

```
┌─────────────────────────────────────┐
│ Preview Results         [Conf] [Can│ ← Text truncated!
└─────────────────────────────────────┘
```

**Issues**:

- Button text truncates ("Confirm" → "Conf")
- Poor touch targets (buttons too narrow)
- Horizontal overflow
- Ugly, unprofessional appearance

**Fixed Code**:

```tsx
<CardHeader>
    <div className="space-y-4">
        <div>
            <CardTitle>Preview Results</CardTitle>
        </div>
        <div className="flex flex-col gap-2 sm:flex-row sm:gap-3">
            <Button variant="primary" className="w-full sm:w-auto">
                Confirm Import
            </Button>
            <Button variant="ghost" className="w-full sm:w-auto">
                Cancel
            </Button>
        </div>
    </div>
</CardHeader>
```

**Result on iPhone SE**:

```
┌─────────────────────────────────────┐
│ Preview Results                     │
│                                     │
│ ┌─────────────────────────────────┐ │
│ │     Confirm Import              │ │ ← Full text!
│ └─────────────────────────────────┘ │
│ ┌─────────────────────────────────┐ │
│ │     Cancel                      │ │
│ └─────────────────────────────────┘ │
└─────────────────────────────────────┘
```

**This Was The Actual Bug Fixed**: See `docs/MOBILE_BUTTON_FIX.md` and `docs/CSRF_FIX_VERIFICATION.md`

---

### Anti-Pattern #2: Fixed-Width Buttons

**Problem Code**:

```tsx
<Button variant="primary" className="w-32">
    Save Changes
</Button>
```

**Issues**:

- `w-32` = 128px fixed width
- May truncate longer text ("Save Changes" → "Save Chan...")
- Doesn't utilize available mobile screen width
- Creates inconsistent button sizes

**Fixed Code**:

```tsx
<Button variant="primary" className="w-full sm:w-auto">
    Save Changes
</Button>
```

---

### Anti-Pattern #3: Icon-Only Mobile Buttons

**Problem Code**:

```tsx
<Button variant="secondary" size="sm">
    <Upload size={18} />
</Button>
```

**Issues**:

- No text label = ambiguous
- Reduced touch target (icon-only = narrow button ~40px)
- Violates accessibility (no screen reader label)
- Violates design system rule (see DESIGN_SYSTEM.md:884)

**Fixed Code**:

```tsx
<Button variant="secondary" size="md" className="w-full sm:w-auto">
    <Upload size={18} />
    Import Products
</Button>
```

---

### Anti-Pattern #4: Insufficient Button Spacing

**Problem Code**:

```tsx
<div className="flex">
    <Button variant="primary">Save</Button>
    <Button variant="ghost">Cancel</Button>
</div>
```

**Issues**:

- No `gap` = buttons touch each other (0px spacing)
- High risk of accidental mis-tap
- Visually cramped

**Fixed Code**:

```tsx
<div className="flex flex-col gap-2 sm:flex-row sm:gap-3">
    <Button variant="primary" className="w-full sm:w-auto">
        Save
    </Button>
    <Button variant="ghost" className="w-full sm:w-auto">
        Cancel
    </Button>
</div>
```

**Minimum Spacing**:

- Mobile: `gap-2` (8px) - prevents accidental taps
- Desktop: `sm:gap-3` (12px) - comfortable spacing

---

### Anti-Pattern #5: Multiple Primary Buttons

**Problem Code**:

```tsx
<div className="flex gap-3">
    <Button variant="primary">Export</Button>
    <Button variant="primary">Import</Button>
    <Button variant="primary">Sync</Button>
</div>
```

**Issues**:

- Violates "one primary action" rule (DESIGN_SYSTEM.md:968)
- Visual hierarchy unclear (which is most important?)
- On mobile, 3 primary buttons side-by-side = cramped

**Fixed Code (Option 1: Prioritize)**:

```tsx
<div className="flex flex-col gap-2 sm:flex-row sm:gap-3">
    <Button variant="primary" className="w-full sm:w-auto">
        Import
    </Button>
    <Button variant="secondary" className="w-full sm:w-auto">
        Export
    </Button>
    <Button variant="secondary" className="w-full sm:w-auto">
        Sync
    </Button>
</div>
```

**Fixed Code (Option 2: Dropdown Menu)**:

```tsx
<div className="flex gap-3">
    <Button variant="primary">Import</Button>
    <DropdownMenu>
        <DropdownTrigger>
            <Button variant="secondary">More Actions</Button>
        </DropdownTrigger>
        <DropdownContent>
            <DropdownItem>Export</DropdownItem>
            <DropdownItem>Sync</DropdownItem>
        </DropdownContent>
    </DropdownMenu>
</div>
```

---

## 5. Testing Checklist

### Before Committing Any UI With Buttons

Run through this checklist using Chrome DevTools Device Mode:

#### Setup

1. Open page in Chrome
2. Open DevTools (F12)
3. Toggle Device Toolbar (Ctrl+Shift+M / Cmd+Shift+M)
4. Select "iPhone SE" preset (375×667)

#### Visual Checks

- [ ] **No horizontal overflow**
    - Entire page content fits within viewport
    - No horizontal scrollbar appears
    - Buttons don't extend past screen edge

- [ ] **Button text fully visible**
    - No truncation ("Conf..." ❌)
    - No text wrapping inside buttons
    - All characters readable

- [ ] **Touch targets feel comfortable**
    - Buttons don't feel cramped
    - Easy to tap without zooming
    - No accidental adjacent button taps

- [ ] **Adequate button spacing**
    - Minimum 8px gap between buttons (visible daylight)
    - Buttons not touching each other
    - Visual breathing room

- [ ] **Buttons stack vertically on mobile**
    - Below 640px, buttons should be in vertical column
    - Full width on mobile (using `w-full`)
    - Transitions to horizontal at ≥640px (`sm:flex-row`)

- [ ] **One clear primary action**
    - Only one `variant="primary"` button visible
    - Primary button visually dominant
    - Secondary actions are muted

- [ ] **No icon-only buttons without text**
    - All buttons have text labels
    - Icons accompany text (not replace it)

#### Breakpoint Checks

Test at these specific widths:

- [ ] **375px** (iPhone SE) - Most constrained mobile
- [ ] **390px** (iPhone 12/13/14) - Common mobile
- [ ] **640px** (sm: breakpoint) - Button layout transition point
- [ ] **768px** (md: breakpoint) - Table/card transition point
- [ ] **1024px** (Desktop) - Full desktop layout

#### Interaction Checks

- [ ] **Tap all buttons**
    - Buttons respond to click/tap
    - No mis-taps on adjacent buttons
    - Touch area feels large enough

- [ ] **Test landscape mode** (if applicable)
    - Rotate device to landscape
    - Layout still works
    - No new overflow issues

#### Accessibility Checks

- [ ] **Screen reader labels**
    - All buttons have accessible text
    - Icon-only buttons have `aria-label`
    - Button purpose is clear

- [ ] **Keyboard navigation**
    - Can tab through buttons
    - Focus visible (blue ring)
    - Enter/Space activates button

### Automated Testing (Optional)

If you want to add automated tests:

```bash
# Example Playwright test
test('buttons responsive on mobile', async ({ page }) => {
  await page.setViewportSize({ width: 375, height: 667 });
  await page.goto('/admin/products/import');

  const confirmButton = page.locator('button:has-text("Confirm Import")');

  // Should be full width on mobile
  const box = await confirmButton.boundingBox();
  expect(box.width).toBeGreaterThan(300); // Most of 375px width

  // Should not overflow
  expect(box.x + box.width).toBeLessThanOrEqual(375);
});
```

---

## 6. Troubleshooting

### "My buttons look ugly on mobile!"

**Step 1: Identify the problem**

Open DevTools Device Mode (iPhone SE preset) and check:

- [ ] Are buttons side-by-side when they should be stacked?
- [ ] Is button text truncated or wrapped?
- [ ] Do buttons extend past screen edge?
- [ ] Are buttons too narrow to tap comfortably?

**Step 2: Check your code structure**

❌ **If you have**:

```tsx
<div className="flex items-center justify-between">
    <Title>...</Title>
    <Button>Action</Button>
</div>
```

✅ **Change to**:

```tsx
<div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <Title>...</Title>
    <Button>Action</Button>
</div>
```

**Step 3: Apply responsive classes**

For button groups, use this template:

```tsx
<div className="flex flex-col gap-2 sm:flex-row sm:gap-3">
    <Button className="w-full sm:w-auto">Primary</Button>
    <Button className="w-full sm:w-auto">Secondary</Button>
</div>
```

**Step 4: Test at 375px**

Reload page, verify buttons stack vertically and look good.

---

### "Buttons overlap on mobile!"

**Cause**: Missing `gap` classes

**Fix**:

```tsx
// ❌ WRONG
<div className="flex">
    <Button>A</Button>
    <Button>B</Button>
</div>

// ✅ CORRECT
<div className="flex flex-col gap-2 sm:flex-row sm:gap-3">
    <Button className="w-full sm:w-auto">A</Button>
    <Button className="w-full sm:w-auto">B</Button>
</div>
```

**Minimum gaps**:

- Mobile: `gap-2` (8px)
- Desktop: `sm:gap-3` (12px)

---

### "Button text is cut off!"

**Cause**: Fixed width or insufficient container width

**Fix**:

```tsx
// ❌ WRONG
<Button className="w-32">Long Button Text</Button>

// ✅ CORRECT
<Button className="w-full sm:w-auto">Long Button Text</Button>
```

**Never use** fixed width classes (`w-32`, `w-40`, etc.) for buttons with variable text length.

---

### "Buttons work on desktop but break on mobile!"

**Cause**: Missing responsive breakpoints

**Fix**: Add `sm:` prefixes for desktop styles

```tsx
// ❌ WRONG - Desktop-only styles
<div className="flex flex-row gap-3 justify-between">
    <Button className="w-auto">Action</Button>
</div>

// ✅ CORRECT - Mobile-first responsive
<div className="flex flex-col gap-2 sm:flex-row sm:gap-3 sm:justify-between">
    <Button className="w-full sm:w-auto">Action</Button>
</div>
```

**Remember**: Default styles = mobile, `sm:` = desktop

---

### "Which breakpoint should I use?"

**Quick Guide**:

- **Simple button layouts**: Use `sm:` (640px)
- **Table/card switch**: Use `md:` (768px)
- **3+ column layouts**: Use `lg:` (1024px)

**When in doubt**: Use `sm:` for button-related responsive changes.

---

### "How do I test on real iPhone?"

**Option 1: DevTools (Fastest)**

1. Chrome DevTools → Device Mode
2. Select "iPhone SE" preset
3. Test all interactions

**Option 2: Real Device (Best)**

1. Find your computer's local IP: `ifconfig` (Mac/Linux) or `ipconfig` (Windows)
2. Start dev server: `composer run dev`
3. On iPhone, open Safari
4. Navigate to `http://[YOUR-IP]:8000`
5. Test directly on device

**Option 3: BrowserStack/LambdaTest (Comprehensive)**

- Cloud-based real device testing
- Test on multiple iPhone models
- Screenshot/video recording

---

### "My fix worked but broke desktop layout!"

**Cause**: Forgot to add `sm:` prefixes for desktop styles

**Example**:

```tsx
// ❌ WRONG - Vertical on desktop too!
<div className="flex flex-col gap-2">
    <Button className="w-full">Action</Button>
</div>

// ✅ CORRECT - Vertical on mobile, horizontal on desktop
<div className="flex flex-col gap-2 sm:flex-row sm:gap-3">
    <Button className="w-full sm:w-auto">Action</Button>
</div>
```

**Always test both**:

1. Mobile (375px) - Check vertical stack works
2. Desktop (1024px+) - Check horizontal layout works

---

## 7. Quick Reference Card

### Copy This Into Your Notion/Notes

```
MOBILE BUTTON PATTERN CHEATSHEET

✅ Page Header Actions
<div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <PageHeader title="..." description="..." />
    <Button variant="primary" size="md">Action</Button>
</div>

✅ Card Header Actions
<CardHeader>
    <div className="space-y-4">
        <div><CardTitle>Title</CardTitle></div>
        <div className="flex flex-col gap-2 sm:flex-row sm:gap-3">
            <Button variant="primary" size="md" className="w-full sm:w-auto">Primary</Button>
            <Button variant="ghost" size="md" className="w-full sm:w-auto">Secondary</Button>
        </div>
    </div>
</CardHeader>

✅ Form Actions
<div className="flex flex-col gap-2 sm:flex-row sm:gap-3 sm:justify-end">
    <Button variant="ghost" size="md" className="w-full sm:w-auto">Cancel</Button>
    <Button variant="primary" size="md" className="w-full sm:w-auto">Save</Button>
</div>

✅ Mobile Card Action Row
<div className="flex gap-2 border-t border-gray-200 pt-2">
    <Link href="..." className="flex-1">
        <Button variant="secondary" size="sm" className="w-full">Edit</Button>
    </Link>
    <Button variant="ghost" size="sm" className="flex-1">Delete</Button>
</div>

RULES:
- Mobile: flex-col, w-full, gap-2 (8px)
- Desktop: sm:flex-row, sm:w-auto, sm:gap-3 (12px)
- Always use w-full sm:w-auto for responsive buttons
- Test at 375px (iPhone SE) before committing
```

---

## 8. Related Documentation

- **DESIGN_SYSTEM.md** - Strict rules and enforcement (Sections 7.2.2-7.2.6)
- **MOBILE_BUTTON_FIX.md** - Specific fix for Import page buttons
- **IMPORT_PRODUCTS.md** - Import feature documentation
- **CSRF_FIX_VERIFICATION.md** - CSRF token fix (related to Import page)

---

## 9. Change Log

**v1.0.0 (2026-01-01)**

- Initial comprehensive guide
- Added all 6 core patterns
- Included anti-patterns gallery
- Added testing checklist
- Created troubleshooting section
- Cross-referenced with DESIGN_SYSTEM.md updates

---

**Questions or Issues?**

If this guide doesn't cover your use case:

1. Check DESIGN_SYSTEM.md Section 7.2 for strict rules
2. Search codebase for similar patterns (use examples as reference)
3. Test on iPhone SE (375px) before asking
4. Document new patterns you discover for future reference

**Remember**: Mobile-first, responsive, accessible. In that order.
