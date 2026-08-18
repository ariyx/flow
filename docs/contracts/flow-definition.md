# Flow Definitions

## Draft definition

The draft is editor-oriented and includes React Flow layout data.

```json
{
  "schema_version": "1",
  "nodes": [],
  "edges": [],
  "viewport": { "x": 0, "y": 0, "zoom": 1 }
}
```

## Runtime definition

The runtime definition is compiled, validated and immutable.

```json
{
  "schema_version": "1",
  "flow_id": "01...",
  "version": 1,
  "trigger": {
    "type": "start_command",
    "config": {}
  },
  "entry_node_id": "01NODE...",
  "nodes": {
    "01NODE...": {
      "id": "01NODE...",
      "type": "send_message",
      "version": 1,
      "name": "Welcome",
      "config": { "text": "Hello {{user.first_name}}" },
      "transitions": { "next": "01END..." }
    }
  }
}
```

## Publish lifecycle

Draft → Laravel validation → Go compile and validation → immutable flow version → activate version.

Warnings may be overridden. Errors block publishing.
