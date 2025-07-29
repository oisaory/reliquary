# Version Management and CI Workflow

## Semantic Versioning

The project uses semantic versioning with automated release management:

- Versioning follows the [Semantic Versioning](https://semver.org/) specification (MAJOR.MINOR.PATCH)
- Version numbers are automatically determined based on commit messages using [semantic-release](https://github.com/semantic-release/semantic-release)
- Commit messages should follow the [Conventional Commits](https://www.conventionalcommits.org/) format:
  - `feat: add new feature` (triggers MINOR version bump)
  - `fix: resolve bug` (triggers PATCH version bump)
  - `feat!: breaking change` or `fix!: breaking change` (triggers MAJOR version bump)
  - `chore: update dependencies` (no version bump)
  - `docs: update documentation` (no version bump)
  - `style: format code` (no version bump)
  - `refactor: improve code structure` (no version bump)
  - `test: add tests` (no version bump)
  - `ci: update CI configuration` (no version bump)

The current version is stored in:
- `VERSION` file in the project root
- `version` field in `package.json`

## CI Workflow

The project includes a GitHub Actions workflow for continuous integration and deployment:

### Workflow Triggers

The CI workflow is triggered on:
- Pushes to the main branch
- Pull requests to the main branch

### CI Process

1. **Semantic Release**
   - Analyzes commit messages since the last release
   - Determines the next version number
   - Creates a new Git tag
   - Updates the CHANGELOG.md, VERSION file, and package.json
   - Creates a GitHub release

2. **Build and Test**
   - Builds the App Docker image
   - Uses Docker Buildx for efficient builds
   - Utilizes GitHub Actions cache for faster builds

3. **Docker Image Publishing**
   - Pushes images to GitHub Container Registry (ghcr.io)
   - Images are tagged with:
     - Branch name
     - PR number (for pull requests)
     - Semantic version (when tagged)
     - Short SHA

4. **Deployment**
   - Images are available at `ghcr.io/your-github-username/reliquary`
   - Manual deployment is required on the production server unless using Watchtower