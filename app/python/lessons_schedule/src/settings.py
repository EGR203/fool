settings = {
    'even': '0',
    'odd': '1',
    "group_number": {
        "row": 11,
        "start_col": 2,
        "stop_col": -3
    },
    "date": {
        'days': {
            'col': 0,
            'rows': [14 + x * 4 * 7 for x in range(0, 6)]
        },
        'times': {
            'col': 1,
            #for day
            'rows': [x * 4 for x in range(0, 7)]
        },
    },
    "single_lesson": {
        #      ROW, COL
        'name': (0, 0),
        'teacher': (2, 0),
        'classroom': (2, 1),
        'type': (0, 1)
    },
    "twin_lesson": {
        'name': [(0, 0), (2, 0)],
        'teacher': [(1, 0), (3, 0)],
        'classroom': [(1, 1), (3, 1)],
        'type': [(0, 1), (2, 1)]
    },
    "lesson_number_to_time": [
        "08:30 - 10:00",
        "10:10 - 11:40",
        "11:50 - 13:20",
        "13:30 - 15:00",
        "15:10 - 16:40",
        "16:50 - 18:20",
        "18:30 - 20:00"
    ]
}
