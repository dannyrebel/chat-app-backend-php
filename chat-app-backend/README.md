# Chat Backend API

Simple chat backend built with **PHP**, **Slim** and **Medoo (SQLite)**.  
Tracks users via **PHP sessions** and allows creating groups and joining them to send messages.
Unit tests with **PHPunit**.

---

## Project Structure

```
├─ src/
│ ├─ Database.php # Database connection
│ ├─ routes/
│ ├─ users.php # User creation route
│ ├─ groups.php # Group creation route
│ └─ group_join.php # Join group route
| └─ messages.php # Create & read messages
├─ tests/UserRouteTest.php
├─ public/index.php # root
├─ composer.json
├─ phpunit.xml - # PHPUnit tests config file
└─ storage/database.sqlite # SQLite database file
```

---

## API Endpoints

### 1. Create User

**Endpoint:**

POST http://localhost:8000/users

**Description:**

Creates a new user and stores their ID in the PHP session. No request body needed.

**Response:**

```json
{
  "success": true,
  "id": "user_63d1e2f8a7b9c",
  "token": 'YOUR_TOKEN'
}
```

---

### 2. Create Group

**Description**

Creates a new chat group

**Endpoint:**
POST http://localhost:8000/groups

Request body:

```json
{
  "name": "My Chat Group" // optional, defaults to "Unnamed group"
}
```

**Response**

```json
{
  "success": true,
  "id": 2,
  "name": "My Chat Group"
}
```

---

### 3. Join group

**Description**

Adds the current session user to the group with the given id from the session.

**Endpoint**

POST http://localhost:8000/groups/{id}/join

**Response**

```json
{
  "success": true,
  "group_id": 2,
  "user_id": "user_63d1e2f8a7b9c"
}
```

### 4. Send a message to group

**Description**

Sends a message to the group - if current user has joined that group.

**Endpoint**

POST http://localhost:8000/groups/{id}/messages

**Response**

```json
{
  "success": true,
  "group_id": "1",
  "user_id": "user_696ddf0bcd95f",
  "message": "Hello from the group!"
}
```

## Example test via web page

### Step 1: Launch server on localhost:8000

php -S localhost:8000 -t public

### Step 2: open the test-flow-jwt.html or click the link below

http://localhost:8000/test-flow-jwt.html

### Step 3: Click each button sequentally to test each step

## Example test in terminal

### Step 0: Initialize DB + Test.

- php scripts/create_chat_tables.php
- php scripts/check_tables_creation.php - returns names of all tables created

### Step 1: Create user and save JWT token

curl (curl.exe for windows) -X POST http://localhost:8000/users

### Step 2: Create a chat group

curl (curl.exe for Windows) -X POST http://localhost:8000/groups

### Step 3: Use that session to join a group

curl (curl.exe for Windows) -X POST http://localhost:8000/groups/1/join -H "Authorization: Bearer YOUR_TOKEN_HERE"

### Step 4: Send a message to the joined group

curl (curl.exe for windows) -X POST http://localhost:8000/groups/1/messages -H "Authorization: Bearer YOUR_TOKEN_HERE" -H "Content-Type: application/json" -d "{\"message\":\"I built this with Doctrine and JWT!\"}"

### Step 5: Fetch all messages from the joined group

curl (curl.exe for windows) http://localhost:8000/groups/1/messages

## Unit tests

Run **./vendor/bin/phpunit tests/UserRouteTest.php** in the terminal. The functionaly tests the **messages.php** POST & GET routes.
