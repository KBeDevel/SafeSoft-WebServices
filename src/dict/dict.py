#! /usr/bin/env python3
#! c:/Python/ python

# AUTHOR:  KBeDeveloper (https://github.com/KBeDeveloper), (https://gitlab.com/KBeDeveloper)
# DATE:    September 10, 2019
# LICENSE: MIT

# Estructure: OBJ_TYPE => COLUMN/PROP_NAME => COLUMN/PROP_TYPE/[POSSIBLE_VALUES]
#
# CROSS LANGUAGE TYPES:
# 
# None    = null
# str     = string
# int     = integer
# float   = float
# Decimal = double
#

import datetime

_main = {
    'user_dict' : {
        'name' : {
            str
        },
        'rut' : {
            str
        },
        'role' : {
            str
        },
        'type' : { # According to the user type, it will have a type code
            'employee' : 0,
            'corp' : 1,
            'medic' : 2,
            'supervisor' : 3,
            'tecnical_speciaist' : 4,
            'admin' : 5,
            'engineer' : 6
        },
        'email' : {
            str
        },
        'pass' : {
            str # SHA256 or SHA512, it will affect the string length (Recommended: SHA256)
        },
        'user_code' : { # A random string with length 10
            'EM-XXXXX',
            'CO-XXXXX',
            'ME-XXXXX',
            'SV-XXXXX',
            'TS-XXXXX',
            'AD-XXXXX',
            'EG-XXXXX'
        },
        'parent_entity' : { # Only if it is an employee
            None,
            'user_code'
        },
        'created_at' : {
            datetime.timestamp
        },
        'updated_at' : {
            datetime.timestamp
        },
        'lastlogin' : {
            datetime.timestamp
        }
    }
}