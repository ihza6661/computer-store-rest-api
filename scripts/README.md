# Design System Enforcement

This directory contains the automated validation tooling that enforces the Apple Standard Design System defined in `DESIGN_SYSTEM.md`.

## Purpose

Prevents UI regression by automatically detecting violations of design system rules before they reach the codebase.

## Files

### `validate-design-system.sh`

Main validation script. Detects forbidden patterns in TypeScript React files.

**Usage:**

```bash
# Check staged files (pre-commit)
./scripts/validate-design-system.sh

# Full codebase audit
./scripts/validate-design-system.sh --full

# Via npm (monthly audit)
npm run audit:design-system
```

## Enforcement Rules

### Automatically Blocked Patterns

**Forbidden Colors:**
- Semantic backgrounds: `bg-blue-*`, `bg-green-*`, `bg-yellow-*`, `bg-orange-*`
- Semantic text: `text-blue-*`, `text-green-*`, `text-yellow-*`, `text-orange-*`
- Slate palette: `text-slate-*`, `bg-slate-*` (use `gray-*` instead)
- Wrong focus rings: `ring-blue-500` (use `ring-[#0071e3]`)
- Hardcoded hex colors (except Apple Blue `#0071e3`)

**Forbidden Effects:**
- Large shadows: `shadow-md`, `shadow-lg`, `shadow-xl`, `shadow-2xl`
- Hover shadows: `hover:shadow-*`

**Forbidden Patterns:**
- Inline styles: `style=` (rare exceptions documented)
- Emojis in source code

### Allowed Exceptions

**Button.tsx** - Blue colors allowed in primary variant only
**welcome.tsx** - Inline styles for SVG `mixBlendMode` (technical requirement)
**Toast.tsx** - Inline style for progress bar width animation

## Exit Codes

- `0` - No violations found
- `1` - Violations detected (commit blocked)

## Git Integration

### Pre-Commit Hook

Location: `.git/hooks/pre-commit`

Runs automatically on `git commit`. Checks only staged files.

**To bypass (not recommended):**
```bash
git commit --no-verify
```

### Monthly Audit

Run full codebase scan:
```bash
npm run audit:design-system
```

Schedule: First of each month (not automated - manual execution required)

## Adding New Exceptions

If a technical requirement demands an exception:

1. **Document the reason** - Add comment in `validate-design-system.sh` explaining why
2. **Add to exception list** - Update `ALLOWED_*` arrays in script
3. **Update documentation** - Add to DESIGN_SYSTEM.md Appendix B

Undocumented exceptions will be removed on discovery.

## Maintenance

### When to Update

- New forbidden patterns discovered in wild
- New technical exceptions needed
- False positives detected

### Testing Changes

After modifying validator:

```bash
# Test on full codebase
./scripts/validate-design-system.sh --full

# Test on specific file
git add path/to/file.tsx
./scripts/validate-design-system.sh
git reset HEAD path/to/file.tsx
```

## Philosophy

This validator embodies a simple principle:

**If the UI feels "designed", you have failed.**

Violations are not creative decisions. They are regressions.

Gray is the default. Color requires justification. Entropy is the enemy.

---

**Last Updated:** January 2026
