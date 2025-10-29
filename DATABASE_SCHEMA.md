## Database Schema

### ERD
```mermaid
erDiagram
  USERS ||--o{ PAGES : has
  USERS ||--o{ PAGE_VERSIONS : creates
  PAGES ||--o{ PAGE_VERSIONS : snapshots
  PAGES ||--o{ PAGE_VIEWS : receives
  THEMES ||--o{ PAGES : styles
  THEMES ||--o{ PAGE_VERSIONS : styles

  USERS {
    bigint id PK
    string name
    string email UNIQUE
    string slug UNIQUE
    enum role("client","admin")
    boolean is_active
    string password
    timestamps
  }

  THEMES {
    bigint id PK
    string name
    string slug UNIQUE
    string primary_color
    string secondary_color
    string background_color
    string text_color
    string accent_color
    string preview_image NULL
    timestamps
  }

  PAGES {
    bigint id PK
    bigint user_id FK -> USERS.id
    string slug UNIQUE
    string title
    json content
    bigint theme_id NULL FK -> THEMES.id
    string logo NULL
    string hero_image NULL
    boolean is_published DEFAULT false
    timestamp published_at NULL
    timestamps
  }

  PAGE_VERSIONS {
    bigint id PK
    bigint page_id FK -> PAGES.id
    bigint user_id FK -> USERS.id
    json content
    bigint theme_id NULL FK -> THEMES.id
    string change_description NULL
    timestamps
  }

  PAGE_VIEWS {
    bigint id PK
    bigint page_id FK -> PAGES.id
    string ip_address
    text user_agent NULL
    string session_id
    boolean is_unique_visitor DEFAULT true
    timestamp visited_at
    timestamps
    INDEX(page_id, visited_at)
    INDEX(session_id)
  }
```

### Tables
- **users**: Authentication and role (`client`/`admin`). A user owns exactly one page (enforced in code via `User::page()`), not by constraints.
- **themes**: Predefined themes with color palette and optional preview image.
- **pages**: The editable, publishable single page for a user. `content` is a JSON map of section fields (hero, about, services, contact, etc.).
- **page_versions**: Version snapshots created before updates that change `content` or `theme_id`.
- **page_views**: Visit records for analytics. Unique visitors inferred by (page_id, ip, 30-day window) in service logic.

### Notable indexes/constraints
- Unique: `users.email`, `users.slug`, `pages.slug`, `themes.slug`.
- Foreign keys with cascades: deleting a user deletes their page and versions; deleting a page deletes its versions and views; deleting a theme sets theme_id to NULL on dependent rows.


