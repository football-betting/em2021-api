# JSON API

## Send Tip

URL: `/api/tip/send`

Method: `POST`

##### Header:

```
Authorization: <Token>
```

example:

```
Authorization: Bearer ab0dde18155a43ee83edba4a4542b973
```

##### Request:

```
{
  "matchId": <matchId>,
  "tipDatetime": <TimeNow># deprecate,
  "tipTeam1": <score>,
  "tipTeam2": <score>
}
```

example:

```json
{
  "matchId": "2021-06-15:2100:DE-FR",
  "tipDatetime": "2020-05-30 14:36",
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

### User all tips

The URL displays all the games and the tips of what the user has typed. The results are for logged-in users

Uri: `/api/user_tip/all`
Method: `GET`

##### Header:

```
Authorization: <Token>
```

example:

```
Authorization: Bearer ab0dde18155a43ee83edba4a4542b973
```

##### Response:

```
{
  "success": true,
  "data": {
    "name": <string:userName>,
    "position": <int:position_in_tabell>,
    "scoreSum": <int:score>,
    "tips": [
      ...
      ...
      ...
      {
        "matchId": <string:matchId>,
        "matchDatetime": <string:matchDateTime>,
        "tipTeam1": <int:user_tip_for_tean1:null_is_allowed>,
        "tipTeam2": <int:user_tip_for_tean2:null_is_allowed>,
        "scoreTeam1": <int:match_scoreTeam1:null_is_allowed>,
        "scoreTeam2": <int:match_scoreTeam1:null_is_allowed>,
        "team1": "FR",
        "team2": "DE",
        "score": <int:tip_score_for_user:null_is_allowed>
      }
    ]
  }
}
```

example

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
        "matchId": "2021-06-04:1010:IT-SP",
        "matchDatetime": "2021-06-04 10:10",
        "tipTeam1": 1,
        "tipTeam2": 0,
        "scoreTeam1": 1,
        "scoreTeam2": 4,
        "team1": "IT",
        "team2": "SP",
        "score": 0
      },
      {
        "matchId": "2021-06-04:1011:PR-AU",
        "matchDatetime": "2021-06-04 10:11",
        "team1": "PR",
        "team2": "AU",
        "scoreTeam1": null,
        "scoreTeam2": null,
        "tipTeam1": null,
        "tipTeam2": null,
        "score": null
      },
      {
        "matchId": "2021-06-04:1012:CZ-NL",
        "matchDatetime": "2021-06-04 10:12",
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





