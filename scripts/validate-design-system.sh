#!/bin/bash
# Design System Compliance Validator
# Enforces DESIGN_SYSTEM.md rules - violations block commits

set -e

VIOLATIONS=0
FULL_SCAN=false

# Check for --full flag (used by monthly audit)
if [[ "$1" == "--full" ]]; then
  FULL_SCAN=true
fi

# Forbidden patterns with contextual descriptions
declare -A PATTERNS=(
  ["bg-blue-[0-9]"]="Blue backgrounds (except Button.tsx primary variant)"
  ["bg-green-[0-9]"]="Green backgrounds (all shades forbidden)"
  ["bg-yellow-[0-9]"]="Yellow backgrounds (all shades forbidden)"
  ["bg-orange-[0-9]"]="Orange backgrounds (all shades forbidden)"
  ["text-blue-[0-9]"]="Blue text (except Button.tsx)"
  ["text-green-[0-9]"]="Green text (all shades forbidden)"
  ["text-yellow-[0-9]"]="Yellow text (all shades forbidden)"
  ["text-orange-[0-9]"]="Orange text (all shades forbidden)"
  ["text-slate-[0-9]"]="Slate text (use text-gray-* instead)"
  ["bg-slate-[0-9]"]="Slate backgrounds (use bg-gray-* instead)"
  ["border-slate-[0-9]"]="Slate borders (use border-gray-* instead)"
  ["ring-blue-500"]="Wrong focus ring (use ring-[#0071e3])"
  ["ring-green-[0-9]"]="Green focus ring (forbidden)"
  ["hover:shadow-md"]="Hover shadow effects (forbidden per §3.2)"
  ["shadow-lg"]="Large shadows (forbidden)"
  ["shadow-xl"]="Extra large shadows (forbidden)"
  ["shadow-2xl"]="2XL shadows (forbidden)"
  ["style="]="Inline styles (forbidden - use Tailwind tokens)"
  ['bg-\[#(?!0071e3)']="Hardcoded hex background (only #0071e3 allowed)"
  ['text-\[#(?!0071e3)']="Hardcoded hex text color (only #0071e3 allowed)"
  ['border-\[#(?!0071e3)']="Hardcoded hex border (only #0071e3 allowed)"
)

# Emoji detection pattern (Unicode ranges)
EMOJI_PATTERN='[\x{1F300}-\x{1F9FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]'

# Determine files to check
if [ "$FULL_SCAN" = true ]; then
  FILES=$(find resources/js -name "*.tsx" -type f)
  echo "🔍 Running FULL design system audit..."
else
  FILES=$(git diff --cached --name-only --diff-filter=ACM | grep "\.tsx$" || true)
  if [ -z "$FILES" ]; then
    exit 0
  fi
  echo "🔍 Checking staged files for design system violations..."
fi

# Exception handling for Button.tsx and technical requirements
BUTTON_FILE="resources/js/components/ui/Button.tsx"
ALLOWED_INLINE_STYLE_FILES=(
  "resources/js/pages/welcome.tsx"        # SVG mixBlendMode (technical requirement)
  "resources/js/components/ui/Toast.tsx"  # Progress bar width animation
)

# Check each forbidden pattern
for PATTERN in "${!PATTERNS[@]}"; do
  DESCRIPTION="${PATTERNS[$PATTERN]}"
  
  if [ -n "$FILES" ]; then
    MATCHES=$(echo "$FILES" | xargs grep -nE "$PATTERN" 2>/dev/null | grep -v "translate-" || true)
    
    # Filter out Button.tsx exceptions for blue colors
    if [[ "$PATTERN" == "bg-blue-"* ]] || [[ "$PATTERN" == "text-blue-"* ]]; then
      MATCHES=$(echo "$MATCHES" | grep -v "$BUTTON_FILE" || true)
    fi
    
    # Filter out allowed inline style exceptions
    if [[ "$PATTERN" == "style=" ]]; then
      for ALLOWED_FILE in "${ALLOWED_INLINE_STYLE_FILES[@]}"; do
        MATCHES=$(echo "$MATCHES" | grep -v "$ALLOWED_FILE" || true)
      done
    fi
    
    if [ -n "$MATCHES" ]; then
      echo ""
      echo "❌ VIOLATION: $DESCRIPTION"
      echo "   Pattern: $PATTERN"
      echo ""
      echo "$MATCHES"
      VIOLATIONS=$((VIOLATIONS + 1))
    fi
  fi
done

# Check for emojis in code (not in comments)
if [ -n "$FILES" ]; then
  EMOJI_MATCHES=$(echo "$FILES" | xargs grep -nP "$EMOJI_PATTERN" 2>/dev/null | grep -v "^\s*//" || true)
  if [ -n "$EMOJI_MATCHES" ]; then
    echo ""
    echo "❌ VIOLATION: Emoji in source code (forbidden)"
    echo ""
    echo "$EMOJI_MATCHES"
    VIOLATIONS=$((VIOLATIONS + 1))
  fi
fi

# Report results
if [ $VIOLATIONS -gt 0 ]; then
  echo ""
  echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
  echo "⛔ DESIGN SYSTEM ENFORCEMENT FAILURE"
  echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
  echo ""
  echo "Found $VIOLATIONS violation(s)."
  echo ""
  echo "Fix by using gray tokens or approved components."
  echo "See DESIGN_SYSTEM.md §2–§6."
  echo ""
  if [ "$FULL_SCAN" = false ]; then
    echo "To bypass this check: git commit --no-verify"
    echo "(Not recommended - violations will be caught in CI)"
  fi
  echo ""
  exit 1
fi

if [ "$FULL_SCAN" = true ]; then
  echo "✅ Full audit complete: No violations found"
else
  echo "✅ All staged files comply with design system"
fi

exit 0
