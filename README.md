# 📦 StepSync Inventory API
A lightweight RESTful API for managing product records, developed for the BSCS Midterm Requirement.

## 🛠 Features & Tech Stack
* *Language:* PHP 8.x
* *Data Format:* JSON
* *Architecture:* REST
* *Pattern:* Single Entry Point with .htaccess

## 🚀 API Endpoints Documentation

| HTTP Method | Route | Operation |
| :--- | :--- | :--- |
| GET | /products | Retrieve all inventory items |
| GET | /products/{id} | Search specific item by ID |
| POST | /products | Add a new item to inventory |
| PUT | /products/{id} | Update existing product details |
| DELETE | /products/{id} | Remove item from records |

## 📄 Response Structure (Example)
```json
{
  "status": "success",
  "item": {
    "product_id": 101,
    "name": "Mechanical Keyboard",
    "price": 3500.00
  }
}
Write to Cresa
