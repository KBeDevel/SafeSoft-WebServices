#! /bin/ruby

path = %x[git rev-parse --show-toplevel]

path = path.chomp!

cmd_export_2_json = "python #{path}/meta/scripts/yaml2json.py";

exec(cmd_export_2_json)

cmd_export_2_sql = "node #{path}/meta/scripts/json2sql.js";

exec(cmd_export_2_sql)
