# Workflow System

The project includes a workflow system for relic approval with the following components:

- **Workflow Configuration**: Defined in `config/packages/workflow.yaml`
- **RelicWorkflowService**: A service that provides methods for interacting with the workflow
- **RelicWorkflowController**: A controller that handles workflow transitions
- **RelicWorkflowSubscriber**: An event subscriber that responds to workflow events

The relic approval workflow has three states:
- `pending`: Initial state for new relics
- `approved`: Relics that have been approved by an admin
- `rejected`: Relics that have been rejected by an admin

And three transitions:
- `approve`: From pending to approved
- `reject`: From pending to rejected
- `resubmit`: From rejected to pending