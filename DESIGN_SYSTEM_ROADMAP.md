# Design System Roadmap

Future evolution paths for design system enforcement. None of these are urgent. All require UI stabilization first.

## Phase 1: Complete ✅

- [x] Token infrastructure (app.css `@theme`)
- [x] Color constraint enforcement
- [x] Pre-commit validation
- [x] CI mirror
- [x] Documentation

**Status:** LOCKED DOWN

---

## Phase 2: Token-Only Mode (Future)

**Goal:** Ban raw Tailwind utilities in favor of semantic tokens.

**When:** After 6 months of stability (not before)

### Current State
```tsx
className="bg-gray-50 text-gray-600 border-gray-200"
```

### Target State
```tsx
className="bg-surface-muted text-secondary border-default"
```

### Why Wait

1. **Pattern discovery** - Let actual usage reveal needed tokens
2. **Premature abstraction** - Adding tokens now would create false semantics
3. **Churn cost** - Every token rename is a migration

### Implementation (When Ready)

#### Step 1: Usage Analysis
```bash
# Find most common gray patterns
grep -rh "gray-[0-9]" resources/js | sort | uniq -c | sort -rn
```

#### Step 2: Token Design
Based on actual usage, create semantic tokens:

```css
/* Surfaces */
--color-surface: #ffffff;
--color-surface-muted: #f9fafb;      /* gray-50 */
--color-surface-elevated: #ffffff;

/* Text */
--color-text-primary: #111827;       /* gray-900 */
--color-text-secondary: #374151;     /* gray-700 */
--color-text-tertiary: #6b7280;      /* gray-500 */
--color-text-muted: #9ca3af;         /* gray-400 */

/* Borders */
--color-border-default: #e5e7eb;     /* gray-200 */
--color-border-muted: #f3f4f6;       /* gray-100 */
--color-border-strong: #d1d5db;      /* gray-300 */

/* Interactive */
--color-interactive: #0071e3;
--color-interactive-hover: #0077ed;

/* Status */
--color-danger: #ef4444;
--color-danger-hover: #dc2626;
```

#### Step 3: Migration Script
```bash
# Automated replacement (with manual review)
sed -i 's/bg-gray-50/bg-surface-muted/g' resources/js/**/*.tsx
sed -i 's/text-gray-600/text-secondary/g' resources/js/**/*.tsx
# ... etc
```

#### Step 4: Validator Update
Add to `validate-design-system.sh`:
```bash
# After migration complete, ban raw utilities
"gray-[0-9]" -> "Use semantic tokens (text-primary, bg-surface, etc.)"
```

### Benefits (After Migration)

- Change entire palette by modifying 15 tokens
- Dark mode = duplicate token set
- Color meaning embedded in name
- Refactors become trivial

### Risks

- **False semantics** - "primary" might not mean "primary" in all contexts
- **Token explosion** - 50 tokens = failure (diminishing returns)
- **Migration churn** - Every component touched = risk

**Decision:** Wait until UI patterns stabilize.

---

## Phase 3: Component Token Props (Speculative)

**Goal:** Move tokens to component APIs

**When:** Maybe never

### Example
```tsx
// Instead of
<Button className="bg-accent text-white">

// Component handles tokens internally
<Button variant="primary">
```

**Status:** Already done for most components. May not need system-wide enforcement.

---

## Phase 4: Runtime Validation (Optional)

**Goal:** Detect violations in browser (dev mode only)

**When:** If pre-commit bypasses become epidemic

### Implementation
```tsx
// Dev-only runtime checker
if (process.env.NODE_ENV === 'development') {
  useEffect(() => {
    const forbidden = document.querySelectorAll('[class*="bg-blue-"]');
    if (forbidden.length > 0) {
      console.error('Design system violation detected:', forbidden);
    }
  }, []);
}
```

**Status:** Not needed yet. Pre-commit + CI are sufficient.

---

## Anti-Patterns to Avoid

### ❌ Don't: Add Warning Colors

Someone will ask for yellow/orange "info" states.

**Response:** "Use gray. If it's important, use red. If it's not important, remove it."

### ❌ Don't: Add Icon Colors

Someone will want green checkmarks.

**Response:** "Gray checkmark. Visual hierarchy through size/weight/spacing."

### ❌ Don't: Add Hover Color Changes

Someone will want hover states to "feel interactive."

**Response:** "Use opacity or background lightness. No hue shifts."

### ❌ Don't: Add Gradient Support

Someone will want "modern" gradients.

**Response:** "Gradients are decoration. Remove them."

### ❌ Don't: Add Animation Colors

Someone will want skeleton loaders to shimmer blue.

**Response:** "Gray shimmer. Neutral pulse."

---

## Entropy Attack Vectors (Stay Vigilant)

### "Just for this one component..."
**Block:** Exceptions require DESIGN_SYSTEM.md update first.

### "But Material Design / Bootstrap does..."
**Block:** This is not Material Design. This is Apple Standard.

### "The user research says..."
**Block:** Neutrality is not negotiable. Improve information architecture, not color coding.

### "Can we A/B test..."
**Block:** A/B test information hierarchy and content. Not visual expression.

### "It looks too plain..."
**Block:** Correct. That is the design.

---

## Success Metrics (Long-Term)

### Code Health
- Zero grep hits for forbidden patterns
- Token usage > 90% (after Phase 2)
- Component coverage > 95%

### Design Drift
- No new colors introduced in 12 months
- No new component variants without justification
- Documentation updates < 3 per year

### Developer Experience
- Violations caught in < 1 second
- Fix time < 2 minutes
- Design questions: zero (nothing to decide)

### Cultural
- UI changes no longer debated
- "It's gray" becomes default answer
- New devs complain about boredom (good sign)

---

## When to Revisit This Document

- After 6 months of stable usage
- When token-only mode is considered
- When new constraint types emerge
- Never (if nothing breaks)

---

**Philosophy:** Premature abstraction is just another form of decoration. Let usage dictate structure. Gray is the default. Entropy is the enemy.

