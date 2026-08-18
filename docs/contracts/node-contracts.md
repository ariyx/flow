# Node Contracts

## Shared node shape

```json
{
  "id": "01...",
  "type": "send_message",
  "version": 1,
  "name": "Welcome",
  "config": {},
  "transitions": {}
}
```

## V0.1 trigger types

- `start_command`
- `custom_command`
- `text_message`
- `button_callback`

Text matching modes:

- `any`
- `exact`
- `contains`

## V0.1 action and logic types

- `send_message`
- `send_photo`
- `ask_question`
- `show_buttons`
- `set_variable`
- `condition`
- `go_to`
- `end_flow`

## Condition operators

- equal
- not_equal
- contains
- starts_with
- greater_than
- less_than
- is_empty
- is_not_empty

Conditions have `true` and `false` transitions.

## Ask Question input types

- text
- number
- email
- phone
- button_choice

The node stores the accepted input in a configured variable and may define a custom validation error message.

## Versioning

Node `version` is independent from flow version. Breaking node-contract changes require a new node version and migration strategy.
