# Contact Ticket System - API Reference

## Overview
This document provides detailed API documentation for the Contact Ticket System.

---

## Public API Endpoints

### Submit Contact Form Message

**Endpoint:** `POST /contact/send`

**Authentication:** None (Public)

**Request Headers:**
```
Content-Type: application/json
X-CSRF-TOKEN: [csrf-token]
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "subject": "Website Issue",
  "message": "I found a bug on the pricing page..."
}
```

**Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | Yes | Client's full name (max 255 chars) |
| email | string | Yes | Valid email address |
| phone | string | No | Phone number for contact |
| subject | string | Yes | Ticket subject (max 255 chars) |
| message | string | Yes | Message content (max 5000 chars) |

**Response - Success (200 OK):**
```json
{
  "success": true,
  "message": "Your message has been sent successfully!",
  "ticket_number": "TKT-000042"
}
```

**Response - Error (500 Internal Server Error):**
```json
{
  "success": false,
  "message": "Failed to send message. Please try again.",
  "error": "Exception message details"
}
```

**Response - Validation Error (422 Unprocessable Entity):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field must be a valid email."],
    "message": ["The message field is required."]
  }
}
```

**Example cURL Request:**
```bash
curl -X POST http://localhost/contact/send \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: token-here" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "subject": "Inquiry",
    "message": "Hello, I would like to inquire about..."
  }'
```

**Example JavaScript Request:**
```javascript
fetch('/contact/send', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
  },
  body: JSON.stringify({
    name: 'John Doe',
    email: 'john@example.com',
    phone: '+1234567890',
    subject: 'Inquiry',
    message: 'Hello...'
  })
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
```

---

## Admin API Endpoints

All admin endpoints require:
- Authentication: Logged in user with SuperAdmin role
- CSRF Token in request headers or body
- Session cookie

### List All Tickets

**Endpoint:** `GET /admin/contact-tickets`

**Authentication:** Required (SuperAdmin)

**Query Parameters:**
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| page | integer | 1 | Pagination page number |
| status | string | all | Filter by status: open, pending_reply, closed |

**Response (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "ticket_number": "TKT-000001",
      "user_email": "client@example.com",
      "user_name": "John Doe",
      "phone": "+1234567890",
      "subject": "Website Issue",
      "status": "open",
      "assigned_to": null,
      "last_reply_date": "2026-01-04T10:30:00Z",
      "auto_closed_at": null,
      "created_at": "2026-01-04T10:00:00Z",
      "updated_at": "2026-01-04T10:30:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 42,
    "last_page": 3
  }
}
```

**Example:**
```
GET /admin/contact-tickets?page=2&status=open
```

---

### View Single Ticket

**Endpoint:** `GET /admin/contact-tickets/{id}`

**Authentication:** Required (SuperAdmin)

**URL Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Ticket ID |

**Response (200 OK):**
```json
{
  "ticket": {
    "id": 1,
    "ticket_number": "TKT-000001",
    "user_email": "client@example.com",
    "user_name": "John Doe",
    "phone": "+1234567890",
    "subject": "Website Issue",
    "status": "open",
    "assigned_to": 5,
    "assigned_admin": {
      "id": 5,
      "fname": "Jane",
      "email": "jane@admin.com"
    },
    "last_reply_date": "2026-01-04T10:30:00Z",
    "auto_closed_at": null,
    "created_at": "2026-01-04T10:00:00Z",
    "updated_at": "2026-01-04T10:30:00Z"
  },
  "messages": [
    {
      "id": 1,
      "ticket_id": 1,
      "sender_type": "client",
      "sender_id": null,
      "message": "I found a bug on the pricing page...",
      "attachments": null,
      "created_at": "2026-01-04T10:00:00Z",
      "updated_at": "2026-01-04T10:00:00Z"
    },
    {
      "id": 2,
      "ticket_id": 1,
      "sender_type": "admin",
      "sender_id": 5,
      "message": "Thank you for reporting. We'll investigate...",
      "attachments": null,
      "created_at": "2026-01-04T10:30:00Z",
      "updated_at": "2026-01-04T10:30:00Z"
    }
  ]
}
```

---

### Assign Ticket to Self

**Endpoint:** `POST /admin/contact-tickets/{id}/assign`

**Authentication:** Required (SuperAdmin)

**URL Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Ticket ID |

**Request Headers:**
```
X-CSRF-TOKEN: [csrf-token]
```

**Response (302 Redirect):**
Redirects back with session message:
```json
{
  "success": "Ticket assigned to you successfully."
}
```

**Response - Already Closed (403 Forbidden):**
```json
{
  "message": "Cannot assign a closed ticket."
}
```

---

### Send Reply to Ticket

**Endpoint:** `POST /admin/contact-tickets/{id}/reply`

**Authentication:** Required (SuperAdmin)

**URL Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Ticket ID |

**Request Headers:**
```
Content-Type: application/json
X-CSRF-TOKEN: [csrf-token]
```

**Request Body:**
```json
{
  "message": "Thank you for your inquiry. We are looking into this issue..."
}
```

**Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| message | string | Yes | Reply message (max 5000 chars) |

**Response - Success (200 OK):**
```json
{
  "success": true,
  "message": "Reply sent successfully."
}
```

**Response - Error (403 Forbidden):**
```json
{
  "success": false,
  "message": "Cannot reply to a closed ticket.",
  "status": 403
}
```

**Response - Validation Error (422):**
```json
{
  "message": "The message field is required.",
  "errors": {
    "message": ["The message field is required."]
  }
}
```

**Example JavaScript:**
```javascript
fetch('/admin/contact-tickets/1/reply', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': csrfToken
  },
  body: JSON.stringify({
    message: 'Thank you for your inquiry...'
  })
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    console.log('Reply sent!');
    location.reload();
  }
});
```

---

### Update Ticket Status

**Endpoint:** `PUT /admin/contact-tickets/{id}/status`

**Authentication:** Required (SuperAdmin)

**URL Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Ticket ID |

**Request Headers:**
```
X-CSRF-TOKEN: [csrf-token]
Content-Type: application/x-www-form-urlencoded
```

**Request Body:**
```
status=closed
```

**Request Body (JSON):**
```json
{
  "status": "closed"
}
```

**Valid Statuses:**
- `open` - Initial status
- `pending_reply` - Waiting for client response
- `closed` - Resolved or closed

**Response (302 Redirect):**
```json
{
  "success": "Ticket status updated successfully."
}
```

---

### Close Ticket

**Endpoint:** `PUT /admin/contact-tickets/{id}/close`

**Authentication:** Required (SuperAdmin)

**URL Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Ticket ID |

**Request Headers:**
```
X-CSRF-TOKEN: [csrf-token]
```

**Response (302 Redirect):**
```json
{
  "success": "Ticket closed successfully."
}
```

---

## Error Responses

### 400 Bad Request
```json
{
  "message": "Bad request"
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 404 Not Found
```json
{
  "message": "The requested ticket was not found."
}
```

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### 500 Internal Server Error
```json
{
  "message": "Server error",
  "error": "Exception message"
}
```

---

## Rate Limiting

Currently, no rate limiting is implemented. Consider adding:

```php
// In routes/web.php
Route::post('/contact/send', [ContactController::class, 'store'])
     ->middleware('throttle:5,1'); // 5 per minute
```

---

## CSRF Protection

All POST, PUT, DELETE requests require a valid CSRF token:

**In Blade Templates:**
```blade
<input type="hidden" name="_token" value="{{ csrf_token() }}">
```

**In AJAX Requests:**
```javascript
headers: {
  'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
}
```

---

## Status Codes Reference

| Code | Meaning | Use Case |
|------|---------|----------|
| 200 | OK | Successful GET, POST, PUT request |
| 302 | Found (Redirect) | Successful form submission, redirect |
| 400 | Bad Request | Malformed request |
| 401 | Unauthorized | Authentication required |
| 403 | Forbidden | Not authorized for action |
| 404 | Not Found | Resource doesn't exist |
| 422 | Unprocessable Entity | Validation failed |
| 500 | Server Error | Application error |

---

## Webhook Events (Future Enhancement)

Potential webhook events to implement:
- `ticket.created` - New ticket submitted
- `ticket.replied` - Admin replied to ticket
- `ticket.closed` - Ticket closed
- `ticket.auto_closed` - Ticket auto-closed due to inactivity

---

## Rate Limiting Recommendations

Implement these throttle limits:
- Contact form submission: 5 per minute per IP
- Admin operations: 30 per minute per user
- Bulk operations: 10 per minute per user

---

## Authentication Methods

Currently supported:
- Session authentication (web)
- Sanctum API tokens (future)

To add API token authentication:
```php
Route::middleware('auth:sanctum')->group(function () {
    // Protected routes
});
```

---

**Last Updated:** January 4, 2026
**API Version:** 1.0
**Status:** Production Ready
