## Status

Approved for implementation.

## Visual reference

`../references/flow-builder-v1.png`

## Purpose

Define the primary visual editing experience for Webilo Flow.

The Flow Builder is the core application surface for creating and editing
Telegram automation workflows.

It should feel like a professional technical tool while remaining clear,
focused, and approachable.

This document defines UI structure, presentation, and interaction behavior.

Product requirements, milestone scope, canonical contracts, architecture
documentation, and accepted ADRs remain authoritative.

## Design direction

The Flow Builder should feel:

- dark-first
- technical
- focused
- compact
- professional
- spatial
- highly usable
- visually restrained

The canvas must remain the primary focus.

Application chrome should support editing without competing with the graph.

## Core layout

Desktop structure:

```text
┌──────────────┬──────────────────────────────────────────────┬──────────────┐
│ App Sidebar  │ Builder Header                               │ Inspector    │
│              ├──────────────────────────────────────────────┤              │
│              │ Node Library │                              │              │
│              │              │          Canvas              │              │
│              │              │                              │              │
│              │              │                              │              │
│              │              │                              │              │
│              │              │                  Minimap     │              │
│              │              │                              │              │
│              └──────────────────────────────────────────────┴──────────────┤
│              │ Save Status / Builder Footer                               │
└──────────────┴─────────────────────────────────────────────────────────────┘
````

The exact panel visibility may change based on available functionality.

The canvas should receive the largest share of the viewport.

## Application shell integration

The Flow Builder lives inside the authenticated Webilo Flow application.

Follow the shared Dashboard Shell for:

* product branding
* account identity
* workspace identity
* global navigation
* responsive application behavior

The builder may use a wider content mode than normal dashboard pages.

Do not constrain the canvas using a dashboard-oriented max-width.

## Builder header

The builder header represents the currently edited Flow.

Potential content includes:

### Left

* back navigation
* flow name
* editable flow title when implemented
* draft/published status

### Center

Optional editing controls such as:

* undo
* redo

Only show these controls when the underlying behavior exists.

### Right

Potential contextual actions:

* Test
* Publish
* additional overflow menu

Do not add functionality simply because it appears in the visual reference.

Buttons must correspond to real product behavior introduced by the active
milestone.

## Flow status

When publishing exists, the Flow state should be clearly represented.

Possible states include:

* Draft
* Published

Do not invent additional lifecycle states outside the canonical product model.

Status should be visually distinct but not visually dominant.

## Node library

The Node Library provides access to node types currently supported by the Flow
contract and active milestone.

It should remain compact and searchable once the number of supported nodes
justifies search.

### Structure

The library may group nodes by meaningful product categories such as:

* Triggers
* Flow
* Actions
* Utilities

Category structure must reflect actual supported node types.

Do not create categories purely for visual completeness.

## Node search

Search may be introduced when enough supported node types exist to justify it.

If implemented:

* filter by node title
* optionally filter by keywords
* update immediately
* support keyboard interaction

Do not implement advanced fuzzy search or indexing infrastructure unless
needed.

## Node library items

Each node entry may include:

* icon
* title
* short description
* category-specific semantic accent

Items should be easy to scan.

Avoid:

* oversized cards
* excessive text
* large illustrations
* unnecessary metadata

## Supported nodes

The visual reference includes conceptual node types such as:

* Telegram Start
* Telegram Text
* Telegram Callback
* Telegram Channel Post
* Telegram Inline Query
* Send Message
* Condition
* Switch
* Delay
* Set Data
* HTTP Request
* Log
* End

These are visual examples only.

Do not implement or expose any node unless it exists in the current canonical
contracts and milestone scope.

## Canvas

The canvas is the primary editing surface.

It should occupy the largest available area.

### Background

Use:

* dark neutral background
* subtle dotted or grid pattern
* low-contrast spatial guidance

Avoid:

* bright grids
* heavy texture
* decorative gradients
* visually noisy backgrounds

The grid should support spatial orientation without competing with nodes.

## Canvas interaction

When supported by the underlying implementation, the canvas may allow:

* pan
* zoom
* select
* drag nodes
* create connections
* delete elements
* multi-select

Only implement interactions required by the current milestone.

Do not expose controls for unsupported interaction behavior.

## Nodes

Nodes should be compact and immediately understandable.

### Basic anatomy

A standard node may contain:

* type icon
* node title
* short optional summary
* input handles
* output handles
* semantic state where needed

Avoid filling nodes with configuration fields.

Detailed configuration belongs in the Inspector.

## Node sizing

Nodes should remain relatively compact.

Prefer enough width to:

* keep titles readable
* show short context
* make connection handles usable

Avoid large card-like nodes that consume unnecessary canvas space.

## Node states

Where applicable, support:

* default
* hover
* selected
* invalid/error
* disabled only if supported by the product model

Selected state should be clearly visible.

Do not communicate selection through color alone.

## Node categories

Node categories may use restrained semantic accents.

Examples:

* Telegram-related nodes may use Telegram-associated blue
* condition/branching nodes may use warning-like accents
* data nodes may use another restrained semantic accent

Do not turn the canvas into a rainbow interface.

The shared dark surfaces and borders should remain dominant.

## Connection handles

Handles must:

* remain visible
* be large enough to interact with
* communicate input/output direction
* not dominate the node design

Increase usable hit area where necessary without increasing visible size
excessively.

## Connections

Connections should:

* clearly communicate direction
* remain easy to trace
* stay visually secondary to nodes
* distinguish meaningful branches where necessary

Avoid excessive glow or animation.

## Branching

Condition and branching nodes may expose multiple outputs.

When branches have semantic meaning, labels may be used.

Example:

* Yes
* No

Branch labels should match actual execution semantics defined by the Flow
contract.

Do not invent branch behavior from the visual reference.

## Edge states

Where useful, connections may support:

* default
* selected
* error/invalid

Do not add runtime execution animation before execution visualization is part of
product scope.

## Inspector

The Inspector provides configuration for the currently selected node.

It should appear as a right-side panel on desktop.

### Principles

The Inspector should be:

* contextual
* structured
* compact
* clear
* independently scrollable when necessary

Only show controls relevant to the selected node.

## Inspector structure

Typical hierarchy:

```text
Node title
Node identifier / metadata if useful

Primary configuration
Secondary configuration

Outputs / branching information

Error handling when supported

Danger zone / delete node
```

The actual fields depend on the canonical node schema.

Do not hard-code configuration UI independently of the contracts.

## Inspector tabs

The reference may show multiple Inspector tabs.

Do not create tabs unless actual node functionality requires distinct sections.

Avoid empty or future-facing tabs.

## Inspector forms

Follow the shared application form rules:

* visible labels
* clear validation
* keyboard accessibility
* focus-visible styles
* loading/saving behavior when relevant

Validation errors should be shown near the relevant field.

## Node deletion

When deletion is supported:

* expose a clear delete action
* destructive styling should use the shared danger color
* require confirmation only when accidental deletion would be meaningfully
  harmful

Avoid unnecessary confirmation dialogs for easily reversible actions.

## Empty canvas

A new Flow should have an intentional empty state.

The canvas should remain visible.

Possible content:

```text
Start building your flow

Add your first node to begin.
```

Potential primary action:

`Add node`

Do not fill the empty canvas with fake nodes.

## Canvas controls

Keep controls minimal.

Potential controls include:

* Select/Pan mode
* Zoom out
* Current zoom
* Zoom in
* Fit view
* Minimap toggle

Only expose controls that are useful and supported by the chosen Flow editing
library.

Do not expose every feature provided by React Flow simply because it exists.

## Zoom

Zoom controls should remain compact and unobtrusive.

Current zoom may be displayed as a percentage.

Avoid placing large toolbars over important canvas content.

## Fit view

When implemented, Fit View should reposition and scale the graph to make
relevant nodes visible.

It should not unexpectedly alter node positions.

## Minimap

The minimap is optional.

Use it only when graph size or navigation complexity justifies it.

If present:

* keep it small
* use simplified node representations
* maintain enough contrast for orientation
* do not allow it to dominate the canvas

Do not add it prematurely to very small flows solely to match the reference.

## Save state

When Flow persistence exists, clearly communicate save status.

Possible states:

* Saving
* Saved
* Save failed

Example direction:

`All changes saved`

Avoid persistent success notifications for routine autosaves.

Save failure should be clearly visible and actionable.

## Autosave

If autosave is part of the accepted product behavior:

* debounce appropriately
* avoid excessive requests
* preserve unsaved changes during temporary failure
* communicate failure clearly

Do not introduce autosave architecture solely from this design specification.

## Test action

The Test action is visualized in the reference.

Only display it when Flow testing behavior exists in the current milestone.

If implemented:

* visually secondary to Publish
* clear running state
* prevent accidental duplicate execution
* provide useful test result/error feedback

Do not build a separate complex testing subsystem from the UI specification.

## Publish action

Only display Publish when Flow publishing exists.

Publish should:

* be clearly distinguishable
* require deliberate user intent
* display progress
* display actionable errors
* clearly update Flow status after success

Do not imply production deployment or infrastructure outside the documented
Flow publishing model.

## Undo and redo

Only display Undo/Redo if the editor implements reliable reversible editing
history.

Do not add visual controls that do nothing.

If implemented:

* disabled state when unavailable
* keyboard shortcuts where appropriate
* actions must match editor history behavior

## Builder footer

A lightweight builder footer may communicate:

* save status
* last saved time
* connection/runtime state when meaningful

Keep footer information concise.

Do not turn the footer into a monitoring/status dashboard.

## Keyboard interaction

Where supported:

* Delete / Backspace may delete selected nodes
* Escape clears temporary selection or closes contextual UI
* common undo/redo shortcuts may be supported
* keyboard focus must remain visible

Do not conflict with text-input editing shortcuts.

## Context menus

Do not implement context menus unless they provide current, useful editing
actions.

Avoid copying desktop IDE interaction patterns without a product need.

## Responsive behavior

The Flow Builder is desktop-first because visual graph editing requires
significant workspace.

### Desktop

* full builder layout
* node library visible when useful
* Inspector visible for selected nodes
* canvas receives most space

### Tablet

Panels may become collapsible.

Prefer preserving canvas space.

Potential behavior:

* node library opens as a panel/sheet
* inspector may collapse
* builder header becomes more compact

### Mobile

The Flow Builder must remain navigable, but full graph editing does not need to
mirror the desktop layout exactly.

Potential mobile approach:

* full-screen canvas
* node library in a sheet
* inspector in a bottom sheet or full-screen panel
* compact builder header
* simplified canvas controls

Do not force desktop side panels into a narrow mobile viewport.

Do not silently make core builder routes inaccessible on mobile.

## Panel resizing

Do not implement resizable Node Library or Inspector panels unless usability
testing demonstrates a real need.

Fixed sensible widths are preferred for v0.1.

## Loading state

When a Flow is loading:

* preserve builder shell
* use lightweight local loading treatment
* avoid showing stale configuration from another Flow

Do not replace the entire application with a global spinner.

## Error state

If a Flow cannot be loaded:

* show a clear error state
* provide retry where appropriate
* prevent editing unknown/stale state
* do not expose raw backend errors

Authorization/workspace ownership failures must fail closed.

## Validation

Invalid Flow state should be understandable before publishing.

Where contract validation exists:

* identify the affected node
* surface useful field-level errors in the Inspector
* indicate invalid nodes on the canvas
* avoid generic `Flow invalid` messages without location/context

Do not invent validation rules outside the canonical contracts.

## Accessibility

Standard UI controls must be accessible.

Requirements include:

* keyboard-accessible buttons
* accessible labels for icon-only controls
* visible focus states
* form labels in the Inspector
* errors not communicated through color alone
* adequate contrast

Canvas-level accessibility should improve progressively without blocking the
v0.1 product scope.

## Visual guidance

Follow:

`../ui-foundation.md`

Builder-specific guidance:

* canvas receives visual priority
* application chrome remains compact
* use dark surfaces and subtle borders
* purple reserved for selection and primary actions
* semantic colors used sparingly
* minimal shadows
* minimal glow
* compact node cards
* clear directional connections
* restrained toolbars
* no marketing-style visuals

## Implementation guidance

Prefer:

* existing React architecture
* React Flow when already selected by architecture/product docs
* Tailwind CSS
* existing reusable application components
* canonical JSON Schema / Flow contracts for configuration UI

Do not introduce:

* another graph/editor framework
* a node plugin platform
* a public Node SDK
* generic workflow abstractions beyond current contracts
* large UI libraries
* unnecessary animation libraries
* custom rendering infrastructure when React Flow already solves the current
  requirement

## Contract-driven UI

The Flow contracts are authoritative for node structure.

Where practical:

* derive field behavior from canonical node definitions
* keep frontend node types aligned with published schema names
* validate data against contracts
* avoid frontend-only hidden node semantics

Do not allow the visual editor to create Flow definitions that the runtime
cannot understand.

## Performance

v0.1 should remain responsive for realistic demo-sized Flows.

Avoid premature optimization.

Do not implement:

* canvas virtualization infrastructure
* custom graph engines
* distributed editor state
* complex memoization systems

unless measured performance shows a real need.

## Product-scope guardrail

The visual reference contains conceptual functionality used to communicate
layout and visual direction.

It does not expand product scope.

Do not infer requirements for:

* all Telegram trigger types shown
* Switch
* Delay
* HTTP Request
* Set Data
* Log
* Test execution
* Publish behavior
* execution history
* advanced error handling
* minimap
* undo/redo
* autosave
* node search
* future integrations

Only implement functionality introduced by:

* the current milestone
* canonical Flow contracts
* product requirements
* accepted architecture
* relevant ADRs

If the visual reference conflicts with these sources, the product sources win.

## Implementation acceptance

The Flow Builder UI is visually complete for a milestone when:

* the canvas is the primary visual surface
* only currently supported node types are exposed
* node appearance follows the approved visual direction
* supported connections are easy to understand
* selected nodes are visually clear
* the Inspector reflects actual node configuration
* unsupported controls are not displayed
* empty-canvas behavior is intentional
* desktop layout is usable
* smaller-screen behavior does not overflow horizontally
* standard controls remain keyboard accessible
* contract validation errors are understandable when applicable
* no future milestone functionality is introduced
* shared UI foundation rules are followed