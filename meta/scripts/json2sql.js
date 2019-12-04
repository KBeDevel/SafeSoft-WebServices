#!/bin/node

const json = require('../dict/dict.json');
const fs = require('fs');

const db = {};

db.name = "ssdb";

let _SQL = "";
let def_header = "";

let sql_stmt = {};

sql_stmt.if_exists = "IF EXISTS";
sql_stmt.if_not_exists = "IF NOT EXISTS";
sql_stmt.database = "DATABASE";
sql_stmt.collation = "CHARACTER SET utf8 COLLATE utf8_general_ci";
sql_stmt.use = "USE";
sql_stmt.drop = "DROP";
sql_stmt.create = "CREATE";
sql_stmt.table = "TABLE";
sql_stmt.primary_key = "PRIMARY KEY";
sql_stmt.unique = "UNIQUE";
sql_stmt.default_now = "DEFAULT NOW()";
sql_stmt.not_null = "NOT NULL";
sql_stmt.default_null = "DEFAULT NULL";
sql_stmt.storage_engine_inno = "ENGINE=InnoDB";
sql_stmt.alter_table = "IF EXISTS ALTER TABLE";
sql_stmt.add_constraint = "ADD CONSTRAINT";
sql_stmt.foreign_key = "FOREIGN KEY";
sql_stmt.references = "REFERENCES";
sql_stmt.break_line = "\n";
sql_stmt.tab = "    ";

def_header += sql_stmt.break_line;
def_header += "-- THIS SCRIPT WAS GENERATED AUTMATICALLY FROM NODE JS (By KBeDeveloper)" + sql_stmt.break_line;
def_header += sql_stmt.break_line;


if ( _SQL.length == 0 ) {
    _SQL += def_header;
    _SQL += sql_stmt.break_line;
    _SQL += sql_stmt.drop + " " + sql_stmt.database + " " + sql_stmt.if_exists + " " + db.name + ";" + sql_stmt.break_line;
    _SQL += sql_stmt.create + " " + sql_stmt.database + " " + sql_stmt.if_not_exists + " " + db.name + " " + sql_stmt.collation + ";" + sql_stmt.break_line;
    _SQL += sql_stmt.use + " " + db.name + ";" + sql_stmt.break_line;
    _SQL += sql_stmt.break_line;
}

for ( let key in json ) {

    _SQL += sql_stmt.create + " " + sql_stmt.table + " " + sql_stmt.if_not_exists + " " + key.toUpperCase() + " (" + sql_stmt.break_line;
    
    for ( let sub_key in json[key] ) {

        var column_comment = ",  -- ";

        _SQL += sql_stmt.break_line + sql_stmt.tab + "`" + sub_key + "` ";
        
        for ( let prop in json[key][sub_key] ) {

            var rules = [];

            if ( prop === 'typ' ) {

                _SQL += json[key][sub_key][prop];

            } else if ( prop === 'len' ) {

                if ( json[key][sub_key][prop] !== null ) {
                    _SQL += "(" + json[key][sub_key][prop] + ")";
                }                

            } else {                
                
                if ( json[key][sub_key][prop].length > 0 ) {

                    for ( let i = 0; i < json[key][sub_key][prop].length; i++ ) {                       

                        if ( json[key][sub_key][prop][i] == 'PK' ) {
                            _SQL += " " + sql_stmt.primary_key;
                        } else if ( json[key][sub_key][prop][i] == 'DEFAULT_NULL' ) {
                            _SQL += " " + sql_stmt.default_null;
                        } else if ( json[key][sub_key][prop][i] == 'UNIQUE' ) {
                            _SQL += " " + sql_stmt.unique;
                        } else if ( json[key][sub_key][prop][i] == 'DEFAULT_NOW' ) {
                            _SQL += " " + sql_stmt.default_now;
                        } else if ( json[key][sub_key][prop][i] == 'NOT_NULL' ){
                            _SQL += " " + sql_stmt.not_null;
                        } else if ( json[key][sub_key][prop][i] == 'FK' ){
                            _SQL += " " + sql_stmt.not_null;
                        }else {
                            if ( column_comment !== ',  -- ' ) {
                                column_comment += ", ";
                                column_comment += json[key][sub_key][prop][i];
                            } else {
                                column_comment += json[key][sub_key][prop][i];
                            }
                        }

                    }
                    
                }

                if ( column_comment === ',  -- ' ) {
                    _SQL += ",";
                } else {
                    _SQL += column_comment;
                }               

            }

        }

    }

    _SQL = _SQL.substring(0, _SQL.length - 1) + sql_stmt.break_line + ") " + sql_stmt.storage_engine_inno + ";" + sql_stmt.break_line + sql_stmt.break_line;
}

_SQL += sql_stmt.break_line;
_SQL += "ALTER TABLE EVENTS ADD CONSTRAINT FK_EVENTS_UserCode FOREIGN KEY (UserCode) REFERENCES USERS(Code);";
_SQL += sql_stmt.break_line;
_SQL += "ALTER TABLE COMMENTS ADD CONSTRAINT FK_COMMENTS_EventId FOREIGN KEY (EventId) REFERENCES EVENTS(EventId);";
_SQL += sql_stmt.break_line;
_SQL += "ALTER TABLE TOOLS ADD CONSTRAINT FK_TOOLS_EventId FOREIGN KEY (EventId) REFERENCES EVENTS(EventId);";

fs.writeFile('./database.sql', _SQL, (err) => {
    if ( err ) throw err
    else console.info("Successful export!\n")
});
