#!/bin/python

"""
AUTHOR:  KBeDeveloper (https://github.com/KBeDeveloper), (https://gitlab.com/KBeDeveloper)
DATE:    October 12, 2019
LICENSE: MIT

THIS SCRIPT REQUIRES GIT INSTALLED
"""

import subprocess
import json
from ruamel.yaml import YAML

try:

    repoRoot = 'git rev-parse --show-toplevel' # EQUALS TO `pwd` COMMAND

    process = subprocess.Popen(repoRoot.split(), stdout=subprocess.PIPE)
    output, error = process.communicate()

    if error :
        raise ModuleNotFoundError("Git is not installed")

    git_root = str(output)[2:][:-3]

    try:

        file = open(git_root+'/meta/dict/dict.yaml')

        if file is None or len(str(file)) is 0 :
            raise Exception("Error getting YAML file")

        try:

            yaml = YAML(typ='safe')
            
            with file as yml_file :
                data = yaml.load(yml_file)

            with open(git_root+'/meta/dict/dict.json', 'w') as outfile :
                json.dump(data, outfile, indent=4)

        except Exception as error:
            print("Error dumping items. Error: ", error)

    except Exception as error :
        print(error)

except ModuleNotFoundError as error :
    print(error)
