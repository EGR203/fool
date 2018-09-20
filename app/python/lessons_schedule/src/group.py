from src.settings import settings
from src.lessons import Lesson
import datetime


class Group:

    def __init__(self, name, _range, sheet):
        self.name = name
        self.sheet = sheet
        self._range = _range
        self.__info = self.__handleTable()

    def __handleTable(self):
        lessons = {}
        lesson_number_to_time = []
        for day, day_offset in enumerate(settings['date']['days']['rows']):
            lessons[day] = {}
            for lesson_number, lesson_offset in enumerate(settings['date']['times']['rows']):
                if len(lesson_number_to_time) < 8:
                    lesson_number_to_time.append(self.__getLessonTimeByRow(day_offset + lesson_offset))
                cols_range = (self._range[0], self._range[0] + 2)
                rows_range = (day_offset + lesson_offset, day_offset + lesson_offset + 4)
                virtual_table = self.__makeVirtualTable(rows_range=rows_range, cols_range=cols_range)
                lesson = Lesson(virtual_table=virtual_table)
                lesson_info = lesson.getInfo()
                if lesson_info:
                    lessons[day][lesson_number] = lesson_info
        self.lesson_number_to_time = lesson_number_to_time
        return lessons

    def __makeVirtualTable(self, rows_range, cols_range):
        virtual_table = [self.sheet.row_values(row, cols_range[0], cols_range[1]) for row in range(*rows_range)]
        return virtual_table

    def __getLessonTimeByRow(self, row):
        col = settings['date']['times']['col']
        val = self.sheet.row_values(row, col, col + 1)[0]
        time = str(val).strip()
        return time

    def getInfo(self):
        return self.__info

