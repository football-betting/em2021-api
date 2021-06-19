# JSON API

## JSON Web Token

> For all urls which start with `/api` you have to send the Bearer-Token

Header:

```
Authorization: <Token>
```

example:

```
Authorization: Bearer ab0dde18155a43ee83edba4a4542b973
```

## Register

URL: `/auth/register`

Method: `POST`

##### Request:

```
{
  "username" : <username>,
  "email" : <email>,
  "password" : <password>
}
```

example:

```json
{
  "username": "DarkNinja",
  "email": "ninja@secret.com",
  "password": "ninjaIsTheBest"
}
```

##### Response:

```json
{
  "success": true,
  "data": {
    "id": 1,
    "username": "DarkNinja"
  }
}
```

Test: _tests/Api/Component/User/Infrastructure/AuthControllerTest.php_

## Login

URL: `/auth/login`

Method: `POST`

##### Request:

```
{
  "email" : <email>,
  "password" : <password>
}
```

example:

```json
{
  "email": "ninja@secret.com",
  "password": "ninjaIsTheBest"
}
```

##### Response:

```json
{
  "success": true,
  "token": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjJ9.2MZDCZHdhlnAB1iF_c_uTR_XV3ylguykSviVfmCTWzM"
}
```

Test: _tests/Api/Component/User/Infrastructure/AuthControllerTest.php_

## User Info

URL: `/api/user/info`

Method: `GET`

##### Response:

```json
{
  "success": true,
  "data": {
    "id": 1,
    "username": "ninja",
    "email": "ninja@em2021.com"
  }
}
```

Test: _tests/Api/Component/User/Infrastructure/UserControllerTest.php_

## Send Tip

URL: `/api/tip/send`

Method: `POST`

##### Request:

```
{
  "matchId": <matchId>,
  "tipTeam1": <score>,
  "tipTeam2": <score>
}
```

example:

```json
{
  "matchId": "2021-06-15:2100:DE-FR",
  "tipTeam1": 2,
  "tipTeam2": 3
}
```

##### Response:

```json
{
  "success": true
}
```

Test: _tests/Api/Component/Tip/Infrastructure/TipControllerTest.php_

## Users Tips

### User all past tips

Uri: `/api/user_tip/past/{username}`
Method: `GET`

##### Response:

```json
{
  "success": true,
  "data": {
    "name": "ninja",
    "position": 1,
    "scoreSum": 24,
    "tips": [
      {
        "matchId": "2000-06-16:2100:FR-DE",
        "matchDatetime": "2000-06-16 21:00",
        "tipTeam1": 2,
        "tipTeam2": 3,
        "scoreTeam1": 1,
        "scoreTeam2": 4,
        "team1": "FR",
        "team2": "DE",
        "score": 2
      },
      {
        "matchId": "2021-06-04:2254:IT-SP",
        "matchDatetime": "2021-06-04 22:54",
        "tipTeam1": 1,
        "tipTeam2": 0,
        "scoreTeam1": 1,
        "scoreTeam2": 4,
        "team1": "IT",
        "team2": "SP",
        "score": 0
      },
      {
        "matchId": "2021-06-04:2255:PR-AU",
        "matchDatetime": "2021-06-04 22:55",
        "team1": "PR",
        "team2": "AU",
        "scoreTeam1": null,
        "scoreTeam2": null,
        "tipTeam1": null,
        "tipTeam2": null,
        "score": null
      }
    ]
  }
}
```

Test: _tests/Acceptance/Component/UserTips/Infrastructure/UserTipsControllerTest.php_



### User all future tips

Uri: `/api/user_tip/future`
Method: `GET`

##### Response:

```json
{
  "success": true,
  "data": {
    "name": "ninja",
    "position": 1,
    "scoreSum": 24,
    "scoreWinTeam": 1,
    "scoreExact": 1,
    "tips": [
      {
        "matchId": "2021-06-04:2258:CZ-NL",
        "matchDatetime": "2021-06-04 22:58",
        "tipTeam1": 4,
        "tipTeam2": 5,
        "team1": "CZ",
        "team2": "NL",
        "scoreTeam1": null,
        "scoreTeam2": null,
        "score": null
      },
      {
        "matchId": "2999-06-20:1800:RU-EN",
        "matchDatetime": "2999-06-20 18:00",
        "tipTeam1": 4,
        "tipTeam2": 2,
        "team1": "RU",
        "team2": "EN",
        "scoreTeam1": null,
        "scoreTeam2": null,
        "score": null
      }
    ]
  }
}
```

Test: _tests/Acceptance/Component/UserTips/Infrastructure/UserTipsControllerTest.php_


### Users Rating

Uri: `/api/rating`
Method: `GET`


##### Response:

For 3 users in system

```json
{
  "success": true,
  "data": {
    "users": [
      {
        "name": "ninja",
        "position": 1,
        "scoreSum": 24
      },
      {
        "name": "rockstar",
        "position": 2,
        "scoreSum": 21
      },
      {
        "name": "motherSoccer",
        "position": 3,
        "scoreSum": 15
      }
    ]
  }
}
```
