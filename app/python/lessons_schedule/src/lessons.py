from src.settings import settings


class Lesson:

    def __init__(self, virtual_table):
        self.virtual_table = virtual_table
        self.__data = self.__handleTable()

    def __handleTable(self):
        classname_row = settings['single_lesson']['name'][0]
        teacher_row = settings['single_lesson']['teacher'][0]

        rows_bool = list(map(lambda row: row[0] or row[1], self.virtual_table))
        filled_rows_count = len(list(filter(None, rows_bool)))

        if filled_rows_count == 0:
            return None


        if filled_rows_count == 2 and rows_bool[classname_row] and rows_bool[teacher_row]:
            data = self.__processSingleLesson()
        else:
            data = self.__processTwinLesson()
        return data

    def __processSingleLesson(self):
        even = settings['even']
        odd = settings['odd']
        data = {even: {}, odd: {}}
        lesson_params = settings['single_lesson']
        for key, offsets in lesson_params.items():
            data[even][key] = str(self.virtual_table[offsets[0]][offsets[1]]).strip()
            data[odd][key] = str(self.virtual_table[offsets[0]][offsets[1]]).strip()
        return data

    def __processTwinLesson(self):
        even = settings['even']
        odd = settings['odd']
        data = {even: {}, odd: {}}
        lessons_params = settings['twin_lesson']
        for key, twin_offsets in lessons_params.items():
            for is_bottom, offsets in enumerate(twin_offsets):
                data[even if is_bottom else odd][key] = str(self.virtual_table[offsets[0]][offsets[1]]).strip()
        # del empty data
        for key, val in data[odd].items():
            if val:
                break
        else:
            data[odd] = {}

        for key, val in data[even].items():
            if val:
                break
        else:
            data[even] = {}
        return data



    def getInfo(self):
        return self.__data