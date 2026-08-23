# Authentication UI

## Status

Approved for implementation.

## Visual references

- `../references/auth-sign-in-v1.png`
- `../references/auth-flow-v1.png`

## Purpose

Provide a focused authentication experience for Webilo Flow while preserving
the product's dark, minimal and technical visual language.

This specification defines presentation and interaction behavior only.
Product requirements and the active milestone remain authoritative.

## Screens

- Sign in
- Create account
- Forgot password
- Reset password
- Password-reset confirmation

Do not add authentication methods that are not part of the current product
scope.

## Desktop layout

Use a centered split authentication container.

Left side:

- Webilo Flow branding
- Short product statement
- Minimal decorative Flow / automation visual
- No functional controls
- Decorative content must remain visually secondary

Right side:

- Screen title
- Short supporting text
- Authentication form
- Primary action
- Relevant secondary navigation

The form area should remain narrow enough for easy scanning and should not fill
the full available width.

## Mobile layout

Below the desktop breakpoint:

- Remove the decorative left panel
- Show a single authentication surface
- Keep the Webilo Flow logo visible
- Use comfortable horizontal padding
- Inputs and primary buttons use the available width
- Avoid oversized vertical gaps

## Sign in

Required UI:

- Email input
- Password input
- Password visibility control
- Remember me control if supported by the implementation
- Sign in button
- Forgot password link
- Create account link

Do not show social login unless it becomes part of an accepted product milestone.

## Create account

Required UI:

- Name input
- Email input
- Password input
- Password confirmation input
- Create account button
- Sign in link

Do not introduce Terms / Privacy consent controls unless a product requirement
explicitly requires them.

## Forgot password

Required UI:

- Email input
- Send reset link button
- Return to sign in link

After a successful request:

- Show a clear confirmation state
- Do not reveal whether an unknown email exists unless Laravel's configured
  behavior intentionally exposes that information

## Reset password

Required UI:

- New password input
- Password confirmation input
- Reset password button
- Return to sign in link where appropriate

## Form behavior

All authentication forms must support:

- disabled submitting state
- inline validation errors
- server-side authentication errors
- keyboard submission
- accessible labels
- visible focus states
- password visibility controls where a password field exists

Avoid generic error messages such as `Something went wrong` when a useful
validation or authentication message is available.

## Loading behavior

During submission:

- Disable duplicate submission
- Preserve entered form values
- Show a subtle loading state in the primary action
- Do not replace the whole page with a global loader

## Success behavior

Successful sign in or registration should transition directly into the
authenticated application experience.

Successful logout should return to authentication.

Successful password reset should provide a clear route back to sign in.

## Visual rules

- Dark-first
- Use the shared UI foundation tokens
- Purple is the primary interactive accent
- Thin borders instead of strong shadows
- No glassmorphism
- No excessive gradients
- No large decorative illustrations that overpower the form
- Inputs and buttons use consistent heights
- Authentication surfaces should feel compact but not cramped

## Components

Prefer reusable application primitives for:

- Logo
- Input
- PasswordInput
- Checkbox
- Button
- FormError
- FieldError
- AuthCard
- AuthHeader

Do not create a large authentication-specific component framework.

## Responsive expectations

The design must remain usable from small mobile screens through large desktop
screens.

Do not rely on fixed desktop widths that overflow small screens.

## Accessibility

- Every input has a programmatic label
- Errors are associated with their fields
- Focus states must be visible
- Controls must be usable by keyboard
- Do not communicate validation using color alone

## Product-scope guardrail

Reference images may contain visual placeholders or future concepts.

Do not implement features merely because they appear in the image.

Examples that are currently visual-only and must not be inferred as
requirements:

- Google authentication
- Social login
- Additional authentication providers