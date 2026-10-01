# MONOLITH — Project Decisions & Business Rules

**Purpose:** Living source of truth for decisions made during MONOLITH product, policy, UX, and website interviews.

**Rule:** New agents must distinguish confirmed business decisions from recommendations and unresolved questions. Do not promote a recommendation into policy until it is explicitly approved.

**Status date:** October 2026

---

## Status Labels

- **APPROVED** — explicitly decided by the project owner.
- **PROVISIONAL** — current working direction; may change.
- **RECOMMENDED / AWAITING APPROVAL** — suggested by research or the agent, but not yet approved.
- **OPEN QUESTION** — must be resolved before production copy or business logic is finalized.

---

## 1. Checkout / Transactional UI

### Approved visual direction
**Status: APPROVED**

The checkout design direction is approved and should be reused as the transactional MONOLITH UI language:

- warm mineral / off-white primary surfaces
- graphite / obsidian contrast areas
- restrained warm-metal / copper accents
- refined serif headlines
- minimal sans-serif UI text
- thin borders
- generous spacing
- clean, calm forms
- very little decorative imagery
- dark sticky order summary on desktop
- compact expandable order summary on mobile
- luxury, architectural, masculine, calm, trustworthy
- clarity takes priority over cinematic effects during checkout

This styling should inform cart, mini-cart, account, order confirmation, sizing forms, and other transactional screens.

### Architecture
**Status: APPROVED**

WooCommerce remains responsible for actual cart, checkout, taxes, shipping, payment, order creation, validation, inventory, and transactional logic. MONOLITH provides the presentation layer.

---

## 2. Support / Contact Experience

### Approved visual direction
**Status: APPROVED**

Support/contact should feel like the support-oriented sibling of checkout:

- same warm mineral / graphite / restrained copper system
- slightly more editorial than checkout
- still calm, minimal, and architectural
- function as a support hub, not just a plain contact form

Current support prototype includes:

- support routing
- help-center topics
- substantial contact form
- pre-purchase product guidance
- FAQ area
- restrained footer

No unverified service promises such as response time, warranty coverage, phone availability, or return windows should be published until approved.

---

## 3. Prototype Navigation

### Current preview behavior
**Status: APPROVED FOR PROTOTYPE**

- MONOLITH logo → homepage
- Bands → homepage Bands section
- Materials → homepage Materials section
- The Ceremony → homepage Ceremony section
- Support / Contact → support page
- Bag → cart page
- Cart → checkout
- Checkout logo / Continue Shopping → homepage
- Checkout Contact link → support page

Pages not yet built should remain placeholders rather than linking to fake destinations.

---

## 4. Ring Sizing Strategy

### Standalone Fit Kit concept
**Status: PROVISIONAL**

Current owner direction:

- Offer a physical sizing kit for approximately **$20**.
- The customer pays for the kit upfront so people do not order free sizers with no purchase intent.
- When the customer later buys a MONOLITH ring, the sizing-kit amount should be credited back against the ring purchase.
- The intended result is that the Fit Kit is effectively free for a genuine ring buyer.

### Recommended implementation
**Status: RECOMMENDED / AWAITING APPROVAL**

Working names:

- **MONOLITH Fit Assurance** — name of the sizing service/system
- **MONOLITH Fit Kit** — name of the physical sizing kit

Recommended customer-facing model:

- Fit Kit price: **$20**
- Customer receives **$20 Band Credit**
- Prefer automatic account/email-linked credit over a coupon the buyer must remember
- If a customer has already chosen a ring but is unsure of size, consider allowing them to place the ring order now, send the Fit Kit first, and hold final ring fulfillment until they confirm size

Recommended premium kit direction:

- use substantial sample-sizing rings rather than only paper or thin belt-style sizers
- match likely MONOLITH widths / comfort-fit feel as closely as practical
- clearly mark ring sizes
- instruct customer to test fit at different times of day

### Open implementation questions
**Status: OPEN QUESTION**

- Is the $20 Fit Kit credit automatic by account/email, or issued as a code?
- Does the ring-order-first / Fit-Kit-before-fulfillment flow become official?
- Which widths / sample sizes should physically ship in the Fit Kit?
- Does the Fit Kit need to be returned or is it kept by the customer?

---

## 5. Wrong-Size Exchange Policy

### First size exchange
**Status: APPROVED**

If the customer receives the ring and the size is wrong:

- MONOLITH offers **one free first size exchange**
- the original ring must be received back by MONOLITH first
- only after MONOLITH receives the returned ring is the replacement size sent

### Additional size exchanges
**Status: APPROVED**

- Any additional size exchange after the first costs **$30**

### Policy wording recommendation
**Status: RECOMMENDED / AWAITING APPROVAL**

Prefer describing the $30 charge as a **size-exchange fee** rather than a “restocking fee” in customer-facing copy, because the charge is tied specifically to repeated sizing changes.

Working service name:

- **MONOLITH First Fit Exchange**

Recommended scope:

- first complimentary size exchange applies to the same ring/configuration
- switching model, width, finish, or other product configuration should be treated as a normal return/exchange rather than a sizing exchange

### Open questions
**Status: OPEN QUESTION**

- Who pays return shipping for the first complimentary size exchange?
- Does MONOLITH provide a prepaid return label?
- What condition must the returned ring be in?
- Is original packaging required?
- What happens when the ring was engraved and the size is wrong?
- Does using the MONOLITH Fit Kit change any exchange eligibility or cost?

---

## 6. Product / Policy Communication Principle

**Status: APPROVED PROJECT PRINCIPLE**

MONOLITH policy and product copy should prioritize:

- certainty
- engineering clarity
- material honesty
- fit confidence
- transparent limitations
- no surprises after checkout

Avoid vague overpromising. Product-specific claims must be verified before they are treated as MONOLITH facts.

---

## 7. Interview-to-Repository Workflow

**Status: APPROVED**

During ongoing owner interviews:

1. Capture the owner's raw operational answer.
2. Translate it into the correct ecommerce concept, feature, policy, or service.
3. Research market practice when useful.
4. Clearly separate:
   - owner-approved decisions
   - provisional directions
   - recommendations
   - open questions
5. Update this document after meaningful decisions.
6. Update dedicated policy/schema documents later when a topic is mature enough.
7. Do not rely on chat memory as the only storage location.

---

## 8. Topics Still To Resolve

Current interview queue:

- return policy for non-sizing dissatisfaction
- engraved ring + wrong size
- warranty scope
- accidental damage / discounted replacement
- shipping / fulfillment timing
- wedding-deadline / rush-order service
- post-order change window
- engraving price, limits, fonts, symbols, and preview
- repair / restoration / refinishing
- support channels and realistic response times
- exact Fit Kit contents and logistics

