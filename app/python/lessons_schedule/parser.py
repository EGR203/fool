import sys
import os
import inspect

def get_script_dir(follow_symlinks=True):
    if getattr(sys, 'frozen', False): # py2exe, PyInstaller, cx_Freeze
        path = os.path.abspath(sys.executable)
    else:
        path = inspect.getabsfile(get_script_dir)
    if follow_symlinks:
        path = os.path.realpath(path)
    return os.path.dirname(path)

sys.path.insert(0,".")
#activate_this = get_script_dir() + '/schedule_parser/bin/activate_this.py'
#exec(open(activate_this).read(), dict(__file__=activate_this))

import json
from src.classificator import Classificator

table = sys.argv[1]
c = Classificator(table)
groups = c.getInfo()

json_e = json.JSONEncoder()
print(json_e.encode(groups))
