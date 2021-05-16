# Transfer funds

## Setup

 - cd into transfer_funds folder
 - run terminal command sh start.sh
 - project is up and running on http://localhost:80

In case 80 port is already located in your local machine cd to docker folder open .env and change WEBSERVER_PORT value.

### In case test data are needed:

 - run terminal command docker exec -it app bash
 - php artisan db:seed
 - this will generate test data. 


## Endpoints 
    
### /client
**Description:**\
Create new client.

**Methods:** POST\
**Body:** name, surname, country.\
**Returns:** 201 status code on success.

### /client/{clientId}
**Description:**\
Get client by client id.

**Methods:** GET\
**Parameters:** clientId\
**Returns:** Status code 200 
```
{
    "id": 44,
    "info": {
        "name": "Telly Thiel III",
        "surname": "Dietrich",
        "country": "Svalbard & Jan Mayen Islands"
    },
    "accounts": []
}
```

### /account

**Description:**\
Create new account for client.

**Methods:** POST\
**Body:** client(client_id), currency, balance.\
**Returns:** 201 status code on success.

### /account/{accountId}
**Description:**\
Get account by account id.

**Methods:** GET\
**Parameters:** accountId\
**Returns:** Status code 200
```
{
    "id": 22,
    "client": 76,
    "currency": "TJS",
    "balance": 94125.35
}
```

### /account/{accountId}/history

**Description:**\
Get account transfer history. Optional accept query parameters limit and offest

**Methods:** GET\
**Body:** accountId, offset(optional), limit(optional).\
**Returns:** 202 status code on success.
```
{
    "id": 44,
    "offset": 0,
    "limit": 0,
    "total": 1,
    "history": [
        {
            "id": 5,
            "datetime": "2021-05-16T13:21:58.000000Z",
            "sender": 44,
            "receiver": 45,
            "amount": 278.2,
            "currency": "MUR"
        }
    ]
}
```

### /transfer

**Description:**\
Create new transfer for clients account.

**Methods:** POST\
**Body:** sender(account_id), receiver(account_id), amount, currency.\
**Returns:** 201 status code on success.