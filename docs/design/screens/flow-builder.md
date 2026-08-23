# Flow Builder

## Status

Design direction approved.
Detailed implementation specification and final reference pending.

## Purpose

The Flow Builder is the primary product workspace for visually creating and
editing Telegram automation flows.

It must prioritize clarity, canvas space and fast understanding of execution
structure.

The builder should feel like a professional technical tool without becoming
visually dense or intimidating.

## Primary layout

Desktop builder consists of:

- Builder header
- Node library / insertion control
- Flow canvas
- Canvas controls
- Node inspector
- Contextual builder actions

The canvas must receive the largest share of screen space.

## Builder header

The header should support only controls available in the active milestone.

Potential elements:

- Back navigation
- Flow name
- Draft / published state
- Save status
- Test action
- Publish action

Do not add controls before their product behavior exists.

## Canvas

The canvas is the visual focus of the page.

Requirements:

- Dark neutral background
- Subtle spatial grid or dots
- Clear node separation
- Connections remain visible without excessive glow
- Selection state is obvious
- Large usable empty area
- Panning and zooming must feel unobstructed

Avoid:

- visually noisy grid backgrounds
- unnecessary decorative animation
- heavy gradients
- excessive connection glow
- giant node cards

## Nodes

Nodes should be compact and immediately scannable.

Basic node anatomy:

- Type icon
- Node title
- Short optional summary
- Input / output handles
- Clear selected state
- Error state where relevant

Node appearance may vary subtly by functional category, but the builder must not
turn into a rainbow interface.

Use semantic colors only where they communicate useful meaning.

## Node states

Support visually distinguishable:

- default
- hover
- selected
- invalid / error
- disabled only if the execution model actually supports disabled nodes

Do not invent execution states not present in the product model.

## Connections

Connections should:

- be easy to follow
- remain visually secondary to nodes
- clearly show direction
- avoid excessive animation

Condition branches must remain understandable when introduced by the relevant
milestone.

## Node library

The node insertion experience should make available only node types currently
supported by the runtime contract.

Possible interaction patterns:

- compact left library
- command / search palette
- add-node popover

Choose the simplest approach appropriate to the milestone.

Do not display future node types as disabled placeholders.

## Inspector

Selecting a node should expose its editable configuration in a side inspector
when that node requires configuration.

Inspector principles:

- Contextual
- Structured
- Narrow
- Scrollable independently where necessary
- Clear save / validation behavior

Do not duplicate controls unnecessarily between the node and inspector.

## Empty canvas

A new flow should not appear broken.

Provide a minimal first-use state such as:

- short hint
- Add first node CTA
- optional keyboard / interaction hint

Keep the canvas itself visible.

## Save state

When persistence is implemented, the interface should clearly distinguish:

- saving
- saved
- save error

Do not fake save states before persistence exists.

## Publish state

When publishing is introduced:

- Draft and published state must be clearly distinguishable
- Publish must be a deliberate action
- Errors must remain actionable
- Do not imply deployment infrastructure beyond the documented Flow publishing model

## Test flow

If a Test action exists in the current milestone:

- Keep it visually secondary to Publish
- Show progress and result clearly
- Do not introduce a separate complex testing environment without product scope

## Canvas controls

Keep controls minimal.

Typical controls may include:

- zoom in
- zoom out
- fit view
- optional minimap if the current implementation benefits from it

Do not add controls merely because React Flow supports them.

## Responsive behavior

The full builder is desktop-first because visual node editing requires working
space.

Tablet:

- Preserve canvas
- Collapse secondary panels where useful

Mobile:

- Must remain navigable
- Complex editing may use simplified panels / sheets
- Do not attempt to reproduce the entire desktop layout at tiny widths

Do not silently make the application desktop-only unless the product
requirements explicitly permit it.

## Visual rules

Follow `../ui-foundation.md`.

Builder-specific rules:

- Canvas gets visual priority
- UI chrome remains restrained
- Use purple for active/primary builder actions
- Nodes should use surfaces and borders more than shadows
- Keep text small but readable
- Keep port/handle interaction areas usable
- Avoid marketing-style visual treatment

## Likely components

Only as needed:

- FlowBuilderShell
- BuilderHeader
- FlowCanvas
- NodeLibrary
- BaseNode
- NodeHandle
- NodeInspector
- CanvasControls
- BuilderEmptyState
- SaveStatus

Do not build a generic node plugin framework in v0.1.

## Accessibility

Where practical:

- Controls must be keyboard accessible
- Icon-only controls require labels
- Inspector fields follow normal form accessibility rules
- Selected nodes need more than color-only indication

Canvas interaction accessibility should improve progressively without blocking
the current v0.1 scope.

## Product-scope guardrail

The visual reference may show node types, analytics, integrations or actions that
are not yet implemented.

Only render and implement functionality defined by:

- the current milestone
- the canonical Flow contracts
- accepted architecture
- product scope

The design reference defines visual direction, not runtime capability.