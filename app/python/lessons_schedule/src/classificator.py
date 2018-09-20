import xlrd
from src.group import Group
from src.settings import settings



class Classificator:

    def __init__(self, workbook_path):
        workbook = xlrd.open_workbook(workbook_path)
        self.workbook = workbook
        self.__info = self.__handleTable()

    def getInfo(self):
        return self.__info

    def __handleTable(self):
        workbook = self.workbook
        groups = {}
        for sheet_name in workbook.sheet_names():
            sheet = workbook.sheet_by_name(sheet_name)
            group_row = sheet.row_values(
                settings['group_number']['row'],
                settings['group_number']['start_col'],
                settings['group_number']['stop_col']
            )
            for i, group_name in enumerate(group_row):
                if not group_name:
                    continue
                if not group_name in groups.keys():
                    groups[group_name] = {}
                col_delta = settings['group_number']['start_col']
                for j, next_group in enumerate(group_row[i+1:]):
                    if not next_group:
                        continue
                    rng = (i + col_delta, j + col_delta + 1)
                    break
                else:
                    rng = (i + col_delta, len(group_row) + col_delta)

                for index in range(0, int((rng[1] - rng[0]) / 2)):
                    start_rng = rng[0] + 2 * index
                    g = Group(name=group_name, _range=(start_rng, start_rng + 2), sheet=sheet)
                    groups[group_name][index] = g.getInfo()
        return groups
