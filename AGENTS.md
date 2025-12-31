# AI AGENT OPERATING RULES

This repository enforces a strict Apple-standard UI design system.

## 0. CORE PRINCIPLE

**If a UI change needs explanation, it is already wrong.**

Visual decisions must be self-evident through spacing, typography, and hierarchy—not through color, decoration, or justification documents.

---

## 1. DESIGN SYSTEM IS LAW

- `DESIGN_SYSTEM.md` is authoritative.
- It overrides all general UI/UX knowledge.
- If there is any conflict between your suggestion and the design system:
  → STOP and report the conflict.
- Do NOT "improve" or reinterpret the rules.

Violations are regressions.

---

## 2. COLOR USAGE (ABSOLUTE)

- You may ONLY use colors explicitly allowed in `DESIGN_SYSTEM.md`.
- If a Tailwind color class is not listed there, it is FORBIDDEN.
- When uncertain:
  → Use gray.
  → Or ask before proceeding.

Common forbidden examples:
- `bg-blue-50`, `bg-green-50`, `bg-yellow-50`
- Semantic color sections
- Color-coded emphasis

---

## 3. COMPONENT USAGE (MANDATORY)

- Use existing UI components (`Button`, `Card`, `Badge`, etc.).
- Do NOT recreate UI patterns inline.
- Do NOT add new variants without explicit instruction.

If a component cannot support a need:
→ Report limitation, do NOT workaround.

---

## 4. NO CREATIVE INTERPRETATION

You are NOT allowed to:
- Add expressive styling
- Add emojis to UI
- Add decorative colors
- Add visual hierarchy through color
- Introduce "just this once" exceptions

If the UI starts to feel “designed”, you have failed.

---

## 5. REQUIRED CHECK BEFORE ANY UI CHANGE

Before outputting code, verify:

- [ ] No forbidden colors used
- [ ] No emojis introduced
- [ ] Only documented components used
- [ ] Focus rings use Apple Blue only
- [ ] No new visual patterns added

If any check fails:
→ DO NOT OUTPUT CODE.

---

## 6. FAILURE MODE

If you cannot complete a request without violating these rules:

- Explain why
- Quote the exact rule being violated
- Propose a compliant alternative

Silence or guessing is not acceptable.

---

## 7. DESIGN SYSTEM EVOLUTION (STRICT)

Any UI change that introduces a new color, variant, or visual concept **must be preceded by an approved DESIGN_SYSTEM.md change**.

Implementation without prior system approval is a rejection condition.

This means:
- No "I'll add this color quickly and document it later"
- No "This is just a small exception"
- No "I'll file an issue after shipping"

Process:
1. Identify need for new pattern
2. Propose DESIGN_SYSTEM.md change
3. Wait for approval
4. Implement only after documentation is updated

Reversed order = automatic rejection.

---

This document is binding.
