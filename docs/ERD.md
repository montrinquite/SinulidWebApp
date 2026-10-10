# Entity Relationship Diagram — Sinulid

```mermaid
erDiagram
    USERS ||--o{ POSTS    : writes
    USERS ||--o{ COMMENTS : writes
    USERS ||--o{ LIKES    : gives
    POSTS ||--o{ COMMENTS : has
    POSTS ||--o{ LIKES    : receives

    USERS {
        int id PK
        varchar username UK
        varchar email UK
        varchar password "bcrypt hash"
        varchar full_name
        varchar bio
        varchar profile_image
        timestamp created_at
        timestamp updated_at
    }
    POSTS {
        int id PK
        int user_id FK
        text content
        varchar image
        timestamp created_at
        timestamp updated_at
    }
    COMMENTS {
        int id PK
        int post_id FK
        int user_id FK
        varchar content
        timestamp created_at
        timestamp updated_at
    }
    LIKES {
        int id PK
        int post_id FK
        int user_id FK
        timestamp created_at
    }
```

## Relationships
- One user has many posts, comments and likes. One post has many comments and likes.
- All foreign keys use `ON DELETE CASCADE`: deleting a user removes their content; deleting a post removes its comments and likes.
- `likes` has `UNIQUE (post_id, user_id)`, so one user can like a post only once.
- `users.username` and `users.email` are unique.

## Table notes
| Table | Required by instructor | Extra columns (kept from the original schema) |
|---|---|---|
| users | id, username, password, full_name, bio, profile_image, created_at | email, updated_at |
| posts | id, user_id, content, image, created_at | updated_at (used to show "edited") |
| comments | id, post_id, user_id, content, created_at | updated_at (used to show "edited") |
| likes | id, post_id, user_id | created_at |
