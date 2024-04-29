# fpPathfinder REST API
This API provides the ability for third-party applications to interact with fpPathfinder's resources and users.

## Authentication
This API uses the OAuth 2.0 Authorization Code grant type.

**OAuth Endpoints**
| Endpoint | Path |
| -------- | ---- |
| Authorization endpoint | `/oauth/authorize` |
| Token endpoint | `/oauth/token` |

**Available OAuth Scopes**
| Scope | Description |
| ----- | ----------- |
| `read_checklists` | Ability to read available checklists for that user. |

To get your OAuth Client Credentials contact the site administrator.

## Endpoints



### Checklists `/wp-json/api/v1/checklists`
Get all or a subset of available checklists.

**HTTP Method:** `GET`
**Authentication:** Bearer Token Header
**OAuth Scope Required:** `read_checklists` 

**Optional Query Parameters:**
| Query Param | Description                                                 |
| ----------- | ----------------------------------------------------------- |
| `category`  | Limit results to a specific category.                       |
| `search`    | A search keyword. Can be a phrase, word, or part of a word. |

**Response:**
A JSON object with property 'checklists' that contains an array of checklists. Each checklist will have 3 properties:

| Property | Value |
| -------- | ----- |
| `"name"` | The name of the checklist |
| `"categories"` | Either `false` (if no categories), or an `array` of categories each with "name" and "slug" properties. The "slug" property is the string that can be specified in the query parameter `category`. |
| `"url"` | The full url for that checklist. |
If no results found the 'checklists' property will be an empty array.

**Example Response:**
```
{
    "checklists": [
        {
            "name": "Issues To Consider In A Client Annual Review Meeting (2020)",
            "categories": false,
            "url": "https://sfpath.projects.cgd.io/checklist/issues-to-consider-in-a-client-annual-review-meeting-2020/"
        },
        {
            "name": "As Someone Who Is Working, What Issues Should I Consider When Reviewing My 2019 Tax Return?",
            "categories": [
                {
                    "name": "Roth IRA",
                    "slug": "roth-ira"
                }
            ],
            "url": "https://sfpath.projects.cgd.io/checklist/as-someone-who-is-working-what-issues-should-i-consider-when-reviewing-my-2019-tax-return/"
        }
    ]
}
```

### Checklist Categories `/wp-json/api/v1/checklist-categories`
Get all checklist categories.

**HTTP Method:** `GET`
**Authentication:** Bearer Token Header
**OAuth Scope Required:** `read_checklists` 

**Response:**
A JSON object with property 'checklist-categories' that contains an array of checklist-category objects. Each category will have 2 properties:

| Property | Value |
| -------- | ----- |
| `"name"` | The name of the checklist category. |
| `"slug"` | The slug of the checklist category. This can be used in other endpoints. |

**Example Response:**
```
{
    "checklist-categories": [
        {
            "name": "Roth IRA",
            "slug": "roth-ira",
        },
    ]
}
```