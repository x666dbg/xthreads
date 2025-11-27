# XThreads API Documentation

Base URL: `http://localhost/api`

## Authentication

This API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header for protected routes.

```
Authorization: Bearer {your_token}
```

## Endpoints

### Authentication

#### Register

-   **POST** `/auth/register`
-   **Body:**

```json
{
    "username": "string (required, unique)",
    "email": "string (required, email, unique)",
    "password": "string (required, min:8)",
    "password_confirmation": "string (required)"
}
```

-   **Response:**

```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "username": "johndoe",
            "email": "john@example.com",
            "created_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|abc123..."
    }
}
```

#### Login

-   **POST** `/auth/login`
-   **Body:**

```json
{
    "email": "string (required, email)",
    "password": "string (required)"
}
```

-   **Response:** Same as register

#### Logout

-   **POST** `/auth/logout` (Protected)
-   **Response:**

```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

#### Get Current User

-   **GET** `/auth/me` (Protected)
-   **Response:**

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "username": "johndoe",
            "email": "john@example.com",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "followers_count": 10,
            "following_count": 5,
            "threads_count": 25
        }
    }
}
```

### Threads

#### Get Timeline

-   **GET** `/threads` (Protected)
-   **Response:**

```json
{
    "success": true,
    "data": {
        "timeline": [
            {
                "id": 1,
                "type": "thread",
                "content": "Hello world!",
                "image": "http://localhost/storage/images/image.jpg",
                "user": {
                    "id": 1,
                    "username": "johndoe"
                },
                "likes_count": 5,
                "reposts_count": 2,
                "replies_count": 3,
                "is_liked": false,
                "is_reposted": false,
                "created_at": "2024-01-01T00:00:00.000000Z"
            }
        ]
    }
}
```

#### Create Thread

-   **POST** `/threads` (Protected)
-   **Body (multipart/form-data):**

```
content: string (required, max:280)
image: file (optional, image, max:2MB)
parent_thread_id: integer (optional, for replies)
```

-   **Response:**

```json
{
    "success": true,
    "message": "Thread created successfully",
    "data": {
        "thread": {
            "id": 1,
            "content": "Hello world!",
            "image": "http://localhost/storage/images/image.jpg",
            "user": {
                "id": 1,
                "username": "johndoe"
            },
            "likes_count": 0,
            "reposts_count": 0,
            "replies_count": 0,
            "is_reply": false,
            "parent_thread_id": null,
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    }
}
```

#### Get Thread with Replies

-   **GET** `/threads/{id}` (Protected)
-   **Response:**

```json
{
    "success": true,
    "data": {
        "thread": {
            "id": 1,
            "content": "Hello world!",
            "image": "http://localhost/storage/images/image.jpg",
            "user": {
                "id": 1,
                "username": "johndoe"
            },
            "likes_count": 5,
            "reposts_count": 2,
            "replies_count": 3,
            "created_at": "2024-01-01T00:00:00.000000Z"
        },
        "replies": [
            {
                "id": 2,
                "content": "Nice thread!",
                "image": null,
                "user": {
                    "id": 2,
                    "username": "janedoe"
                },
                "likes_count": 1,
                "created_at": "2024-01-01T01:00:00.000000Z"
            }
        ]
    }
}
```

#### Delete Thread

-   **DELETE** `/threads/{id}` (Protected)
-   **Response:**

```json
{
    "success": true,
    "message": "Thread deleted successfully"
}
```

### Likes

#### Like Thread

-   **POST** `/threads/{id}/like` (Protected)
-   **Response:**

```json
{
    "success": true,
    "message": "Thread liked successfully",
    "data": {
        "thread_id": 1,
        "likes_count": 6,
        "is_liked": true
    }
}
```

#### Unlike Thread

-   **DELETE** `/threads/{id}/like` (Protected)
-   **Response:**

```json
{
    "success": true,
    "message": "Thread unliked successfully",
    "data": {
        "thread_id": 1,
        "likes_count": 5,
        "is_liked": false
    }
}
```

#### Toggle Like

-   **POST** `/threads/{id}/toggle-like` (Protected)
-   **Response:**

```json
{
    "success": true,
    "message": "Thread liked successfully",
    "data": {
        "thread_id": 1,
        "likes_count": 6,
        "is_liked": true,
        "action": "liked"
    }
}
```

#### Get Thread Likes

-   **GET** `/threads/{id}/likes` (Protected)
-   **Response:**

```json
{
    "success": true,
    "data": {
        "thread_id": 1,
        "likes_count": 5,
        "users": [
            {
                "id": 2,
                "username": "janedoe",
                "liked_at": "2024-01-01T01:00:00.000000Z"
            }
        ]
    }
}
```

### Reposts

#### Repost Thread

-   **POST** `/threads/{id}/repost` (Protected)
-   **Response:**

```json
{
    "success": true,
    "message": "Thread reposted successfully",
    "data": {
        "thread_id": 1,
        "reposts_count": 3,
        "is_reposted": true
    }
}
```

#### Remove Repost

-   **DELETE** `/threads/{id}/repost` (Protected)
-   **Response:**

```json
{
    "success": true,
    "message": "Repost removed successfully",
    "data": {
        "thread_id": 1,
        "reposts_count": 2,
        "is_reposted": false
    }
}
```

#### Toggle Repost

-   **POST** `/threads/{id}/toggle-repost` (Protected)
-   **Response:**

````

### Users

#### Get User Profile
- **GET** `/users/{id}` (Protected)
- **Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "username": "johndoe",
            "email": "john@example.com",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "followers_count": 10,
            "following_count": 5,
            "threads_count": 25
        },
        "timeline": [
            {
                "id": 1,
                "type": "thread",
                "content": "Hello world!",
                "image": "http://localhost/storage/images/image.jpg",
                "likes_count": 5,
                "reposts_count": 2,
                "replies_count": 3,
                "created_at": "2024-01-01T00:00:00.000000Z"
            }
        ]
    }
}
````

#### Follow User

-   **POST** `/users/{id}/follow` (Protected)
-   **Response:**

```json
{
    "success": true,
    "message": "Successfully followed @johndoe",
    "data": {
        "user_id": 1,
        "username": "johndoe",
        "is_following": true,
        "followers_count": 11
    }
}
```

#### Unfollow User

-   **DELETE** `/users/{id}/follow` (Protected)
-   **Response:**

```json
{
    "success": true,
    "message": "Successfully unfollowed @johndoe",
    "data": {
        "user_id": 1,
        "username": "johndoe",
        "is_following": false,
        "followers_count": 10
    }
}
```

#### Toggle Follow

-   **POST** `/users/{id}/toggle-follow` (Protected)
-   **Response:**

```json
{
    "success": true,
    "message": "Successfully followed @johndoe",
    "data": {
        "user_id": 1,
        "username": "johndoe",
        "is_following": true,
        "followers_count": 11,
        "action": "followed"
    }
}
```

#### Get User Followers

-   **GET** `/users/{id}/followers` (Protected)
-   **Response:**

```json
{
    "success": true,
    "data": {
        "user_id": 1,
        "username": "johndoe",
        "followers_count": 10,
        "followers": [
            {
                "id": 2,
                "username": "janedoe",
                "followed_at": "2024-01-01T01:00:00.000000Z"
            }
        ]
    }
}
```

#### Get User Following

-   **GET** `/users/{id}/following` (Protected)
-   **Response:**

```json
{
    "success": true,
    "data": {
        "user_id": 1,
        "username": "johndoe",
        "following_count": 5,
        "following": [
            {
                "id": 3,
                "username": "bobsmith",
                "followed_at": "2024-01-01T02:00:00.000000Z"
            }
        ]
    }
}
```

#### Search Users

-   **GET** `/users/search?q={query}` (Protected)
-   **Response:**

```json
{
    "success": true,
    "data": {
        "query": "john",
        "users": [
            {
                "id": 1,
                "username": "johndoe",
                "email": "john@example.com",
                "followers_count": 10,
                "following_count": 5,
                "threads_count": 25
            }
        ]
    }
}
```

## Error Responses

All endpoints may return error responses in the following format:

```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

Common HTTP status codes:

-   `200` - Success
-   `201` - Created
-   `400` - Bad Request
-   `401` - Unauthorized
-   `403` - Forbidden
-   `404` - Not Found
-   `422` - Validation Error
-   `500` - Server Error

## Rate Limiting

API requests are limited to 60 requests per minute per user/IP address.

## Notes

1. All timestamps are in ISO 8601 format (UTC)
2. Image uploads are resized to max 800x600 pixels
3. Thread content is limited to 280 characters
4. Users cannot like/repost their own threads
5. Users cannot follow themselves
   "thread_id": 1,
   "reposts_count": 3,
   "is_reposted": true,
   "action": "reposted"
   }
   }

````

#### Get Thread Reposts
- **GET** `/threads/{id}/reposts` (Protected)
- **Response:**
```json
{
    "success": true,
    "data": {
        "thread_id": 1,
        "reposts_count": 2,
        "users": [
            {
                "id": 3,
                "username": "bobsmith",
                "reposted_at": "2024-01-01T02:00:00.000000Z"
            }
        ]
    }
}
````
