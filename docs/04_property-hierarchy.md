
# docs/property-hierarchy.md

**Hierarchy:** Property → Block → Zone → Slot

### `properties`

| column     | type    | notes                       |
| ---------- | ------- | --------------------------- |
| id         | uuid    |                             |
| name       | string  | marina/yard name            |
| timezone   | string  | IANA, e.g., `Asia/Maldives` |
| currency   | string  | ISO 4217                    |
| address    | string  |                             |
| is\_active | boolean |                             |

### `blocks`

| column       | type    | notes                  |
| ------------ | ------- | ---------------------- |
| id           | uuid    |                        |
| property\_id | fk      |                        |
| name         | string  | quay/pier/yard section |
| is\_active   | boolean |                        |

### `zones`

| column     | type    | notes                 |
| ---------- | ------- | --------------------- |
| id         | uuid    |                       |
| block\_id  | fk      |                       |
| name       | string  | sub‑area within block |
| notes      | text    | optional              |
| is\_active | boolean |                       |

### `slots`

| column        | type         | notes              |
| ------------- | ------------ | ------------------ |
| id            | uuid         |                    |
| zone\_id      | fk           |                    |
| code          | string       | unique within zone |
| max\_loa\_m   | decimal(6,2) |                    |
| max\_beam\_m  | decimal(5,2) |                    |
| max\_draft\_m | decimal(4,2) |                    |
| shore\_power  | boolean      |                    |
| is\_active    | boolean      |                    |

---