#Synopsis
This is very simple JSON REST API for user registration and login. Used technologies: PHP, MySQL, Nette framework

#Code example
**Request**
```
POST /user

{
	"username":"some_username",
	"password":"some password",
	"password_confirm":"some password"
}
```

**Success response example**
```
Status: 200 OK
{
  "data": {
    "id": 5,
    "username": "some_username"
  }
}
```

**Error response example**
```
Status: 400 Bad Request
{
  "errors": [
    "E_DUPLICATE_ENTRY"
  ]
}
```

#Motivation
This project is purely for job application purposes. There is almost 100% chance, that this project will not be maintained in the future.

#Installation
1. Clone repository
2. Set write permissions for directories: /log, /temp
3. Run requirements checker to be sure your server meets Nette framework's requirements.
4. Create database structure. You can use prepared MySQL database revision placed at /contrib/rev/rev0001.sql

#API Reference

##POST /user

**Input fields**

username  
password  
password_confirm  

**List of error codes**

E_API_METHOD_NOT_SUPPORTED  
Request method is not supported. This endpoint supports the POST request method only.

E_USERNAME_EMPTY  
Missing or empty username.

E_PASSWORD_EMPTY  
E_PASSWORD_CONFIRM_EMPTY  
Missing or empty password or password confirmation.

E_PASSWORD_MISMATCH  
Password and password confirmation does not match.

E_USERNAME_EXISTS  
Username is already used by another account.

##POST /user/login

**Input fields**

username  
password

**List of error codes**

E_API_METHOD_NOT_SUPPORTED  
Request method is not supported. This endpoint supports the POST request method only.

E_USER_ACCOUNT_DOES_NOT_EXIST  
Account with given username does not exist.

E_AUTHENTICATION_FAILED  
Given password does not match with one from the account.
