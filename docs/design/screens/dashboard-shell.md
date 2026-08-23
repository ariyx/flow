````md
# Dashboard Shell

## Status

Approved for implementation.

## Visual reference

`../references/dashboard-empty-v1.png`

## Purpose

Define the authenticated application shell and first-use dashboard experience
for Webilo Flow.

The Dashboard Shell is the structural foundation for authenticated product
pages and should remain visually stable as later milestones introduce additional
functionality.

This document defines UI structure, presentation, and interaction behavior.

Product requirements, milestone scope, architecture documentation, and accepted
ADRs remain authoritative.

## Design direction

The Dashboard Shell should feel:

- dark-first
- minimal
- technical
- compact
- focused
- premium
- product-oriented

The authenticated application should feel more functional and compact than
marketing surfaces.

Prefer clarity and usable workspace over decorative UI.

## Core layout

Desktop structure:

```text
┌──────────────────────────────────────────────────────┐
│ Sidebar │                 Main                       │
│         │                                            │
│ Brand   │  Page Header                               │
│         │  ───────────────────────────────────────   │
│ Nav     │                                            │
│         │              Page Content                  │
│         │                                            │
│         │                                            │
│─────────│                                            │
│Workspace│                                            │
│ Account │                                            │
└──────────────────────────────────────────────────────┘
````

The main application area should receive most of the available viewport.

Avoid oversized navigation chrome or unnecessarily narrow content areas.

## Sidebar

The sidebar remains visible on desktop.

### Visual behavior

* dark surface slightly distinct from the application background
* subtle border separating it from the main content
* compact spacing
* small and consistent navigation icons
* restrained active-state treatment
* minimal visual noise
* no heavy shadows
* no decorative gradients

### Branding

Show:

* Webilo Flow mark
* Webilo Flow product name

Keep branding compact and visually secondary to application content.

## Navigation

Only render routes that currently exist in the product.

Do not populate the sidebar with future modules merely because they appear in a
visual reference.

Potential future destinations may include:

* Dashboard
* Flows
* Bots
* Executions
* Settings

Each destination should only become visible when the corresponding product
functionality exists.

Do not show disabled placeholders for future features.

## Navigation states

Navigation items should support:

* default
* hover
* active
* focus-visible

The active state should use more than color alone.

Prefer a combination of:

* subtle surface change
* accent indicator
* icon distinction
* text emphasis

## Workspace identity

Webilo Flow v0.1 has exactly one owner workspace per account.

The sidebar may display:

* workspace name
* workspace icon or generated initials
* owner relationship when useful

Do not implement:

* workspace switching
* multiple workspace UI
* organization switching
* team members
* invitations
* membership management
* multi-workspace controls

The interface must not visually imply that multiple workspaces are supported.

## Account area

Place the authenticated user identity near the bottom of the sidebar.

It may include:

* user name
* email address
* avatar or generated initials
* account menu trigger

Only expose account actions that actually exist.

Do not create placeholder account functionality.

## Main header

The reusable page header may contain:

### Left

* page title
* optional short description

### Right

* contextual primary action
* optional secondary actions
* global controls only when implemented

Do not permanently reserve empty space for unsupported actions.

For the first-use dashboard, the page title may simply be:

`Dashboard`

## First-use dashboard

A newly registered account has no flows, bots, or execution history.

The dashboard should represent this state intentionally.

Do not render fake:

* statistics
* charts
* execution counts
* recent activity
* flows
* bots
* success rates
* analytics
* product usage data

The page should feel intentionally empty rather than unfinished.

## Welcome area

Keep introductory copy short and product-focused.

Example direction:

```text
Welcome to Webilo Flow

Build and automate your Telegram workflows visually.
```

Do not turn the authenticated dashboard into a marketing landing page.

## Empty state

Recommended direction:

```text
No flows yet

Create your first flow to start building your Telegram automation.
```

A restrained Flow-themed illustration may be used.

The visual may communicate concepts such as:

* nodes
* connections
* automation
* branching

The illustration is decorative only.

It must not imply unsupported runtime behavior or future product functionality.

## Primary action

Example:

`Create your first flow`

Only render this action when creating a Flow belongs to the current milestone.

If Flow creation is not yet implemented, use a milestone-appropriate empty-state
message instead of introducing fake navigation or behavior.

## Secondary actions

The approved visual reference may contain secondary actions such as:

`Explore templates`

Do not render them until the corresponding functionality exists in product
scope.

Visual references do not create product requirements.

## Main content area

Use consistent application padding.

The shell must support both:

* standard dashboard/content pages
* wide application surfaces such as the Flow Builder

Do not impose a restrictive global max-width that prevents future canvas-based
interfaces from using available horizontal space.

## Loading states

Keep the application shell stable while session or workspace data loads.

Prefer local loading states inside the content area.

Avoid:

* replacing the entire application with a large spinner
* unnecessary layout shifts
* hiding stable navigation during normal data loading

## Error states

If session or workspace resolution fails:

* fail closed
* display a useful error state
* provide retry when appropriate
* do not expose raw backend errors
* do not render workspace-scoped content when ownership cannot be verified

Authentication failure should return the user to the appropriate authentication
flow.

## Responsive behavior

### Desktop

* sidebar remains visible
* main content occupies remaining width
* workspace and account identity remain in the sidebar

### Tablet

* sidebar may become more compact
* preserve useful main-content width
* reduce secondary text when necessary
* avoid overcrowding navigation

### Mobile

Replace the persistent sidebar with a drawer or sheet.

The mobile shell should preserve access to:

* application navigation
* workspace identity
* account actions
* contextual page actions

Use a compact mobile application header.

Avoid horizontal scrolling.

Do not reproduce the full desktop sidebar at mobile width.

## Empty-state responsiveness

On smaller screens:

* prioritize heading
* prioritize description
* prioritize the primary action
* reduce decorative illustration size
* reduce unnecessary vertical spacing

Users should not need to scroll through decoration before reaching the primary
action.

## Components

Prefer a small reusable component vocabulary:

* `AppShell`
* `Sidebar`
* `SidebarNav`
* `SidebarItem`
* `WorkspaceIdentity`
* `AccountMenu`
* `PageHeader`
* `EmptyState`
* `MobileNavigation`

Only create abstractions required by current screens.

Do not build a generic application-layout framework.

## Interaction behavior

### Navigation

Navigation should feel immediate.

Avoid unnecessary transition animations.

### Account menu

If implemented:

* keyboard accessible
* dismissible with Escape
* closes on outside interaction
* exposes a clear logout action

### Mobile navigation

If implemented as a drawer or sheet:

* trap focus appropriately
* restore focus after closing
* close after successful navigation
* support Escape dismissal

## Accessibility

* Navigation must be keyboard accessible.
* Active navigation must not rely on color alone.
* Icon-only controls require accessible names.
* Focus-visible states must remain visible.
* Text and background contrast must remain readable.
* Mobile navigation must follow accessible dialog/sheet behavior.
* Interactive targets should remain comfortably usable on touch devices.

## Visual guidance

Follow:

`../ui-foundation.md`

Dashboard-specific guidance:

* use a slightly separated surface for the sidebar
* use subtle borders for surface hierarchy
* keep primary purple restrained
* keep application chrome compact
* prefer borders and spacing over strong shadows
* avoid decorative gradients
* avoid excessive nested cards
* avoid oversized icons
* avoid unnecessary visual effects

## Implementation guidance

Prefer:

* existing React architecture
* existing routing architecture
* existing authentication/session architecture
* Tailwind CSS
* existing shadcn/ui primitives where useful
* small reusable local components

Do not introduce:

* another styling framework
* a dashboard UI framework
* chart libraries for fake data
* animation libraries solely for this screen
* a large custom design system
* unnecessary layout abstractions

## Product-scope guardrail

The approved visual reference defines visual direction and layout only.

It does not expand product scope.

Do not infer requirements for:

* Templates
* Analytics
* Executions
* notifications
* theme switching
* multi-workspace switching
* team management
* workspace membership
* fake dashboard metrics
* future navigation destinations

Current milestone requirements, product documentation, architecture decisions,
and accepted ADRs always override the visual reference.

## Implementation acceptance

The Dashboard Shell is visually complete when:

* desktop layout follows the approved visual direction
* responsive/mobile navigation works correctly
* workspace identity represents the single-owner v0.1 model
* only implemented routes are visible
* the empty dashboard contains no fake product data
* unsupported secondary actions are not shown
* loading and error states are handled
* keyboard navigation works
* focus-visible states are present
* no horizontal overflow exists
* shared UI foundation rules are followed
* no future milestone functionality was introduced