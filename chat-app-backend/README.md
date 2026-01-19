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
├─ public/index.php # root
├─ composer.json
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
  "id": "user_63d1e2f8a7b9c"
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

#### Example test in terminal

## Step 0: Initialize DB + Test.

- php scripts/create_chat_tables.php
- php scripts/check_tables_creation.php - returns names of all tables created

## Step 1: Create user and save session

curl (curl.exe for Windows) -i -c cookies.txt -X POST http://localhost:8000/users

## Step 2: Create a chat group

curl (curl.exe for Windows) -X POST http://localhost:8000/groups

## Step 2: Use that session to join a group

curl (curl.exe for Windows) -i -b cookies.txt -X POST http://localhost:8000/groups/1/join

## Step 3: Send a message to the joined group

curl (curl.exe for Windows) -i -b cookies.txt -X POST http://localhost:8000/groups/1/messages -H "Content-Type: application/json" -d "@message.json"

I have created a message.json file due to Powershell issues with testing - you can edit the message there as you please.

## Step 4: Fetch all messages from the joined group

curl (curl.exe for windows) -i -b cookies.txt -X GET http://localhost:8000/groups/1/messages

## Unit tests

Run **./vendor/bin/phpunit tests/UserRouteTest.php** in the terminal. The functionaly tests the **messages.php** POST & GET routes.
